<?php

declare(strict_types=1);

namespace Hi\Tests;

use Hi\Kernel;
use Hi\Kernel\Console;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class KernelTest extends TestCase
{
    /**
     * @var Kernel
     */
    protected $kernel;

    protected function setUp(): void
    {
        $this->kernel = new Kernel(dirname(__DIR__));
    }

    public function testCreateDefault()
    {
        $basepath = dirname(__DIR__);
        $kernel = new Kernel($basepath);

        $this->assertSame($basepath, $kernel->getBasePath());
        $this->assertSame($basepath . DIRECTORY_SEPARATOR . 'storage', $kernel->getStoragePath());

        $this->assertInstanceOf(ContainerInterface::class, $kernel->getContainer());
        $this->assertTrue($kernel->getContainer()->has('console'));
        $this->assertInstanceOf(Console::class, $kernel->getContainer()->get('console', false, [$kernel->getContainer()]));
    }

    // public function testLoad()
    // {
    //     $this->kernel->load(function (ContainerInterface $container) {
    //         $container->get('console')->add([]);
    //     });
    // }
}
