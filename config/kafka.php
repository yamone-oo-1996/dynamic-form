<?php

/*
|--------------------------------------------------------------------------
| Config for kafka
|--------------------------------------------------------------------------
|
 */

return [
    'topic' => [
        'miko_pdf_generator_topic' =>
        [
            'topic' =>  env('MIKO_PDF_GENERATOR_TOPIC', ''),
            'sasl_username' => env('SASL_MIKO_PDF_GENERATOR_USERNAME', ''),
            'sasl_password' => env('SASL_MIKO_PDF_GENERATOR_PASSWORD', ''),
        ]
    ],
    'kafka_brokers' => env('KAFKA_BROKERS', ''),
    'security_protocol' => env('SECURITY_PROTOCOL', 'sasl_ssl'),
    'sasl_mechanisms' => env('SASL_MECHANISMS', 'PLAIN'),
    'group_id' => env('GROUP_ID', ''),
];
