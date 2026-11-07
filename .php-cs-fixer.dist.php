<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(
        [
            __DIR__ . '/bin',
            __DIR__ . '/etc',
            __DIR__ . '/resources',
            __DIR__ . '/routes',
            __DIR__ . '/src',
            __DIR__ . '/tests',
            __DIR__ . '/views',
        ]
    )
    ->exclude(
        [
            'tmp',
            'cache',
        ]
    )
    ->name('*.php')
    ->notName(
        [
            '*.blade.php',
            '*.edge.php',
        ]
    );

return new Config()
    ->setRiskyAllowed(true)
    ->setRules(
        include __DIR__ . '/vendor/windwalker/core/.php-cs-fixer.rules.php'
    )
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setFinder($finder);
