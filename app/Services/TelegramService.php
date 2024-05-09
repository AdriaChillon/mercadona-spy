<?php
namespace App\Services;

use TelegramBot\Api\BotApi;

class TelegramService
{
    protected $bot;

    public function __construct()
    {
        $this->bot = new BotApi(config('services.telegram.token'));
    }

    public function sendMessage($chatId, $message)
    {
        $this->bot->sendMessage($chatId, $message);
    }
}
