<?php

declare(strict_types=1);

namespace Hi;

use Closure;
use Hi\Kernel\Config;
use Hi\Kernel\Container;

class Kernel
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * Instance Container
     */
    protected Container $container;

    /**
     * 项目根目录
     */
    protected string $basePath;

    /**
     * The custom storage path defined by the developer.
     */
    protected string $storagePath;

    protected Config $config;

    /**
     * 初始化框架 Kernel 实例
     *
     * @param string $basePath
     */
    public function __construct(string $basePath, Container $container = null)
    {
        if (!static::$instance) {
            static::$instance = $this;
        }

        $this->initBaseEnvVars($basePath);
        $this->initConfig();
        $this->initContainer($container);
    }

    /**
     * 环境变量
     */
    protected function initBaseEnvVars(string $basePath)
    {
        $this->basePath    = $basePath;
        $this->storagePath = $this->path('storage');
    }

    protected function initConfig()
    {
        $this->config = new Config();
    }

    /**
     * 容器初始化（如果未传入则创建新容器）
     */
    protected function initContainer(Container $container = null)
    {
        if ($container) {
            $this->container = $container;
        } else {
            $this->container = new Container();
        }

        $this->container->set('config', $this->config);
        $this->container->set('console', \Hi\Kernel\Console::class);
    }

    /**
     * 返回基于项目根目录的绝对路径
     */
    public function path(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getStoragePath(): string
    {
        return $this->storagePath;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * 加载框架配置
     */
    public function load(Closure $callback): Kernel
    {
        call_user_func($callback, $this->container);

        return $this;
    }

    /**
     * 以命令行模式启动服务
     */
    public function bootstrap(array $argv = [])
    {
        $this->container->get('console')->dispatch($argv);
    }

    /**
     * 以 PHP-FPM 模式启动服务
     */
    public function bootstrapForFpm()
    {
        $this->container->get('console')->dispatch(['', 'http', 'start']);
    }

    public static function instance(): Kernel
    {
        return static::$instance;
    }
}
