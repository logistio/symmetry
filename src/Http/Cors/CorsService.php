<?php


namespace Logistio\Symmetry\Http\Cors;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CorsService
 * @package SquareRoute\Http\Cors
 *
 * A service to handle CORS requests from clients.
 *
 */
class CorsService
{
    const HTTP_OPTIONS_METHOD = 'OPTIONS';

    /**
     * A CORS request can be recognised when an Origin
     * header is present and the Origin is NOT
     * the same as the host.
     *
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request)
    {
        return $request->headers->has('Origin') && !$this->isSameHost($request);
    }

    /**
     * A `Preflight` request is sent by the browser when a
     * NON standard request is sent to a cross origin
     * domain. The browser automatically defines
     * the HTTP request method as `OPTIONS`
     * and adds the `Access-Control-Request-Method` for
     * the ACTUAL HTTP request method the client is trying to make.
     *
     * @param Request $request
     * @return bool
     */
    public function isPreflightRequest(Request $request)
    {
        return ($this->isCorsRequest($request))
            && ($request->getMethod() == static::HTTP_OPTIONS_METHOD)
            && ($request->headers->has('Access-Control-Request-Method'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSameHost(Request $request)
    {
        return $request->headers->get('Origin') === $request->getSchemeAndHttpHost();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handlePreflightRequest(Request $request)
    {
        /** @var JsonResponse $response */
        $response = \response()->json();

        $this->setCorsResponseHeaders($response, $request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param Request $request
     */
    public function setCorsResponseHeaders($response, Request $request)
    {
        $response->headers->set('Access-Control-Allow-Credentials', true);
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        $response->headers->set('Access-Control-Max-Age', '0');
        $response->headers->set('Access-Control-Allow-Methods', '*');

        $response->headers->set('Access-Control-Expose-Headers', implode(',', [
            'Content-Disposition'
        ]));

        // $request->headers->get('Access-Control-Request-Headers')
        $response->headers->set('Access-Control-Allow-Headers',
            $request->headers->get('Access-Control-Request-Headers'));
    }
}