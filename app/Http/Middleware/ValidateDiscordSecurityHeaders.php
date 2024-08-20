<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use SodiumException;
use Symfony\Component\HttpFoundation\Response;

class ValidateDiscordSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Signature-Ed25519', '');
        $timestamp = $request->header('X-Signature-Timestamp', '');
        $body = file_get_contents( "php://input" );
        $pk = Config::get('app.discord.public_key');
        $valid = false;

        Log::debug($timestamp.$body);

        try {
            $valid = sodium_crypto_sign_verify_detached(
                sodium_hex2bin($signature),
                $timestamp.$body,
                sodium_hex2bin($pk)
            );
        } catch(SodiumException) {
            $valid = false;
        }

        return $valid ? $next($request) : response()->json(['message' => 'Invalid signature'])->setStatusCode(401);
    }
}
