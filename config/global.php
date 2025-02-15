<?php
return [
    // App Namespace
    'app_namespace' => env('APP_NAMESPACE', 'App'),
    // Composer Path
    'composer_path' => base_path('vendor/autoload.php'),

    // Mail Settings
    'mail_settings' => [
        'welcome_subject' => env('WELCOME_MAIL_SUBJECT', 'Access data for your new server #DOMAINNAME# at Find A Super Tutor'),
        'suspend_subject' => env('SUSPEND_MAIL_SUBJECT', 'Open invoice / your account #DOMAINNAME# has been blocked'),
        'unsuspend_subject' => env('UNSUSPEND_MAIL_SUBJECT', 'Your account #DOMAINNAME# has been reactivated'),
        'welcome_sender_name' => env('WELCOME_MAIL_SENDER_NAME', 'Find A Super Tutor Accounting'),
        'welcome_sender_email' => env('WELCOME_MAIL_FROM', 'info@findasupertutor.com'),
        'nic_mail_sender_name' => env('NIC_MAIL_SENDER_NAME', 'Find A Super Tutor - Domain Registration'),
        'newsletter_sender_email' => env('NEWSLETTER_FROM', 'news@findasupertutor.com'),
        'newsletter_sender_name' => env('NEWSLETTER_SENDER_NAME', 'Find A Super Tutor'),
        'invoice_sender_email' => env('INVOICE_FROM', 'buha@findasupertutor.com'),
        'invoice_sender_name' => env('INVOICE_SENDER_NAME', 'Find A Super Tutor accounting'),
        'invoice_reply_email' => env('INVOICE_REPLY_TO', 'buha@findasupertutor.com'),
        'admin_email' => env('ADMIN_MAIL', 'admin@findasupertutor.com')
    ],
    'exchangerate_app_id' => env('EXCHANGERATE_APP_ID')
];