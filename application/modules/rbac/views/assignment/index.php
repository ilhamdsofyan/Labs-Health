<?php
$this->layout->setSection('title', 'Access Control');
$this->layout->setSection('subtitle', 'Assignment');
$this->layout->setSection('title_icon', 'shield');
?>

<?php $this->layout->startSection('content') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-group">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Group</th>
                            <th width="5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->layout->endSection() ?>
