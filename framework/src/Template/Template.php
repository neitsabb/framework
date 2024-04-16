<?php

namespace Neitsab\Framework\Template;

use Neitsab\Framework\Core\Application;
use Neitsab\Framework\Session\SessionInterface;


class Template
{
	/**
	 * @var string $themePath - The path to the theme.
	 */
	protected string $themePath;

	/**
	 * @var SessionInterface $session - The session.
	 */
	protected SessionInterface $session;

	public function __construct(string $themePath, SessionInterface $session)
	{
		$this->themePath = $themePath;
		$this->session = $session;
	}

	/**
	 * Build the view.
	 * 
	 * @param string $view - The view to render.
	 * @param array $params - The parameters to pass to the view
	 * @param string $_layout - The layout to use.
	 * @return string
	 */
	public function build(
		string $view,
		array $params = [],
		string $_layout = null
	): string {
		if (!$_layout) {
			$_layout = Application::$container->getController()->layout;
		}

		$layout = $this->getLayout($_layout);

		$params = [
			'session' => $this->session,
			...$params,
		];

		$page = $this->getContentPage($view, $params);

		return str_replace('{{ page }}', $page, $layout);
	}

	/**
	 * Get the layout.
	 * 
	 * @param string $layout - The layout to get.
	 * @return string
	 */
	private function getLayout(string $layout): string
	{
		ob_start();
		include_once $this->themePath . '/layouts/' . $layout . '.php';
		return ob_get_clean();
	}

	/**
	 * Get the content page.
	 * 
	 * @param string $page - The page to get.
	 * @param array $params - The parameters to pass to the page
	 * @return string
	 */
	private function getContentPage(string $page, array $params): string
	{
		foreach ($params as $key => $value) {
			$$key = $value;
		}

		ob_start();
		include_once $this->themePath . '/pages/' . $page . '.php';
		return ob_get_clean();
	}
}
