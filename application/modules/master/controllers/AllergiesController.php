<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AllergiesController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
		]);
	}

	public function actionIndex()
	{
		$this->layout->title = 'Master';
		$this->layout->subtitle = 'Allergies';
		$this->layout->title_icon = 'database';

		$this->layout->render('index');
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->allergies->getDatatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->name;
            $row[] = $field->description;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview'><i class='fa fa-eye'></i></button> 
	            	<a href='". base_url("/rbac/user/edit/{$field->id}") ."' class='btn btn-warning btn-xs'><i class='fa fa-pencil-alt'></i></a> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-alt'></i></button>
	            	<button data-id='{$field->id}' class='btn btn-primary btn-xs btn-add-detail' title='Tambah Detail'>
	            		<i class='fa fa-share'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->allergies->countDatatablesAll(),
            "recordsFiltered" => $this->allergies->countDatatablesFiltered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

}

/* End of file AllergiesController.php */
/* Location: ./application/modules/master/controllers/AllergiesController.php */
