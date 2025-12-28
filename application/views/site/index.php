<?php
    $this->layout->setSection('title_icon', 'activity');

    $this->layout->push('css', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />');
    $this->layout->push('scripts', '<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>');
    $this->layout->push('scripts', '<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>');
?>

<?php $this->layout->startSection('content') ?>

<div class="card card-waves mb-4 mt-5">
    <div class="card-body p-5">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h2 class="text-primary"><span id="greetings"></span>. Selamat datang kembali, <?= def($this->session->userdata('detail_identity'), 'nama_depan') ?>!
                <small id="timestamp"></small></h2>
                <p class="text-gray-700">Dashboard rekam medis UKS kamu udah siap! Di sini kamu bisa lihat data pasien, catatan kunjungan, bikin rujukan, dan download laporan kesehatan langsung dari dashboard ini.</p>
                <a class="btn btn-primary p-3" href="#!">
                    Rekam Medis
                    <i data-feather="arrow-right" class="ms-1"></i>
                </a>
            </div>
            <div class="col d-none d-lg-block mt-xxl-n4"><img class="img-fluid px-xl-4 mt-xxl-n5" src="<?= base_url('/web/assets/img/illustrations/statistics.svg') ?>"></div>
        </div>

        <small class="text-muted">Shortcut:</small>

        <div>
            <a href="#" class="btn btn-primary btn-sm">Visits <i data-feather="file-text" class="ms-1"></i></a>
        </div>
    </div>
</div>

<?php $this->layout->endSection() ?>
