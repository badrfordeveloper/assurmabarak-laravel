<?php

return [

    'paths' => ['api/*'], // Apply CORS to API routes only

    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, etc.)

    'allowed_origins' => ['https://assurmabarak.com'], // Your frontend domain

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Allow cookies/auth headers
];
