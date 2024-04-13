<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\Container\ContainerException;
use App\Exceptions\Container\NotFoundException;

class Container implements ContainerInterface
{
	/**
	 * @var array $bindings - Contains the bindings of dependencies.
	 */
	private array $bindings = [];
	/**
	 * @var array $instances - Contains the instances of dependencies.
	 */
	private array $instances = [];

	private const CLASS_NOT_FOUND_MESSAGE = "Class %s not found";
	private const CLASS_NOT_INSTANTIABLE_MESSAGE = "Class %s is not instantiable";
	private const MISSING_TYPE_HINT_MESSAGE = "Missing type hint for parameter %s in %s constructor";
	private const UNION_TYPE_NOT_SUPPORTED_MESSAGE = "Union type is not supported for parameter %s in %s constructor";
	private const INVALID_PARAM_MESSAGE = "Invalid param %s in %s constructor";

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
	 * Instantiate a class by resolving its dependencies through constructor injection.
	 * 
	 * @param string id The class name to resolve from the container.
	 * 
	 * @return mixed returns an instance of the class specified after resolving its dependencies if any. If the class has a constructor with parameters,
	 * it will attempt to resolve those dependencies recursively before instantiating the class.
	 */
	public function resolve(string $id): mixed
	{
		if (!class_exists($id)) {
			throw new NotFoundException(sprintf(self::CLASS_NOT_FOUND_MESSAGE, $id));
		}

		$reflectClass = new \ReflectionClass($id);
		if (!$reflectClass->isInstantiable()) {
			throw new ContainerException(sprintf(self::CLASS_NOT_INSTANTIABLE_MESSAGE, $id));
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
						sprintf(self::MISSING_TYPE_HINT_MESSAGE, $name, $id)
					);
				}

				if ($type instanceof \ReflectionUnionType) {
					throw new ContainerException(
						sprintf(self::UNION_TYPE_NOT_SUPPORTED_MESSAGE, $name, $id)
					);
				}

				if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
					// Vérifie si une liaison existe pour l'interface demandée
					if (isset($this->bindings[$type->getName()][$id])) {
						// Récupérez l'implémentation associée à ce contexte
						$concrete = $this->bindings[$type->getName()][$id];
						// Résolvez l'implémentation concrète associée à l'interface
						return $this->resolve($concrete);
					} elseif (class_exists($id)) {
						// Si le nom de classe est valide, résout l'interface normalement
						return $this->get($type->getName());
					} else {
						// Sinon, lancez une exception ou traitez l'erreur selon vos besoins
						throw new \Exception("Class {$id} not found");
					}
				}

				throw new \Exception(
					sprintf(self::INVALID_PARAM_MESSAGE, $name, $id)
				);
			},
			$constructorParams
		);

		return $reflectClass->newInstanceArgs($dependencies);
	}

	/**
	 * Adds a contextual binding to the container.
	 * 
	 * @param string $abstract - The abstract class to bind.
	 * @param string $context - The context to bind the abstract class to.
	 * @param string $concrete - The concrete class to bind to the abstract class.
	 */
	private function addContextualBinding(string $abstract, string $context, string $concrete): void
	{
		$this->bindings[$abstract][$context] = $concrete;
	}

	/**
	 * Load the providers from the modulesconfiguration file and bind them to the container.
	 * 
	 * @param array $providers - The providers to load from the modules configuration file.
	 */
	public function loadProviders(array $providers): void
	{
		foreach ($providers as $interface => $modules) {
			foreach ($modules as $module => $concrete) {
				$this->addContextualBinding($interface, $module, $concrete);
			}
		}
	}
}
