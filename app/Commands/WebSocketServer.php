<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;
use App\Libraries\WebSocketServer as WebSocketHandler;

class WebSocketServer extends BaseCommand
{
    protected $group       = 'Sultan';
    protected $name        = 'websocket:serve';
    protected $description = 'Menjalankan WebSocket Server untuk notifikasi realtime';

    public function run(array $params)
    {
        CLI::write('Starting WebSocket Server...', 'green');
        CLI::write('Press Ctrl+C to stop the server', 'yellow');

        // Create event loop
        $loop = Factory::create();

        // Create WebSocket server
        $webSocket = new WebSocketHandler();

        // Set up periodic task to check pending payments
        $loop->addPeriodicTimer(30, function () use ($webSocket) {
            CLI::write('Checking pending payments...', 'yellow');
            $webSocket->checkAllPendingPayments();
        });

        // Create server socket
        $socket = new Reactor('0.0.0.0:8080', $loop);

        // Create server
        $server = new IoServer(
            new HttpServer(
                new WsServer($webSocket)
            ),
            $socket,
            $loop
        );

        CLI::write('WebSocket Server running at 0.0.0.0:8080', 'green');

        // Run the server
        $server->run();
    }
}
