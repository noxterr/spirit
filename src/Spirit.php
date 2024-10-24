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

        if ($b2->failed) {
            throw new \Exception($b2->error);
        }

        else {
            config([
                'spirit.base_url' => $b2->api_url,
            ]);
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
            'method' => 'GET'
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
            $this->setup();
        }

        $result = Native::fetch("/b2api/v3/b2_get_upload_url?bucketId={$this->spirit->bucket_id}", [
            'method' => 'GET',
            'header' => [
                "Authorization: {$this->spirit->authorization_token}"
            ]
        ]);

        if ($result->errcode == 0) {
            // Setup the response
            $parsed_upload_file = \Noxterr\Spirit\Helper\FileUpload::parseGetUploadUrl($result->data);

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

        $uploader = $this->spirit->file;

        $file_content = file_get_contents($file->path);

        // Since I am uploading and the upload process uses the custom upload URL, instead of the base URL
        // I will just change the config and revert the edit at the end

        config([
            'spirit.base_url' => $uploader->upload_url
        ]);

        // Emtpty string for the URl since I don't need anything after the base URL
        $result = Native::fetch('', [
            'method' => 'POST',
            'header' => [
                "Authorization: {$uploader->authorization_token}",
                "Content-Type: application/octet-stream",
                "X-Bz-File-Name: {$file->name}",
                "X-Bz-Content-Sha1: " . sha1($file_content)
            ],
            'post_data' => $file_content
        ]);

        // Revert the config
        config([
            'spirit.base_url' => $this->spirit->api_url
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