<?php

return [
<<<<<<< HEAD

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

=======
    /*
     * |--------------------------------------------------------------------------
     * | Third Party Services
     * |--------------------------------------------------------------------------
     * |
     * | This file is for storing the credentials for third party services such
     * | as Mailgun, Postmark, AWS and more. This file provides the de facto
     * | location for this type of information, allowing packages to have
     * | a conventional file to locate the various service credentials.
     * |
     */
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
<<<<<<< HEAD

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

=======
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
<<<<<<< HEAD

=======
    'google' => [
        'user_claims_spreadsheet_id' => env('GOOGLE_SHEETS_USER_CLAIMS_ID'),
        'leads_spreadsheet_id' => env('GOOGLE_SHEETS_LEADS_ID'),
        'consultations_spreadsheet_id' => env('GOOGLE_SHEETS_CONSULTATIONS_ID'),
        'sheet_name' => env('GOOGLE_SHEET_NAME', 'Sheet1')
    ],
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
];
