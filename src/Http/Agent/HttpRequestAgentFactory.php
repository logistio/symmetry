<?php

namespace Logistio\Symmetry\Http\Agent;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HttpRequestAgentFactory
{
    /**
     * @param Agent $agent
     * @param Request $request
     * @return HttpRequestAgent
     */
    public function make(Agent $agent, Request $request)
    {
        $httpRequestAgent = new HttpRequestAgent();

        $httpRequestAgent->setIp($request->ip());

        $httpRequestAgent->setDeviceName($agent->device());

        $httpRequestAgent->setSystemName($agent->platform());

        $httpRequestAgent->setPlatformVersion($agent->version($agent->platform()));

        $httpRequestAgent->setBrowserName($agent->browser());

        $httpRequestAgent->setBrowserVersion($agent->version($agent->browser()));

        $httpRequestAgent->setIsDesktop($agent->isDesktop());

        $httpRequestAgent->setIsTablet($agent->isTablet());

        $httpRequestAgent->setIsPhone($agent->isPhone());

        $httpRequestAgent->setIsRobot($agent->isRobot());

        $deviceScreenCookie = $request->cookie('device_screen');

        if ($deviceScreenCookie) {
            $screenDimensions = explode(";", $deviceScreenCookie);

            $httpRequestAgent->setScreenWidth(array_get($screenDimensions, 0));
            $httpRequestAgent->setScreenHeight(array_get($screenDimensions, 1));
        }

        return $httpRequestAgent;
    }
}