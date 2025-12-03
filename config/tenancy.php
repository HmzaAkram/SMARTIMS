<?php
// config/tenancy.php
return [
    'central_domains' => ['smartims.test', 'admin.smartims.test'],
    'tenant_model' => \App\Models\Tenant::class,
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'mysql'),
        'template_tenant_connection' => null,
        'tenant_connection' => 'tenant',
    ],
];