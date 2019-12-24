<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Setores extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('setor_model');
		}
		public function index()
		{
			$this->template->load('default/index', 'setores/index');
		}
		public function cadastrar()
		{
			$data['dados'] = (object) $this->setor_model->desc_table(); //Preenche as variáveis com valores em branco
			$this->template->load('default/index', 'setores/formulario', $data);
		}
		public function editar($id)
		{
			$data['dados'] = $this->setor_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				$this->template->load('default/index', 'setores/formulario', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function visualizar($id)
		{
			$data['dados'] = $this->setor_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				grava_log($this->base_url_controller, 'Visualizou (setores) registro ID:'.$data['dados']->id);
				$this->template->load('default/index', 'setores/visualizar', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function salvar()
		{
			$this->form_validation->set_rules('txtNome', strong('Nome'), 'trim|required');
			$this->form_validation->set_rules('txtDescricao', strong('Descrição'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$data = array(
					//'usuario_id'=>$this->session->dados_usuario->id,
					'nome'=>$this->input->post('txtNome'),
					'descricao'=>$this->input->post('txtDescricao'),
				);

				if(!$this->input->post('id'))
				{
					$insert_id = $this->setor_model->insert($data);
					grava_log($this->base_url_controller, 'Cadastrou (setores) registro ID:'.$insert_id);
					$response['action'] = 'insert';
					$response['status'] = 1;
					$response['msg'] = 'Registro adicionado com sucesso!';
				}
				else
				{
					$this->setor_model->update($this->input->post('id'), $data);
					grava_log($this->base_url_controller, 'Atualizou (setores) registro ID:'.$this->input->post('id'));
					$response['action'] = 'update';
					$response['status'] = 1;
					$response['msg'] = 'Registro atualizado com sucesso!';
				}
				echo json_encode($response);
			}
			else
			{
				$erros = array();
				foreach ($this->input->post() as $key => $value)
				{
					$erros[$key] = form_error($key);
				}
				$response['erros'] = array_filter($erros);
				$response['status'] = 0;
				echo json_encode($response);
			}
		}
		public function excluir()
		{
			$ids = explode(',', $this->input->post('id'));
			foreach ($ids as $key => $value)
			{
				$return = $this->setor_model->delete($value);
				grava_log($this->base_url_controller, 'Excluiu (setores) registro ID:'.$value);
			}
			if($return)
			{
				$response['status'] = 1;
				$response['msg'] = 'Registro(s) excluído(s) com sucesso!';
			}
			else
			{
				$response['status'] = 0;
				$response['msg'] = 'Erro ocorrido na operação!';
			}
			echo json_encode($response);
		}
		public function setorusuario()
		{
			if( $this->session->dados_usuario->grupo_id == 1 )
			{
				$data['dados'] = $this->setor_model->select('id, nome')->get();
			}
			else
			{
				$sql = '
						SELECT setores.id, setores.nome FROM setores
								INNER JOIN  setores_usuarios
									ON setores_usuarios.setor_id = setores.id
						WHERE
							setores_usuarios.usuario_id='.$this->input->post('usuario_id').'
							AND
							setores_usuarios.chefe=1
						';
				$data['dados'] = (object) $this->db->query($sql)->result();
			}
			echo json_encode($data);
		}
		public function get()
		{
			if( $this->input->post('id') )
			{
				$data['setor'] = $this->setor_model->select('id, nome, descricao')->get($this->input->post('id'));
			}
			else
			{
				$data['dados'] = $this->setor_model->select('id, nome')->get();
			}
			echo json_encode($data);
		}
		public function setores_usuario_administrador()
		{
			$setores = array();
			foreach($this->session->dados_usuario->setores as $setor)
			{
				$setores[] = $setor;
			}
			foreach($this->session->dados_usuario->setores_administrador as $setor)
			{
				$setores[] = $setor;
			}
			$setores = array_unique($setores);
			$data['dados'] = $this->setor_model->select('id, nome')->get( array('id'=>$setores) );
			echo json_encode($data);
		}
		public function setores_administrador()
		{
			$setores = array();
			foreach ($this->session->dados_usuario->setores_administrador as $setor)
			{
				$setores[] = $setor;
			}
			$setores = array_unique($setores);
			$data['dados'] = $this->setor_model->select('id, nome')->get( array('id'=>$setores) );
			echo json_encode($data);
		}
		public function getvaluesinputs()
		{
			$data['inputs'] = $this->setor_model->get($this->input->post('id'));
			foreach ($data['inputs'] as $key => $value)
			{
				$data_temp[sha1($this->setor_model->table().'.'.$key)] = $value;
			}
			$data['inputs'] = $data_temp;
			echo json_encode($data);
		}
		public function typeahead()
		{
			$data['Setores'] = $this->setor_model->like( array('nome'=>$this->input->post('query')) )->get();
			$data['status'] = ($data['Setores']) ? 1 : 0;
			echo json_encode($data);
		}
		public function bootstrap_table()
		{
			$limit  = (isset($_GET['limit']))  ? $_GET['limit']  : 10;
			$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
			$sort   = (isset($_GET['sort']))   ? $_GET['sort']   : '';
			$order  = (isset($_GET['order']))  ? $_GET['order']  : "asc";
			$search = (isset($_GET['search'])) ? $_GET['search'] : "";

			$customers = array();
			if ($search == "")
			{
				$customers = $this->setor_model->order_by($sort, $order)->get();
			}
			else
			{
				if( $_GET['like_search'] == 'all' )
				{
					$campos = $this->db->query('DESC '.$this->setor_model->order_by($sort, $order)->_table)->result();
					foreach ($campos as $campo)
					{
						$arraySearch[$campo->Field] = "".$search."";
					}
				}
				else
				{
					$campos = explode('|', $_GET['like_search']);
					foreach ($campos as $campo)
					{
						$arraySearch[$campo] = "".$search."";
					}
				}
				$customers = $this->setor_model->order_by($sort, $order)->like($arraySearch)->get();
			}
			
			$count = sizeof($customers);
			$customers = array_slice($customers, $offset, $limit);

			echo "{";
				echo '"total": ' . $count . ',';
				echo '"rows": ';
				echo json_encode($customers);
			echo "}";
		}
	}
	/* End of file Setores.php */
	/* Location: ./application/controllers/Setores.php */