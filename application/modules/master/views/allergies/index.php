<?php
	$this->layout->setSection('title', 'Master');
	$this->layout->setSection('subtitle', 'Allergies');
	$this->layout->setSection('title_icon', 'database');
?>

<?php $this->layout->startSection('content') ?>

<div class="card">
	<div class="card-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Deskripsi</th>
					<th>Tanggal</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<?php $this->layout->endSection() ?>
