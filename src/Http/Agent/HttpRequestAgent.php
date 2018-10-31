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
    public function getDeviceName(): string
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     */
    public function setDeviceName(string $deviceName): void
    {
        $this->deviceName = $deviceName;
    }

    /**
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName(string $systemName): void
    {
        $this->systemName = $systemName;
    }

    /**
     * @return string
     */
    public function getPlatformVersion(): string
    {
        return $this->platformVersion;
    }

    /**
     * @param string $platformVersion
     */
    public function setPlatformVersion(string $platformVersion): void
    {
        $this->platformVersion = $platformVersion;
    }

    /**
     * @return string
     */
    public function getBrowserName(): string
    {
        return $this->browserName;
    }

    /**
     * @param string $browserName
     */
    public function setBrowserName(string $browserName): void
    {
        $this->browserName = $browserName;
    }

    /**
     * @return string
     */
    public function getBrowserVersion(): string
    {
        return $this->browserVersion;
    }

    /**
     * @param string $browserVersion
     */
    public function setBrowserVersion(string $browserVersion): void
    {
        $this->browserVersion = $browserVersion;
    }

    /**
     * @return bool
     */
    public function isDesktop(): bool
    {
        return $this->isDesktop;
    }

    /**
     * @return bool
     */
    public function isTablet(): bool
    {
        return $this->isTablet;
    }

    /**
     * @param bool $isTablet
     */
    public function setIsTablet(bool $isTablet): void
    {
        $this->isTablet = $isTablet;
    }

    /**
     * @param bool $isDesktop
     */
    public function setIsDesktop(bool $isDesktop): void
    {
        $this->isDesktop = $isDesktop;
    }

    /**
     * @return bool
     */
    public function isPhone(): bool
    {
        return $this->isPhone;
    }

    /**
     * @param bool $isPhone
     */
    public function setIsPhone(bool $isPhone): void
    {
        $this->isPhone = $isPhone;
    }

    /**
     * @return bool
     */
    public function isRobot(): bool
    {
        return $this->isRobot;
    }

    /**
     * @param bool $isRobot
     */
    public function setIsRobot(bool $isRobot): void
    {
        $this->isRobot = $isRobot;
    }

    /**
     * @return int
     */
    public function getScreenWidth(): int
    {
        return $this->screenWidth;
    }

    /**
     * @param int $screenWidth
     */
    public function setScreenWidth(int $screenWidth): void
    {
        $this->screenWidth = $screenWidth;
    }

    /**
     * @return int
     */
    public function getScreenHeight(): int
    {
        return $this->screenHeight;
    }

    /**
     * @param int $screenHeight
     */
    public function setScreenHeight(int $screenHeight): void
    {
        $this->screenHeight = $screenHeight;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
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