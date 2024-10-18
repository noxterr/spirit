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
            Base::setupMultipleKeys();
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
            $this->setup();
        }

        $result = Native::fetch("/b2api/v3/b2_get_upload_url?bucketId={$this->spirit->bucket_id}", [
            'method' => 'GET',
            'header' => [
                "Authorization: {$this->spirit->authorization_token}"
            ]
        ]);

        if ($result->errcode != 0) {
            return $result;
        }

        return null;
    }
}