<?php

namespace Neitsab\Framework\Core;

class FileCache
{
	public string $cacheDir;

	public function __construct()
	{
		$this->cacheDir = Application::$rootDir . DIRECTORY_SEPARATOR . Application::$app->config->get('cache.path');
	}

	/**
	 * Get the cached data for the given key if it's still valid.
	 *
	 * @param string $key The key to retrieve.
	 * @return mixed|null The cached data if it exists and is still valid, null otherwise.
	 */
	public function get(string $key): mixed
	{
		if ($this->isValid($key)) {
			$cacheData = $this->getCacheData($key);
			return $cacheData['data'];
		}
		// Cache expired or not found
		return null;
	}

	/**
	 * Stores data in a cache file with an optional expiration time.
	 * 
	 * @param string $key - The unique identifier for the cached data. 
	 * @param mixed $value - The data to store in the cache. 
	 * @param int $expiration - The duration for which the cached data should be considered valid.
	 */
	public function set(string $key, mixed $value, int $expiration = 0)
	{
		$cacheFile = $this->getCacheFilePath($key);
		$cacheData = [
			'expiration' => $expiration > 0 ? time() + $expiration : 0,
			'data' => $value,
		];
		file_put_contents($cacheFile, serialize($cacheData));
	}

	/**
	 * Check if a cached item exists for the given key.
	 *
	 * @param string $key The key to check.
	 * @return bool True if the cached item exists and is still valid, false otherwise.
	 */
	public function exists(string $key): bool
	{
		return $this->isValid($key);
	}

	/**
	 * Check if a cached item exists for the given key and if it's still valid.
	 *
	 * @param string $key The key to check.
	 * @return bool True if the cached item exists and is still valid, false otherwise.
	 */
	public function isValid(string $key): bool
	{
		$cacheFile = $this->getCacheFilePath($key);

		if (file_exists($cacheFile)) {
			// Récupérer la date de dernière modification du fichier de cache
			$cacheLastModified = filemtime($cacheFile);
			// Récupérer la date de dernière modification du fichier source
			$sourceLastModified = filemtime($this->getSourceFilePath($key));
			// Vérifier si le fichier source a été modifié après la création du fichier de cache
			return $cacheLastModified >= $sourceLastModified;
		}

		return false;
	}

	public function getSourceFilePath(string $key): string
	{
		// We need check if the views of the controller has been modified
		$fileViewName = Application::$rootDir . '/themes/default/pages/' . $controllerViews[0] . '.php';

		dd($fileViewName);

		return $sourceFile;
	}

	/**
	 * Delete the cache file for the given key.
	 *
	 * @param string $key The key of the cache file to delete.
	 * @return bool True if the cache file was successfully deleted, false otherwise.
	 */
	public function deleteCacheFile(string $key): bool
	{
		dd('delete cache file');
		$cacheFile = $this->getCacheFilePath($key);
		if (file_exists($cacheFile)) {
			return unlink($cacheFile);
		}
		return false;
	}

	/**
	 * Get the cache data for the given key.
	 *
	 * @param string $key The key to retrieve.
	 * @return array|null The cache data if it exists, null otherwise.
	 */
	private function getCacheData(string $key): ?array
	{
		$cacheFile = $this->getCacheFilePath($key);

		if (file_exists($cacheFile)) {
			$cacheData = unserialize(file_get_contents($cacheFile));
			return is_array($cacheData) ? $cacheData : null;
		}
		return null;
	}


	/**
	 * Get the full path to the cache file for the given key.
	 *
	 * @param string $key The cache key.
	 * @return string The full path to the cache file.
	 */
	private function getCacheFilePath(string $key): string
	{
		// Générer un nom de fichier unique basé sur la clé
		$filename = md5($key) . '.cache';
		return $this->cacheDir . DIRECTORY_SEPARATOR . $filename;
	}

	/**
	 * Get the modification time of the cache file associated with the given cache key.
	 * 
	 * @param string $cacheKey The cache key.
	 * @return int The modification time of the cache file, or 0 if the cache file does not exist.
	 */
	public function getCacheFileModifiedTime(string $cacheKey): int
	{
		$cacheFile = $this->getCacheFilePath($cacheKey);
		if (file_exists($cacheFile)) {
			return filemtime($cacheFile);
		}
		return 0;
	}
}
