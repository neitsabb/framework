<?php

/**
 * Create the container of the application and configure
 * the services and dependencies.
 * 
 * @var \Neitsab\Framework\Core\Application $app
 * @return \Neitsab\Framework\Core\Application
 */
$app = new Neitsab\Framework\Core\Application(
	APP_ROOT
);

$app->configure();

return $app;
