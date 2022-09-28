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

function emergency(string $message, array $context = [])
{
    app('logger')->emergency($message, $context);
}

function alert(string $message, array $context = [])
{
    app('logger')->alert($message, $context);
}

function critical(string $message, array $context = [])
{
    app('logger')->critical($message, $context);
}

function error(string $message, array $context = [])
{
    app('logger')->error($message, $context);
}

function warning(string $message, array $context = [])
{
    app('logger')->warning($message, $context);
}

function notice(string $message, array $context = [])
{
    app('logger')->notice($message, $context);
}

function info(string $message, array $context = [])
{
    app('logger')->info($message, $context);
}

function debug(string $message, array $context = [])
{
    app('logger')->debug($message, $context);
}
