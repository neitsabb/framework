Coucou index posts <br />
<?php dd($session->getFlash('success')) ?>
<?php if ($session->hasFlash('success')) : ?>
	<div class="alert alert-success">
		<?= $session->getFlash('success') ?>
	</div>
<?php endif; ?>