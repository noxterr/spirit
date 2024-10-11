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
        'base_url' => env('B2_API_URL'),
        'bucket_name' => env('B2_BUCKET_NAME'),
        'bucket_id' => env('B2_BUCKET_ID'),
        'key' => env('B2_KEY'),
    ],
];
