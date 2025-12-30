<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends MY_Model
{
	public $tableName = 'events';
	public $soft_delete = false;
	public $timestamps = true;
	public $blameable = true;

	public $categories = [
		'holiday' => [
			'value' => 'Tanggal Merah / Hari Nasional',
			'color' => '#DC3545'
		],
		'joint-leave' => [
			'value' => 'Cuti Bersama (umum, bukan personal)',
			'color' => '#F76707'
		],
		'meeting' => [
			'value' => 'Meeting',
			'color' => '#1E88E5'
		],
		'deadline' => [
			'value' => 'Deadline Project',
			'color' => '#6F42C1'
		],
		'reminder' => [
			'value' => 'Reminder / Task kecil',
			'color' => '#198754'
		],
		'training' => [
			'value' => 'Training / Workshop',
			'color' => '#20C997'
		],
		'birthday' => [
			'value' => 'Ulang Tahun (opsional, biasanya dipake di team calendar)',
			'color' => '#E83E8C'
		],
		'travel' => [
			'value' => 'Perjalanan Dinas',
			'color' => '#0AA2C0'
		],
		'event' => [
			'value' => 'Event / Seminar',
			'color' => '#FD7E14'
		],
		'maintenance' => [
			'value' => 'Maintenance System',
			'color' => '#6C757D'
		],
		'observance' => [
			'value' => 'Hari Peringatan (non-libur)',
			'color' => '#ADB5BD'
		]
	];

	public function getCategoryOptions()
	{
		$options = [];

		foreach ($this->categories as $key => $category) {
			$options[$key] = $category['value'];
		}

		return $options;
	}

	public function getEvents($ranges = [])
	{
		$data = self::find()
			->select('uuid, title, start_date, end_date, all_day, category, description')
			->where([
				'start_date >=' => isset($ranges['start']) ? date('Y-m-d H:i:s', strtotime($ranges['start'])) : date('Y-m-d 00:00:00'),
				'end_date <=' => isset($ranges['end']) ? date('Y-m-d 23:59:59', strtotime($ranges['end'])) : date('Y-m-d 23:59:59'),
			])
			->or_where([
				"user_id" => $this->session->userdata('identity')->id,
				"user_id" => null,
			])
			->order_by('start_date', 'ASC')
			->get()
			->result();

		return $data;
	}

	public function store($data)
	{
		return $this->insert($data);
	}
}

/* End of file Events.php */
/* Location: ./application/models/Events.php */
