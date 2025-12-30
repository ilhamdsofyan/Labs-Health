<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$status_code = isset($status_code) ? $status_code : 500;
$images = [
	400 => '400-error-bad-request.svg',
	401 => '401-error-unauthorized.svg',
	403 => '403-error-forbidden.svg',
	404 => '404-error.svg',
	500 => '500-internal-server-error.svg',
	503 => '503-error-service-unavailable.svg',
	504 => '504-error-gateway-timeout.svg',
];
$img = isset($images[$status_code]) ? $images[$status_code] : '500-internal-server-error.svg';

// Determine layout object: prefer passed $layout, fallback to $this->layout or CI->layout
$layout = isset($layout) ? $layout : (isset($this) && isset($this->layout) ? $this->layout : (function_exists('get_instance') ? (get_instance()->layout ?? null) : null));
if ($layout) {
	$layout->setSection('title', $status_code .' | '. (isset($heading) ? $heading : 'Exception'));
	$layout->setSection('title_icon', 'alert-circle');
	$layout->startSection('content');
} else {
	// no layout available — render content directly
}
?>

<img class="img-fluid p-4" src="<?= base_url('web/assets/img/illustrations/'. $img) ?>" alt>
<div class="error-details">
	<h4>An uncaught Exception was encountered</h4>
	<p><strong>Type:</strong> <?= htmlspecialchars(get_class($exception), ENT_QUOTES, 'UTF-8') ?></p>
	<p><strong>Message:</strong> <?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) ?></p>
	<p><strong>Filename:</strong> <?= htmlspecialchars($exception->getFile(), ENT_QUOTES, 'UTF-8') ?></p>
	<p><strong>Line Number:</strong> <?= htmlspecialchars($exception->getLine(), ENT_QUOTES, 'UTF-8') ?></p>

	<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

		<p><strong>Backtrace:</strong></p>
		<?php foreach ($exception->getTrace() as $error): ?>

			<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

				<p style="margin-left:10px">
				File: <?= htmlspecialchars($error['file'], ENT_QUOTES, 'UTF-8') ?><br />
				Line: <?= htmlspecialchars(isset($error['line']) ? $error['line'] : '', ENT_QUOTES, 'UTF-8') ?><br />
				Function: <?= htmlspecialchars(isset($error['function']) ? $error['function'] : '', ENT_QUOTES, 'UTF-8') ?>
				</p>
			<?php endif ?>

		<?php endforeach ?>

	<?php endif ?>

</div>

<?php if (isset($layout) && $layout) { $layout->endSection(); } ?>
