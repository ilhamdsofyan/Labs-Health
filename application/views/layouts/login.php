<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="<?= env('APP_FULL_NAME') ?>" name="description" />
        <meta content="<?= env('APP_COPYRIGHT') .' '. env('APP_COMPANY') ?>" name="author" />
        <title><?= $title .' | '. getEnv('APP_FULLNAME') ?></title>
        <link rel="stylesheet" href="<?= base_url('/web/assets/css/styles.css') ?>">
        <link rel="icon" type="image/x-icon" href="<?= base_url('/web/assets/img/favicon.png') ?>">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <!-- Basic login form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="fw-light my-4">Login</h3></div>
                                    <div class="card-body">
										<!-- BEGIN ALERT FLASHDATA -->
										<?php if ($this->session->flashdata('danger')): ?>
											<div class="alert alert-danger alert-icon" role="alert">
												<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
												<div class="alert-icon-aside">
													<i data-feather="alert-octagon"></i>
												</div>
												<div class="alert-icon-content">
													<?= $this->session->flashdata('danger'); ?>
												</div>
											</div>
										<?php endif ?>

										<?php if ($this->session->flashdata('info')): ?>
											<div class="alert alert-info alert-icon" role="alert">
												<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
												<div class="alert-icon-aside">
													<i data-feather="info"></i>
												</div>
												<div class="alert-icon-content">
													<?= $this->session->flashdata('info'); ?>
												</div>
											</div>
										<?php endif ?>

										<?php if ($this->session->flashdata('warning')): ?>
											<div class="alert alert-warning alert-icon" role="alert">
												<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
												<div class="alert-icon-aside">
													<i data-feather="alert-triangle"></i>
												</div>
												<div class="alert-icon-content">
													<?= $this->session->flashdata('warning'); ?>
												</div>
											</div>
										<?php endif ?>

										<?php if ($this->session->flashdata('success')): ?>
											<div class="alert alert-success alert-icon" role="alert">
												<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
												<div class="alert-icon-aside">
													<i data-feather="check-circle"></i>
												</div>
												<div class="alert-icon-content">
													<?= $this->session->flashdata('success'); ?>
												</div>
											</div>
										<?php endif ?>
										<!-- END ALERT FLASHDATA -->

                                        <?php $this->load->view($view, $data); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="footer-admin mt-auto footer-dark">
                    <div class="container-xl px-4">
                        <div class="row">
                            <div class="col-md-6 small"><?= env('APP_YEAR') ?> &copy; <?= env('APP_COPYRIGHT') ?></div>
                            <div class="col-md-6 text-md-end small">
                                <b>Version</b> <?= env('APP_VERSION') ?>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url('/web/assets/js/scripts.js') ?>"></script>

        <script src="https://assets.startbootstrap.com/js/sb-customizer.js"></script>
    </body>
</html>
