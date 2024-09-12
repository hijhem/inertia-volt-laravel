<?php

declare(strict_types=1);

namespace InertiaVolt\Tests\Unit;

use Exception;
use InertiaVolt\Laravel\Routing\PageHandlerFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionFunction;
use ReflectionMethod;

class PageHandlerFactoryTest extends TestCase
{
    protected ContainerInterface|MockObject $containerMock;
    protected PageHandlerFactory $pageHandlerFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerMock = $this->createPartialMock(ContainerInterface::class, ['get', 'has']);
        $this->pageHandlerFactory = new PageHandlerFactory(
            $this->containerMock,
        );
    }

    public function test_fail_when_invalid_string_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Handler is not a callable or invokable class.');

        $this->containerMock->expects($this->never())->method('get');

        $this->pageHandlerFactory->createHandler('invalid handler');
    }

    public function test_fail_when_non_callable_class_provided(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Handler is not a callable or invokable class.');

        $this->containerMock->expects($this->once())
            ->method('get')
            ->with(NonCallableClass::class)
            ->willReturn(new NonCallableClass());

        $this->pageHandlerFactory->createHandler(NonCallableClass::class);
    }

    public function test_closure(): void
    {
        $closure = fn() => [];

        $this->containerMock->expects($this->never())
            ->method('get');

        [$handler, $reflection] = $this->pageHandlerFactory->createHandler($closure);

        $this->assertIsCallable($handler);
        $this->assertInstanceOf(ReflectionFunction::class, $reflection);
    }

    public function test_callable_class(): void
    {
        $this->containerMock->expects($this->once())
            ->method('get')
            ->with(CallablaClass::class)
            ->willReturn(new CallablaClass());

        [$handler, $reflection] = $this->pageHandlerFactory->createHandler(CallablaClass::class);

        $this->assertIsCallable($handler);
        $this->assertInstanceOf(CallablaClass::class, $handler);
        $this->assertInstanceOf(ReflectionMethod::class, $reflection);
    }
}

class NonCallableClass {}

class CallablaClass
{
    public function __invoke()
    {
        return [];
    }
}
