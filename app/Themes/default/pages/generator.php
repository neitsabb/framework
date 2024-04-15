<div class="max-w-xl mx-auto bg-slate-800 rounded-lg p-8 ring-1 ring-slate-700/70 shadow-xl">
	<h1 class="text-white text-lg font-semibold ">Générateur de mot de passe</h1>
	<p class="text-slate-400 text-sm mt-1 pb-4 border-b border-dashed border-slate-700/50">
		Un générateurdddd de mot de passe simple pour créer des mots de passe sécurisés.
	</p>
	<?php if (isset($password)) : ?>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4">
			<label class="text-slate-100/90 font-medium text-sm mb-2 block">Mot de passe</label>
			<div class="bg-slate-700/70 text-white text-md text-shadow font-medium py-2 px-4 rounded-lg shadow-xl mt-4">
				<p><?= $password ?></p>
			</div>
		</div>
		<div class="mt-6 flex justify-end">
			<a href="/" class="bg-indigo-600 text-md text-white text-shadow py-2 px-4 rounded-lg shadow-xl focus:outline-none focus-visible:ring focus:ring-2 ring-slate-700 hover:bg-indigo-700">Générer un autre mot de passe</a>
		</div>
	<?php endif; ?>
	<form action="" method="POST">
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4">
			<label class="text-slate-100/90 font-medium text-sm mb-2 block flex items-center justify-between">Longueur <span>8</span></label>
			<div class="slider-container">
				<input type="range" name="length" min="8" max="64" value="<?php echo isset($request['length']) ? $request['length'] : 8 ?>" class="slider" id="myRange">
				<div class="slider-fill" id="sliderFill"></div>
			</div>
			<?php if (isset($errors['length'])) : ?>
				<div class="bg-red-500 text-white text-md text-shadow font-medium py-2 px-4 rounded-lg shadow-xl mt-4">
					<p><?= $errors['length'] ?></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4 flex justify-between items-center">
			<label class="text-slate-100/90 font-medium text-sm">Lettres minuscules</label>
			<div class="switch-button">
				<input class="switch-button-checkbox" type="checkbox" name="lowercase" <?php echo isset($request['lowercase']) ? "checked" : "" ?>></input>
				<label class="switch-button-label"></label>
			</div>
		</div>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4 flex justify-between items-center">
			<label class="text-slate-100/90 font-medium text-sm">Lettres majuscules</label>
			<div class="switch-button">
				<input class="switch-button-checkbox" type="checkbox" name="uppercase" <?php echo isset($request['uppercase']) ? "checked" : "" ?>></input>
				<label class="switch-button-label"></label>
			</div>
		</div>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4 flex justify-between items-center">
			<label class="text-slate-100/90 font-medium text-sm">Chiffres</label>
			<div class="switch-button">
				<input class="switch-button-checkbox" type="checkbox" name="digits" <?php echo isset($request['digits']) ? "checked" : "" ?>></input>
				<label class="switch-button-label"></label>
			</div>
		</div>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4 flex justify-between items-center">
			<label class="text-slate-100/90 font-medium text-sm">Charactères spéciaux</label>
			<div class="switch-button">
				<input class="switch-button-checkbox" type="checkbox" name="specialchars" <?php echo isset($request['specialchars']) ? "checked" : "" ?>></input>
				<label class="switch-button-label"></label>
			</div>
		</div>
		<?php if (isset($errors['options'])) : ?>
			<div class="bg-red-500 text-white text-md text-shadow py-2 px-4 rounded-lg shadow-xl mt-4">
				<p><?= $errors['options'] ?></p>
			</div>
		<?php endif; ?>
		<div class="mt-6 flex justify-end">
			<button class="bg-indigo-600 text-md text-white text-shadow py-2 px-4 rounded-lg shadow-xl focus:outline-none focus-visible:ring focus:ring-2 ring-slate-700 hover:bg-indigo-700">Générer</button>
		</div>
	</form>
</div>