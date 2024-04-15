<?php

namespace Neitsab\Framework\Core;


class Template
{

	protected string $themePath;

	public function __construct()
	{
		$this->themePath = Application::$app->theme->getPath();
	}


	public function build(string $view, array $params = [], string $_layout = null)
	{
		if (!$_layout) {
			$_layout = Application::$app->controller->layout;
		}

		$layout = $this->getLayout($_layout);
		$page = $this->getContentPage($view, $params);

		return str_replace('{{ page }}', $page, $layout);
	}

	private function getLayout(string $layout)
	{
		ob_start();
		include_once $this->themePath . '/layouts/' . $layout . '.php';
		return ob_get_clean();
	}

	private function getContentPage(string $page, array $params)
	{
		foreach ($params as $key => $value) {
			$$key = $value;
		}

		ob_start();
		include_once $this->themePath . '/pages/' . $page . '.php';
		return ob_get_clean();
	}
}
