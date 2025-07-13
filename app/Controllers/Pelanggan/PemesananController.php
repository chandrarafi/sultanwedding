<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\PemesananPaketModel;
use App\Models\PembayaranModel;
use CodeIgniter\I18n\Time;

class PemesananController extends BaseController
{
    protected $paketModel;
    protected $pemesananModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->pemesananModel = new PemesananPaketModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    /**
     * Tampilkan form pemesanan paket
     */
    public function pemesanPaket($kdpaket)
    {
        // Debug session data
        log_message('debug', '=== PEMESANAN PAKET DEBUG ===');
        log_message('debug', 'Session data: ' . json_encode(session()->get()));
        log_message('debug', 'KdPelanggan: ' . session()->get('kdpelanggan'));
        log_message('debug', 'User ID: ' . session()->get('user_id'));
        log_message('debug', 'Role: ' . session()->get('role'));
        log_message('debug', 'KdPaket: ' . $kdpaket);

        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('logged_in')) {
            log_message('debug', 'User not logged in, redirecting to login');
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        // Cek apakah ada data kdpelanggan
        if (!session()->has('kdpelanggan')) {
            log_message('debug', 'No kdpelanggan in session, trying to get from database');
            // Coba ambil dari database
            $pelangganModel = new \App\Models\PelangganModel();
            $pelanggan = $pelangganModel->getByUserId(session()->get('user_id'));

            if ($pelanggan) {
                log_message('debug', 'Found pelanggan record, setting kdpelanggan: ' . $pelanggan['kdpelanggan']);
                session()->set('kdpelanggan', $pelanggan['kdpelanggan']);
            } else {
                log_message('debug', 'No pelanggan record found, redirecting to login');
                return redirect()->to(site_url('auth/login'))->with('error', 'Data pelanggan tidak ditemukan');
            }
        }

        // Ambil data paket
        $paket = $this->paketModel->find($kdpaket);

        if (!$paket) {
            log_message('debug', 'Paket not found: ' . $kdpaket);
            return redirect()->to(site_url('paket'))->with('error', 'Paket tidak ditemukan');
        }

        log_message('debug', 'Paket found: ' . $paket['namapaket']);
        log_message('debug', 'Rendering pemesanan form');

        $data = [
            'title' => 'Pemesanan Paket ' . $paket['namapaket'],
            'paket' => $paket
        ];

        return view('pelanggan/pemesanan/form', $data);
    }

    /**
     * Proses pemesanan paket (AJAX)
     */
    public function processPemesanan()
    {
        // Periksa apakah ini adalah request AJAX atau form submission biasa
        $isAjax = $this->request->isAJAX() ||
            ($this->request->getServer('HTTP_X_REQUESTED_WITH') &&
                strtolower($this->request->getServer('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

        // Debug untuk melihat nilai parameter
        log_message('debug', '=== PROCESS PEMESANAN DEBUG ===');
        log_message('debug', 'Is AJAX: ' . ($isAjax ? 'true' : 'false'));
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

        // Validasi input
        $rules = [
            'kdpaket' => 'required|numeric',
            'tgl' => 'required|valid_date',
            'alamatpesanan' => 'required',
            'jumlahhari' => 'required|numeric|greater_than[0]',
            'luaslokasi' => 'required',
            'metodepembayaran' => 'required|in_list[transfer,cash]',
        ];

        $customMessages = [
            'kdpaket.required' => 'ID paket tidak boleh kosong',
            'kdpaket.numeric' => 'ID paket harus berupa angka',
            'tgl.required' => 'Tanggal acara tidak boleh kosong',
            'tgl.valid_date' => 'Format tanggal acara tidak valid',
            'alamatpesanan.required' => 'Alamat lokasi acara tidak boleh kosong',
            'jumlahhari.required' => 'Jumlah hari tidak boleh kosong',
            'jumlahhari.numeric' => 'Jumlah hari harus berupa angka',
            'jumlahhari.greater_than' => 'Jumlah hari minimal 1',
            'luaslokasi.required' => 'Luas lokasi tidak boleh kosong',
            'metodepembayaran.required' => 'Metode pembayaran tidak boleh kosong',
            'metodepembayaran.in_list' => 'Metode pembayaran tidak valid',
        ];

        if (!$this->validate($rules, $customMessages)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Validasi gagal, silakan periksa kembali data yang dimasukkan',
                    'errors' => $this->validator->getErrors()
                ]);
            } else {
                // Untuk form submission biasa, kembalikan dengan flash data
                return redirect()->back()
                    ->with('error', 'Validasi gagal, silakan periksa kembali data yang dimasukkan')
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }
        }

        $kdpaket = $this->request->getPost('kdpaket');
        $tglPemesanan = $this->request->getPost('tgl');
        $alamatpesanan = $this->request->getPost('alamatpesanan');
        $jumlahhari = $this->request->getPost('jumlahhari');
        $luaslokasi = $this->request->getPost('luaslokasi');
        $metodepembayaran = $this->request->getPost('metodepembayaran');

        // Ambil data paket
        $paket = $this->paketModel->find($kdpaket);

        if (!$paket) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Paket tidak ditemukan atau telah dihapus'
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Paket tidak ditemukan atau telah dihapus')
                    ->withInput();
            }
        }

        // Hitung grand total berdasarkan harga paket dan jumlah hari
        $grandTotal = $paket['harga'];

        // Jika jumlah hari lebih dari 4, tambahkan biaya tambahan 10%
        if ($jumlahhari > 4) {
            // Hitung biaya tambahan 10% dari harga paket
            $biayaTambahan = $paket['harga'] * 0.1;
            $grandTotal = $paket['harga'] + $biayaTambahan;
        }

        // Buat pembayaran DP 10%
        $kdpembayaran = $this->pembayaranModel->createBookingPayment($grandTotal, $metodepembayaran);

        if (!$kdpembayaran) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal membuat pembayaran. Silakan coba lagi nanti.'
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal membuat pembayaran. Silakan coba lagi nanti.')
                    ->withInput();
            }
        }

        // Generate kode pemesanan
        $kdpemesananpaket = $this->pemesananModel->generateBookingCode();

        // Simpan data pemesanan
        $pemesananData = [
            'kdpemesananpaket' => $kdpemesananpaket,
            'tgl' => $tglPemesanan,
            'kdpelanggan' => session()->get('kdpelanggan'),
            'kdpaket' => $kdpaket,
            'hargapaket' => $paket['harga'],
            'alamatpesanan' => $alamatpesanan,
            'jumlahhari' => $jumlahhari,
            'luaslokasi' => $luaslokasi,
            'grandtotal' => $grandTotal,
            'status' => 'pending',
            'kdpembayaran' => $kdpembayaran,
            'metodepembayaran' => $metodepembayaran // Simpan metode pembayaran di tabel pemesanan
        ];

        $success = $this->pemesananModel->insert($pemesananData);

        if (!$success) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menyimpan pemesanan. Silakan coba lagi nanti.'
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal menyimpan pemesanan. Silakan coba lagi nanti.')
                    ->withInput();
            }
        }

        if ($isAjax) {
            $response = [
                'status' => true,
                'message' => 'Pemesanan berhasil, silahkan lakukan pembayaran DP dalam waktu 5 menit',
                'kdpemesanan' => $kdpemesananpaket,
                'kdpembayaran' => $kdpembayaran,
                'redirect' => site_url('pelanggan/pemesanan/bayar/' . $kdpemesananpaket)
            ];

            log_message('debug', 'AJAX Response: ' . json_encode($response));
            return $this->response->setJSON($response);
        } else {
            $redirectUrl = site_url('pelanggan/pemesanan/bayar/' . $kdpemesananpaket);
            log_message('debug', 'Redirect URL: ' . $redirectUrl);
            return redirect()->to($redirectUrl)
                ->with('success', 'Pemesanan berhasil, silahkan lakukan pembayaran DP dalam waktu 5 menit');
        }
    }

    /**
     * Tampilkan halaman pembayaran
     */
    public function pembayaran($kdpemesanan)
    {
        // Periksa apakah ini adalah request AJAX atau form submission biasa
        $isAjax = $this->request->isAJAX() ||
            ($this->request->getServer('HTTP_X_REQUESTED_WITH') &&
                strtolower($this->request->getServer('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

        // Debug untuk melihat nilai parameter
        log_message('debug', '=== PEMBAYARAN DEBUG ===');
        log_message('debug', 'KdPemesanan: ' . $kdpemesanan);
        log_message('debug', 'Is AJAX: ' . ($isAjax ? 'true' : 'false'));

        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        // Ambil data pemesanan
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

        if (!$pemesanan) {
            log_message('debug', 'Pemesanan tidak ditemukan: ' . $kdpemesanan);
            return redirect()->to(site_url('pelanggan'))->with('error', 'Pemesanan tidak ditemukan');
        }

        if ($pemesanan['kdpelanggan'] != session()->get('kdpelanggan')) {
            log_message('debug', 'Pemesanan bukan milik pelanggan ini. KdPelanggan pemesanan: ' . $pemesanan['kdpelanggan'] . ', KdPelanggan session: ' . session()->get('kdpelanggan'));
            return redirect()->to(site_url('pelanggan'))->with('error', 'Pemesanan tidak ditemukan');
        }

        // Cek apakah pemesanan sudah dibatalkan
        if ($pemesanan['status'] === 'cancelled') {
            return redirect()->to(site_url('pelanggan'))->with('error', 'Pemesanan telah dibatalkan karena melewati batas waktu pembayaran');
        }

        // Cek apakah waktu pembayaran sudah lewat (5 menit)
        if ($pemesanan['status'] === 'pending') {
            $createdTime = new Time($pemesanan['created_at']);
            $now = new Time('now');

            // Jika lebih dari 5 menit dan masih pending, batalkan
            if ($createdTime->difference($now)->getMinutes() >= 5) {
                $this->pemesananModel->update($kdpemesanan, ['status' => 'cancelled']);
                return redirect()->to(site_url('pelanggan'))->with('error', 'Pemesanan telah dibatalkan karena melewati batas waktu pembayaran (5 menit)');
            }

            // Hitung sisa waktu untuk ditampilkan
            $remainingSeconds = 300 - $createdTime->difference($now)->getSeconds(); // 300 seconds = 5 minutes
            if ($remainingSeconds < 0) $remainingSeconds = 0;

            $pemesanan['remaining_seconds'] = $remainingSeconds;
        }

        $data = [
            'title' => 'Pembayaran Paket',
            'pemesanan' => $pemesanan
        ];

        return view('pelanggan/pemesanan/pembayaran', $data);
    }

    /**
     * Proses upload bukti pembayaran DP (AJAX)
     */
    public function uploadBuktiPembayaran()
    {
        // Debug log
        log_message('debug', '=== UPLOAD BUKTI PEMBAYARAN DEBUG ===');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'FILES data: ' . json_encode($this->request->getFiles()));

        // Periksa apakah ini adalah request AJAX atau form submission biasa
        $isAjax = $this->request->isAJAX() ||
            ($this->request->getServer('HTTP_X_REQUESTED_WITH') &&
                strtolower($this->request->getServer('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

        // Jika AJAX, pastikan semua response adalah JSON
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        $rules = [
            'kdpemesanan' => 'required'
        ];

        // Ambil data pemesanan terlebih dahulu untuk mengetahui metode pembayaran
        $kdpemesanan = $this->request->getPost('kdpemesanan');
        log_message('debug', 'kdpemesanan: ' . $kdpemesanan);

        if (empty($kdpemesanan)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID pemesanan tidak ditemukan'
                ]);
            } else {
                return redirect()->to(site_url('pelanggan'))
                    ->with('error', 'ID pemesanan tidak ditemukan');
            }
        }

        try {
            $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

            if (!$pemesanan || $pemesanan['kdpelanggan'] != session()->get('kdpelanggan')) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Pemesanan tidak ditemukan'
                    ]);
                } else {
                    return redirect()->to(site_url('pelanggan'))
                        ->with('error', 'Pemesanan tidak ditemukan');
                }
            }

            log_message('debug', 'Pemesanan ditemukan: ' . json_encode($pemesanan));

            // Tambahkan validasi bukti pembayaran hanya jika metode pembayaran adalah transfer
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                $rules['bukti_pembayaran'] = 'uploaded[bukti_pembayaran]|is_image[bukti_pembayaran]|max_size[bukti_pembayaran,2048]';
            }

            if (!$this->validate($rules)) {
                log_message('debug', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));

                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Validasi gagal, silakan periksa kembali data yang dimasukkan',
                        'errors' => $this->validator->getErrors()
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Validasi gagal, silakan periksa kembali data yang dimasukkan')
                        ->withInput()
                        ->with('errors', $this->validator->getErrors());
                }
            }

            // Cek apakah waktu pembayaran sudah lewat (5 menit)
            if ($pemesanan['status'] === 'pending') {
                $createdTime = new Time($pemesanan['created_at']);
                $now = new Time('now');

                // Jika lebih dari 5 menit dan masih pending, batalkan
                if ($createdTime->difference($now)->getMinutes() >= 5) {
                    $this->pemesananModel->update($kdpemesanan, ['status' => 'cancelled']);

                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Pemesanan telah dibatalkan karena melewati batas waktu pembayaran (5 menit)',
                            'redirect' => site_url('pelanggan')
                        ]);
                    } else {
                        return redirect()->to(site_url('pelanggan'))
                            ->with('error', 'Pemesanan telah dibatalkan karena melewati batas waktu pembayaran (5 menit)');
                    }
                }
            }

            $buktipembayaran = null;

            // Upload bukti pembayaran jika metode transfer
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                // Upload bukti pembayaran
                $buktiFile = $this->request->getFile('bukti_pembayaran');

                try {
                    // Pastikan direktori upload ada
                    $uploadDir = ROOTPATH . 'public/uploads/pembayaran';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if ($buktiFile && $buktiFile->isValid() && !$buktiFile->hasMoved()) {
                        $newName = $pemesanan['kdpembayaran'] . '_dp_' . $buktiFile->getRandomName();
                        $buktiFile->move($uploadDir, $newName);
                        $buktipembayaran = $newName;
                        log_message('debug', 'Bukti pembayaran berhasil diupload: ' . $buktipembayaran);
                    } else if ($buktiFile) {
                        log_message('debug', 'Bukti pembayaran gagal diupload: ' . $buktiFile->getErrorString());
                        throw new \RuntimeException($buktiFile->getErrorString());
                    } else {
                        throw new \RuntimeException("File bukti pembayaran tidak ditemukan");
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Exception saat upload file: ' . $e->getMessage());

                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
                    }
                }

                if (!$buktipembayaran) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran'
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran');
                    }
                }
            }

            // Update data pembayaran
            $updateData = ['status' => 'pending']; // Tetap pending sampai dikonfirmasi admin
            if ($buktipembayaran) {
                $updateData['buktipembayaran'] = $buktipembayaran;
            }

            $result = $this->pembayaranModel->update($pemesanan['kdpembayaran'], $updateData);

            log_message('debug', 'Update pembayaran result: ' . ($result ? 'success' : 'failed'));
            log_message('debug', 'Update data: ' . json_encode($updateData));

            if (!$result) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Gagal menyimpan bukti pembayaran'
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Gagal menyimpan bukti pembayaran');
                }
            }

            if ($isAjax) {
                $response = [
                    'status' => true,
                    'message' => 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.',
                    'redirect' => site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan)
                ];
                log_message('debug', 'Response: ' . json_encode($response));
                return $this->response->setContentType('application/json')
                    ->setJSON($response);
            } else {
                return redirect()->to(site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan))
                    ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Unhandled exception: ' . $e->getMessage());
            log_message('error', $e->getTraceAsString());

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    /**
     * Process payment for H-1 (additional 10%)
     */
    public function processH1Payment()
    {
        // Debug log
        log_message('debug', '=== PROCESS H1 PAYMENT DEBUG ===');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'FILES data: ' . json_encode($this->request->getFiles()));

        // Periksa apakah ini adalah request AJAX atau form submission biasa
        $isAjax = $this->request->isAJAX() ||
            ($this->request->getServer('HTTP_X_REQUESTED_WITH') &&
                strtolower($this->request->getServer('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

        // Jika AJAX, pastikan semua response adalah JSON
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        $rules = [
            'kdpemesanan' => 'required'
        ];

        // Ambil data pemesanan terlebih dahulu untuk mengetahui metode pembayaran
        $kdpemesanan = $this->request->getPost('kdpemesanan');
        log_message('debug', 'kdpemesanan: ' . $kdpemesanan);

        if (empty($kdpemesanan)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID pemesanan tidak ditemukan'
                ]);
            } else {
                return redirect()->to(site_url('pelanggan'))
                    ->with('error', 'ID pemesanan tidak ditemukan');
            }
        }

        try {
            $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

            if (!$pemesanan || $pemesanan['kdpelanggan'] != session()->get('kdpelanggan')) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Pemesanan tidak ditemukan'
                    ]);
                } else {
                    return redirect()->to(site_url('pelanggan'))
                        ->with('error', 'Pemesanan tidak ditemukan');
                }
            }

            log_message('debug', 'Pemesanan ditemukan: ' . json_encode($pemesanan));

            // Tambahkan validasi bukti pembayaran hanya jika metode pembayaran adalah transfer
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                $rules['bukti_pembayaran'] = 'uploaded[bukti_pembayaran]|is_image[bukti_pembayaran]|max_size[bukti_pembayaran,2048]';
            }

            if (!$this->validate($rules)) {
                log_message('debug', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));

                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Validasi gagal, silakan periksa kembali data yang dimasukkan',
                        'errors' => $this->validator->getErrors()
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Validasi gagal, silakan periksa kembali data yang dimasukkan')
                        ->withInput()
                        ->with('errors', $this->validator->getErrors());
                }
            }

            // Upload bukti pembayaran jika metode transfer
            $buktipembayaran = null;
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                $buktiFile = $this->request->getFile('bukti_pembayaran');

                try {
                    // Pastikan direktori upload ada
                    $uploadDir = ROOTPATH . 'public/uploads/pembayaran';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if ($buktiFile && $buktiFile->isValid() && !$buktiFile->hasMoved()) {
                        $newName = $pemesanan['kdpembayaran'] . '_h1_' . $buktiFile->getRandomName();
                        $buktiFile->move($uploadDir, $newName);
                        $buktipembayaran = $newName;
                        log_message('debug', 'Bukti pembayaran H1 berhasil diupload: ' . $buktipembayaran);
                    } else if ($buktiFile) {
                        log_message('debug', 'Bukti pembayaran H1 gagal diupload: ' . $buktiFile->getErrorString());
                        throw new \RuntimeException($buktiFile->getErrorString());
                    } else {
                        throw new \RuntimeException("File bukti pembayaran tidak ditemukan");
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Exception saat upload file H1: ' . $e->getMessage());

                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
                    }
                }

                if (!$buktipembayaran) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran'
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran');
                    }
                }
            }

            // Proses pembayaran H-1 - hanya upload bukti, status tetap pending sampai dikonfirmasi admin
            if ($buktipembayaran) {
                $result = $this->pembayaranModel->processH1Payment($pemesanan['kdpembayaran'], $pemesanan['metodepembayaran'], $buktipembayaran);
            } else {
                $result = $this->pembayaranModel->processH1Payment($pemesanan['kdpembayaran'], $pemesanan['metodepembayaran']);
            }

            log_message('debug', 'Update pembayaran H1 result: ' . ($result ? 'success' : 'failed'));

            if (!$result) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Gagal memproses pembayaran'
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Gagal memproses pembayaran');
                }
            }

            if ($isAjax) {
                $response = [
                    'status' => true,
                    'message' => 'Bukti pembayaran H-1 berhasil diunggah. Menunggu konfirmasi admin.',
                    'redirect' => site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan)
                ];
                log_message('debug', 'Response: ' . json_encode($response));
                return $this->response->setContentType('application/json')
                    ->setJSON($response);
            } else {
                return redirect()->to(site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan))
                    ->with('success', 'Bukti pembayaran H-1 berhasil diunggah. Menunggu konfirmasi admin.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Unhandled exception: ' . $e->getMessage());
            log_message('error', $e->getTraceAsString());

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    /**
     * Proses pembayaran pelunasan (AJAX)
     */
    public function processFullPayment()
    {
        // Debug log
        log_message('debug', '=== PROCESS FULL PAYMENT DEBUG ===');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'FILES data: ' . json_encode($this->request->getFiles()));

        // Periksa apakah ini adalah request AJAX atau form submission biasa
        $isAjax = $this->request->isAJAX() ||
            ($this->request->getServer('HTTP_X_REQUESTED_WITH') &&
                strtolower($this->request->getServer('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

        // Jika AJAX, pastikan semua response adalah JSON
        if ($isAjax) {
            $this->response->setContentType('application/json');
        }

        $rules = [
            'kdpemesanan' => 'required'
        ];

        // Ambil data pemesanan terlebih dahulu untuk mengetahui metode pembayaran
        $kdpemesanan = $this->request->getPost('kdpemesanan');
        log_message('debug', 'kdpemesanan: ' . $kdpemesanan);

        if (empty($kdpemesanan)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID pemesanan tidak ditemukan'
                ]);
            } else {
                return redirect()->to(site_url('pelanggan'))
                    ->with('error', 'ID pemesanan tidak ditemukan');
            }
        }

        try {
            $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

            if (!$pemesanan || $pemesanan['kdpelanggan'] != session()->get('kdpelanggan')) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Pemesanan tidak ditemukan'
                    ]);
                } else {
                    return redirect()->to(site_url('pelanggan'))
                        ->with('error', 'Pemesanan tidak ditemukan');
                }
            }

            log_message('debug', 'Pemesanan ditemukan: ' . json_encode($pemesanan));

            // Tambahkan validasi bukti pembayaran hanya jika metode pembayaran adalah transfer
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                $rules['bukti_pembayaran'] = 'uploaded[bukti_pembayaran]|is_image[bukti_pembayaran]|max_size[bukti_pembayaran,2048]';
            }

            if (!$this->validate($rules)) {
                log_message('debug', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));

                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Validasi gagal, silakan periksa kembali data yang dimasukkan',
                        'errors' => $this->validator->getErrors()
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Validasi gagal, silakan periksa kembali data yang dimasukkan')
                        ->withInput()
                        ->with('errors', $this->validator->getErrors());
                }
            }

            // Upload bukti pembayaran jika metode transfer
            $buktipembayaran = null;
            if ($pemesanan['metodepembayaran'] === 'transfer') {
                $buktiFile = $this->request->getFile('bukti_pembayaran');

                try {
                    // Pastikan direktori upload ada
                    $uploadDir = ROOTPATH . 'public/uploads/pembayaran';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if ($buktiFile && $buktiFile->isValid() && !$buktiFile->hasMoved()) {
                        $newName = $pemesanan['kdpembayaran'] . '_full_' . $buktiFile->getRandomName();
                        $buktiFile->move($uploadDir, $newName);
                        $buktipembayaran = $newName;
                        log_message('debug', 'Bukti pelunasan berhasil diupload: ' . $buktipembayaran);
                    } else if ($buktiFile) {
                        log_message('debug', 'Bukti pelunasan gagal diupload: ' . $buktiFile->getErrorString());
                        throw new \RuntimeException($buktiFile->getErrorString());
                    } else {
                        throw new \RuntimeException("File bukti pembayaran tidak ditemukan");
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Exception saat upload file pelunasan: ' . $e->getMessage());

                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
                    }
                }

                if (!$buktipembayaran) {
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => 'Gagal mengupload bukti pembayaran'
                        ]);
                    } else {
                        return redirect()->back()
                            ->with('error', 'Gagal mengupload bukti pembayaran');
                    }
                }
            }

            // Proses pembayaran pelunasan
            $result = $this->pembayaranModel->processFullPayment($pemesanan['kdpembayaran'], $pemesanan['metodepembayaran'], $buktipembayaran);

            log_message('debug', 'Update pembayaran pelunasan result: ' . ($result ? 'success' : 'failed'));

            if (!$result) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Gagal memproses pembayaran'
                    ]);
                } else {
                    return redirect()->back()
                        ->with('error', 'Gagal memproses pembayaran');
                }
            }

            if ($isAjax) {
                $response = [
                    'status' => true,
                    'message' => 'Bukti pembayaran pelunasan berhasil diunggah. Menunggu konfirmasi admin.',
                    'redirect' => site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan)
                ];
                log_message('debug', 'Response: ' . json_encode($response));
                return $this->response->setContentType('application/json')
                    ->setJSON($response);
            } else {
                return redirect()->to(site_url('pelanggan/pemesanan/pembayaran/' . $kdpemesanan))
                    ->with('success', 'Bukti pembayaran pelunasan berhasil diunggah. Menunggu konfirmasi admin.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Unhandled exception: ' . $e->getMessage());
            log_message('error', $e->getTraceAsString());

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    /**
     * Daftar pemesanan pelanggan
     */
    public function daftarPemesanan()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        // Get pemesanan with payment information
        $pemesanan = $this->pemesananModel->select('pemesananpaket.*, paket.namapaket, paket.foto as fotopaket, pembayaran.status as statuspembayaran, pembayaran.totalpembayaran, pembayaran.sisa')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left')
            ->where('pemesananpaket.kdpelanggan', session()->get('kdpelanggan'))
            ->orderBy('pemesananpaket.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Daftar Pemesanan',
            'pemesanan' => $pemesanan
        ];

        return view('pelanggan/pemesanan/index', $data);
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkPaymentStatus($kdpemesanan)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return $this->response->setContentType('application/json')
                ->setJSON([
                    'status' => false,
                    'message' => 'Sesi login tidak valid'
                ]);
        }

        // Ambil data pemesanan
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

        if (!$pemesanan || $pemesanan['kdpelanggan'] != session()->get('kdpelanggan')) {
            return $this->response->setContentType('application/json')
                ->setJSON([
                    'status' => false,
                    'message' => 'Pemesanan tidak ditemukan'
                ]);
        }

        // Cek status pembayaran
        if ($pemesanan['status'] === 'pending') {
            $createdTime = new Time($pemesanan['created_at']);
            $now = new Time('now');

            // Hitung sisa waktu
            $remainingSeconds = 300 - $createdTime->difference($now)->getSeconds(); // 300 seconds = 5 minutes

            // Jika lebih dari 5 menit dan masih pending, batalkan
            if ($remainingSeconds <= 0) {
                $this->pemesananModel->update($kdpemesanan, ['status' => 'cancelled']);

                return $this->response->setContentType('application/json')
                    ->setJSON([
                        'status' => false,
                        'expired' => true,
                        'message' => 'Pemesanan telah dibatalkan karena melewati batas waktu pembayaran',
                        'redirect' => site_url('pelanggan')
                    ]);
            }

            return $this->response->setContentType('application/json')
                ->setJSON([
                    'status' => true,
                    'payment_status' => 'pending',
                    'remaining_seconds' => $remainingSeconds,
                    'message' => 'Silahkan selesaikan pembayaran'
                ]);
        }

        return $this->response->setContentType('application/json')
            ->setJSON([
                'status' => true,
                'payment_status' => $pemesanan['status'],
                'message' => 'Status pembayaran: ' . $pemesanan['status']
            ]);
    }

    /**
     * Check for rejected payments and return notification data
     */
    public function checkRejectedPayments()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return $this->response->setContentType('application/json')
                ->setJSON([
                    'status' => false,
                    'message' => 'Sesi login tidak valid'
                ]);
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Get pemesanan with rejected payments
        $rejectedPayments = $this->pemesananModel->select('pemesananpaket.*, paket.namapaket, pembayaran.status as statuspembayaran, pembayaran.rejected_reason, pembayaran.rejected_at, pembayaran.dp_confirmed, pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.full_paid, pembayaran.tipepembayaran')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpelanggan', $kdpelanggan)
            ->where('pembayaran.rejected_reason IS NOT NULL')
            ->where('pembayaran.rejected_at IS NOT NULL')
            ->orderBy('pembayaran.rejected_at', 'DESC')
            ->findAll();

        $notifications = [];

        foreach ($rejectedPayments as $payment) {
            $paymentStage = 'dp'; // Default to DP
            $actionRequired = '';
            $actionUrl = site_url('pelanggan/pemesanan/pembayaran/' . $payment['kdpemesananpaket']);

            // Determine payment stage and action required
            if (isset($payment['dp_confirmed']) && $payment['dp_confirmed'] == 1) {
                if (
                    isset($payment['h1_paid']) && $payment['h1_paid'] == 1 &&
                    (!isset($payment['h1_confirmed']) || $payment['h1_confirmed'] == 0)
                ) {
                    $paymentStage = 'h1';
                    $actionRequired = 'Silahkan upload ulang bukti pembayaran H-1.';
                } elseif (
                    (isset($payment['tipepembayaran']) && $payment['tipepembayaran'] == 'lunas') ||
                    (isset($payment['full_paid']) && !empty($payment['full_paid']))
                ) {
                    $paymentStage = 'full';
                    $actionRequired = 'Silahkan upload ulang bukti pelunasan.';
                }
            } else {
                // DP rejected means order cancelled
                $paymentStage = 'dp';
                $actionRequired = 'Pemesanan dibatalkan. Silahkan lakukan pemesanan baru.';
                $actionUrl = site_url('pelanggan/paket');
            }

            $notifications[] = [
                'id' => $payment['kdpemesananpaket'],
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran ' . ($paymentStage == 'dp' ? 'DP' : ($paymentStage == 'h1' ? 'H-1' : 'Pelunasan')) .
                    ' untuk paket ' . $payment['namapaket'] . ' ditolak. Alasan: ' . $payment['rejected_reason'],
                'action_required' => $actionRequired,
                'action_url' => $actionUrl,
                'time' => date('d M Y H:i', strtotime($payment['rejected_at'])),
                'payment_stage' => $paymentStage,
                'is_read' => false // Default to unread
            ];
        }

        return $this->response->setContentType('application/json')
            ->setJSON([
                'status' => true,
                'count' => count($notifications),
                'notifications' => $notifications
            ]);
    }
}
