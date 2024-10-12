<?php


namespace Noxterr\Spirit;

class Spirit
{
    /**
     * The Cashier library version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The Cashier library version.
     *
     * @var string
     */
    const NATIVE = \Noxterr\Spirit\Native;

    /**
     * Spirit show all the buckets in the account
     *
     * @return array
     */
    public static function listBuckets()
    {
        $cr = new \Noxterr\Spirit\Helper\ClassReturn();

        $response = static::NATIVE::fetch(static::NATIVE::init()['list_buckets']);

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