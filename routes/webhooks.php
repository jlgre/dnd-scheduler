<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscordWebhookController;
use App\Http\Middleware\ValidateDiscordSecurityHeaders;

Route::post('/discord', [DiscordWebhookController::class, 'handle'])
    ->middleware([ValidateDiscordSecurityHeaders::class]);
