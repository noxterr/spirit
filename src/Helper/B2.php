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

        if (! isset($response->accountId) || $response->errcode != 1) {
            $b2->failed = true;
            $b2->error = 'Failed to authorize account';
            return $b2;
        }

        $b2->account_id = $response->accountId;
        $b2->api_url = $response->apiInfo->storageApi->apiUrl;
        $b2->s3_api_url = $response->apiInfo->storageApi->s3ApiUrl;
        $b2->download_url = $response->apiInfo->storageApi->downloadUrl;
        $b2->capabilities = $response->apiInfo->storageApi->capabilities;
        $b2->application_key_expiration_timestamp = $response->applicationKeyExpirationTimestamp;
        $b2->authorization_token = $response->authorizationToken;

        return $b2;
    }
}
