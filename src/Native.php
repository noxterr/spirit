<?php

namespace Noxterr\Spirit;

class Native {

    public static function fetch($url, $settings)
    {
        $http = new \Noxterr\Spirit\Helper\Curl([
            'url' => config('spirit.base_url') . $url,
            'protocol' => $settings['protocol'] ?? 'GET',
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

            return json_decode($data);
        } catch (\Exception $e) {
            return json_encode([
                'message' => $e->getMessage(),
                'errcode' => 1
            ]);
        }
    }
}