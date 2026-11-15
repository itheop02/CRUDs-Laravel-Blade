<?php

return [
    'admin_user_ids' => array_values(array_filter(array_map('trim', explode(',', env('GDPR_ADMIN_USER_IDS', ''))), fn ($id) => $id !== '')),
    'opa_query_url' => env('OPA_QUERY_URL', 'http://localhost:8181/v1/query'),
    'metadata' => json_decode(file_get_contents(base_path('integration/privacy-config.json')), true, 512, JSON_THROW_ON_ERROR),
];
