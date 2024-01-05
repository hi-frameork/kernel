<?php

declare(strict_types=1);

namespace Hi\Kernel\Console;

use AlecRabbit\ConsoleColour\Themes;
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
        $theme = new Themes();

        // 打印标题/标头
        $content = $theme->dark($this->title) . PHP_EOL;
        if ($this->description) {
            $content .= $theme->dark($this->description) . PHP_EOL;
        }
        fwrite(STDOUT, $content . PHP_EOL);

        // 打印使用示例
        $content = $theme->green('使用示例:') . PHP_EOL;
        $example = $this->example ?: './bootstrap.php ' . $this->command . ' [action] [options]';
        $content .= '  ' . $theme->cyan($example) . PHP_EOL;
        fwrite(STDOUT, $content . PHP_EOL);

        // 打印可用操作
        $maxLen = 0;
        foreach ($this->actions as $action => $description) {
            $maxLen = strlen($action) > $maxLen ? strlen($action) : $maxLen;
        }
        $maxLen += 4;
        $content = $theme->green('可用命令:') . PHP_EOL;
        foreach ($this->actions as $action => $description) {
            $content .= '  ' . $theme->bold(str_pad($action, $maxLen, ' ', STR_PAD_RIGHT)) . $description . PHP_EOL;
        }
        fwrite(STDOUT, $content . PHP_EOL);
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
