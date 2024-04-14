<?php

namespace Neitsab\Framework\Core;

class Config
{
	/**
	 * @var array $settings - Configuration settings
	 */
	protected array $settings = [];

	/**
	 * @var string $path - The path to the configuration files
	 */
	protected string $path;

	public function __construct(string $rootDir)
	{
		$this->path = $rootDir . '/app/config';

		$files = scandir($this->path);

		foreach ($files as $file) {
			if ($file === '.' || $file === '..' || is_dir($rootDir . "/config/$file")) {
				continue;
			}

			$key = pathinfo($file, PATHINFO_FILENAME);
			$config = require $this->path . DIRECTORY_SEPARATOR . $file;

			// Merge the configuration with existing settings
			$this->mergeConfig($this->settings, $key, $config);
		}
	}

	/**
	 * 
	 * Get a configuration value
	 * 
	 * @param string $key - The configuration key
	 * @param string $default - The default value if the key does not exist
	 * @return mixed
	 * 
	 */
	public function get(string $key, string $default = null): mixed
	{
		return $this->_get($this->settings, $key, $default);
	}

	/**
	 * The set function in PHP assigns a value to a specified key in an array called settings.
	 * 
	 * @param string key - represents the key of the setting you want to set or update in the settings array.
	 * @param string value - a string type variable that represents the value to be assigned to the specified key in the settings array.
	 */
	public function set(string $key, string $value): mixed
	{
		$this->settings[$key] = $value;
	}

	/**
	 * The above PHP function returns all settings as an array.
	 * 
	 * @return array An array containing all the settings stored in the object.
	 */
	public function all(): array
	{
		return $this->settings;
	}

	/**
	 * The has function in PHP checks if a key exists in the settings array.
	 * 
	 * @param string key - represents the key you want to check for in the settings array.
	 * @return bool The function returns a boolean value indicating whether the key exists in the settings array.
	 */
	public function has(string $key): bool
	{
		return isset($this->settings[$key]);
	}

	/**
	 * The mergeConfig function in PHP merges the configuration settings with the existing settings.
	 * 
	 * @param array settings - represents the settings array to which the configuration settings will be merged.
	 * @param string key - represents the key of the configuration settings to be merged.
	 * @param array config - represents the configuration settings to be merged with the existing settings.
	 */
	protected function mergeConfig(&$settings, $key, $config): void
	{
		if (isset($settings[$key]) && is_array($settings[$key]) && is_array($config)) {
			$settings[$key] = array_merge($settings[$key], $config);
		} else {
			$settings[$key] = $config;
		}
	}

	/**
	 * The _get function in PHP retrieves a value from an array using a dot-separated key.
	 * 
	 * @param array array - represents the array from which the value will be retrieved.
	 * @param string key - represents the key used to retrieve the value from the array.
	 * @param mixed default - represents the default value to be returned if the key does not exist in the array.
	 * @return mixed The function returns the value retrieved from the array using the specified key.
	 */
	protected function _get($array, $key, $default = null): mixed
	{
		foreach (explode('.', $key) as $segment) {
			if (!is_array($array) || !array_key_exists($segment, $array)) {
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}
}
