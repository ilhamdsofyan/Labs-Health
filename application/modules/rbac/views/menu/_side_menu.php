<div class="card card-collapsable">
	<a class="card-header" href="#side-menu-collapsable" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="side-menu-collapsable">
		Custom Link

		<div class="card-collapsable-arrow">
            <i class="fas fa-chevron-down"></i>
        </div>
	</a>

	<div class="collapse show" id="side-menu-collapsable">
		<div class="card-body">
			<div class="mb-3">
				<label for="url">URL</label>
				<input type="text" name="url" id="url" class="form-control" placeholder="http://" value="http://" />
			</div>
			<div class="mb-3">
				<label for="label">Link Text</label>
				<input type="text" name="label" id="label" class="form-control" placeholder="Sample text" />
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php echo $this->html->submitButton('Add to Menu', ['class' => 'btn btn-light float-end', 'id' => 'add-custom-to-menu']) ?>
				</div>
			</div>
		</div>
	</div>
</div>
