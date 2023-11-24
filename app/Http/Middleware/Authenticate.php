<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Closure;
// use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle(Request $request, Closure $next, $guard = null)
    {
        try {
            $token = JWTAuth::getToken();
            if ($token === null) {
                return response()->json(['status' => false, 'message' => 'Invalid Token', 'code' => 401], 401);
            }
            $id = JWTAuth::decode($token)->toArray();
            $request->id = $id['sub'];
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => false, 'message' => 'Token Expired Exception', 'code' => 401], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => false, 'message' => 'Invalid Token', 'code' => 401], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Internal Server Error', 'code' => 403, 'e' => $e], 403);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Internal Server Error', 'code' => 500], 500);
        }

        return $next($request);
    }
}
