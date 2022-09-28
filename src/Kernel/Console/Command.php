<?php

declare(strict_types=1);

namespace Hi\Kernel\Console;

use Hi\Kernel\Argument;

abstract class Command
{
    /**
     * 服务名称
     */
    protected string $title = '';

    /**
     * 命令
     */
    protected string $command = '';

    /**
     * 服务介绍
     */
    protected string $description = '';

    /**
     * 使用示例
     */
    protected string $example = '';

    /**
     * 可用操作
     */
    protected array $actions = [];

    /**
     * The actions service list.
     */
    protected array $options = [];

    public function getTitle()
    {
        return $this->title;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function display()
    {
        $content = '';

        $content .= $this->title . PHP_EOL;
        $content .= $this->description . PHP_EOL;
        $content .= PHP_EOL;

        $content .= '使用示例:' . PHP_EOL;
        $content .= $this->example . PHP_EOL;
        $content .= PHP_EOL;

        foreach ($this->actions as $action => $description) {
            $content .= sprintf('%s %30s', $action, $description) . PHP_EOL;
        }
        $content .= PHP_EOL;

        fwrite(STDOUT, $content);
    }

    public function boot(Argument $argument)
    {
        if (isset($this->actions[$argument->getAction()])) {
            return $this->execute($argument);
        }

        $this->display();
    }

    abstract public function execute(Argument $argument): bool;
}
