<?php

namespace Neitsab\Framework\Core;

class Modules
{
    /**
     * @var string $path - The path to the modules directory
     */
    private string $path;

    /**
     * @var array $modules - The modules array that contains the components of each module
     */
    private array $modules = [];

    public function __construct()
    {

        $this->path = Application::$rootDir . Application::$config->get('modules.path');

        $this->loadModules();
    }

    /**
     * Loads all the modules in the modules directory if they are valid.
     * It means that it will scan the directory modules and store the components
     * names in the modules array.
     * 
     * @return void
     */
    private function loadModules(): void
    {
        $modules = scandir($this->path);

        foreach ($modules as $module) {
            if ($this->isValidModule($module)) {
                $this->loadModule($module);
            }
        }
    }

    /**
     * Checks if a module is valid by ensuring that it is not a directory or a parent directory.
     * 
     * @param string $module - The name of the module to check
     * @return bool - Returns true if the module is valid, otherwise false
     */
    private function isValidModule(string $module): bool
    {
        return !in_array($module, ['.', '..']);
    }

    /**
     * Loads a module by loading its components.
     * 
     * @param string $moduleName - The name of the module to load
     * @return void
     */
    private function loadModule($moduleName): void
    {
        // If the module name is not a Pascal case string, throw an exception
        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $moduleName)) {
            throw new \Exception("Invalid module name: $moduleName. Module name must be a Pascal case string.");
        }

        // If the module name is not a directory, throw an exception
        if (!is_dir($this->path . DIRECTORY_SEPARATOR . $moduleName)) {
            throw new \Exception("Module $moduleName does not exist.");
        }

        // If( the module name is already loaded, return)
        if (isset($this->modules[$moduleName])) {
            return;
        }

        $modulePath = $this->path . DIRECTORY_SEPARATOR . $moduleName;
        $components = [];

        $folders = scandir($modulePath);
        foreach ($folders as $component) {
            if ($this->isValidModule($component)) {
                $components[$component] = $this->loadComponent($modulePath . DIRECTORY_SEPARATOR . $component);
            }
        }

        $this->modules[$moduleName] = $components;
    }

    /**
     * Scans the directory for PHP files and extracts the namespace from the filename of each components. The extracted namespaces are then
     * stored in an array and returned as the result.
     * 
     * @param string path - the directory path where the components are located.   
     * @return array An array of namespaces of PHP files found in the specified directory path 
     */
    private function loadComponent(string $path): array
    {
        $loadedComponent = [];

        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if ($extension === 'php') {
                    $namespace = pathinfo($file, PATHINFO_FILENAME);
                    $loadedComponent[] = ucfirst($namespace);
                }
            }
        }

        return $loadedComponent;
    }


    /**
     * Gets the components of a module by its name.
     * 
     * @param string $module - The name of the module to get its components
     * @return array|null - Returns an array of components if the module exists, otherwise null
     */
    public function get($module): ?array
    {
        return $this->modules[$module] ?? null;
    }

    /**
     * Gets all the modules.
     * 
     * @return array - Returns an array of all the modules
     */
    public function all(): array
    {
        return $this->modules;
    }
}
