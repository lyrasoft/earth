<?php

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2023 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Config;

use Windwalker\Core\Service\ErrorService;

return ErrorService::getReportLevel(
    [
        /**
         * Fatal run-time errors. These indicate errors that can not be
         * recovered from, such as a memory allocation problem.
         * Execution of the script is halted.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_ERROR => true,

        /**
         * Catchable fatal error. It indicates that a probably dangerous error
         * occurred, but did not leave the Engine in an unstable state. If the error
         * is not caught by a user defined handle (see also
         * **set_error_handler**), the application aborts as it
         * was an **E_ERROR**.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_RECOVERABLE_ERROR => true,

        /**
         * Run-time warnings (non-fatal errors). Execution of the script is not
         * halted.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_WARNING => false,

        /**
         * Compile-time parse errors. Parse errors should only be generated by
         * the parser.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_PARSE => true,

        /**
         * Run-time notices. Indicate that the script encountered something that
         * could indicate an error, but could also happen in the normal course of
         * running a script.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_NOTICE => (bool) env('ERROR_REPORT_NOTICE', '1'),

        /**
         * Enable to have PHP suggest changes
         * to your code which will ensure the best interoperability
         * and forward compatibility of your code.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_STRICT => (bool) env('ERROR_REPORT_STRICT', '1'),

        /**
         * Run-time notices. Enable this to receive warnings about code
         * that will not work in future versions.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_DEPRECATED => (bool) env('ERROR_REPORT_DEPRECATED', '1'),

        /**
         * Fatal errors that occur during PHP's initial startup. This is like an
         * **E_ERROR**, except it is generated by the core of PHP.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_CORE_ERROR => true,

        /**
         * Warnings (non-fatal errors) that occur during PHP's initial startup.
         * This is like an **E_WARNING**, except it is generated
         * by the core of PHP.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_CORE_WARNING => (bool) env('ERROR_REPORT_WARNING', '1'),

        /**
         * Fatal compile-time errors. This is like an **E_ERROR**,
         * except it is generated by the Zend Scripting Engine.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_COMPILE_ERROR => true,

        /**
         * Compile-time warnings (non-fatal errors). This is like an
         * **E_WARNING**, except it is generated by the Zend
         * Scripting Engine.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_COMPILE_WARNING => true,

        /**
         * User-generated error message. This is like an
         * **E_ERROR**, except it is generated in PHP code by
         * using the PHP function **trigger_error**.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_USER_ERROR => true,

        /**
         * User-generated warning message. This is like an
         * **E_WARNING**, except it is generated in PHP code by
         * using the PHP function **trigger_error**.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_USER_WARNING => (bool) env('ERROR_REPORT_WARNING', '1'),

        /**
         * User-generated notice message. This is like an
         * **E_NOTICE**, except it is generated in PHP code by
         * using the PHP function **trigger_error**.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_USER_NOTICE => (bool) env('ERROR_REPORT_NOTICE', '1'),

        /**
         * User-generated warning message. This is like an
         * **E_DEPRECATED**, except it is generated in PHP code by
         * using the PHP function **trigger_error**.
         * @link https://php.net/manual/en/errorfunc.constants.php
         */
        E_USER_DEPRECATED => (bool) env('ERROR_REPORT_DEPRECATED', '1'),
    ]
);
