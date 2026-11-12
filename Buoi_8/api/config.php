<?php
declare(strict_types=1);

// Cấu hình mặc định cho MySQL của XAMPP trên máy cục bộ.
// Có thể đặt biến môi trường để đổi cấu hình mà không sửa mã nguồn.
return [
    'host' => getenv('BUOI8_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('BUOI8_DB_PORT') ?: '3306',
    'database' => getenv('BUOI8_DB_NAME') ?: 'buoi_8',
    'username' => getenv('BUOI8_DB_USER') ?: 'root',
    'password' => getenv('BUOI8_DB_PASSWORD') ?: '',
];
