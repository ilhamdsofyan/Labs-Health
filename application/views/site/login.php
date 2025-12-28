<?= form_open(''); ?>
	<div class="mb-3">
		<label class="small mb-1" for="username">Username</label>
		<?= form_input('username', $model->username, [
			'class' => 'form-control',
			'autocomplete' => 'off',
			'placeholder' => 'Masukkan Username',
		]); ?>
	</div>

	<div class="mb-3">
		<label class="small mb-1" for="password">Password</label>
		<?= form_password('password', $model->password, [
			'class' => 'form-control',
			'autocomplete' => 'off',
			'placeholder' => 'Password',
		]); ?>
	</div>

	<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
		<button type="submit" class="btn btn-primary">Login</button>
	</div>
<?= form_close(); ?>

<script type="text/javascript">
    sessionStorage.clear();
</script>
