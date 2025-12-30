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
	$layout->setSection('title', $status_code .' | '. $heading);
	$layout->setSection('title_icon', 'alert-circle');
	$layout->startSection('content');
} else {
	// no layout available — render content directly
}
?>

<img class="img-fluid p-4" src="<?= base_url('web/assets/img/illustrations/'. $img) ?>" alt>
<div class="lead">
	<h3><i class="fas fa-exclamation-triangle text-warning"></i> <?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h3>
	<p><?= nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<?php if (isset($layout) && $layout) { $layout->endSection(); } ?>
