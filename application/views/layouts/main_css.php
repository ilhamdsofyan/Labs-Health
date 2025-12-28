<link rel="stylesheet" href="<?= base_url('/web/assets/css/styles.css') ?>">

<link rel="stylesheet" href="<?= base_url('/web/assets/plugins/daterangepicker/daterangepicker.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/plugins/sweetalert2/sweetalert2.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/css/custom.css') ?>">

<style type="text/css">
	.label-required::after {
    	content: ' * ';
	    color: red;
	}
	#overlay {
		background: rgba(236, 240, 241, 0.5);
		color: #666666;
		position: fixed;
		height: 100%;
		width: 100%;
		z-index: 10050;
		top: 0;
		left: 0;
		float: left;
		text-align: center;
		padding-top: 25%;
	}
	/*Example 1, all the CSS is defined here and not in JS*/
    #markerWithExternalCss {
        background-image: url('<?= base_url('/web/images/marker.png') ?>');
        background-size: cover;
        width: 22px;
        height: 32px;
        cursor: pointer;
    }
</style>

<?php 
# Load self-made file views for setting css
if (!empty($view_css)) {
	if (is_string($view_css)) {
		$this->load->view($view_css);

	} elseif (is_array($view_css)) {
		foreach ($view_css as $key => $css) {
			$this->load->view($css);
		}

	}
}

$this->layout->yieldStack('css');

echo $this->layout->yield('css-inline');
?>
