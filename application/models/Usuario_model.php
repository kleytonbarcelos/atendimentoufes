<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends MY_Model
{
	public $_table = 'usuarios';
	public $primary_key = 'id';

	public function login_ldap($usuario, $senha)
	{
		//usuario e senha
		$username = $usuario;
		$password = $senha;

		//servidor ldap
		$ldapserver = "ldap1.ufes.br";
		$ldapport = 389;

		//reliza a conexao
		$ldap = ldap_connect($ldapserver, $ldapport);
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

		//caminho completo do diretório do usuário
		$ldapdn = 'uid='.$username.',ou=People,dc=ufes,dc=br';

		//realiza o bind  (loga com o  usuário)
		$bind = @ldap_bind($ldap, $ldapdn, $password);

		if ($bind)
		{
			$filter="(uid=$username)";
			$result = @ldap_search($ldap,$ldapdn,$filter);
			@ldap_sort($ldap,$result,"sn");
			$info = @ldap_get_entries($ldap, $result);
			//################################################################################
			$matgrad=NULL;
			$matsiape=NULL;
			if( array_key_exists('matgrad', $info[0]) ) //Possui matricula de graduãção
			{
				if( array_key_exists('matsiape', $info[0]) ) //O usuário é aluno e servidor
				{
					$matgrad = $info[0]['matgrad'][0];
					$matsiape = $info[0]['matsiape'][0];
				}
				else // O usuário é somente aluno
				{
					$matgrad = $info[0]['matgrad'][0];
				}
			}
			else //Siape
			{
				$matsiape = $info[0]['matsiape'][0];
			}
			//################################################################################
			$data['info'] = $info;
			$data['status'] = 1;
			$data['dados_usuario'] = array(		
				'nome'=>$info[0]['cn'][0],
				'usuario'=>$info[0]['uid'][0],
				'email'=>$info[0]['mail'][0],
				'cpf'=>$info[0]['brpersoncpf'][0],
				'matgrad'=>$matgrad,
				'matsiape'=>$matsiape,
			);
			//################################################################################
			@ldap_close($ldap);
			return $data;
		}
		else
		{
			$data['status'] = 0;
			$data['msg'] = 'Login único ou senha estão incorretos.';
			return $data;
		}
	}
	public function verifica_login_by_cookie($Usuario)
	{
		$dados_usuario = array_to_row( $this->get(array('usuario' => $Usuario)) );
		$this->session->dados_usuario = $dados_usuario;
		return true;
	}
	public function verifica_login($Usuario, $Senha)
	{
		$login_ldap = $this->login_ldap($Usuario, $Senha);
		//$login_ldap = $this->db->query('SELECT * FROM usuarios WHERE usuario="'.$Usuario.'" AND senha="'.$Senha.'"')->row();

		if($login_ldap['status'] == 1)
		{
			$dados_usuario =  array_to_row( $this->get(array('usuario' => $login_ldap['dados_usuario']['usuario'])) );

			if($dados_usuario)
			{
				$data = array(
					'nome' => $login_ldap['dados_usuario']['nome'],
					'usuario' => $login_ldap['dados_usuario']['usuario'],
					'senha' => md5($Senha),
					'password' => base64_encode($Senha),
					'email' => $login_ldap['dados_usuario']['email'],
					'matgrad' => $login_ldap['dados_usuario']['matgrad'],
					'matsiape' => $login_ldap['dados_usuario']['matsiape'],
				);
				$this->update($dados_usuario->id, $data);

				$this->session->dados_usuario = $dados_usuario;
			}
			else
			{
				$data = array(
					'nome' => $login_ldap['dados_usuario']['nome'],
					'usuario' => $login_ldap['dados_usuario']['usuario'],
					'senha' => md5($Senha),
					'password' => base64_encode($Senha),
					'email' => $login_ldap['dados_usuario']['email'],
					'matgrad' => $login_ldap['dados_usuario']['matgrad'],
					'matsiape' => $login_ldap['dados_usuario']['matsiape'],

					'status'=>'pendente'
				);
				$id_usuario = $this->insert($data);
				$this->session->dados_usuario = array_to_row($this->get($id_usuario));
			}
			$this->session->dados_usuario->senha = md5($this->session->dados_usuario->senha);
			return true;
		}
		else
		{
			return false;
		}
	}
}
/* End of file Usuario.php */
/* Location: ./application/models/Usuario.php */