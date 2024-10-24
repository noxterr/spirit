<?php

namespace Noxterr\Spirit\Helper;


class FileUpload {

    public $file = null;

    public $upload_url = "";

    public $authorization_token = "";

    public static function parseGetUploadUrl($response): FileUpload
    {
        $fileUpload = new self();

        if (! isset($response->authorizationToken)) {
            $fileUpload->failed = true;
            $fileUpload->error = 'Failed to get upload url';
            return $fileUpload;
        }

        $fileUpload->upload_url = $response->uploadUrl;
        $fileUpload->authorization_token = $response->authorizationToken;

        return $fileUpload;
    }
}