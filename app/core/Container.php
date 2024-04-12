<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\Container\ContainerException;

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

	/**
	 * Binds an interface to a concrete class.
	 * 
	 * @param string $interface - The interface to bind.
	 * @param string $concrete - The concrete class to bind to the interface.
	 */
	public function bind(string $interface, string $concrete): void
	{
		$this->bindings[$interface] = $concrete;
	}

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

				throw new ContainerException(
					"Invalid param {$name} in {$id} constructor"
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
	 * Charge les fournisseurs de bindings depuis le fichier de configuration et les exécute.
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
