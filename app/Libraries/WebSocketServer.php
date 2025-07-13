<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use CodeIgniter\I18n\Time;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $db;
    protected $pemesananModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->db = \Config\Database::connect();
        $this->pemesananModel = model('App\Models\PemesananPaketModel');
        $this->pembayaranModel = model('App\Models\PembayaranModel');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        // Handle different message types
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'register':
                    // Register client with kdpemesanan
                    if (isset($data['kdpemesanan'])) {
                        $from->kdpemesanan = $data['kdpemesanan'];
                        echo "Client {$from->resourceId} registered for pemesanan {$data['kdpemesanan']}\n";
                    }
                    break;

                case 'check_payment_status':
                    if (isset($from->kdpemesanan)) {
                        $this->checkPaymentStatus($from, $from->kdpemesanan);
                    }
                    break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Check payment status and send notification if payment is expired
     */
    public function checkPaymentStatus($conn, $kdpemesanan)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesanan);

        if ($pemesanan && $pemesanan['status'] === 'pending') {
            $createdTime = new Time($pemesanan['created_at']);
            $now = new Time('now');

            // Calculate time difference in minutes
            $diffMinutes = $createdTime->difference($now)->getMinutes();

            // If more than 5 minutes and still pending
            if ($diffMinutes >= 5) {
                // Cancel the pemesanan
                $this->pemesananModel->update($kdpemesanan, ['status' => 'cancelled']);

                // Send notification to client
                $conn->send(json_encode([
                    'type' => 'payment_expired',
                    'message' => 'Waktu pembayaran telah habis. Pemesanan dibatalkan secara otomatis.'
                ]));

                echo "Payment for pemesanan {$kdpemesanan} has expired and been cancelled.\n";
            } else {
                // Send remaining time
                $remainingMinutes = 5 - $diffMinutes;
                $remainingSeconds = (5 * 60) - $createdTime->difference($now)->getSeconds();

                $conn->send(json_encode([
                    'type' => 'payment_timer',
                    'remaining_minutes' => $remainingMinutes,
                    'remaining_seconds' => $remainingSeconds,
                    'message' => "Sisa waktu pembayaran: {$remainingMinutes} menit"
                ]));
            }
        }
    }

    /**
     * Broadcast payment status to specific client
     */
    public function broadcastPaymentStatus($kdpemesanan)
    {
        foreach ($this->clients as $client) {
            if (isset($client->kdpemesanan) && $client->kdpemesanan === $kdpemesanan) {
                $this->checkPaymentStatus($client, $kdpemesanan);
            }
        }
    }

    /**
     * Check all pending payments and cancel expired ones
     */
    public function checkAllPendingPayments()
    {
        $pendingPemesanan = $this->pemesananModel->where('status', 'pending')->findAll();

        foreach ($pendingPemesanan as $pemesanan) {
            $createdTime = new Time($pemesanan['created_at']);
            $now = new Time('now');

            // If more than 5 minutes and still pending
            if ($createdTime->difference($now)->getMinutes() >= 5) {
                // Cancel the pemesanan
                $this->pemesananModel->update($pemesanan['kdpemesananpaket'], ['status' => 'cancelled']);

                // Broadcast to any connected clients
                $this->broadcastPaymentStatus($pemesanan['kdpemesananpaket']);

                echo "Payment for pemesanan {$pemesanan['kdpemesananpaket']} has expired and been cancelled.\n";
            }
        }
    }
}
