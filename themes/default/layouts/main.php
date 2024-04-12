<!DOCTYPE html>
<html lang="en">

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

		.slider-container {
			width: 100%;
			height: 25px;
			/* Largeur souhaitée de la barre de plage */
			position: relative;
			/* Position relative pour placer la poignée correctement */
		}

		.slider {
			-webkit-appearance: none;
			appearance: none;
			width: 100%;
			height: 8px;
			background: transparent;
			outline: none;
			position: relative;
		}

		.slider::-webkit-slider-thumb {
			-webkit-appearance: none;
			border-radius: 50%;
			background-color: rgb(30 41 59);
			border: 1.5px solid rgb(51 65 85);
			appearance: none;
			width: 20px;
			height: 20px;
			cursor: pointer;
			position: relative;
			z-index: 11;
			margin-left: 1px;
			/* Position la poignée au-dessus de la barre de plage */
		}

		.slider-fill {
			background-color: rgb(51 65 85);
			;
			height: 8px;
			position: absolute;
			top: 10px;
			left: 0;
			z-index: 10;
			border-radius: 10px 0 0 10px;
		}

		.switch-button {
			padding: 2px;
			background: rgb(51 65 85);
			border-radius: 80px;
			overflow: hidden;
			width: 40px;
			position: relative;
			padding-right: 20px;
			position: relative;
		}

		.switch-button:before {
			position: absolute;
			top: 0;
			bottom: 0;
			right: 0;
			width: 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 3;
			pointer-events: none;
		}

		.switch-button-checkbox {
			cursor: pointer;
			position: absolute;
			top: 0;
			left: 0;
			bottom: 0;
			width: 100%;
			height: 100%;
			opacity: 0;
			z-index: 2;
		}

		.switch-button-checkbox:checked+.switch-button-label:before {
			transform: translateX(18px);
			transition: transform 300ms linear;
		}

		.switch-button-checkbox+.switch-button-label {
			position: relative;
			height: 18px;
			display: block;
			user-select: none;
			pointer-events: none;
		}

		.switch-button-checkbox+.switch-button-label:before {
			content: "";
			background-color: rgb(30 41 59);
			height: 100%;
			width: 100%;
			position: absolute;
			left: 0;
			top: 0;
			border-radius: 100%;
			transform: translateX(0);
			transition: transform 300ms;
			margin-right: 1px;
		}

		.switch-button-checkbox+.switch-button-label .switch-button-label-span {
			position: relative;
		}
	</style>
</head>

<body class="bg-slate-900  h-screen flex items-center justify-center">
	{{ page }}
</body>

<script>
	var slider = document.getElementById("myRange");
	var sliderFill = document.getElementById("sliderFill");

	slider.oninput = function() {
		var percent = (this.value - this.min) / (this.max - this.min) * 100;
		console.log(percent);
		sliderFill.style.width = percent + "%";
	}
</script>

</html>