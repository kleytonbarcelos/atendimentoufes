<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('usuario_model');
			$this->load->model('setorusuario_model');
		}
		//#######################################################
		public function index()
		{
			$this->load->view('login/login');
		}
		public function sempermissao()
		{
			$this->load->view('login/sempermissao');
		}
		public function verifica()
		{
			$this->form_validation->set_rules('txtUsuario', str_to_strong('Usuario'), 'trim|required');
			$this->form_validation->set_rules('txtSenha', str_to_strong('Senha'), 'trim|required');

			if( $this->form_validation->run() == true )
			{
				$login = $this->usuario_model->verifica_login($this->input->post('txtUsuario'), $this->input->post('txtSenha'));
				grava_log($this->base_url_controller, 'Logou no sistema');
				if($login)
				{
					//###############################################################################################
					//###############################################################################################
					$this->load->model('setorusuario_model');
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
					if($this->session->dados_usuario->setores)
					{					
						$setor = $this->setor_model->select('nome')->get( $this->session->dados_usuario->setores[0] );
						$this->session->dados_usuario->setor = $setor->nome;
					}
					//###############################################################################################
					//###############################################################################################
					$response['usuario_status'] = $this->session->dados_usuario->status;
					$response['url_redirect'] = $this->session->url_redirect;
					$response['status'] = 1;
				}
				else
				{
					$response['msg'] = str_to_strong('Usuário').' ou '.str_to_strong('Senha').' estão incorretos';
					$response['status'] = 0;
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
		public function password()
		{
			$password = array_to_row($this->usuario_model->select('password')->get( array('usuario'=>$this->input->post('txtUsuario')) ));
			if( $password )
			{
				$data['status'] = 1;
				$data['password'] = $password->password;
				$data['msg'] = 'Operação realizado com sucesso!';
			}
			else
			{
				$data['status'] = 0;
				$data['password'] = '';
				$data['msg'] = 'erro ao detectar password';
			}
			echo json_encode($data);
		}
		public function logoff()
		{
			grava_log($this->base_url_controller, 'Logoff no sistema');
			unset($_SESSION['dados_usuario']);
			redirect('home','refresh');
		}
	}