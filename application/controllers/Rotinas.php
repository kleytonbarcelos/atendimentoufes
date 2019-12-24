<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Rotinas extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('rotina_model');
			$this->load->model('setor_model');
			$this->load->model('setorusuario_model');
		}
		public function index()
		{
			$this->template->load('default/index', 'rotinas/index');
		}
		public function cadastrar()
		{
			$data['dados'] = (object) $this->rotina_model->desc_table(); //Preenche as variáveis com valores em branco
			$this->template->load('default/index', 'rotinas/formulario', $data);
		}
		public function editar($id)
		{
			$data['dados'] = $this->rotina_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				$this->template->load('default/index', 'rotinas/formulario', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function visualizar($id)
		{
			$data['dados'] = $this->rotina_model->get_by('MD5(id)', $id);
			$data['setor'] = $this->setor_model->get($data['dados']->setor_id);
			if($data['dados'])
			{
				grava_log($this->base_url_controller, 'Visualizou (rotinas) registro ID:'.$data['dados']->id);
				$this->template->load('default/index', 'rotinas/visualizar', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function salvar()
		{	
			$this->form_validation->set_rules('txtNome', strong('Nome'), 'trim|required');
			$this->form_validation->set_rules('txtTipo', strong('Tipo'), 'trim|required');
			$this->form_validation->set_rules('txtDescricao', strong('Descrição'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$data = array(
					'usuario_id'=>$this->session->dados_usuario->id,
					'setor_id'=>$this->input->post('txtSetor'),
					'nome'=>$this->input->post('txtNome'),
					'definicao'=>$this->input->post('txtDefinicao'),
					'tipo'=>$this->input->post('txtTipo'),
					'fluxo'=>$this->input->post('txtFluxo'),
					'descricao'=>$this->input->post('txtDescricao'),
				);
				//#############################################################################################################
				//#############################################################################################################
				if ( !empty($_FILES['txtMapaMental']['name']) )
				{
					if( $this->input->post('id') )
					{
						$data_upload = (object) $this->rotina_model->get($this->input->post('id'));
						@unlink('assets/uploads/'.$data_upload->mapamental);
					}
					//#####################################################################################
					$dir_uploads = './assets/uploads/';
					$config['upload_path']	= $dir_uploads;
					if( !is_dir($config['upload_path']) ){ mkdir($config['upload_path']); } // Criar pasta se não existir
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']= 2000;
					//$config['min_width']= 800;
					//$config['min_height']= 600;
					//$config['max_width']= 1920;
					//$config['max_height'] = 300;
					$config['file_ext_tolower'] = true;
					$config['encrypt_name']  = true;
					$this->load->library('upload', $config);

					if( !$this->upload->do_upload('txtMapaMental') )
					{
						$erros = array();
						$erros['txtMapaMental'] = $this->upload->display_errors();
						$response['erros'] = array_filter($erros);
						$response['status'] = 0;
						echo json_encode($response);
						exit;
					}
					else
					{
						$upload = $this->upload->data();
						// $config['image_library'] = 'gd2';
						// $config['source_image'] = $dir_uploads.$upload['file_name'];
						// $config['maintain_ratio'] = true;
						// $config['width'] = 800;
						// $this->load->library('image_lib', $config);
						// $this->image_lib->resize();
						$tmp = array('mapamental'=>$upload['file_name']);
						$data = array_merge($data, $tmp);
					}
				}
				//#############################################################################################################
				//#############################################################################################################
				if(!$this->input->post('id'))
				{
					$insert_id = $this->rotina_model->insert($data);
					grava_log($this->base_url_controller, 'Cadastrou (rotinas) registro ID:'.$insert_id);
					$response['action'] = 'insert';
					$response['status'] = 1;
					$response['msg'] = 'Registro adicionado com sucesso!';
				}
				else
				{
					$this->rotina_model->update($this->input->post('id'), $data);
					grava_log($this->base_url_controller, 'Atualizou (rotinas) registro ID:'.$this->input->post('id'));
					$response['action'] = 'update';
					$response['status'] = 1;
					$response['msg'] = 'Registro atualizado com sucesso!';
				}
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
			}
			echo json_encode($response);
		}
		public function excluir_arquivo()
		{
			$field = $this->input->post('field');
			$data = (object) $this->rotina_model->get($this->input->post('id'));
			grava_log($this->base_url_controller, 'Excluiu (file rotinas) registro ID:'.$this->input->post('id'));
			@unlink('assets/uploads/'.$data->$field);
			$this->rotina_model->update($this->input->post('id'), array($field=>NULL));
			$response['status'] = 1;
			$response['msg'] = 'Operação realizada com sucesso!';
			echo json_encode($response);
		}
		public function publicarrotina()
		{
			$rotina_publicada = $this->rotina_model->get( $this->input->post('id') );
			if($rotina_publicada)
			{
				$this->rotina_model->update($rotina_publicada->id, array('status'=>'publicado', 'parent_id'=>NULL)); // altera dados da ROTINA (pendente) para (publicado)
				$this->rotina_model->update($rotina_publicada->parent_id, array('status'=>'historico', 'parent_id'=>$rotina_publicada->id)); // altera dados da ROTINA de (publicado) para (historico)
				$this->rotina_model->update_by(array('parent_id'=>$rotina_publicada->parent_id), array('parent_id'=>$rotina_publicada->id)); // alterado todos parent_id (historico) para novo parent_id (publicado)

				$response['status'] = 1;
				$response['msg'] = 'Registro atualizado com sucesso!';
				echo json_encode($response);
			}
		}
		public function rotinassetores($status='publicado')
		{
			if($status == 'publicado')
			{		
				$data['setores'] = $this->setorusuario_model->get( array('usuario_id'=>$this->session->dados_usuario->id) );
				$tmp = array();
				foreach ($data['setores'] as $setor)
				{
					$tmp[] = $setor->setor_id;
				}
				$data['rotinas'] = $this->rotina_model->get( array('setor_id'=>$tmp, 'status'=>'publicado') );
			}
			else
			{
				$data['setores'] = $this->setorusuario_model->get( array('usuario_id'=>$this->session->dados_usuario->id) );

				$tmp = array();
				foreach ($data['setores'] as $setor)
				{
					$tmp[] = $setor->setor_id;
				}
				$data['rotinas'] = $this->rotina_model->get( array('setor_id'=>$tmp, 'status'=>'pendente') );
			}
			echo json_encode($data);
		}
		public function pesquisar()
		{
			$sql = '
					SELECT *
						FROM rotinas
					WHERE
						setor_id IN (SELECT setor_id FROM setores_usuarios WHERE usuario_id='.$this->session->dados_usuario->id.')
					AND (
						nome LIKE "%'.$this->input->post('search').'%"
							OR
						definicao LIKE "%'.$this->input->post('search').'%"
							OR
						descricao LIKE "%'.$this->input->post('search').'%"
					)
					ORDER BY setor_id;
			';
			$data['rotinas'] =  $this->db->query($sql)->result();
			echo json_encode($data);
		}
		public function sugerir_edicao_salvar()
		{
			$this->form_validation->set_rules('txtNome', strong('Nome'), 'trim|required');
			$this->form_validation->set_rules('txtTipo', strong('Tipo'), 'trim|required');
			$this->form_validation->set_rules('txtDescricao', strong('Descrição'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$data = array(
					'parent_id'=>$this->input->post('parent_id'),
					'usuario_id'=>$this->session->dados_usuario->id,
					'setor_id'=>$this->input->post('setor_id'),
					'nome'=>$this->input->post('txtNome'),
					'definicao'=>$this->input->post('txtDefinicao'),
					'tipo'=>$this->input->post('txtTipo'),
					'fluxo'=>$this->input->post('txtFluxo'),
					'descricao'=>$this->input->post('txtDescricao'),
					'status'=>'pendente',
					'mapamental'=>$this->input->post('mapamental'),
				);

				$insert_id = $this->rotina_model->insert($data);
				grava_log($this->base_url_controller, 'Sugeriu (rotinas) registro ID:'.$insert_id);
				$response['action'] = 'insert';
				$response['status'] = 1;
				$response['msg'] = 'Registro adicionado com sucesso!';

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
				$return = $this->rotina_model->delete($value);
				grava_log($this->base_url_controller, 'Excluiu (rotinas) registro ID:'.$value);
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
				$data['rotina'] = $this->rotina_model->get($this->input->post('id'));
			}
			else
			{
				$data['dados'] = $this->rotina_model->select('id, nome')->get();
			}
			echo json_encode($data);
		}
		public function getvaluesinputs()
		{
			$data['inputs'] = $this->rotina_model->get($this->input->post('id'));
			foreach ($data['inputs'] as $key => $value)
			{
				$data_temp[sha1($this->rotina_model->table().'.'.$key)] = $value;
			}
			$data['inputs'] = $data_temp;
			echo json_encode($data);
		}
		public function typeahead()
		{
			$data['Rotinas'] = $this->rotina_model->like( array('nome'=>$this->input->post('query')) )->get();
			$data['status'] = ($data['Rotinas']) ? 1 : 0;
			echo json_encode($data);
		}
		public function bootstrap_table()
		{
			$limit  = (isset($_GET['limit']))  ? $_GET['limit']  : 10;
			$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
			$sort   = (isset($_GET['sort']))   ? $_GET['sort']   : '';
			$order  = (isset($_GET['order']))  ? $_GET['order']  : "asc";
			$search = (isset($_GET['search'])) ? $_GET['search'] : "";
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			$status = '';
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
				$setores .= 'setor_id='.$setor;
				if( sizeof($tmp) > 1){ $setores .= ' OR '; }
			}
			if( sizeof($tmp) > 1){ $setores = substr($setores, 0, -4); }
			$setores = '('.$setores.')';
			//#################################################################################
			$like = '';
			if($search)
			{
				if( $_GET['like_search'] == 'all' )
				{
					$like .= '(';
					$i = 0;
					$campos = $this->db->query('DESC '.$this->rotina_model->_table)->result();
					foreach ($campos as $campo)
					{
						if($i>=1)
						{
							$like .= ' OR ';
						}
						$like .= $campo->Field.' LIKE "%'.$search.'%"';
						$i++;
					}
					$like .= ')';
				}
				else
				{
					$like .= '(';
					$i = 0;
					$campos = explode('|', $_GET['like_search']);
					foreach ($campos as $campo)
					{
						if($i>=1)
						{
							$like .= ' OR ';
						}
						$like .= $campo.' LIKE "%'.$search.'%"';
						$i++;
					}
					$like .= ')';
				}
			}
			//#################################################################################
			if( $this->session->dados_usuario->grupo_id == 1 )
			{
				$status = ($search) ? 'AND status<>"historico"' : 'status<>"historico"';
			}
			else
			{
				$status = 'AND status<>"historico"';
				if($search)
				{
					$like = 'AND '.$like;
				}
			}
			//#################################################################################
			if( $this->session->dados_usuario->grupo_id == 1 )
			{
				$sql = '
					SELECT
						*
					FROM
						rotinas
					WHERE
						'.$like.'
						'.$status.'
					ORDER BY
						'.$sort.' '.$order.'
				';
			}
			else
			{
				$sql = '
					SELECT
						*
					FROM
						rotinas
					WHERE
						'.$setores.'
						'.$like.'
						'.$status.'
					ORDER BY
						'.$sort.' '.$order.'
				';
			}
			$customers = $this->db->query($sql)->result();
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			// $setores = array();
			// foreach ($this->session->dados_usuario->setores as $setor)
			// {
			// 	$setores[] = $setor;
			// }
			// foreach ($this->session->dados_usuario->setores_administrador as $setor)
			// {
			// 	$setores[] = $setor;
			// }
			// $setores = array_unique($setores);
			// ##############################################################
			// $customers = array();
			// if (!$search)
			// {
			// 	$customers = ($this->session->dados_usuario->grupo_id == 1) ? $this->rotina_model->order_by($sort, $order)->get( array('status<>'=>'historico') ) : $this->rotina_model->order_by($sort, $order)->get( array('setor_id'=>$setores, 'status<>'=>'historico') );
			// }
			// else
			// {
			// 	if( $_GET['like_search'] == 'all' )
			// 	{
			// 		$campos = $this->db->query('DESC '.$this->rotina_model->order_by($sort, $order)->_table)->result();
			// 		foreach ($campos as $campo)
			// 		{
			// 			$arraySearch[$campo->Field] = $search;
			// 		}
			// 	}
			// 	else
			// 	{
			// 		$campos = explode('|', $_GET['like_search']);
			// 		foreach ($campos as $campo)
			// 		{
			// 			$arraySearch[$campo] = $search;
			// 		}
			// 	}
			// 	//##############################################################################
			// 	//##############################################################################
			// 	$customers = ($this->session->dados_usuario->grupo_id == 1) ? $this->rotina_model->like($arraySearch)->get( array('status<>'=>'historico') ) : $this->rotina_model->like($arraySearch)->get( array('setor_id'=>$setores, 'status<>'=>'historico') );
			// }
			//#################################################################################
			//#################################################################################
			//#################################################################################
			//#################################################################################
			$count = sizeof($customers);
			$customers = array_slice($customers, $offset, $limit);
			echo "{";
				echo '"total": ' . $count . ',';
				echo '"rows": ';
				echo json_encode($customers);
			echo "}";
		}
		public function bootstrap_table_public($status='publicado')
		{
			$limit  = (isset($_GET['limit']))  ? $_GET['limit']  : 10;
			$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
			$sort   = (isset($_GET['sort']))   ? $_GET['sort']   : '';
			$order  = (isset($_GET['order']))  ? $_GET['order']  : "asc";
			$search = (isset($_GET['search'])) ? $_GET['search'] : "";

			##############################################################
			$setores = array();
			foreach ($this->session->dados_usuario->setores as $setor)
			{
				$setores[] = $setor;
			}
			foreach ($this->session->dados_usuario->setores_administrador as $setor)
			{
				$setores[] = $setor;
			}
			$setores = array_unique($setores);
			##############################################################
			$customers = array();
			if ($search == "")
			{
				$customers = ($this->session->dados_usuario->grupo_id == 1) ? $this->rotina_model->order_by($sort, $order)->get( array('status'=>$status) ) : $this->rotina_model->order_by($sort, $order)->get( array('setor_id'=>$setores, 'status'=>$status) );
			}
			else
			{
				if( $_GET['like_search'] == 'all' )
				{
					$campos = $this->db->query('DESC '.$this->rotina_model->order_by($sort, $order)->_table)->result();
					foreach ($campos as $campo)
					{
						$arraySearch[$campo->Field] = $search;
					}
				}
				else
				{
					$campos = explode('|', $_GET['like_search']);
					foreach ($campos as $campo)
					{
						$arraySearch[$campo] = $search;
					}
				}
				$customers = ($this->session->dados_usuario->grupo_id == 1) ? $this->rotina_model->order_by($sort, $order)->like($arraySearch)->get( array('status'=>$status) ) : $this->rotina_model->order_by($sort, $order)->like($arraySearch)->get( array('setor_id'=>$setores, 'status'=>$status) );
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
	/* End of file Rotinas.php */
	/* Location: ./application/controllers/Rotinas.php */