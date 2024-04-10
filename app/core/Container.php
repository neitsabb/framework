<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\Container\ContainerException;

class Container implements ContainerInterface
{
	/**
	 * @var array $instances - Contains the instances of dependencies.
	 */
	private array $instances = [];

	/**
	 * Retrieves an instance of a class from the container.
	 * 
	 * @param string $id - The class name to retrieve from the container.
	 * @return mixed - The instance of the class.
	 */
	public function get(string $id): mixed
	{
		if ($this->has($id)) {
			$instances = $this->instances[$id];
			return $instances($this);
		}

		return $this->resolve($id);
	}

	/**
	 * Checks if the container has an instance of a class.
	 * 
	 * @param string $id - The class name to check for in the container.
	 * @return bool - Returns true if the class exists in the container, otherwise false.
	 */
	public function has(string $id): bool
	{
		return isset($this->instances[$id]);
	}

	/**
	 * Sets an instance of a class in the container.
	 * 
	 * @param string $id - The class name to set in the container.
	 * @param callable $concrete - The instance of the class to set in the container.
	 */
	public function set(string $id, callable $concrete): void
	{
		$this->instances[$id] = $concrete;
	}

	/**
	 * Resolves a class from the container.
	 * 
	 * @param string $id - The class name to resolve from the container.
	 * @return mixed - The resolved class instance.
	 */
	public function resolve(string $id): mixed
	{
		if (!class_exists($id)) {
			throw new ContainerException("Class {$id} not found");
		}

		$reflectClass = new \ReflectionClass($id);
		if (!$reflectClass->isInstantiable()) {
			throw new ContainerException("Class {$id} is not instantiable");
		}

		$constructor = $reflectClass->getConstructor();
		if (!$constructor) {
			return new $id;
		}

		$constructorParams = $constructor->getParameters();
		if (!$constructorParams) {
			return new $id;
		}

		$dependencies = array_map(
			function (\ReflectionParameter $param) use ($id) {
				$name = $param->getName();
				$type = $param->getType();
				if (!$type) {
					throw new ContainerException(
						"Missing type hint for parameter {$name} in {$id} constructor"
					);
				}

				if ($type instanceof \ReflectionUnionType) {
					throw new ContainerException(
						"Union type is not supported for parameter {$name} in {$id} constructor"
					);
				}

				if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
					return $this->get($type->getName());
				}

				throw new ContainerException(
					"Invalide param {$name} in {$id} constructor"
				);
			},
			$constructorParams
		);

		return $reflectClass->newInstanceArgs($dependencies);
	}
}
