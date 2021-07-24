<?php

declare(strict_types=1);

namespace DiscordAPI\manager;

use DiscordAPI\webhook\Webhook;
use pocketmine\scheduler\BulkCurlTask;
use pocketmine\Server;

class WebhookManager{

    public static function sendWebhook(Webhook $webhook, string $url) : void{
        $server = Server::getInstance();

        $server->getAsyncPool()->submitTask(new class($url, $webhook) extends BulkCurlTask{
            public function __construct($url, $webhook){
                parent::__construct([
                    [
                        "page" => $url,
                        "extraOpts" => [
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: application/json"
                            ],
                            CURLOPT_POSTFIELDS => json_encode($webhook),
                            CURLOPT_POST => true,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_SSL_VERIFYPEER => false,
                        ]
                    ]
                ]);
            }
        });
    }
}