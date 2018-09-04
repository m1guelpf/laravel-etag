<?php

namespace M1guelpf\Etag;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as Middleware;

class EtagMiddleware extends Middleware
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  int|string               $maxAttempts
     * @param  float|int                $decayMinutes
     * @return mixed
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        if (! $request->isMethod('get')) {
            return parent::handle($request, $next, $maxAttempts, $decayMinutes);
        }
        
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        $response = $next($request);

        $response->setEtag($etag = md5($response->getContent()));
        $requestEtag = str_replace('"', '', $request->getETags());

        if ($requestEtag && $requestEtag[0] == $etag) {
            return $this->addHeaders(
                $response->setNotModified(), $maxAttempts,
                $this->calculateRemainingAttempts($key, $maxAttempts)
            );
        }
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            throw $this->buildException($key, $maxAttempts);
        }
        
        $this->limiter->hit($key, $decayMinutes);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
}
