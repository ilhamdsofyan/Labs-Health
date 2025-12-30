<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8" />
		<title>
			<?= $this->layout->yield('title', getenv('APP_NAME')) .' | '. getEnv('APP_FULLNAME') ?>
		</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="<?= env('APP_FULL_NAME') ?>" name="description" />
		<meta content="<?= env('APP_COPYRIGHT') .' '. env('APP_COMPANY') ?>" name="author" />

        <link rel="shortcut icon" href="<?= base_url('/web/images/Logo.png') ?>" />

		<?php $this->load->view('layouts/main_css') ?>
	</head>
	<!-- END HEAD -->

	<body class="nav-fixed">
		<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
            <!-- Sidenav Toggle Button-->
            <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
            <!-- Navbar Brand-->
            <!-- * * Tip * * You can use text or an image for your navbar brand.-->
            <!-- * * * * * * When using an image, we recommend the SVG format.-->
            <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
            <a class="navbar-brand pe-3 ps-4 ps-lg-2" href='/'>
				<img src="<?= base_url('/web/images/Logo.png') ?>"
						alt="AdminLTE Logo"
						class="brand-image img-circle elevation-3"
						style="opacity: .8">
					<span class="brand-text font-weight-light"><?= env('APP_NAME') ?></span>
			</a>
            <!-- Navbar Search Input-->
            <form class="form-inline me-auto d-none d-lg-block me-3">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control pe-0" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-text"><i data-feather="search"></i></div>
                </div>
            </form>
            <!-- Navbar Items-->
            <ul class="navbar-nav align-items-center ms-auto">
                <!-- Navbar Search Dropdown-->
                <li class="nav-item dropdown no-caret me-3 d-lg-none">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="searchDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="search"></i></a>
                    <!-- Dropdown - Search-->
                    <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--fade-in-up" aria-labelledby="searchDropdown">
                        <form class="form-inline me-auto w-100">
                            <div class="input-group input-group-joined input-group-solid">
                                <input class="form-control pe-0" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-text"><i data-feather="search"></i></div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Alerts Dropdown-->
                <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i data-feather="bell"></i> <?php if (!empty($notifikasi) && $notifikasi['all'] > 0): ?> <span class="badge bg-danger"><?= $notifikasi['all'] ?></span> <?php endif; ?></a>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts">
                        <h6 class="dropdown-header dropdown-notifications-header">
                            <i class="me-2" data-feather="bell"></i>
                            Notifikasi
                        </h6>

						<?php if (!empty($notifikasi) && $notifikasi['all'] > 0): ?>

							<?php foreach ($notifikasi['list'] as $key => $value): ?>
								<a class="dropdown-item dropdown-notifications-item" href="<?= $value->redirect_url ? $value->redirect_url : 'javascript:;' ?>" data-id="<?= $value->id ?>">
									<div class="dropdown-notifications-item-icon bg-warning"><i data-feather="activity"></i></div>
									<div class="dropdown-notifications-item-content">
										<div class="dropdown-notifications-item-content-details"><time class="time timeago" 
											datetime="<?= date('Y-m-d\TH:i:sO', strtotime($value->created_at)) ?>">
												<?= date('d M Y', strtotime($value->created_at)) ?></time></div>
										<div class="dropdown-notifications-item-content-text"><?= $value->content ?></div>
									</div>
								</a>
							<?php endforeach ?>

						<?php else: ?>

							<a class="dropdown-item dropdown-notifications-item text-muted" href="javascript:;">
								Belum ada notifikasi
							</a>

						<?php endif; ?>

                        <a class="dropdown-item dropdown-notifications-footer" href="#!">Lihat Semua Notifikasi</a>
                    </div>
                </li>

                <!-- Messages Dropdown-->
                <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownMessages" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="mail"></i></a>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownMessages">
                        <h6 class="dropdown-header dropdown-notifications-header">
                            <i class="me-2" data-feather="mail"></i>
                            Pesan
                        </h6>
                        <!-- Example Message 1  -->
                        <a class="dropdown-item dropdown-notifications-item" href="#!">
                            <img class="dropdown-notifications-item-img" src="<?= base_url('web/assets/img/illustrations/profiles/profile-2.png') ?>">
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                                <div class="dropdown-notifications-item-content-details">Thomas Wilcox · 58m</div>
                            </div>
                        </a>
                        <!-- Footer Link-->
                        <a class="dropdown-item dropdown-notifications-footer" href="#!">Baca Semua Pesan</a>
                    </div>
                </li>
                <!-- User Dropdown-->
                <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
							<img class="img-fluid" src="<?= $this->session->userdata('detail_identity')->profile_pic ?>">
						<?php else: ?>
							<img class="img-fluid" src="<?= base_url('/web/images/no_avatar.jpg') ?>">
						<?php endif ?>
					</a>

                    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                        <h6 class="dropdown-header d-flex align-items-center">
							<?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
								<img class="dropdown-user-img" src="<?= $this->session->userdata('detail_identity')->profile_pic ?>">
                            <?php else: ?>
								<img class="dropdown-user-img" src="<?= base_url('/web/images/no_avatar.jpg') ?>">
                            <?php endif ?>

                            <div class="dropdown-user-details">
                                <div class="dropdown-user-details-name"><?= def($this->session->userdata('identity'), 'username', 'Guest'); ?></div>

								<?php if (!empty($this->session->userdata('detail_identity')->email)): ?>
									<div class="dropdown-user-details-email">
										<a href="mailto:<?= $this->session->userdata('identity')->email; ?>" class="__cf_email__">[<?= $this->session->userdata('identity')->email; ?>]</a>
									</div>
								<?php endif ?>
                            </div>
                        </h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#!">
                            <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                            Profil
                        </a>

                        <a class="dropdown-item" href="<?= base_url('/site/logout') ?>">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

		<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
				<nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">
					   <?php if (!empty($this->session->userdata('identity'))) $this->menuhelper->run() ?>
                    </div>
				</nav>
			</div>

			<div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                        <div class="container-xl px-4">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                        <h1 class="page-header-title">
                                            <div class="page-header-icon"><?php if (!empty($this->layout->yield('title_icon'))): ?>
                                                    <i data-feather="<?= $this->layout->yield('title_icon') ?>"></i>
                                                <?php endif; ?>
                                            </div>
                                            <?= $this->layout->yield('title', getenv('APP_NAME')) ?>
                                        </h1>
                                        <div class="page-header-subtitle"><?= $this->layout->yield('subtitle') ?></div>
                                    </div>
                                    <div class="col-12 col-xl-auto mt-4"><?= $this->layout->yield('opt_header') ?></div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
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

						<?= $this->layout->yield('content') ?>
                    </div>
                </main>

                <footer class="footer-admin mt-auto footer-light">
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

		<!-- MY MODAL -->
        <div id="modal-preview" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">File tidak ditemukan atau browser tidak support.</div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MY MODAL -->

        <!-- OVERLAY LOADER -->
        <div id="overlay" style="display: none;">
            <img src="<?= base_url('/web/images/ajax-loading.gif') ?>" alt="Loading" /><br/>
            Memuat...
        </div> 
        <!-- OVERLAY LOADER -->

        <script type="text/javascript" id="csrf">
            var csrf_name = '<?= $this->security->get_csrf_token_name() ?>';
            var csrf_hash = '<?= $this->security->get_csrf_hash() ?>';
            var key_map = '<?= getEnv('LOCATIONIQ_API_KEY') ?>';
        </script>

        <?php $this->load->view('layouts/main_js') ?>
	</body>
</html>
