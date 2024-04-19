<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset=" UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Générateur de mot de passe</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
	<style>
		body {
			font-family: "Inter", sans-serif !important;
		}
	</style>
</head>

<body class="bg-slate-200  min-h-screen">
	Hello <?= $name ?>, welcome to the home page
	<?php include_once dirname(__DIR__) . '/partials/header.php'; ?>
	{{ page }}

</body>

</html>