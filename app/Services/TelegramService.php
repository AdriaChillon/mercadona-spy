<?php
// app/Services/TelegramService.php

namespace App\Services;

use TelegramBot\Api\BotApi;

class TelegramService
{
    protected $bot;
    protected $chatId;

    public function __construct()
    {
        $this->bot = new BotApi(config('services.telegram.token'));
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage($message)
    {
        if (!$this->chatId) {
            throw new \Exception("Chat ID is not set");
        }
        $this->bot->sendMessage($this->chatId, $message);
    }
}
