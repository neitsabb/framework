<div class="max-w-xl mx-auto bg-slate-800 rounded-lg p-8 ring-1 ring-slate-700/70 shadow-xl">
	<h1 class="text-white text-lg font-semibold ">Générateur de numéro de téléphone</h1>
	<p class="text-slate-400 text-sm mt-1 pb-4 border-b border-dashed border-slate-700/50">
		Un générateur de numéro de téléphone simple pour créer des numéros de téléphone aléatoires.
	</p>
	<?php if (isset($phone)) : ?>
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4">
			<label class="text-slate-100/90 font-medium text-sm mb-2 block">Mot de passe</label>
			<div class="bg-slate-700/70 text-white text-md text-shadow font-medium py-2 px-4 rounded-lg shadow-xl mt-4">
				<p><?= $phone ?></p>
			</div>
		</div>
	<?php endif; ?>
	<form action="/phone" method="POST">
		<div class="rounded-lg ring-1 ring-slate-700/50 shadow-xl mt-6 p-4 flex justify-between items-center">
			<label class="text-slate-100/90 font-medium text-sm">Pays</label>
			<select type="text" name="country" class="bg-slate-700/70 text-white text-md text-shadow font-medium py-2 px-4 rounded-lg shadow-xl mt-4">
				<option value="fr">France</option>
				<option value="us">États-Unis</option>
				<option value="uk">Royaume-Uni</option>
				<option value="de">Allemagne</option>
				<option value="es">Espagne</option>
			</select>
			<?php if (isset($errors['country'])) : ?>
				<div class="bg-red-500 text-white text-md text-shadow font-medium py-2 px-4 rounded-lg shadow-xl mt-4">
					<p><?= $errors['country'] ?></p>
				</div>
			<?php endif; ?>
		</div>
		<div class="mt-6 flex justify-end">
			<button class="bg-indigo-600 text-md text-white text-shadow py-2 px-4 rounded-lg shadow-xl focus:outline-none focus-visible:ring focus:ring-2 ring-slate-700 hover:bg-indigo-700">Générer</button>
		</div>
	</form>
</div>