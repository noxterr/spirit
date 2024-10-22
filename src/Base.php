<?php


namespace Noxterr\Spirit;

use Noxterr\Spirit\Helper\B2;
use Noxterr\Spirit\Helper\ClassReturn;
use Noxterr\Spirit\Helper\Curl;

class Base
{
    /**
     * The default URL of B2 for API calls.
     *
     * @var string
     */
    const DEFAULT_URL = 'https://api.backblazeb2.com';

    /**
     * The default user-agent of B2 for API calls.
     *
     * @var string
     */
    const USER_AGENTS = 'blazer/0.7.1';

    /**
     * The initial function to authorize an account.
     * Endpoint is - b2_authorize_account
     *
     * @return B2
     */
    public static function authorizeAccount($application_key_id, $application_key, $settings = []): B2
    {
        $response = new ClassReturn();

        if (config('spirit.has_multiple_keys')) {
            if ($settings['use_read_key']) {
                $application_key = config('spirit.read_key_id');
            } elseif ($settings['use_write_key']) {
                $application_key = config('spirit.write_key_id');
            }
        }

        $http = new Curl([
            'url' => self::DEFAULT_URL . '/b2api/v2/b2_authorize_account',
            'method' => "GET",
            'header' => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization: Basic ' . base64_encode("{$application_key_id}:{$application_key}")
            ],
        ]);

        try {
            $result = $http->execute();
            if ($result->errcode != 0) {
                $response->message = $result->message;
                $response->errcode = $result->errcode;
            }

            else {
                $data = $http->getData();

                $response->data = json_decode($data);
            }

        } catch (\Exception $e) {
            $response->message = $e->getMessage();
            $response->errcode = 1;
        }

        return B2::parseAuthorizeAccount(
            $response->data
        );
    }

    /**
     * The function to re-authorize an account.
     * Does call authorize account again
     *
     * @return B2
     */
    public static function reAuthorizeAccount($application_key_id, $application_key): B2
    {
        return self::authorizeAccount($application_key_id, $application_key);
    }

    /**
     * Allow users to use (for now) 2 keys for upload/download
     * @return void
     */
    public static function setupMultipleKeys(): void
    {
        config([
            'spirit.has_multiple_keys' => true,
            'spirit.read_key_id' => env('B2_READ_KEY_ID'),
            'spirit.write_key_id' => env('B2_WRITE_KEY_ID'),
        ]);
    }
}