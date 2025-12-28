<?php
$this->layout->setSection('title', 'User');
$this->layout->setSection('title_icon', 'user');
?>

<?php $this->layout->startSection('content') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="btn-group float-end mb-3">
                                <a href="<?= site_url('/rbac/user/create') ?>" class="btn btn-primary"> Tambah
                                    <i data-feather="plus" class="ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-user">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status User</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            	<?= form_open('', ['id' => 'form-detail']); ?>

                    <div class="mb-3">
                        <?= form_label('Nama Karyawan', 'id_nama_depan'); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_depan]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_depan',
                                    'placeholder' => 'Nama Depan',
                                    'required' => true,
                                ]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_tengah]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_tengah',
                                    'placeholder' => 'Nama Tengah',
                                ]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_belakang]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_belakang',
                                    'placeholder' => 'Nama Belakang',
                                ]); ?>
                            </div>
                        </div>
                    </div>

            	<?= form_close(); ?>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="form-detail" id="btn-save" class="btn btn-success">
                	<span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->layout->endSection() ?>
