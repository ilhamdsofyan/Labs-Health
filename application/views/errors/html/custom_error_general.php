<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Prefer passed $layout object, fallback to $this->layout or global CI->layout
$layout = isset($layout) ? $layout : (isset($this) && isset($this->layout) ? $this->layout : (function_exists('get_instance') ? (get_instance()->layout ?? null) : null));
if ($layout) {
	$layout->setSection('title', $status_code .' | '. $heading);
	$layout->setSection('title_icon', 'alert-circle');
}
?>

<?php
if ($layout) {
	$layout->startSection('content');
} else {
	// no layout, render content directly
}
?>

<img class="img-fluid p-4" src="<?= base_url('web/assets/img/illustrations/500-internal-server-error.svg') ?>" alt>
<p class="lead">
	<?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
</p>
<a class="text-arrow-icon" href="<?= base_url('/site') ?>">
	<i class="ms-0 me-1" data-feather="arrow-left"></i>
	Return to Dashboard
</a>

<?php
if ($layout) {
	$layout->endSection();
}
?>

