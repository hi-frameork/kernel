<?php

declare(strict_types=1);

namespace Hi\Tests;

use Hi\Kernel;
use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase
{
    protected Kernel $kernel;

    protected string $basePath;

    protected function setUp(): void
    {
        $this->basePath = dirname(__DIR__);
        $this->kernel = new Kernel($this->basePath);
    }

    public function testBasePath()
    {
        $this->assertSame(dirname(__DIR__), basePath());
    }

    public function testConfig()
    {
        $this->assertSame('hi-server', config('application.name'));
        $this->assertSame(null, config('server'));
    }

    public function testBastPath()
    {
        $this->assertSame($this->basePath, basePath());
        $this->assertSame($this->kernel->getBasePath(), basePath());
        $this->assertSame($this->basePath . '/src', basePath('src'));
        $this->assertSame($this->basePath . '/src', basePath('/src'));
        $this->assertSame($this->basePath . '/src', basePath('/src/'));
    }

    public function testStoragePath()
    {
        $this->assertSame($this->kernel->getStoragePath(), storagePath());
        $this->assertSame($this->basePath . '/storage/logs', storagePath('logs'));
        $this->assertSame($this->basePath . '/storage/logs', storagePath('/logs'));
        $this->assertSame($this->basePath . '/storage/logs', storagePath('/logs/'));
    }
}
