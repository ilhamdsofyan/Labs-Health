<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userdetail extends MY_Model {

	public $tableName = 'tbl_user_detail';

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/user',
		]);
	}

	public function mergeFullName($user_detail = [])
	{
		if (!empty($this->nama_depan) && empty($user_detail)) {
			$user_detail = $this;
		}

		$nama = '';

		if (!empty($user_detail->nama_depan) && $user_detail->nama_depan != '-') {
			$nama .= $user_detail->nama_depan;
		}

		if (!empty($user_detail->nama_tengah) && $user_detail->nama_tengah != '-') {
			$nama .= ' '. $user_detail->nama_tengah;
		}

		if (!empty($user_detail->nama_belakang) && $user_detail->nama_belakang != '-') {
			$nama .= ' '. $user_detail->nama_belakang;
		}

		return $nama;
	}

	public function getImage()
	{
		$default = '/web/images/no_avatar.jpg';
		$img = $default;

		if ($this->profile_pic) {
			$img = $this->profile_pic;
		}

		if (!file_exists($img)) {
			$img = $default;
		}

		return str_replace('./', '', base_url($img));
	}

}
/* End of file modelName.php */
/* Location: ./application/models/modelName.php */