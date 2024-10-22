<?php


namespace Noxterr\Spirit;

use Noxterr\Spirit\Helper\B2;

class Spirit
{
    /**
     * The Spirit library version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The Spirit main object.
     *
     * @var mixed
     */
    protected $spirit;

    /**
     * The Spirit constructor, to setup default variables.
     */
    public function __construct($settings = [])
    {
        $this->setup($settings);
    }

    /**
     * The Setup function for default variables.
     *
     */
    protected function setup($settings = [])
    {
        $b2 = Base::authorizeAccount(
            config('spirit.account_id'),
            config('spirit.key')
        );

        if (
            isset($settings['has_multiple_keys']) &&
            $settings['has_multiple_keys'])
        {
            // Enable multiple keys
            Base::setupMultipleKeys();

            // Authorize keys for operation
            // I can specify the usage of multiple keys here anyway.
            // If they are not set, the default keys will be used.
            $b2 = Base::authorizeAccount(
                config('spirit.account_id'),
                config('spirit.key'),
                $settings
            );
        }

        $this->spirit = $b2;

        $this->spirit->bucket_id = config('spirit.bucket_id');
    }

    /**
     * Spirit show all the buckets in the account
     *
     * @return array
     */
    public function listBuckets()
    {
        $cr = new \Noxterr\Spirit\Helper\ClassReturn();

        $response = \Noxterr\Spirit\Native::fetch( '/b2api/v3/b2_list_buckets', [
            'protocol' => 'GET'
        ]);

        if (isset($response->buckets)) {
            $cr->errcode = 0;
            $cr->data = $response->buckets;
        }

        else {
            $cr->errcode = 1;
            $cr->data = $response;
        }

        return $response;
    }

    /**
     * Spirit show all the files in the bucket
     *
     * @param string $bucket_id
     * @return array
     */

    public function listFiles(string $bucket_id)
    {
        $cr = new \Noxterr\Spirit\Helper\ClassReturn();

        $response = \Noxterr\Spirit\Native::fetch( '/b2api/v3/b2_list_file_names', [
            'post_data' => [
                'bucketId' => $bucket_id
            ]
        ]);

        if (isset($response->files)) {
            $cr->errcode = 0;
            $cr->data = $response->files;
        }

        else {
            $cr->errcode = 1;
            $cr->data = $response;
        }

        return $response;
    }

    /**
     * Get the data in order to upload a file.
     * Endpoint is - b2_get_upload_url
     *
     * @return mixed
     */
    public function getUploadUrl(): mixed
    {
        if (! $this->spirit) {
            $this->setup([
                'use_read_key' => true,
            ]);
        }

        $result = Native::fetch("/b2api/v3/b2_get_upload_url?bucketId={$this->spirit->bucket_id}", [
            'method' => 'GET',
            'header' => [
                "Authorization: {$this->spirit->authorization_token}"
            ]
        ]);

        if ($result->errcode != 0) {
            // Setup the response
            $parsed_upload_file = \Noxterr\Spirit\Helper\FileUpload::parseGetUploadUrl($result);

            $this->spirit->file = $parsed_upload_file;

            return $parsed_upload_file;
        }

        return null;
    }

    /**
     * Upload a file to the bucket.
     * Endpoint is - b2_upload_file
     *
     * @param mixed $file
     * @return mixed
     */
    public function uploadFile($file): mixed
    {
        if (! $this->spirit->file) {
            $this->getUploadUrl();
        }

        $uploadUrl = $this->spirit->file->upload_url;

        $file_content = file_get_contents($file->path);

        $result = Native::fetch($uploadUrl->upload_url, [
            'method' => 'POST',
            'header' => [
                "Authorization: {$uploadUrl->authorization_token}",
                "Content-Type: application/octet-stream",
                "X-Bz-File-Name: {$file->name}",
                "X-Bz-Content-Sha1: " . sha1_file($file_content)
            ],
            'post_data' => $file_content
        ]);

        return $result;
    }

    /**
     * Download a file from the bucket.
     * Endpoint is - b2_download_file_by_id
     *
     * @param string $file_name
     * @return mixed
     */
    public function downloadFile(string $file_name): mixed
    {
        $cr = new \Noxterr\Spirit\Helper\ClassReturn();

        $response = Native::fetch( $this->spirit->download_url . '/file/'. config('spirit.bucket_name') . "/{$file_name}", [
            'post_data' => [
                'bucketName' => config('spirit.bucket_name'),
                'fileName' => $file_name
            ]
        ]);

        if (isset($response->fileContent)) {
            $cr->errcode = 0;
            $cr->data = $response->fileContent;
        }

        else {
            $cr->errcode = 1;
            $cr->data = $response;
        }

        return $response;
    }
}