<?php

namespace Neitsab\Framework\Template;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Database\Connection;
use Neitsab\Framework\Http\Request\Request;
use Neitsab\Framework\Session\SessionInterface;

class Template
{
	protected string $themePath;
	protected SessionInterface $session;
	protected Connection $connection;

	protected bool $isAdmin;

	public function __construct(
		string $themePath,
		SessionInterface $session,
		Connection $connection
	) {
		$this->themePath = $themePath;
		$this->session = $session;
		$this->connection = $connection;
		$this->isAdmin = Application::$controller->layout === 'admin';
	}

	public function build(array $params, Request $request, ?string $view)
	{
		$layout = $this->getLayout($params);
		$content = $this->getContent($params, $request->uri(), $view);

		return str_replace('{{ page }}', $content, $layout);
	}

	private function getLayout(array $params): string
	{
		$layoutPath = '';

		if ($this->isAdmin) {
			$layoutPath = Application::$rootDir . "/app/admin/layouts/" . Application::$controller->layout . ".php";
		} else if (Application::$controller->layout) {
			$layoutPath = $this->themePath . '/layouts/' . Application::$controller->layout . '.php';
		} else {
			$layoutPath = $this->themePath . '/layouts/default.php';
		}

		return $this->renderView($layoutPath, $params);
	}

	private function getContent(array $params, string $uri, ?string $view): ?string
	{
		if (Application::$controller->layout === 'admin') {
			return $this->renderView(Application::$rootDir . "/app/admin/views/$view.php", $params);
		}

		$page = $this->getCurrentPage($uri);

		if ($page) {
			// throw new \Exception('Page not found in the database');
			$components = $this->getComponentsForCurrentPage($page);
			$content = '';

			foreach ($components as $component) {
				$content .= $this->renderView($component['path'] . '/index.php', $params);
			}

			return $content;
		}

		return $this->renderView($this->themePath . '/views/' . $view . '.php', $params);
	}

	private function renderView(string $viewPath, array $params = []): string
	{
		ob_start();
		extract($params);
		include $viewPath;
		return ob_get_clean();
	}

	private function getCurrentPage(string $uri): array|bool
	{
		$stmt = $this->connection->prepare("SELECT * FROM pages WHERE slug = :uri");
		$stmt->execute(['uri' => $uri]);
		return $stmt->fetch();
	}

	private function getComponentsForCurrentPage(array $page): array
	{
		$stmt = $this->connection->prepare(
			"SELECT components.* FROM components
            JOIN components_pages ON components.id = components_pages.component_id
            WHERE components_pages.page_id = :page_id"
		);
		$stmt->execute(['page_id' => $page['id']]);
		return $stmt->fetchAll();
	}
}
