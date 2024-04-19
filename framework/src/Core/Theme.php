<?php

namespace Neitsab\Framework\Core;

use Neitsab\Framework\Database\Connection;

class Theme
{
	/**
	 * @var string $path - The path to the theme directory.
	 */
	private string $path;

	/**
	 * @var array $configTheme - The configThemeuration of the theme.
	 */
	private array $configTheme;

	private Connection $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;

		$this->configTheme = Application::$config->get('themes');
		$this->load();
	}

	/**
	 * Load the active theme.
	 *
	 * @return void
	 */
	public function load()
	{
		$this->path = Application::$rootDir .
			$this->configTheme['path'] .
			DIRECTORY_SEPARATOR .
			$this->configTheme['active'];

		if (!is_dir($this->path)) {
			throw new \Exception("The theme directory does not exist: {$this->path}");
		}

		$this->loadconfigTheme();
		if ($this->configTheme) {
			$this->generateVariables();
		}
		$this->checkComponentsRegistered();
	}

	private function checkComponentsRegistered()
	{
		// Récupérer tous les chemins de composants déjà enregistrés dans la base de données
		$registeredPaths = $this->connection->manager->query('SELECT path FROM components')->fetchAll(\PDO::FETCH_COLUMN);

		// Charger les composants du dossier components
		$newComponents = $this->registerComponents($registeredPaths);

		// Enregistrer les nouveaux composants dans la base de données
		$this->saveNewComponents($newComponents);
	}

	/**
	 * Charger les composants du dossier components qui ne sont pas déjà enregistrés.
	 *
	 * @param array $registeredPaths
	 * @return array
	 */
	private function registerComponents(array $registeredPaths): array
	{
		// Chemins complets des composants dans le dossier components
		$componentsInDirectory = glob($this->path . '/components/*');

		// Filtrer les composants qui ne sont pas déjà enregistrés
		$newComponents = array_diff($componentsInDirectory, $registeredPaths);

		return $newComponents;
	}

	/**
	 * Enregistrer les nouveaux composants dans la base de données.
	 *
	 * @param array $newComponents
	 * @return void
	 */
	private function saveNewComponents(array $newComponents)
	{
		if (empty($newComponents)) {
			return; // Pas de nouveaux composants à enregistrer
		}

		foreach ($newComponents as $path) {
			// Lire le fichier de configuration JSON
			$config = json_decode(file_get_contents($path . '/component.json'), true);

			// Insérer le composant dans la base de données avec les informations de configuration
			$this->connection->manager->prepare('INSERT INTO components (name, description, path) VALUES (?, ?, ?)')
				->execute([$config['name'], $config['description'], $path]);
		}
	}
	/**
	 * Load the theme configThemeuration.
	 *
	 * @return void
	 */
	private function loadconfigTheme()
	{
		if (file_exists($this->path . '/theme.json')) {
			$configTheme = json_decode(file_get_contents($this->path . '/theme.json'), true);
			$this->configTheme = $configTheme;
		}
	}

	/**
	 * Generate the CSS variables from the theme configThemeuration.
	 *
	 * @return void
	 */
	private function generateVariables()
	{
		$css = '';
		foreach ($this->configTheme as $key => $entry) {
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
