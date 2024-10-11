<?php

declare(strict_types=1);

namespace Noxterr\Spirit\Console\Commands;

use Illuminate\Console\Command;

final class SpiritInitializerCommand extends Command
{
    protected $signature = "spirit:init {applicationKeyID : the application key ID} {applicationKey : the application key}";

    protected $description = "Initializes a new B2 authorized flow, returning the token and base URL";

    protected $type = 'B2 Storage authorization';

    public function handle()
    {
        $response = new \Noxterr\Spirit\Helper\ClassReturn();

        $applicationKeyID = $this->argument('applicationKeyID');
        $applicationKey = $this->argument('applicationKey');

        $http = new \Noxterr\Spirit\Helper\Curl([
            'url' => 'https://api.backblazeb2.com/b2api/v2/b2_authorize_account',
            'protocol' => "URL",
            'header' => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization: Basic ' . base64_encode($applicationKeyID . ':' . $applicationKey)
            ],
        ]);


        try {
            $result = $http->execute();
            if ($result->errcode != 0) {
                return $result;
            }

            $data = $http->getData();

            $response->data = json_decode($data);
        } catch (\Exception $e) {
            $response->message = $e->getMessage();
            $response->errcode = 1;
        }

        // The keys should be accountId, authorizationToken, apiUrl, downloadUrl, recommendedPartSize, absoluteMinimumPartSize
        return $response;
    }
}