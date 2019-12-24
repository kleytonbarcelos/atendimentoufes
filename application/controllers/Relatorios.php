<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Relatorios extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('atendimento_model');
			$this->load->model('atendimentotipo_model');
			$this->load->model('setor_model');
		}
		public function index()
		{
			$this->template->load('default/index', 'relatorios/index');
		}
		public function get_atendentes()
		{
			$setores = '';
			$tmp = array();
			foreach ($this->session->dados_usuario->setores as $setor)
			{
				$tmp[] = $setor;
			}
			foreach ($this->session->dados_usuario->setores_administrador as $setor)
			{
				$tmp[] = $setor;
			}
			$tmp = array_unique($tmp);
			foreach ($tmp as $setor)
			{
				$setores .= 'A.setor_id='.$setor;
				if( sizeof($tmp) > 1){ $setores .= ' OR '; }
			}
			if( sizeof($tmp) > 1){ $setores = substr($setores, 0, -4); }
			$setores = '('.$setores.')';
			//#################################################################################
			//#################################################################################
			$sql = 'SELECT DISTINCT A.usuario_id id, U.nome FROM atendimentos A, usuarios U WHERE (A.usuario_id=U.id) AND '.$setores;
			$response['dados'] = $this->db->query($sql)->result();
			echo json_encode($response);
		}
		public function search()
		{
			$limit  = (isset($_POST['limit']))  ? $_POST['limit']  : 10;
			$offset = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
			$sort   = (isset($_POST['sort']))   ? $_POST['sort']   : '';
			$order  = (isset($_POST['order']))  ? $_POST['order']  : "asc";
			$search = (isset($_POST['search'])) ? $_POST['search'] : "";
			//#################################################################################
			//#################################################################################
			if( $this->input->post('txtSetorAtendimento') )
			{
				$setores = '(A.setor_id='.$this->input->post('txtSetorAtendimento').')';
			}
			else
			{
				$setores = '';
				$tmp = array();
				foreach ($this->session->dados_usuario->setores as $setor)
				{
					$tmp[] = $setor;
				}
				foreach ($this->session->dados_usuario->setores_administrador as $setor)
				{
					$tmp[] = $setor;
				}
				$tmp = array_unique($tmp);
				foreach ($tmp as $setor)
				{
					$setores .= 'A.setor_id='.$setor;
					if( sizeof($tmp) > 1){ $setores .= ' OR '; }
				}
				if( sizeof($tmp) > 1){ $setores = substr($setores, 0, -4); }
				$setores = '('.$setores.')';
			}
			//#################################################################################
			//#################################################################################
			$nome  = ($_POST['txtNome'])  ? $this->input->post('txtNome')  : NULL;
			$tipo_usuario  = ($this->input->post('txtTipoUsuario'))  ? $this->input->post('txtTipoUsuario')  : NULL;
			$tipo_atendimento  = ($this->input->post('txtTipoAtendimento'))  ? $this->input->post('txtTipoAtendimento')  : NULL;
			$atendente  = ($this->input->post('txtAtendente'))  ? $this->input->post('txtAtendente')  : NULL;
			$assunto  = ($this->input->post('txtAssunto'))  ? $this->input->post('txtAssunto')  : NULL;
			$status  = ($this->input->post('txtStatus'))  ? $this->input->post('txtStatus')  : NULL;
			
			$data_inicio  = ($this->input->post('txtDataInicial'))  ? date_to_us($this->input->post('txtDataInicial'))  : date('Y').'-01-01';
			$data_final  = ($this->input->post('txtDataFinal'))  ? date_to_us($this->input->post('txtDataFinal'))  : date('Y').'-12-31';
			//#################################################################################
			$like = '';
			if($nome)
			{
				$like .= ' AND A.nome LIKE "%'.$nome.'%"';
			}
			if($tipo_usuario)
			{
				$like .= ' AND A.tipousuario="'.$tipo_usuario.'"';
			}
			if($tipo_atendimento)
			{
				$like .= ' AND T.id LIKE "%'.$tipo_atendimento.'%"';
			}
			if($atendente)
			{
				$like .= ' AND U.id LIKE "%'.$atendente.'%"';
			}
			if($assunto)
			{
				$like .= ' AND A.assunto LIKE "%'.$assunto.'%"';
			}
			if($status)
			{
				$like .= ' AND A.status="'.$status.'"';
			}
			if($like)
			{				
				$like = 'AND('.substr($like, 5).')';
			}
			//#################################################################################
			//#################################################################################
			if( $this->input->post('txtPeriodicidade') == 'diario' )
			{
				$data = 'ANY_VALUE(DATE_FORMAT(A.data, "%d/%m/%Y")) AS "data",';
				$group_by = 'GROUP BY day(data)';
			}
			else if( $this->input->post('txtPeriodicidade') == 'semanal' )
			{
				$data = 'ANY_VALUE(DATE_FORMAT(A.data, "%Vº semana de %Y")) AS "data",';
				$group_by = 'GROUP BY week(data)';
			}
			else if( $this->input->post('txtPeriodicidade') == 'mensal' )
			{
				$data = 'ANY_VALUE(DATE_FORMAT(A.data, "%m/%Y")) AS "data",';
				$group_by = 'GROUP BY month(data)';
			}
			else if( $this->input->post('txtPeriodicidade') == 'anual' )
			{
				$data = 'ANY_VALUE(DATE_FORMAT(A.data, "%Y")) AS "data",';
				$group_by = 'GROUP BY year(data)';
			}
			//#################################################################################
			//#################################################################################
			$sql = '
					SELECT
						'.$data.'
						COUNT(*) AS "value"
					FROM
						atendimentos AS A,
						atendimentos_tipos AS T,
						usuarios AS U
					WHERE
						(A.atendimentotipo_id=T.id AND A.usuario_id=U.id)
						AND'.$setores.''.$like.'
						AND (DATE_FORMAT(data, "%Y-%m-%d") BETWEEN ("'.$data_inicio.'") AND ("'.$data_final.'"))
					'.$group_by.'
					ORDER BY data ASC
			';
			$response['status'] = 1;
			$response['atendimentos'] = $this->db->query($sql)->result();
			echo json_encode($response);
		}
	}
	/* End of file Relatorios.php */
	/* Location: ./application/controllers/Relatorios.php */