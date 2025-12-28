<!DOCTYPE html>
<html lang="en">
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
    <body class="bg-white">
        <div id="layoutError">
            <div id="layoutError_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center mt-4">
                                    <?= $this->layout->yield('content') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutError_footer">
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

        <?php $this->load->view('layouts/main_js') ?>
</body>
</html>
