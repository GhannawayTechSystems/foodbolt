<?php

declare(strict_types=1);

/**
 * Application configuration.
 *
 * Adjust these values to match your environment. The app is designed to run
 * with zero external dependencies — just PHP 8.1+ and the built-in web server
 * (`php -S localhost:8000 -t public`).
 */

return [
    'name'      => 'OrderFoodHub',
    'tagline'   => 'Order from multiple kitchens, one cart',

    // Where JSON data files are stored (relative to project root).
    'storage'   => __DIR__ . '/../storage',

    // Admin login credentials. Change these before deploying.
    'admin'     => [
        'username' => 'gts',
        'password' => 'gts1211',
    ],

    // Currency symbol shown in the UI.
    'currency'  => 'Ksh',

    // Order status flow. Each order progresses through these in order.
    'statuses'  => ['pending', 'preparing', 'ready', 'completed', 'cancelled'],

    // Default status assigned to new orders.
    'default_status' => 'pending',
];
