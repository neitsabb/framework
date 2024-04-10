<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\Container\ContainerException;

class Container implements ContainerInterface
{
	private array $instances = [];
	public function get(string $id): mixed
	{
		if ($this->has($id)) {
			$instances = $this->instances[$id];
			return $instances($this);
		}

		return $this->resolve($id);
	}

	public function has(string $id): bool
	{
		return isset($this->instances[$id]);
	}

	public function set(string $id, callable $concrete): void
	{
		$this->instances[$id] = $concrete;
	}

	public function resolve(string $id): mixed
	{
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
