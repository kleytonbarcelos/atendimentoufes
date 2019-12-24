<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Atendimentos extends MY_Controller
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
			$this->template->load('default/index', 'atendimentos/index');
		}
		public function editar($id)
		{
			$data['dados'] = $this->atendimento_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				$this->template->load('default/index', 'atendimentos/formulario', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function visualizar($id)
		{
			$data['dados'] = $this->atendimento_model->get_by('MD5(id)', $id);
			if($data['dados'])
			{
				grava_log($this->base_url_controller, 'Visualizou (atendimentos) registro ID:'.$data['dados']->id);
				$data['setor'] = $this->setor_model->select('nome')->get($data['dados']->setor_id);
				$data['tipo'] = $this->atendimentotipo_model->select('nome')->get($data['dados']->atendimentotipo_id);

				$sql = '
					SELECT
						usuarios.nome atendente,
						SUBSTRING_INDEX(trim(mensagem), " ", 1) acao,
						logs.data data
					FROM
						logs
					INNER JOIN usuarios
						ON (logs.usuario_id=usuarios.id)
					INNER JOIN atendimentos
						ON (atendimentos.id='.$data['dados']->id.')
					WHERE
						mensagem LIKE "%(atendimentos)%"
						AND
						SUBSTRING_INDEX(trim(mensagem), ":", -1)='.$data['dados']->id.'
					ORDER BY
						logs.id DESC
				';
				$data['logs'] = $this->db->query($sql)->result();
				$this->template->load('default/index', 'atendimentos/visualizar', $data);
			}
			else
			{
				redirect('home','refresh');
			}
		}
		public function salvar()
		{
			$this->form_validation->set_rules('txtNome', strong('Nome do usuário'), 'trim|required');
			$this->form_validation->set_rules('txtTipoUsuario', strong('Tipo do usuário'), 'trim|required');
			$this->form_validation->set_rules('txtSetorAtendimento', strong('Setor'), 'trim|required');
			//$this->form_validation->set_rules('txtTipoAtendimento', strong('Tipo de atendimento'), 'trim|required');
			$this->form_validation->set_rules('txtAssunto', strong('Assunto'), 'trim|required');
			//$this->form_validation->set_rules('txtDescricaoAtendimento', strong('Descrição'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$data = array(
					'usuario_id'=>$this->session->dados_usuario->id,
					'setor_id'=>$this->input->post('txtSetorAtendimento'),
					'nome'=>$this->input->post('txtNome'),
					'tipousuario'=>$this->input->post('txtTipoUsuario'),
					'atendimentotipo_id'=>$this->input->post('txtTipoAtendimento'),
					'assunto'=>$this->input->post('txtAssunto'),
					'descricao'=>$this->input->post('txtDescricaoAtendimento'),

					'status'=>$this->input->post('status'),
					'time'=>$this->input->post('time'),
				);
				if($this->input->post('status')=='concluido')
				{
					$data = array_merge($data, array('data_encerramento'=>NOW()));
				}
				if(!$this->input->post('id'))
				{
					$insert_id = $this->atendimento_model->insert($data);
					grava_log($this->base_url_controller, 'Cadastrou (atendimentos) registro ID:'.$insert_id);
					$response['action'] = 'insert';
					$response['status'] = 1;
					$response['msg'] = 'Registro adicionado com sucesso!';
				}
				else
				{
					$this->atendimento_model->update($this->input->post('id'), $data);
					grava_log($this->base_url_controller, 'Atualizou (atendimentos) registro ID:'.$this->input->post('id'));
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
		public function salvareditar()
		{	
			$this->form_validation->set_rules('txtNome', strong('Nome do usuário'), 'trim|required');
			$this->form_validation->set_rules('txtAssunto', strong('Assunto'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$data = array(
					'nome'=>$this->input->post('txtNome'),
					'tipousuario'=>$this->input->post('txtTipoUsuario'),
					'assunto'=>$this->input->post('txtAssunto'),
					'descricao'=>$this->input->post('txtDescricaoAtendimento'),
					//'usuario_id'=>$this->session->dados_usuario->id,
					//'setor_id'=>$this->input->post('txtSetorAtendimento'),
					//'atendimentotipo_id'=>$this->input->post('txtTipoAtendimento'),
					'status'=>$this->input->post('txtStatus'),
					//'time'=>$this->input->post('time'),


					//'data_encerramento'=>NOW(),
				);
				if(!$this->input->post('id'))
				{
					$atendimento_id = $this->atendimento_model->insert($data);
					grava_log($this->base_url_controller, 'Cadastrou (atendimentos) registro ID:'.$atendimento_id);
					$response['action'] = 'insert';
					$response['status'] = 1;
					$response['msg'] = 'Registro adicionado com sucesso!';
				}
				else
				{
					$this->atendimento_model->update($this->input->post('id'), $data);
					grava_log($this->base_url_controller, 'Atualizou (atendimentos) registro ID:'.$this->input->post('id'));
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
		public function excluir()
		{
			$ids = explode(',', $this->input->post('id'));
			foreach ($ids as $key => $value)
			{
				$return = $this->atendimento_model->delete($value);
				grava_log($this->base_url_controller, 'Excluiu (atendimentos) registro ID:'.$value);
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
		public function criaatendimento()
		{
			$data = array(
				'time'=>0,
				'status'=>'pendente',
				'setor_id'=>$this->session->dados_usuario->setores[0],
				'usuario_id'=>$this->session->dados_usuario->id,
			);
			$insert_id = $this->atendimento_model->insert($data);
			grava_log($this->base_url_controller, 'Iniciou (atendimentos) registro ID:'.$insert_id);

			$response['id'] = $insert_id;
			$response['status'] = 1;
			$response['msg'] = 'Registro adicionado com sucesso!';
			echo json_encode($response);
		}
		public function excluiratendimento()
		{
			$this->atendimento_model->delete($this->input->post('id'));
			grava_log($this->base_url_controller, 'Excluiu (atendimentos) registro ID:'.$this->input->post('id'));

			$response['status'] = 1;
			echo json_encode($response);
		}
		public function get()
		{
			if( $this->input->post('id') )
			{
				$data['atendimento'] = $this->atendimento_model->get($this->input->post('id'));
			}
			else
			{
				$data['dados'] = $this->atendimento_model->get();
			}
			echo json_encode($data);
		}
		public function atendimentos_pendentes()
		{
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
			$data['dados'] = $this->atendimento_model->get( array('status'=>'pendente', 'setor_id'=>$setores) );
			echo json_encode($data);
		}
		public function getvaluesinputs()
		{
			$data['inputs'] = $this->atendimento_model->get($this->input->post('id'));
			foreach ($data['inputs'] as $key => $value)
			{
				$data_temp[sha1($this->atendimento_model->table().'.'.$key)] = $value;
			}
			$data['inputs'] = $data_temp;
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
			switch ($sort)
			{
				case 'nome':
					$sort = 'A.nome';
					break;
				case 'assunto':
					$sort = 'A.assunto';
					break;
				case 'status':
					$sort = 'A.status';
					break;
				case 'usuario':
					$sort = 'U.nome';
					break;
				case 'tipo':
					$sort = 'T.nome';
					break;
				default:
					$sort = 'A.data';
					break;
			}
			//#################################################################################
			//#################################################################################
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
			$like = '';
			if($search)
			{
				$like = '
						AND
						(
							A.nome LIKE "%'.$search.'%"
							OR
							A.assunto LIKE "%'.$search.'%"
							OR
							DATE_FORMAT(A.data, "%d/%m/%Y") LIKE "%'.$search.'%"
							OR
							A.status LIKE "%'.$search.'%"
							OR
							T.nome LIKE "%'.$search.'%"
							OR
							U.nome LIKE "%'.$search.'%"
						)
				';
			}
			$sql = '
					SELECT
						A.id AS id,
						A.nome AS nome,
						A.assunto AS assunto,
						DATE_FORMAT(A.data, "%d/%m/%Y") AS data,
						A.status AS status,
						T.nome AS tipo,
						U.nome AS usuario
					FROM
						atendimentos AS A,
						atendimentos_tipos AS T,
						usuarios AS U
					WHERE
						(A.atendimentotipo_id=T.id AND A.usuario_id=U.id)
						AND
						'.$setores.''.$like.'
					ORDER BY
						'.$sort.' '.$order.'
			';
			$customers = $this->db->query($sql)->result();
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
	}
	/* End of file Atendimentos.php */
	/* Location: ./application/controllers/Atendimentos.php */