<?php


namespace Logistio\Symmetry\Http\Agent;


use Illuminate\Contracts\Support\Arrayable;

class HttpRequestAgent implements Arrayable
{
    /**
     * @var string
     */
    private $deviceName;

    /**
     * @var string
     */
    private $systemName;

    /**
     * @var string
     */
    private $platformVersion;

    /**
     * @var string
     */
    private $browserName;

    /**
     * @var string
     */
    private $browserVersion;

    /**
     * @var bool
     */
    private $isDesktop = false;

    /**
     * @var bool
     */
    private $isTablet = false;

    /**
     * @var bool
     */
    private $isPhone = false;

    /**
     * @var bool
     */
    private $isRobot = false;

    /**
     * @var int
     */
    private $screenWidth;

    /**
     * @var int
     */
    private $screenHeight;

    /**
     * @var string
     */
    private $ip;

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     */
    public function setDeviceName($deviceName)
    {
        $this->deviceName = $deviceName;
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
    }

    /**
     * @return string
     */
    public function getPlatformVersion()
    {
        return $this->platformVersion;
    }

    /**
     * @param string $platformVersion
     */
    public function setPlatformVersion($platformVersion)
    {
        $this->platformVersion = $platformVersion;
    }

    /**
     * @return string
     */
    public function getBrowserName()
    {
        return $this->browserName;
    }

    /**
     * @param string $browserName
     */
    public function setBrowserName($browserName)
    {
        $this->browserName = $browserName;
    }

    /**
     * @return string
     */
    public function getBrowserVersion()
    {
        return $this->browserVersion;
    }

    /**
     * @param string $browserVersion
     */
    public function setBrowserVersion($browserVersion)
    {
        $this->browserVersion = $browserVersion;
    }

    /**
     * @return bool
     */
    public function isDesktop()
    {
        return $this->isDesktop;
    }

    /**
     * @return bool
     */
    public function isTablet()
    {
        return $this->isTablet;
    }

    /**
     * @param bool $isTablet
     */
    public function setIsTablet($isTablet)
    {
        $this->isTablet = $isTablet;
    }

    /**
     * @param bool $isDesktop
     */
    public function setIsDesktop($isDesktop)
    {
        $this->isDesktop = $isDesktop;
    }

    /**
     * @return bool
     */
    public function isPhone()
    {
        return $this->isPhone;
    }

    /**
     * @param bool $isPhone
     */
    public function setIsPhone($isPhone)
    {
        $this->isPhone = $isPhone;
    }

    /**
     * @return bool
     */
    public function isRobot()
    {
        return $this->isRobot;
    }

    /**
     * @param bool $isRobot
     */
    public function setIsRobot($isRobot)
    {
        $this->isRobot = $isRobot;
    }

    /**
     * @return int
     */
    public function getScreenWidth()
    {
        return $this->screenWidth;
    }

    /**
     * @param int $screenWidth
     */
    public function setScreenWidth($screenWidth)
    {
        $this->screenWidth = $screenWidth;
    }

    /**
     * @return int
     */
    public function getScreenHeight()
    {
        return $this->screenHeight;
    }

    /**
     * @param int $screenHeight
     */
    public function setScreenHeight($screenHeight)
    {
        $this->screenHeight = $screenHeight;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function toArray()
    {
        return [
            'ip' => $this->getIp(),
            'device_name' => $this->getDeviceName(),
            'system_name' => $this->getSystemName(),
            'platform_version' => $this->getPlatformVersion(),
            'browser_name' => $this->getBrowserName(),
            'browser_version' => $this->getBrowserVersion(),
            'is_desktop' => $this->isDesktop(),
            'is_tablet' => $this->isTablet(),
            'is_phone' => $this->isPhone(),
            'is_robot' => $this->isRobot(),
            'screen_width' => $this->getScreenWidth(),
            'screen_height' => $this->getScreenHeight(),
        ];
    }


}