<?php

use Hi\Kernel;

function kernel(): Kernel
{
    return Kernel::instance();
}

function config(string $key, $defaultValue = null)
{
    return kernel()->getConfig()->get($key, $defaultValue);
}

function basePath(string $subPath = '')
{
    return pathJoin(kernel()->getBasePath(), $subPath);
}

function storagePath(string $subPath = '')
{
    return pathJoin(kernel()->getStoragePath(), $subPath);
}

function pathJoin(string $basePath, string $subPath = '')
{
    if ($subPath) {
        return $basePath . DIRECTORY_SEPARATOR . trim($subPath, DIRECTORY_SEPARATOR);
    }

    return $basePath;
}

function app(string $id)
{
    return kernel()->getContainer()->get($id);
}

if (!function_exists('backtracePoint')) {
    /**
     * 返回指定调用回溯位置信息
     *
     * @param int $depth 栈深度
     */
    function backtracePoint(int $depth = 2): array
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $depth);

        return array_pop($traces);
    }
}
