<?php

namespace Noxterr\Spirit;

use Noxterr\Spirit\Helper\ClassReturn;

class Native {

    public static function fetch($url, $settings)
    {
        $classReturn = new ClassReturn();

        $http = new \Noxterr\Spirit\Helper\Curl([
            'url' => config('spirit.base_url') . $url,
            'method' => $settings['method'] ?? 'GET',
            'header' => [
                'Accept: application/json',
                ...$settings['header'] ?? []
            ],
            'post_data' => $settings['post_data'] ?? []
        ]);

        try {
            $result = $http->execute();
            if ($result->errcode != 0) {
                return $result;
            }

            $data = $http->getData();

            $classReturn->data = json_decode($data);
        } catch (\Exception $e) {
            $classReturn->message = $e->getMessage();
            $classReturn->errcode = 1;
        }

        return $classReturn;
    }
}