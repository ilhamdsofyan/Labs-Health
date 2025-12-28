<?php
	$this->layout->setSection('title', 'Calendar');
	$this->layout->setSection('title_icon', 'calendar');

	$this->layout->push('css', '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />');
	$this->layout->push('scripts', '<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>');
	$this->layout->push('scripts', '<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>');
?>

<?php $this->layout->startSection('css-inline') ?>
<style>
</style>
<?php $this->layout->endSection() ?>
<!-- ----------------------------------------------- -->

<?php $this->layout->setSection('opt_header', '<button id="btn-add-event" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Add Event</button>'); ?>

<?php $this->layout->startSection('content') ?>

<div class="card">
	<div class="card-body">
		<div id="calendar" style="height: 500px;"></div>
	</div>
</div>

<!-- Modal Event Form -->
<div class="modal fade" id="modal-event" tabindex="-1" role="dialog" aria-labelledby="form-title" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="form-title">Add Event</h5>
				<button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" id="close-form"></button>
			</div>
			<div class="modal-body">
				<?= form_open('', ['id' => 'form-event']); ?>
					<?= form_hidden('Event[id]'); ?>

					<div class="mb-3">
						<?= form_label('Title', 'input-title', [
							'class' => 'form-label label-required'
						]); ?>

						<?= form_input([
							'name' => 'Event[title]',
							'class' => 'form-control',
							'id' => 'input-title',
							'required' => true,
							'placeholder' => 'Independence Day',
							'title' => 'Title'
						]); ?>
					</div>


					<div class="row">
						<div class="col-6 mb-3">
							<?= form_label('Start', 'input-start', [
								'class' => 'form-label'
							]); ?>

							<?= form_input([
								'name' => 'Event[start_date]',
								'class' => 'form-control flatpickr',
								'id' => 'input-start',
								'placeholder' => 'yyyy-mm-dd',
								'title' => 'Start'
							]); ?>
						</div>

						<div class="col-6 mb-3">
							<?= form_label('End', 'input-end', [
								'class' => 'form-label'
							]); ?>

							<?= form_input([
								'name' => 'Event[end_date]',
								'class' => 'form-control flatpickr',
								'id' => 'input-end',
								'placeholder' => 'yyyy-mm-dd',
								'title' => 'End'
							]); ?>
						</div>
					</div>

					<div class="form-check mb-3">
						<?= form_checkbox([
							'name' => 'Event[all_day]',
							'class' => 'form-check-input',
							'id' => 'input-all_day'
						], 1); ?>

						<?= form_label('All Day', 'input-all_day', [
							'class' => 'form-check-label',
						]); ?>
					</div>

					<div class="mb-3">
						<?= form_label('Category', 'select-category', [
							'class' => 'form-label'
						]); ?>

						<?= $this->html->dropDownList('Event[category]', null, $categories, [
							'class' => 'form-control',
							'id' => 'select-category',
							'prompt' => '- Choose Category -'
						]) ?>
					</div>

					<div class="mb-3">
						<?= form_label('Description', 'input-desc', [
							'class' => 'form-label'
						]); ?>

						<?= form_textarea([
							'name' => 'Event[description]',
							'class' => 'form-control',
							'id' => 'input-desc',
							'title' => 'Description',
							'style' => 'height: 100px;resize: none'
						]); ?>
					</div>

				<?= form_close(); ?>
			</div>
			<div class="modal-footer justify-content-between">
				<button class="btn btn-outline-danger btn-sm" type="button" id="btn-delete" style="display:none">
					<i class="fa fa-trash me-2"></i> Delete</button>

				<button class="btn btn-primary" type="submit" form="form-event">
					<i class="fa fa-save me-2"></i> Save</button>
			</div>
		</div>
	</div>
</div>

<?php $this->layout->endSection() ?>
<!-- ----------------------------------------------- -->
<?php $this->layout->startSection('scripts-inline') ?>

<script>
	var calendarEl = document.getElementById('calendar');

	const calendar = new FullCalendar.Calendar(calendarEl, {
		initialView: 'dayGridMonth',
		selectable: true,
		events: '<?= base_url('calendar/get-events') ?>',
		displayEventTime: false,
		headerToolbar: {
			left: 'prevYear prev,today,next nextYear',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
		},
		views: {
            dayGridMonth: { buttonText: 'Month' },
            timeGridWeek: { buttonText: 'Week' },
            timeGridDay: { buttonText: 'Day' },
            listMonth: { buttonText: 'List' }
        },
		select: function(info) {
            $('#form-title').text('Add Event');

			// Reset form first, and then set value
			resetForm();

            $('#input-start').val(`${info.startStr.substr(0, 10)}`);
            $('#input-end').val(`${info.endStr.substr(0, 10)}`);

            $('#modal-event').modal('show');
        },
		eventClick: function(info) {
			const event = info.event;

			$('input[name="Event[id]"]').val(event.id);

            $('#form-title').text('Edit Event');
            $('#input-title').val(event.title);

			$('#input-all_day').prop('checked', event.allDay).trigger('change');

            $('#input-start').val(`${event.startStr.substr(0, 10)} ${event.startStr.substr(11, 5)}`);

            if (event.end) {
                $('#input-end').val(`${event.endStr.substr(0, 10)} ${event.endStr.substr(11, 5)}`);
            }

			$('#select-category').val(event.extendedProps.category).trigger('change');
			$('#input-desc').val(event.extendedProps.description);

			$('#modal-event').modal('show');
			
			$('#btn-delete').show().off('click').on('click', function() {
				customConfirmation({message: 'Are you sure you want to delete this event?'}, (confirmed) => {
					if (!confirmed) return;

					$.ajax({
						url: '<?=  base_url('/calendar/delete/') ?>' + event.id,
						type: 'post',
						dataType: 'json',
						data: {
							[csrf_name] : csrf_hash
						},
						success: function(response) {
							// Hapus event dari calendar
							calendar.refetchEvents();
							$('#modal-event').modal('hide');
						},
						error: function(xhr, status, error) {
							// Handle error response
							console.error('Error deleting event: ' + error);
						}
					});
				});
			});
        }
	});

	$(document).ready(function() {

		calendar.render();

	    initPickers(true);
	});

	// Add Event Button
    $('#btn-add-event, #fab-add-event').on('click', function() {
        $('#form-title').text('Add Event');
        resetForm()
        $('#modal-event').modal('show');
    });

    // All Day toggle
    $('#input-all_day').on('change', function() {
        if ($(this).is(':checked')) {
            initPickers(false);
        } else {
            initPickers(true);
        }
    });

    // Form Submit
    $('#form-event').on('submit', function(e) {
        e.preventDefault();
        
		// TODO: Ajax kirim ke server
		$.ajax({
			url: '<?=  base_url('/calendar/save') ?>',
			type: 'POST',
			dataType: 'json',
			data: $(this).serialize(),
			success: function(response) {
				resetForm();
				$('#modal-event').modal('hide');
				calendar.refetchEvents();
			},
			error: function(xhr, status, error) {
				// Handle error response
				console.error('Error saving event: ' + error);
			}
		});		

        calendar.getEventSources()[0].refetch();
        $('#eventForm').removeClass('show');
    });

    function initPickers(enableTime = true) {
	    $(".flatpickr").flatpickr({
	        enableTime: enableTime,
	        dateFormat: enableTime ? "Y-m-d H:i" : "Y-m-d",
	        time_24hr: true
	    });

		if (!enableTime) {
			$('#input-end').attr('disabled', true).parent().hide();
		} else {
			$('#input-end').attr('disabled', false).parent().show();
		}
	}

	function getEvents(year = null) {
		year = year ?? new Date().getFullYear();

		$.ajax({
			url: '<?= base_url('calendar/get-events') ?>',
			type: 'GET',
			dataType: 'json',
			// data: {param1: 'value1'},
		})
		.done(function(data) {
			console.log(data);
		})
		.fail(function(err) {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function resetForm() {
		$('#form-event')[0].reset();
		$('input[name="Event[id]"]').val('');
		$('#btn-delete').hide();
		$('#input-all_day').prop('checked', false).trigger('change');
	}
</script>

<?php $this->layout->endSection() ?>
