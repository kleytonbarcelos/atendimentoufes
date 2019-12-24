<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Atendimento_model extends MY_Model
{
	public $belongs_to = array(
		'setor'=>array('model'=>'setor_model', 'primary_key'=>'setor_id'),
		'usuario'=>array('model'=>'usuario_model', 'primary_key'=>'usuario_id'),
	);
	public $_table = 'atendimentos';
	public $primary_key = 'id';

}
/* End of file Usuario.php */
/* Location: ./application/models/Atendimento_model.php */