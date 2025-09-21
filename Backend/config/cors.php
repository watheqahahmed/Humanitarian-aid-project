<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | هذا الملف يحدد إعدادات CORS للسماح للواجهة الأمامية (localhost:5173)
    | بالاتصال مع الـ API في Laravel (localhost:8000).
    |
    */

    // المسارات التي تطبق عليها سياسة CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // الطرق المسموح بها (GET, POST, PUT, DELETE, ...)
    'allowed_methods' => ['*'],

    // المواقع (Origins) المسموح لها بالوصول
    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],

    // أنماط للمطابقة (عادة تترك فارغة)
    'allowed_origins_patterns' => [],

    // الرؤوس (Headers) المسموح بها
    'allowed_headers' => ['*'],

    // الرؤوس (Headers) التي يمكن كشفها للمتصفح
    'exposed_headers' => [],

    // المدة بالثواني لتخزين سياسة CORS في المتصفح
    'max_age' => 0,

    // السماح باستخدام الكوكيز/الجلسات (True مع Sanctum مثلاً)
    'supports_credentials' => true,

];
