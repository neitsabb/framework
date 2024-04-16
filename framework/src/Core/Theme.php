<?php

namespace Neitsab\Framework\Core;

class Theme
{
	/**
	 * @var string $path - The path to the theme directory.
	 */
	private string $path;

	/**
	 * @var array $config - The configuration of the theme.
	 */
	private array $config;

	public function __construct()
	{
		$this->config = Application::$config->get('themes');
		$this->load();
	}

	/**
	 * Load the active theme.
	 *
	 * @return void
	 */
	public function load()
	{
		$theme = $this->config['active'];
		$this->path = Application::$rootDir .
			$this->config['path'] .
			DIRECTORY_SEPARATOR .
			$theme;

		if (!is_dir($this->path)) {
			throw new \Exception("The theme directory does not exist: {$this->path}");
		}

		$this->loadConfig();
		$this->generateVariables();
	}

	/**
	 * Load the theme configuration.
	 *
	 * @return void
	 */
	private function loadConfig()
	{
		$config = json_decode(file_get_contents($this->path . '/theme.json'), true);
		$this->config = $config;
	}

	/**
	 * Generate the CSS variables from the theme configuration.
	 *
	 * @return void
	 */
	private function generateVariables()
	{
		$css = '';
		foreach ($this->config as $key => $entry) {
			switch ($key) {
				case 'colors':
					foreach ($entry as $color => $value) {
						if (is_array($value)) {
							foreach ($value as $key => $val) {
								$css .= "--clr-{$color}-{$key}: {$val};";
							}
						}
					}
					break;
				case 'spacing':
					foreach ($entry as $space => $value) {
						$css .= "--space-{$space}: {$value};";
					}
					break;
				case 'fontSizes':
					foreach ($entry as $size => $value) {
						$css .= "--fs-{$size}: {$value};";
					}
					break;
				case 'fonts':
					foreach ($entry as $type => $value) {
						$css .= "--ff-{$type}: {$value};";
						//--ff-open-sans: 'Open Sans', sans-serif;
					}
					break;
				case 'borderRadius':
					foreach ($entry as $radius => $value) {
						$css .= "--rounded-{$radius}: {$value};";
					}
					break;
				case 'breakpoints':
					foreach ($entry as $breakpoint => $value) {
						$css .= "--breakpoint-{$breakpoint}: {$value};";
					}
					break;
			}
		}
	}

	/**
	 * Get the path to the theme directory.
	 *
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}
}
