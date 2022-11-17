<?php

declare(strict_types=1);

namespace Hi\Kernel\Console;

use Hi\Kernel\Argument;

class DefaultCommand extends Command
{
    /**
     * 服务名称
     */
    protected string $title = '> Hi Framework';

    /**
     * 服务别名
     */
    protected string $command = '';

    /**
     * 服务介绍
     */
    protected string $description = '  Keep simple, keep reliable.';

    /**
     * 使用示例
     */
    protected string $example = './bootstrap.php command [action] [options]';

    /**
     * 可用操作
     */
    protected array $actions = [];

    /**
     * @param Command[] $actions
     */
    public function withActions(array $actions): DefaultCommand
    {
        foreach ($actions as $command => $instance) {
            $this->actions[$command] = $instance->getTitle();
        }

        return $this;
    }

    public function execute(Argument $argument): bool
    {
        return true;
    }
}
