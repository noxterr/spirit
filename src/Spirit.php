<?php


namespace Noxterr\Spirit;

class Spirit
{
    /**
     * The Spirit library version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Spirit show all the buckets in the account
     *
     * @return array
     */
    public static function listBuckets()
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

    public static function listFiles(string $bucket_id)
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
}