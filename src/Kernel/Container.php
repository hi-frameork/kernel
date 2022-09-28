<?php

declare(strict_types=1);

namespace Hi\Kernel;

use function call_user_func;
use function call_user_func_array;
use function class_exists;

use Hi\Kernel\Container\NotFoundException;

use function is_object;
use function is_string;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected array $services = [];

    /**
     * @var array
     */
    protected array $instances = [];

    /**
     * Attempts to register a service in the services container
     * Only is successful if a service hasn't been registered previously
     * with the same id
     *
     * @param string $id
     * @param mixed  $definition
     */
    public function attempt(string $id, $definition)
    {
        if ($this->has($id)) {
            return false;
        }

        $this->services[$id] = $definition;

        return true;
    }

    public function set(string $id, $definition)
    {
        $this->services[$id] = $definition;
    }

    /**
     * Resolves the service based on its configuration
     *
     * @param string     $id
     * @param array|null $parameters
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $id, bool $newInstance = false, array $parameters = null)
    {
        if ($newInstance) {
            return $this->resolve($id, $parameters);
        }

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $this->instances[$id] = $this->resolve($id, $parameters);

        return $this->instances[$id];
    }

    /**
     * Returns a service definition without resolving
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getRaw(string $id)
    {
        return $this->getService($id);
    }

    /**
     * Check whether the DI contains a service by a id
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Returns a Service instance
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getService(string $id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException('服务 "' . $id . '" 在 container 中未找到', 500);
        }

        return $this->services[$id] ?? null;
    }

    protected function resolve($id, array $parameters = null)
    {
        return static::make($this->getService($id), $parameters);
    }

    public static function make($definition, array $parameters = null)
    {
        if (is_string($definition) && true === class_exists($definition)) {
            if (true !== empty($parameters)) {
                return new $definition(...$parameters);
            }

            return new $definition();
        } elseif (true === is_object($definition) && $definition instanceof \Closure) {
            if (true !== empty($parameters)) {
                return call_user_func_array($definition, $parameters);
            }

            return call_user_func($definition);
        }

        return $definition;
    }
}
