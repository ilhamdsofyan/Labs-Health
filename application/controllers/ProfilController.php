<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfilController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/userdetail',
			'rbac/usergroup',
			'rbac/group',
			'forms/FormChangePassword',
		]);
	}

	public function actionIndex()
	{
		$id = $this->input->get('id');

		$this->layout->view_js = [
			'_partial/sub_js',
			'_partial/js_password',
		];
		$this->layout->view_css = '/_partial/sub_css';
		$this->layout->title = 'Profil';	

		if ($id) {
			$user = $this->user->findOne($id);
			$user_detail = $user->userDetail;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
			$param = $id;
		} else {
			$user = $this->user->findOne($this->session->userdata('identity')->id);
			$user_session = '';
			$user_detail = $user->userDetail;
			$data_login = '';
			$data_profil = '';
			$param = '';
		}

		$this->layout->render('index', [
			'user_detail' => $user_detail,
			'user_session' => $user_session,
			'param' => $param,
			'user' => $user,
			'data_login' => $data_login,
			'data_profil' => $data_profil,
		]);
	}

	public function actionSimpanInfoPersonal()
	{
		$post = $this->input->post('Personal');

		$id = $this->input->get('id');
		if ($id) {
			$redirect = "/profil?id={$id}";
		} else {
			$id = $this->session->userdata('identity')->id;
			$redirect = "/profil";
		}

		$current_data = $this->userdetail->get(['user_id' => $id]);

		if ($post) {
			# Reformat tanggal_lahir
			$post['tanggal_lahir'] = $post['tanggal_lahir'] ? date('Y-m-d', strtotime($post['tanggal_lahir'])) : '';

			# Jika data ada, maka edit. Selain itu insert
			if ($current_data) {
				$save = $this->userdetail->update($post, $current_data->id);
			} else {
				$post['user_id'] = $id;
				$post['created_by'] = $id;

				$save = $this->userdetail->insert($post);
			}

			if ($save) {
				$this->session->set_flashdata('success', 'Penyimpanan data berhasil');
			} else {
				$this->session->set_flashdata('danger', 'Penyimpanan data gagal');
			}
		}

		redirect($redirect, 'refresh');
	}
    
	public function actionUbahFoto()
	{
		$id = $this->input->get('id');
		if (empty($id)) {
			$id = $this->session->userdata('identity')->id;
		}

		$user_detail = $this->userdetail->get(['user_id' => $id]);
		$result = [
	    	'message' => 'Ubah foto profil gagal, silahkan lengkapi data personal terlebih dahulu',
	    	'images' => "/web/assets/pages/img/no_avatar.jpg",
    		'success' => false,
	    ];

		if ($user_detail) {
			$path = './web/uploads/profile';

			if (!file_exists($path)) {
			    mkdir($path, 0777, true);
			}

			$config['upload_path']		= $path;
		    $config['allowed_types']	= 'png|jpeg|jpg';
		    $config['file_name']		= 'Foto_Profil_' . $id;
		    $config['overwrite']		= true;
		    $config['max_size']			= 500; // 500KB

		    $this->load->library('upload', $config);

		    if ($this->upload->do_upload('profil_pic')) {
		    	# RESIZE IMAGE WITH JCROP OPTIONS
		    	$uploaded_file = $this->upload->data();
		    	unset($config);
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = $path .'/'. $uploaded_file['file_name'];
                $config['quality'] = "100%";
                $config['maintain_ratio'] = FALSE; 
		    	$config['width'] = 512;
		    	$config['height'] = 512;
                $config['new_image'] = $path .'/'. $uploaded_file['file_name'];
		    	$config['x_axis'] = intval($this->input->post('x1'));
		    	$config['y_axis'] = intval($this->input->post('y1'));
                $config['width'] = intval($this->input->post('w'));
                $config['height'] = intval($this->input->post('h'));
                $this->load->library('image_lib', $config);
                $this->image_lib->crop();

		    	$update = $this->userdetail->update([
		    		'profile_pic' => $path .'/'. $this->upload->data("file_name")
		    	], $user_detail->id);

		    	if ($update) {
		    		if (empty($this->input->get('id'))) {
		    			$this->session->set_userdata('detail_identity', $this->userdetail->get(['user_id' => $id]));
		    		}

			        $result = [
				    	'message' => 'Ubah foto profil berhasil',
				    	'images' => $path .'/'. $this->upload->data("file_name"),
			    		'success' => true,
				    ];
		    	} else {
		    		$result = [
				    	'message' => 'Proses simpan foto ke database gagal. Silahkan coba lagi',
			    		'images' => ($user_detail->profile_pic ? $user_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"),
			    		'success' => false,
				    ];
		    	}

		    } else {
		    	$result = [
			    	'message' => $this->upload->display_errors(),
			    	'images' => ($user_detail->profile_pic ? $user_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"),
			    	'success' => false,
			    ];
		    }
		}

	    return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionUbahPassword($id)
	{
		$user = $this->user->findOne($id);

		if ($post = $this->input->post('ChangePassword')) {
			$model = new FormChangePassword;
			$model->setAttributes($post);

			if ($model->validate()) {
				$user->password = password_hash($post['new_password'], PASSWORD_DEFAULT);

				if ($user->save(false)) {
					$result = [
						'message' => 'Perubahan password berhasil, akun anda akan logout',
						'status' => 'success'
					];
				} else {
					$result = [
						'message' => 'Perubahan password gagal, silahkan coba lagi',
						'status' => 'error'
					];
				}
			} else {
				$result = [
					'message' => implode('; ', array_values($model->getErrors())),
					'status' => 'error'
				];
			}
		}

		if (empty($result)) {
			$result = [
				'message' => 'Permohonan perubahan password gagal, mohon periksa isian anda',
				'status' => 'info'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}
/* End of file ProfilController.php */
/* Location: ./application/controllers/ProfilController.php */