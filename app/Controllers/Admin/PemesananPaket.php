<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PemesananPaketModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;
use App\Models\PembayaranModel;

class PemesananPaket extends BaseController
{
    protected $pemesananModel;
    protected $paketModel;
    protected $pelangganModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->pemesananModel = new PemesananPaketModel();
        $this->paketModel = new PaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    /**
     * Display list of all orders
     */
    public function index()
    {
        $pemesanan = $this->pemesananModel->getPemesananPaket();
        $paket = $this->paketModel->findAll();
        $pelanggan = $this->pelangganModel->findAll();

        $data = [
            'title' => 'Daftar Pemesanan Paket',
            'pemesanan' => $pemesanan,
            'paket' => $paket,
            'pelanggan' => $pelanggan
        ];

        return view('admin/pemesananpaket/index', $data);
    }

    /**
     * Show form to create a new order
     */
    public function create()
    {
        $paket = $this->paketModel->findAll();
        $pelanggan = $this->pelangganModel->findAll();

        $data = [
            'title' => 'Tambah Pemesanan',
            'paket' => $paket,
            'pelanggan' => $pelanggan
        ];

        return view('admin/pemesananpaket/create', $data);
    }

    /**
     * Show form to edit an order
     */
    public function edit($kdpemesananpaket)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }


        if ($pemesanan['status'] === 'completed' || $pemesanan['status'] === 'cancelled') {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan yang sudah selesai atau dibatalkan tidak dapat diedit');
        }

        $paket = $this->paketModel->findAll();
        $pelanggan = $this->pelangganModel->findAll();

        $data = [
            'title' => 'Edit Pemesanan',
            'pemesanan' => $pemesanan,
            'paket' => $paket,
            'pelanggan' => $pelanggan
        ];

        return view('admin/pemesananpaket/edit', $data);
    }

    /**
     * Update an order
     */
    public function update($kdpemesananpaket)
    {

        $rules = [
            'tgl' => 'required|valid_date',
            'alamatpesanan' => 'required',
            'jumlahhari' => 'required|numeric|greater_than[0]',
            'luaslokasi' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $pemesanan = $this->pemesananModel->find($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }


        if ($pemesanan['status'] === 'completed' || $pemesanan['status'] === 'cancelled') {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan yang sudah selesai atau dibatalkan tidak dapat diedit');
        }


        $paket = $this->paketModel->find($pemesanan['kdpaket']);


        $jumlahhari = $this->request->getPost('jumlahhari');
        $grandTotal = $paket['harga'];


        if ($jumlahhari > 4) {

            $biayaTambahan = $paket['harga'] * 0.1;
            $grandTotal = $paket['harga'] + $biayaTambahan;
        }


        $updateData = [
            'tgl' => $this->request->getPost('tgl'),
            'alamatpesanan' => $this->request->getPost('alamatpesanan'),
            'jumlahhari' => $jumlahhari,
            'luaslokasi' => $this->request->getPost('luaslokasi'),
            'grandtotal' => $grandTotal
        ];

        $success = $this->pemesananModel->update($kdpemesananpaket, $updateData);

        if (!$success) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate pemesanan');
        }

        return redirect()->to('/admin/pemesananpaket')->with('success', 'Pemesanan berhasil diupdate');
    }

    /**
     * Show order details
     */
    public function detail($kdpemesananpaket)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pemesanan',
            'pemesanan' => $pemesanan
        ];

        return view('admin/pemesananpaket/detail', $data);
    }

    /**
     * Update order status
     */
    public function updateStatus($kdpemesananpaket)
    {
        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'process', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $pemesanan = $this->pemesananModel->find($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }


        $this->pemesananModel->update($kdpemesananpaket, [
            'status' => $status
        ]);

        return redirect()->to('/admin/pemesananpaket/detail/' . $kdpemesananpaket)->with('success', 'Status pemesanan berhasil diupdate');
    }

    /**
     * Cancel an order
     */
    public function cancel($kdpemesananpaket)
    {
        $pemesanan = $this->pemesananModel->find($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }


        if ($pemesanan['status'] === 'completed') {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan yang sudah selesai tidak dapat dibatalkan');
        }


        $this->pemesananModel->update($kdpemesananpaket, [
            'status' => 'cancelled'
        ]);

        return redirect()->to('/admin/pemesananpaket/detail/' . $kdpemesananpaket)->with('success', 'Pemesanan berhasil dibatalkan');
    }

    /**
     * Delete an order
     */
    public function delete($kdpemesananpaket)
    {
        $pemesanan = $this->pemesananModel->find($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }


        if ($pemesanan['status'] === 'completed') {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan yang sudah selesai tidak dapat dihapus');
        }


        $this->pemesananModel->delete($kdpemesananpaket);


        if (!empty($pemesanan['kdpembayaran'])) {
            $this->pembayaranModel->delete($pemesanan['kdpembayaran']);
        }

        return redirect()->to('/admin/pemesananpaket')->with('success', 'Pemesanan berhasil dihapus');
    }

    /**
     * Process new order creation
     */
    public function store()
    {

        $rules = [
            'kdpelanggan' => 'required',
            'kdpaket' => 'required',
            'tgl' => 'required|valid_date',
            'alamatpesanan' => 'required',
            'jumlahhari' => 'required|numeric|greater_than[0]',
            'luaslokasi' => 'required',
            'metodepembayaran' => 'required|in_list[transfer,cash]',
        ];

        if (!$this->validate($rules)) {

            session()->setFlashdata('pelanggan_display', $this->request->getPost('pelanggan_display'));
            session()->setFlashdata('paket_display', $this->request->getPost('paket_display'));

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $kdpaket = $this->request->getPost('kdpaket');
        $paket = $this->paketModel->find($kdpaket);

        if (!$paket) {
            return redirect()->back()->withInput()->with('error', 'Paket tidak ditemukan');
        }


        $grandTotal = $paket['harga'];


        $jumlahhari = $this->request->getPost('jumlahhari');
        if ($jumlahhari > 4) {

            $biayaTambahan = $paket['harga'] * 0.1;
            $grandTotal = $paket['harga'] + $biayaTambahan;
        }


        $metodepembayaran = $this->request->getPost('metodepembayaran');
        $kdpembayaran = $this->pembayaranModel->createBookingPayment($grandTotal, $metodepembayaran);

        if (!$kdpembayaran) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pembayaran');
        }


        $kdpemesananpaket = $this->pemesananModel->generateBookingCode();


        $pemesananData = [
            'kdpemesananpaket' => $kdpemesananpaket,
            'tgl' => $this->request->getPost('tgl'),
            'kdpelanggan' => $this->request->getPost('kdpelanggan'),
            'kdpaket' => $kdpaket,
            'hargapaket' => $paket['harga'],
            'alamatpesanan' => $this->request->getPost('alamatpesanan'),
            'jumlahhari' => $this->request->getPost('jumlahhari'),
            'luaslokasi' => $this->request->getPost('luaslokasi'),
            'grandtotal' => $grandTotal,
            'status' => 'process', // For admin-created orders, set as process directly
            'kdpembayaran' => $kdpembayaran,
            'metodepembayaran' => $metodepembayaran
        ];

        $success = $this->pemesananModel->insert($pemesananData);

        if (!$success) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pemesanan');
        }


        if ($metodepembayaran === 'cash') {

            $this->pembayaranModel->update($kdpembayaran, [
                'status' => 'partial',
                'dp_confirmed' => 1,
                'dp_confirmed_at' => date('Y-m-d H:i:s'),
                'dp_confirmed_by' => session()->get('id') // ID admin yang login
            ]);
        }

        return redirect()->to('/admin/pemesananpaket')->with('success', 'Pemesanan berhasil ditambahkan');
    }

    /**
     * Cetak faktur pemesanan paket dalam format PDF
     *
     * @param string $kdpemesananpaket
     * @return void
     */
    public function cetakFaktur($kdpemesananpaket)
    {

        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }

        $data = [
            'pemesanan' => $pemesanan,
            'title' => 'Faktur Pemesanan Paket',
        ];


        $dompdf = new \Dompdf\Dompdf();


        $html = view('admin/pemesananpaket/faktur', $data);


        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');


        $dompdf->render();


        $dompdf->stream("Faktur_Pemesanan_Paket_" . $kdpemesananpaket . ".pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * Tampilkan faktur pemesanan paket dalam browser
     *
     * @param string $kdpemesananpaket
     * @return void
     */
    public function lihatFaktur($kdpemesananpaket)
    {

        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananpaket);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananpaket')->with('error', 'Pemesanan tidak ditemukan');
        }

        $data = [
            'pemesanan' => $pemesanan,
            'title' => 'Faktur Pemesanan Paket',
        ];

        return view('admin/pemesananpaket/faktur', $data);
    }
}
