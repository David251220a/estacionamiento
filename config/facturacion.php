<?php

return [

    // Links para QR
    'link_qr' => [
        'produccion' => 'https://ekuatia.set.gov.py/consultas/qr?',
        'test' => 'https://ekuatia.set.gov.py/consultas-test/qr?',
    ],

    'qr_version' => 'nVersion=150&Id=',

    // Carpeta segura para firma digital
    'firma_key_path' => storage_path('app/keys/'),
];
