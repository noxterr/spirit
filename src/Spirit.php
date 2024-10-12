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

        $response = \Noxterr\Spirit\Native::fetch(config('base_url'), '/b2api/v3/b2_list_buckets');

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
}