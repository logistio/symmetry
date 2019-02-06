<?php

namespace Logistio\Symmetry\Test\Notification\Exception;

use Logistio\Symmetry\Notification\Exception\UnhandledExceptionNotificationModelFactory;
use Logistio\Symmetry\Service\App\Application;
use Logistio\Symmetry\Test\TestCase;

class UnhandledExceptionNotificationFactoryTest extends TestCase
{
    public function test_it_creates_unhandled_exception_notification_model()
    {
        $exception = new \Exception("The exception", 500);

        app()->singleton(Application::class, function() {
            return $this->getApplicationMock();
        });

        $factory = new UnhandledExceptionNotificationModelFactory();

        $model = $factory->makeModel($exception);

        $this->assertEquals(500, $model->code);
        $this->assertEquals("The exception", $model->message);
        $this->assertEquals("/", $model->request);
        $this->assertEquals(null, $model->user);
        $this->assertEquals("V0.0.1", $model->version);
    }

    private function getApplicationMock()
    {
        $mock =  \Mockery::mock(Application::class);

        $mock->shouldReceive('getVersion')
            ->andReturn('V0.0.1');

        return $mock;
    }
}