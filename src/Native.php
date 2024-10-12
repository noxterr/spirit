<?php

namespace Noxterr\Spirit;

class Native {

    public static function init()
    {
        return [
            'list_buckets' => '/b2api/v3/b2_list_buckets',
        ];
    }

    public static function fetch($url, $settings)
    {
        $http = new \Noxterr\Spirit\Helper\Curl([
            'url' => config('spirit.base_url') . $url,
            'protocol' => "URL",
            'header' => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization: Bearer ' . config('spirit.key')
            ],
        ]);

        try {
            $result = $http->execute();
            if ($result->errcode != 0) {
                return $result;
            }

            $data = $http->getData();

            return json_decode($data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}