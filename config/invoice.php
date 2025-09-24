<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SUNAT Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para la integración con SUNAT usando Greenter.
    | Los valores se obtienen de las variables de entorno.
    |
    */

    'ruc' => env('SUNAT_RUC'),
    'user' => env('SUNAT_USER'),
    'password' => env('SUNAT_PASSWORD'),
    'certificate_path' => env('SUNAT_CERTIFICATE_PATH'),
    'environment' => env('SUNAT_ENVIRONMENT', 'beta'),
];
