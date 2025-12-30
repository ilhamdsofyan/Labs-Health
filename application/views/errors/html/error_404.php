<?php
// Determine layout object: prefer passed $layout, fallback to $this->layout or CI->layout
$layout = isset($layout) ? $layout : (isset($this) && isset($this->layout) ? $this->layout : (function_exists('get_instance') ? (get_instance()->layout ?? null) : null));
if ($layout) {
	$layout->setSection('title', '404 Not Found');
	$layout->setSection('title_icon', 'alert-circle');
	$layout->startSection('content');
} else {
	// no layout available — render content directly
}
?>

<img class="img-fluid p-4" src="<?= base_url('web/assets/img/illustrations/404-error-with-a-cute-animal.svg') ?>" alt>
<p class="lead">This requested URL was not found on this server.</p>
<a class="text-arrow-icon" href="<?= base_url('/site') ?>">
	<i class="ms-0 me-1" data-feather="arrow-left"></i>
	Return to Dashboard
</a>

<?php if (isset($layout) && $layout) { $layout->endSection(); } ?>
