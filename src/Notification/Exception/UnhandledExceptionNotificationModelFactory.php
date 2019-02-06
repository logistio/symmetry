<?php

namespace Logistio\Symmetry\Notification\Exception;

use Logistio\Symmetry\Service\App\Application;

class UnhandledExceptionNotificationModelFactory implements IUnhandledExceptionNotificationModelFactory
{
    /**
     * @var IUnhandledExceptionNotificationDecorator
     */
    protected $decorator;

    /**
     * UnhandledExceptionNotificationModelFactory constructor.
     * @param IUnhandledExceptionNotificationDecorator|null $decorator
     */
    public function __construct(IUnhandledExceptionNotificationDecorator $decorator = null)
    {
        if (!$decorator) {
            $decorator = new UnhandledExceptionNotificationDecorator(app()->make(Application::class));
        }

        $this->decorator = $decorator;
    }

    /**
     * @param \Exception $exception
     * @return UnhandledExceptionNotificationModelInterface
     */
    public function makeModel(\Exception $exception): UnhandledExceptionNotificationModelInterface
    {
        $model = new UnhandledExceptionNotificationModel();

        $this->decorator->decorate($model, $exception);

        return $model;
    }
}