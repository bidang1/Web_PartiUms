<?php

return [
    'active_year' => env('PARTI_ACTIVE_YEAR', 2026),
    'max_upload_size_mb' => env('MAX_UPLOAD_SIZE_MB', 10),
    'allowed_file_types' => ['pdf', 'docx'],
    'allowed_mimes' => [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ],
    'gform_domains' => ['docs.google.com/forms', 'forms.gle'],

    // Konfigurasi akun media sosial resmi dan pengaturan SEO
    'socials' => [
        'parti' => [
            'instagram' => env('SOCIAL_PARTI_INSTAGRAM', 'https://www.instagram.com/parti.ums/'),
            'tiktok' => env('SOCIAL_PARTI_TIKTOK', 'https://www.tiktok.com/@parti.ums?is_from_webapp=1&sender_device=pc'),
        ],
        'himatif' => [
            'instagram' => env('SOCIAL_HIMATIF_INSTAGRAM', 'https://www.instagram.com/himatifums/'),
        ],
    ],
    'seo' => [
        'twitter_handle' => env('SEO_TWITTER_HANDLE', '@himatif_ums'),
    ],
];
