<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class HandlePassportRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (request()->path() == 'oauth/authorize') {
            // 1. Capture the initial Passport OAuth parameters from the query string
            if ($request->has('redirect_uri')) {
                session([
                    'passport_client_id'    => $request->query('client_id'),
                    'passport_redirect_uri' => $request->query('redirect_uri'),
                    'passport_scope'        => $request->query('scope'),
                    'passport_state'        => $request->query('state'),
                    'passport_response_type'=> $request->query('response_type', 'code'),
                ]);
            }
        }

        return $next($request);
    }
}
