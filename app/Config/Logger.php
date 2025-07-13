<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Log\Handlers\FileHandler;

class Logger extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Threshold
     * --------------------------------------------------------------------------
     *
     * The threshold for what messages to log. Anything at this level or higher
     * will be logged, while messages below this will be ignored. This is useful
     * for turning on debugging during development, but turning it off for production.
     *
     * @var int
     */
    public $threshold = 4;

    /**
     * --------------------------------------------------------------------------
     * Date Format for Logs
     * --------------------------------------------------------------------------
     *
     * Each item that is logged has an associated date. You can use PHP date
     * codes to set your own date formatting
     */
    public string $dateFormat = 'Y-m-d H:i:s';

    /**
     * --------------------------------------------------------------------------
     * Log Handlers
     * --------------------------------------------------------------------------
     *
     * The logging system supports multiple actions to be taken when something
     * is logged. This is done by allowing for multiple Handlers, special classes
     * designed to write the log to their chosen destinations, whether that is
     * a file on the getServer, a cloud-based service, or even taking actions such
     * as emailing the dev team.
     *
     * Each handler is defined by the class name used for that handler, and it
     * MUST implement the `CodeIgniter\Log\Handlers\HandlerInterface` interface.
     *
     * The value of each key is an array of configuration items that are sent
     * to the constructor of each handler. The only required configuration item
     * is the 'handles' element, which must be an array of integer log levels.
     * This is most easily handled by using the constants defined in the
     * `Psr\Log\LogLevel` class.
     *
     * Handlers are executed in the order defined in this array, starting with
     * the handler on top and continuing down.
     *
     * @var array<class-string, array<string, int|list<string>|string>>
     */
    public array $handlers = [
        /*
         * --------------------------------------------------------------------
         * File Handler
         * --------------------------------------------------------------------
         */
        FileHandler::class => [
            // The log levels that this handler will handle.
            'handles' => [
                'critical',
                'alert',
                'emergency',
                'debug',
                'error',
                'info',
                'notice',
                'warning',
            ],

            /*
             * The default filename extension for log files.
             * An extension of 'php' allows for protecting the log files via basic
             * scripting, when they are to be stored under a publicly accessible directory.
             *
             * NOTE: Leaving it blank will default to 'log'.
             */
            'fileExtension' => '',

            /*
             * The file system permissions to be applied on newly created log files.
             *
             * IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
             * integer notation (i.e. 0700, 0644, etc.)
             */
            'filePermissions' => 0644,

            /*
             * Logging Directory Path
             *
             * By default, logs are written to WRITEPATH . 'logs/'
             * Specify a different destination here, if desired.
             */
            'path' => '',
        ],

        /*
         * The ChromeLoggerHandler requires the use of the Chrome web browser
         * and the ChromeLogger extension. Uncomment this block to use it.
         */
        // 'CodeIgniter\Log\Handlers\ChromeLoggerHandler' => [
        //     /*
        //      * The log levels that this handler will handle.
        //      */
        //     'handles' => ['critical', 'alert', 'emergency', 'debug',
        //                   'error', 'info', 'notice', 'warning'],
        // ],

        /*
         * The ErrorlogHandler writes the logs to PHP's native `error_log()` function.
         * Uncomment this block to use it.
         */
        // 'CodeIgniter\Log\Handlers\ErrorlogHandler' => [
        //     /* The log levels this handler can handle. */
        //     'handles' => ['critical', 'alert', 'emergency', 'debug', 'error', 'info', 'notice', 'warning'],
        //
        //     /*
        //     * The message type where the error should go. Can be 0 or 4, or use the
        //     * class constants: `ErrorlogHandler::TYPE_OS` (0) or `ErrorlogHandler::TYPE_SAPI` (4)
        //     */
        //     'messageType' => 0,
        // ],
    ];
}
