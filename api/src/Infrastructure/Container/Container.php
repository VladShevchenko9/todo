<?php

namespace App\Infrastructure\Container;

use Exception;
use ReflectionClass;
use ReflectionException;

class Container
{
    /** @var array */
    private array $bindings = [];

    /**
     * @param string $id
     * @param callable $resolver
     */
    public function set(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    /**
     * @param string $id
     * @param array $params
     * @return object
     * @throws ReflectionException
     */
    public function get(string $id, array $params = [])
    {
        if (isset($this->bindings[$id])) {
            return $this->bindings[$id]($this, $params);
        }

        return $this->resolve($id, $params);
    }

    /**
     * @param string $class
     * @param array $params
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    private function resolve(string $class, array $params = [])
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class;
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (array_key_exists($name, $params)) {
                $dependencies[] = $params[$name];
                continue;
            }

            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName(), $params);
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            throw new Exception(
                "Cannot resolve parameter \${$name} of {$class}"
            );
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
