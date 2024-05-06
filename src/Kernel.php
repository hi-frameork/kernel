<?php

declare(strict_types=1);

namespace Hi;

use Spiral\Core\Container;
use Spiral\Exceptions\ExceptionHandler;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\Exceptions\ExceptionRendererInterface;
use Spiral\Exceptions\ExceptionReporterInterface;

class Kernel
{
    public function __construct(
        string $basePath,
        ExceptionHandlerInterface|string|null $exceptionHandler = null,
        protected Container $container = new Container(),
    ) {
        $this->initExceptionHandler($exceptionHandler);
    }

    protected function initExceptionHandler(
        ExceptionHandlerInterface|string|null $exceptionHandler,
        bool $handleErrors = true
    ): void {
        $exceptionHandler ??= ExceptionHandler::class;
        if (\is_string($exceptionHandler)) {
            $exceptionHandler = $this->container->make($exceptionHandler);
        }
        assert($exceptionHandler instanceof ExceptionHandlerInterface);

        $exceptionHandler->register();

        $this->container->bindSingleton(ExceptionHandlerInterface::class, $exceptionHandler);
        $this->container->bindSingleton(ExceptionRendererInterface::class, $exceptionHandler);
        $this->container->bindSingleton(ExceptionReporterInterface::class, $exceptionHandler);
        $this->container->bindSingleton(ExceptionHandler::class, $exceptionHandler);
    }

    public function bootstrap(): void
    {
    }
}
