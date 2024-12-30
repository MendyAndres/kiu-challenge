<?php

namespace Src\FlightSearch\Infrastructure\DI;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionUnionType;
use Closure;

class Container
{
    protected $bindings = [];
    protected $instances = [];


    /**
     * Bind a concrete implementation to an abstract type in the container.
     *
     * @param string $abstract The abstract type or alias to bind.
     * @param mixed $concrete The concrete implementation or factory to associate with the abstract type.
     * @return void
     */
    public function bind(string $abstract, mixed $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve and return the instance for the given abstract type from the container.
     *
     * @param string $abstract The abstract type or alias to resolve.
     * @return mixed The resolved instance of the given abstract type.
     */
    public function make(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract] ?? $abstract;

        if ($concrete instanceof Closure) {
            $object = $concrete($this);
        } else {
            $object = $this->build($concrete);
        }

        $this->instances[$abstract] = $object;

        return $object;
    }

    /**
     * Build and return an instance of the given class name, resolving its dependencies recursively.
     *
     * @param string $concrete The fully qualified class name to instantiate.
     * @return object The instantiated object of the given class.
     * @throws Exception If the class does not exist, is not instantiable, or its dependencies cannot be resolved.
     */
    public function build(string $concrete)
    {
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new Exception("The class {$concrete} does not exist");
        }

        if (!$reflector->isInstantiable()) {
            throw new Exception("The class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        // Si no tiene constructor, se hace new $concrete
        if (is_null($constructor)) {
            return new $concrete;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencyClass = $type->getName();
                $dependencies[] = $this->make($dependencyClass);
            } elseif ($type instanceof ReflectionUnionType) {
                throw new \Exception("Cannot resolve union types in {$concrete}.");
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve the dependencies for \${$parameter->getName()} in {$concrete}.");
                }
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}