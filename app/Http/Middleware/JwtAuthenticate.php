<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthenticate extends BaseMiddleware
{
    /**
     * check JWT token
     *
     * @param [type] $request
     * @param Closure $next
     * @return void
     */
    public function handle($request, Closure $next)
    {
        // Log::debug('REQUEST FROM CLIENT' . print_r($request->header(), true));

        try {
            $token = JWTAuth::setRequest($request)->parseToken();

            $payLoad = $token->getPayload()->toArray();
            Log::debug('Client Payload: ' . print_r($payLoad, true));

            if (!empty($payLoad) && isset($payLoad['iss']) && !empty($payLoad['iss'])) {
                Log::Debug('Client id Authenticated');
                return $next($request);
            }
            return response()->json(['status' => 'Token Miss Match'], 401);
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => 'Token is Expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => 'Token is Invalid'], 401);
        } catch (JWTException $e) {
            return response()->json([['status' => 'Authorization Token not found']], 401);
        }
        return response()->json(['status' => 'Jwt Authentication failure'], 406);
    }
}
