<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Usuarios extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('usuario_model');
			$this->load->model('setorusuario_model');
		}
		public function index()
		{
			$this->template->load('default/index', 'usuarios/index');
		}
		// public function cadastrar()
		// {
		// 	$data['dados'] = (object) $this->usuario_model->desc_table(); //Preenche as variáveis com valores em branco
		// 	$this->template->load('default/index', 'usuarios/formulario', $data);
		// }
		public function editar($id)
		{
			$data['dados'] = $this->usuario_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				//###############################################################################################
				$tmp = array();
				$dados_setorusuario = $this->setorusuario_model->get( array('usuario_id'=>$data['dados']->id, 'chefe'=>0) );
				foreach ($dados_setorusuario as $key => $value)
				{
					$tmp[] = $value->setor_id;
				}
				$data['usuario_setores'] = $tmp;
				//###############################################################################################
				$tmp = array();
				$dados_setorusuario = $this->setorusuario_model->get( array('usuario_id'=>$data['dados']->id, 'chefe'=>1) );
				foreach ($dados_setorusuario as $key => $value)
				{
					$tmp[] = $value->setor_id;
				}
				$data['administrador_setores'] = $tmp;
				//###############################################################################################
				$this->template->load('default/index', 'usuarios/formulario', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function visualizar($id)
		{
			$data['dados'] = $this->usuario_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				grava_log($this->base_url_controller, 'Visualizou (usuarios) registro ID:'.$data['dados']->id);
				$this->template->load('default/index', 'usuarios/visualizar', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function salvar()
		{
			$this->form_validation->set_rules('txtNome', strong('Nome'), 'trim|required');

			if( !$this->input->post('txtUsuarioDoSetor') )
			{
				$erros['txtUsuarioDoSetor'] = 'O campo <strong>Setor</strong> é obrigatório';
				$response['erros'] = array_filter($erros);
				$response['status'] = 0;
				echo json_encode($response);
				exit;
			}

			if( $this->form_validation->run() == true )
			{
				$AdministradorDoSetor = ( $this->input->post('txtAdministradorDoSetor') ) ? $this->input->post('txtAdministradorDoSetor') : array();
				//########################################################################################
				//######################################################################################## Atualiza dados TABELA (Usuários)
				$data = array(
						'status'=>$this->input->post('txtStatus'),
						'grupo_id'=>$this->input->post('txtGrupoUsuario'),
					);
				$this->usuario_model->update($this->input->post('id'), $data);
				grava_log($this->base_url_controller, 'Atualizou (usuarios) registro ID:'.$this->input->post('id'));
				//######################################################################################## Exclui registros atuais da TABELA (Setores Usuários)
				$this->setorusuario_model->delete_by( array('usuario_id'=>$this->input->post('id')) );
				########################################################################################## Inseri novos dados na TABELA (Setores Usuários)
				unset($data);
				foreach ($this->input->post('txtUsuarioDoSetor') as $value)
				{
					$data = array(
						'setor_id'=>$value,
						'usuario_id'=>$this->input->post('id'),
						'chefe'=>0,
					);
					$this->setorusuario_model->insert( $data );
				}
				//######################################################################################## Inseri novos dados na TABELA (Setores Usuários)
				unset($data);
				foreach ($AdministradorDoSetor as $value)
				{
					$data = array(
						'setor_id'=>$value,
						'usuario_id'=>$this->input->post('id'),
						'chefe'=>1,
					);
					$this->setorusuario_model->insert( $data );
				}
				//########################################################################################
				//########################################################################################
				// Adicionando os setor na SESSSAO DO USUARIO - SE ELE PRÓPRIO SE EDITAR
				//###############################################################################################
				//###############################################################################################
				$this->load->model('setorusuario_model');
				if( $this->input->post('id') == $this->session->dados_usuario->id )
				{
					unset($tmp);
					$tmp = array();
					$dados_setorusuario = $this->setorusuario_model->get( array('usuario_id'=>$this->session->dados_usuario->id, 'chefe'=>0) );
					foreach ($dados_setorusuario as $key => $value)
					{
						$tmp[] = $value->setor_id;
					}
					$this->session->dados_usuario->setores = $tmp;
					//###############################################################################################
					unset($tmp);
					$tmp = array();
					$dados_setorusuario = $this->setorusuario_model->get( array('usuario_id'=>$this->session->dados_usuario->id, 'chefe'=>1) );
					foreach ($dados_setorusuario as $key => $value)
					{
						$tmp[] = $value->setor_id;
					}
					$this->session->dados_usuario->setores_administrador = $tmp;
					//###############################################################################################
					$this->load->model('setor_model');
					$setor = $this->setor_model->select('nome')->get( $this->session->dados_usuario->setores[0] );
					$this->session->dados_usuario->setor = $setor->nome;
				}
				//###############################################################################################
				//###############################################################################################
				$response['status'] = 1;
				$response['action'] = 'update';
				$response['msg'] = 'Registro atualizado com sucesso!';
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
				$return = $this->usuario_model->delete($value);
				grava_log($this->base_url_controller, 'Excluiu (usuarios) registro ID:'.$value);
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
		public function get()
		{
			if( $this->input->post('id') )
			{
				$data['usuario'] = $this->usuario_model->select('id, nome')->get($this->input->post('id'));
			}
			else
			{
				$data['dados'] = $this->usuario_model->select('id, nome')->get();
			}
			echo json_encode($data);
		}
		public function getvaluesinputs()
		{
			$data['inputs'] = $this->usuario_model->get($this->input->post('id'));
			foreach ($data['inputs'] as $key => $value)
			{
				$data_temp[sha1($this->usuario_model->table().'.'.$key)] = $value;
			}
			$data['inputs'] = $data_temp;
			echo json_encode($data);
		}
		public function typeahead()
		{
			$data['Usuarios'] = $this->usuario_model->like( array('nome'=>$this->input->post('query')) )->get();
			$data['status'] = ($data['Usuarios']) ? 1 : 0;
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
				$customers = $this->usuario_model->order_by($sort, $order)->get();
			}
			else
			{
				if( $_GET['like_search'] == 'all' )
				{
					$campos = $this->db->query('DESC '.$this->usuario_model->order_by($sort, $order)->_table)->result();
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
				$customers = $this->usuario_model->order_by($sort, $order)->like($arraySearch)->get();
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
	/* End of file Usuarios.php */
	/* Location: ./application/controllers/Usuarios.php */