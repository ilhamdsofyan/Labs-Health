<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendarController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'events'
		]);
	}

	public function actionIndex()
	{
		$categories = $this->events->getCategoryOptions();

		$this->layout->render('index', [
			'categories' => $categories,
		]);
	}

	public function actionSave()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		if ($post = $this->input->post('Event')) {
			$model = new Events;

			if (!empty($post['id'])) {
				$model = $model->findOne(['uuid' => $post['id']]);

				if (!$model) {
					$result = [
						'message' => 'Data event tidak ditemukan'
					];

					return $this->output
				        ->set_content_type('application/json')
				        ->set_status_header(200) // Return status
				        ->set_output(json_encode($result));
				}
			}

			$post['user_id'] = $this->session->userdata('identity')->id;
			$post['all_day'] = empty($post['all_day']) ? 0 : $post['all_day'];

			if ($post['all_day'] == 1) {
				$post['start_date'] = date('Y-m-d 00:00:00', strtotime($post['start_date']));
				$post['end_date'] = date('Y-m-d 59:59:59', strtotime($post['start_date']));
			}

			$model->setAttributes($post);

			if ($model->save()) {
				$result = [
					'message' => 'Penambahan data event berhasil'
				];
			} else {
				$errors = 'Proses simpan penambahan data event gagal, silahkan coba beberapa saat lagi';
				if ($model->getErrors()) {
					$errors = $this->helpers->valueErrors($this->events->getErrors(), true);
				}

				$result = [
					'message' => $errors
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetEvents()
	{
		$start = $this->input->get('start');
		$end = $this->input->get('end');

		$model = $this->events->getEvents([
			'start' => $start,
			'end' => $end,
		]);

		$data = [];
		// Json for fullcalendar
		foreach ($model as $event) {
			$data[] = [
				'id' => $event->uuid,
				'title' => $event->title,
				'start' => date('c', strtotime($event->start_date)), // iso format
				'end' => date('c', strtotime($event->end_date)),
				'allDay' => $event->all_day == 1 ? true : false,
				'category' => $event->category,
				'description' => $event->description,
				'backgroundColor' => $this->events->categories[$event->category]['color'],
				'borderColor' => $this->events->categories[$event->category]['color']
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($data));
	}

	public function actionDelete($id)
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		if ($this->events->delete(['uuid' => $id])) {
			$result = [
				'message' => 'Hapus data event berhasil'
			];
		} else {
			$errors = 'Proses hapus data event gagal, silahkan coba beberapa saat lagi';
			if ($this->events->getErrors()) {
				$errors = $this->helpers->valueErrors($this->events->getErrors(), true);
			}

			$result = [
				'message' => $errors
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file CalendarController.php */
/* Location: ./application/controllers/CalendarController.php */
