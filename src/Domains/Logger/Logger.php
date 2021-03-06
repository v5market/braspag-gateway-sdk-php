<?php

namespace Braspag\Gateway\Domains\Logger;

use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;

class Logger
{
    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;
    public const ALERT = 550;
    public const EMERGENCY = 600;

    private static $instance;

    private function __construct()
    {
        /** Previning */
    }

    public static function getInstance(array $opts = []): Monolog
    {
        if (self::$instance === null) {
            self::init($opts);

            if (!is_dir(BRASPAG_LOG)) {
                mkdir(BRASPAG_LOG, 0777, true);
            }

            $dateFormat = "Y-m-d\TH:i:s";
            $formatter = new PsrFormatter($dateFormat);

            $stream = new StreamHandler(BRASPAG_LOG . '/braspag_gateway_' . date('Y-m-d') . '.log');
            $stream->setFormatter($formatter);

            self::$instance = new Monolog('security');
            self::$instance->pushHandler($stream);
        }

        return self::$instance;
    }

    /**
     * Adds a log record at an arbitrary level.
     *
     * @param mixed  $level   The log level
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function log($level, $message, array $context = []): void
    {
        (self::getInstance())->log($level, $message, $context);
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function debug($message, array $context = []): void
    {
        self::log(Monolog::DEBUG, $message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function info($message, array $context = []): void
    {
        self::log(Monolog::INFO, $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function notice($message, array $context = []): void
    {
        self::log(Monolog::NOTICE, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function warning($message, array $context = []): void
    {
        self::log(Monolog::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function error($message, array $context = []): void
    {
        self::log(Monolog::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function critical($message, array $context = []): void
    {
        self::log(Monolog::CRITICAL, $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function alert($message, array $context = []): void
    {
        self::log(Monolog::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public static function emergency($message, array $context = []): void
    {
        self::log(Monolog::EMERGENCY, $message, $context);
    }

    /**
     * Cria as constantes necess??rias
     *
     * @param Array $opts
     */
    private static function init(array $opts)
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        $path = __DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . 'log';

        if (defined('DIR_LOGS')) {
            $path = DIR_LOGS;
        }

        if (isset($opts['path'])) {
            $path = $opts['path'];
        }

        if (!defined('BRASPAG_LOG')) {
            define('BRASPAG_LOG', $path);
        }
    }
}
