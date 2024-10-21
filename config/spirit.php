<?php



return [

    /*
    |--------------------------------------------------------------------------
    | B2 variables
    |--------------------------------------------------------------------------
    |
    | You will have setup in the config all the environemnt variables regarding
    | the B2 storage service. Those include the URL, bucket name and ID and keys
    | to avoid any confusion with other Laravel variables, we will use the prefix
    | 'B2_' for all the variables and keep the config under the 'spirit' key.
    | the usage is config('spirit.{name}')
    |
    */

    'spirit' => [
        'bucket_id' => env('B2_BUCKET_ID'),
        'bucket_name' => env('B2_BUCKET_NAME'),
        'key' => env('B2_KEY'),
        'account_id' => env('B2_ACCOUNT_ID'),
        'application_key_id' => env('B2_ACCOUNT_ID'),
    ],
];
