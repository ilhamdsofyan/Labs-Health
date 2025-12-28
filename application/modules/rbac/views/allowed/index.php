<?php
$this->layout->setSection('title', 'Access Control');
$this->layout->setSection('subtitle', 'Allowed');
$this->layout->setSection('title_icon', 'shield');
?>

<?php $this->layout->startSection('content') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="row">
		            <div class="col-sm-5">
		                <div class="input-group input-group-joined mb-3">
		                    <input class="form-control search ps-0" data-target="available" placeholder="Cari route yang tersedia">
		                    <span class="input-group-btn">
		                        <?= $this->html->a('<i data-feather="refresh-cw"></i>', '/rbac/allowed/refresh', [
		                            'class' => 'btn btn-light',
		                            'id' => 'btn-refresh'
		                        ]) ?>
		                    </span>
		                </div>

		                <select multiple size="20" class="form-control list" data-target="available"></select>
		            </div>

		            <div class="col-sm-2 text-center">
		                <br><br>
		                <?= $this->html->a('<i data-feather="chevrons-right"></i>', '/rbac/allowed/assign', [
		                    'class' => 'btn btn-success btn-assign',
		                    'data-target' => 'available',
		                    'title' => 'Assign',
		                ]) ?><br><br>
		                <?= $this->html->a('<i data-feather="chevrons-left"></i>', '/rbac/allowed/remove', [
		                    'class' => 'btn btn-danger btn-assign',
		                    'data-target' => 'assigned',
		                    'title' => 'Remove'
		                ]) ?>
		            </div>

		            <div class="col-sm-5">
		                <input class="form-control search mb-3" data-target="assigned" placeholder="Cari route allowed">

		                <select multiple size="20" class="form-control list" data-target="assigned"></select>
		            </div>
		        </div>
            </div>
        </div>
    </div>
</div>

<?php $this->layout->endSection() ?>
