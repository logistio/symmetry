<?php


namespace Logistio\Symmetry\Http\Middleware\Cors;

use Logistio\Symmetry\Http\Cors\CorsService;
use Illuminate\Http\Request;
use Closure;

class CorsMiddleware
{
    /**
     * @var CorsService
     */
    private $corsService;

    /**
     * CorsMiddleware constructor.
     * @param CorsService $corsService
     */
    public function __construct(CorsService $corsService)
    {
        $this->corsService = $corsService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->corsService->isPreflightRequest($request)) {
            return $this->corsService->handlePreflightRequest($request);
        }

        $response = $next($request);

        if ($this->corsService->isCorsRequest($request)) {
            $this->corsService->setCorsResponseHeaders($response, $request);
        }

        return $response;
    }
}