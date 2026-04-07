<?php

return [
    // Thêm các đường dẫn đăng nhập/đăng xuất vào đây
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'add-user'],

    'allowed_methods' => ['*'],

    // Chỉ định chính xác link Client, KHÔNG để dấu gạch chéo (/) ở cuối link
    'allowed_origins' => [
        'https://he-thong-thi-trac-nghiem-client.onrender.com'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];