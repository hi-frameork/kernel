<?php

declare(strict_types=1);

namespace Hi\Kernel;

use Hi\Kernel\Console\Command;
use Hi\Kernel\Console\DefaultCommand;
use Psr\Container\ContainerInterface;

class Console
{
    protected Container $continer;

    /**
     * @var string[]
     */
    protected $registers = [];

    /**
     * @var Command[]
     */
    protected $commands = [];

    public function __construct(ContainerInterface $container)
    {
        $this->continer = $container;
    }

    /**
     * @param string[]
     */
    public function withCommands(array $commands)
    {
        $this->registers = array_merge($this->registers, $commands);
    }

    public function dispatch(array $argv)
    {
        $this->loadCommand();

        $argument = new Argument($argv);
        $command  = $argument->getCommand();

        // 输入命令存在定义中
        if (isset($this->commands[$command])) {
            return $this->commands[$command]->boot($argument);
        }

        // 输入命令不存在定义中，执行默认命令
        return (new DefaultCommand())
            ->withActions($this->commands)
            ->boot($argument)
        ;
    }

    protected function loadCommand()
    {
        foreach ($this->registers as $definition) {
            $this->continer->attempt($definition, $definition);
        }

        foreach ($this->registers as $definition) {
            /** @var Command $instance */
            $instance                                = $this->continer->get($definition);
            $this->commands[$instance->getCommand()] = $instance;
        }
    }
}
