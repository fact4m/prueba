<?php

return [
    'app_url_base' => env('APP_URL_BASE'),
    'number_manual' => env('NUMBER_MANUAL',false),
    'items_per_page' => env('ITEMS_PER_PAGE', 20),
    'password_change' => env('PASSWORD_CHANGE', false),
    'prefix_database' => env('PREFIX_DATABASE', 'tenancy'),

    'signature_note' => env('SIGNATURE_NOTE', 'FACTURALO'),
    'signature_uri' => env('SIGNATURE_URI', 'signatureFACTURALO'),

    'document_type_03_filter' => env('DOCUMENT_TYPE_03_FILTER', true),    
    'select_first_document_type_03' => env('SELECT_FIRST_DOCUMENT_TYPE_03', false),
    'select_customer_various' => env('SELECT_CUSTOMER_VARIOUS', false),
    'custom_ticket_format' => env('CUSTOM_TICKET_FORMAT', false),
    'is_franchise' => env('IS_FRANCHISE', false),

];
