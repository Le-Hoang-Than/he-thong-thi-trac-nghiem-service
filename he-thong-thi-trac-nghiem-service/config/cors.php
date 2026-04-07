<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Cho phép tất cả các đường dẫn bắt đầu bằng api/ và link lấy CSRF
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'], 

    'allowed_methods' => ['*'],

    // QUAN TRỌNG: Điền chính xác link trang Client của bạn vào đây
    'allowed_origins' => ['https://he-thong-thi-trac-nghiem-client.onrender.com'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // PHẢI LÀ TRUE để đăng nhập được
    'supports_credentials' => true,
];