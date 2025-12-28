<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="<?= base_url('/web/assets/js/scripts.js') ?>"></script>
<script src="<?= base_url('/web/assets/plugins/sweetalert2/sweetalert2.js') ?>"></script>

<?php 
# Load self-made file views for setting js
if (!empty($view_js)) {
	if (is_string($view_js)) {
		$this->load->view($view_js);

	} elseif (is_array($view_js)) {
		foreach ($view_js as $key => $js) {
			$this->load->view($js);
		}

	}
}

$this->layout->yieldStack('scripts');

echo $this->layout->yield('scripts-inline');
?>

<script src="<?= base_url("/web/assets/js/my_custom.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/js/jquery.timeago.js") ?>" type="text/javascript"></script>

<script type="text/javascript" id="notification-link">
	$(document).on('click', "#header_notification_bar ul li ul li a", function(event) {
		event.preventDefault();
		/* Act on the event */

		let url = $(this).attr('href');
		let id = $(this).data('id');

		if (id) {
			myLoader();

			$.ajax({
				url: '<?= site_url('/notifikasi/read/') ?>' + id,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(data) {
				console.log(data);
			})
			.always(function() {
				myLoader(false);

				if (url != 'javascript:;') {
					window.location.href = url;
				}
			});
		}

	});

	$(document).ready(function() {
		$('time.timeago').timeago();
	});
</script>
