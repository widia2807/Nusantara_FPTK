<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    // === ALIASES ===
    public array $aliases = [
        'csrf'         => \CodeIgniter\Filters\CSRF::class,
        'toolbar'      => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'     => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars' => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders'=> \CodeIgniter\Filters\SecureHeaders::class,

        // custom
        'cors'         => \App\Filters\Cors::class,
        'rememberme' => \App\Filters\RememberMeFilter::class,
    ];

    // === GLOBAL FILTERS ===
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'invalidchars',
            // 'csrf',
            // 'cors', // aktifkan nanti kalau perlu
        ],
        'after'  => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    // === HTTP METHOD-SPECIFIC ===
    public array $methods = [
        // 'post' => ['csrf'],
    ];

    // === PATTERN FILTERS ===
    public array $filters = [
        // 'cors' => [
        //     'before' => ['api/*'],
        // ],
    ];
}
