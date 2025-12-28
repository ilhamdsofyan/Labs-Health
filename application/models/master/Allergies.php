<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allergies extends MY_Model
{
	public $tableName = 'mstr_allergies';
	public $datatable_columns = ['id', 'name', 'description', 'created_at', 'created_by'];
	public $datatable_search = ['name', 'description'];
    public $blameable = true;
    public $timestamps = true;

}

/* End of file Allergies.php */
/* Location: ./application/models/master/Allergies.php */