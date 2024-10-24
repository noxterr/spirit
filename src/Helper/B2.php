<?php

namespace Noxterr\Spirit\Helper;


class B2
{
    public $account_id = null;

    public $application_key_expiration_timestamp = null;

    public $api_url = null;

    public $s3_api_url = null;

    public $download_url = null;

    public $authorization_token = null;

    public $capabilities = [];

    public $failed = false;

    public $error = null;

    public $file = null;

    public static function parseAuthorizeAccount($response): B2
    {
        $b2 = new self();

        if (! isset($response->accountId) || (isset($response->code) && $response->code == 401)) {
            $b2->failed = true;
            $b2->error = 'Failed to authorize account';
            return $b2;
        }

        // I get a different response from the API than the documentation (Postman)
        // Instead of getting `apiInfo`, I get `allowed` and the keys are different
        // I will check those uniquely

        if (isset($response->allowed)) {
            if (isset($response->allowed->capabilities)) {
                $b2->capabilities = $response->allowed->capabilities;
            }

            $b2->account_id = $response->accountId;
            $b2->api_url = $response->apiUrl;
            $b2->s3_api_url = $response->s3ApiUrl;
            $b2->download_url = $response->downloadUrl;
            $b2->authorization_token = $response->authorizationToken;
        }

        elseif (isset($response->apiInfo)) {
            if (isset($response->apiInfo->capabilities)) {
                $b2->capabilities = $response->apiInfo->storageApi->capabilities;
            }

            $b2->account_id = $response->accountId;
            $b2->api_url = $response->apiInfo->storageApi->apiUrl;
            $b2->s3_api_url = $response->apiInfo->storageApi->s3ApiUrl;
            $b2->download_url = $response->apiInfo->storageApi->downloadUrl;
            $b2->application_key_expiration_timestamp = $response->applicationKeyExpirationTimestamp;
            $b2->authorization_token = $response->authorizationToken;
        }

        else {
            $b2->failed = true;
            $b2->error = 'Failed to authorize account';
        }

        return $b2;
    }
}
