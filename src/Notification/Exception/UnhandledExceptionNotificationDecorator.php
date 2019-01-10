<?php


namespace Logistio\Symmetry\Notification\Exception;


use Logistio\Symmetry\Auth\UserAuthProvider;
use Logistio\Symmetry\Service\App\Application;

class UnhandledExceptionNotificationDecorator implements IUnhandledExceptionNotificationDecorator
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * UnhandledExceptionNotificationDecorator constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param UnhandledExceptionNotificationModelInterface|UnhandledExceptionNotificationModel $model
     * @param \Exception $exception
     * @return mixed|void
     */
    public function decorate(UnhandledExceptionNotificationModelInterface $model, \Exception $exception)
    {
        $model->message = $exception->getMessage();
        $model->code = $exception->getCode();
        $model->request = request()->path();
        $model->user = $this->getUserEmail();
        $model->file = $exception->getFile() . " at line " . $exception->getLine();
        $model->version = $this->application->getVersion();
    }

    /**
     * @return null
     */
    protected function getUserEmail()
    {
        $user = UserAuthProvider::getAuthUser();

        if ($user) {
            return $user->email;
        }

        return null;
    }

}