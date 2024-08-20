<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class DiscordService
{
    private static $instance;
    private $token;
    private $headers;
    private $appId;
    private $baseUrl;

    private function __construct()
    {
        $this->token = Config::get('app.discord.token');
        $this->appId = Config::get('app.discord.app_id');
        $this->headers = $this->generateHeaders($this->token);
        $this->baseUrl = 'https://discord.com/api/v10';
    }

    private function generateHeaders($token)
    {
        return [
            'Authorization' => 'Bot '.$token
        ];
    }

    public static function inst(): self
    {
        return isset(self::$instance) ? self::$instance : new self();
    }

    private function request($method, $url, $body = null)
    {
        return Http::withHeaders($this->headers)->acceptJson()->$method($this->baseUrl.'/'.$url, $body);
    }

    public function getCommands()
    {
        return $this->request('get', '/applications/'.$this->appId.'/commands');
    }

    public function deleteCommand($id)
    {
        return $this->request('delete', '/applications/'.$this->appId.'/commands/'.$id);
    }

    public function registerCommand($name, $description)
    {
        return $this->request('post', '/applications/'.$this->appId.'/commands', [
            'name' => $name,
            'description' => $description,
        ]);
    }
}
