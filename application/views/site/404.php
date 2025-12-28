<?php
	$this->layout->setLayout('error');

	$this->layout->setSection('title', '404 Not Found');
	$this->layout->setSection('title_icon', 'alert-circle');
?>

<?= $this->layout->startSection('content') ?>

<img class="img-fluid p-4" src="<?= base_url('web/assets/img/illustrations/404-error-with-a-cute-animal.svg') ?>" alt>
<p class="lead">This requested URL was not found on this server.</p>
<a class="text-arrow-icon" href="<?= base_url('/site') ?>">
	<i class="ms-0 me-1" data-feather="arrow-left"></i>
	Return to Dashboard
</a>

<?php $this->layout->endSection() ?>
