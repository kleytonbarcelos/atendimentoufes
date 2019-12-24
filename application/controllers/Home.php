<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('usuario_model');
			$this->load->model('setorusuario_model');
			$this->load->model('rotina_model');
			$this->load->model('setor_model');
			$this->load->model('atendimento_model');
			$this->load->model('ldap_model');
		}
		public function index()
		{
			$this->template->load('default/index', 'home/index');
		}
		public function typeahead_ldap($id=null)
		{
			if($id)
			{
				$data['dados'] = $this->usuario_model->get($id);
			}
			else
			{
				$search = array(
					'uid'=>$this->input->post('query'),
					'displayname'=>$this->input->post('query'),
				);
				$data['dados'] = $this->ldap_model->like($search)->get();
				//$data['dados'] = $this->consulta_ldap($this->input->post('query'));
			}
			$data['status'] = ($data['dados']) ? 1 : 0;
			echo json_encode($data);
		}
		public function update_ldap($type='alunos')
		{
			set_time_limit(0);

			if ($type=='alunos')
			{
				$table = 'alunos';
				$field = 'matgrad';
			}
			else if('tecnicos')
			{
				$table = 'tecnicos';
				$field = 'matsiape';
			}
			else
			{
				$table = 'docentes';
				$field = 'matsiape';
			}

			$registros = $this->db->query('SELECT * FROM ldap WHERE '.$field.'<>""')->result();
			foreach ($registros as $value)
			{
				$this->db->query('UPDATE '.$table.' SET uid="'.$value->uid.'", '.$field.'="'.$value->$field.'" WHERE nome="'.$value->displayname.'"');
			}

			echo '<pre>';
				print_r($registros);
			echo '</pre>';
		}
		public function ldap()
		{
			$this->template->load('default/index', 'home/ldap');
		}
		public function consulta_ldap($str='Daniel Lopes')
		{
			$str = urldecode($str);

			//servidor ldap
			$ldapserver = "ldap1.ufes.br";
			$ldapport = 389;

			//reliza a conexao
			$ldap = ldap_connect($ldapserver, $ldapport);
			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

			//caminho completo do diretório do usuário
			$ldapdn = 'uid=user.sugrad,ou=sugrad,ou=apps,dc=ufes,dc=br';
			//realiza o bind  (loga com o  usuário)
			$bind = @ldap_bind($ldap, $ldapdn, 'tamatrexucu');
			if ($bind)
			{
				$filter = '(displayname=*'.$str.'*)';
				$result = ldap_search($ldap, 'ou=people,dc=ufes,dc=br', $filter);
				@ldap_sort($ldap, $result, 'displayname');
				$info = @ldap_get_entries($ldap, $result);
				echo '<pre>';
					print_r($info);
				echo '</pre>';
				exit;
			}
		}
		public function consulta_ldap_por_letra($letra)
		{
			//servidor ldap
			$ldapserver = "ldap1.ufes.br";
			$ldapport = 389;

			//reliza a conexao
			$ldap = ldap_connect($ldapserver, $ldapport);
			ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

			//caminho completo do diretório do usuário
			$ldapdn = 'uid=user.sugrad,ou=sugrad,ou=apps,dc=ufes,dc=br';
			
			//realiza o bind  (loga com o  usuário)
			$bind = @ldap_bind($ldap, $ldapdn, 'tamatrexucu');
			if ($bind)
			{
				$filter = '(&(NsAccountLock=false)(uid='.$letra.'*))';
				$result = @ldap_search($ldap, 'ou=people,dc=ufes,dc=br', $filter);
				@ldap_sort($ldap, $result, "uid");
				$info = ldap_get_entries($ldap, $result);
				//###############################################################################
				unset($info['count']);
				$tmp = array();
				foreach ($info as $key => $value)
				{
					if( $value['uid']['count'] == 1 )
					{
						$uid = ( array_key_exists('uid', $value) ) ? $value['uid'][0] : NULL;
						$displayname =  ( array_key_exists('displayname', $value) ) ? $value['displayname'][0] : NULL;
						$sn =  ( array_key_exists('sn', $value) ) ? $value['sn'][0] : NULL;
						$cn =  ( array_key_exists('cn', $value) ) ? $value['cn'][0] : NULL;
						$gecos =  ( array_key_exists('gecos', $value) ) ? $value['gecos'][0] : NULL;
						$matsiape =  ( array_key_exists('matsiape', $value) ) ? $value['matsiape'][0] : NULL;
						$matgrad =  ( array_key_exists('matgrad', $value) ) ? $value['matgrad'][0] : NULL;
						$cursograd =  ( array_key_exists('cursograd', $value) ) ? $value['cursograd'][0] : NULL;
						$x121address =  ( array_key_exists('x121address', $value) ) ? $value['x121address'][0] : NULL;
						//###############################################################################
						$tmp[] = array(
							'uid'=>$uid,
							'displayname'=>$displayname,
							//'sn'=>$sn,
							//'cn'=>$cn,
							//'gecos'=>$gecos,
							'matsiape'=>$matsiape,
							'matgrad'=>$matgrad,
							'cursograd'=>$cursograd,
							'x121address'=>$x121address,
						);
					}
				}
				$this->ldap_model->insert_many($tmp);
				unset($tmp);
				return true;
				// foreach(range('a', 'z') as $letra_secundaria)
				// {
				// 	foreach(range('a', 'z') as $letra_tercearia)
				// 	{
				// 		//$filter = '(uid=felipe.secato*)';
				// 		$filter = '(uid='.$letra.$letra_secundaria.$letra_tercearia.'*)';
				// 		$result = @ldap_search($ldap, 'ou=people,dc=ufes,dc=br', $filter);
				// 		@ldap_sort($ldap, $result, "uid");
				// 		$info = ldap_get_entries($ldap, $result);
				// 		//###############################################################################
				// 		unset($info['count']);
				// 		$tmp = array();
				// 		foreach ($info as $key => $value)
				// 		{
				// 			if( $value['uid']['count'] == 1 )
				// 			{
				// 				$uid = ( array_key_exists('uid', $value) ) ? $value['uid'][0] : NULL;
				// 				$displayname =  ( array_key_exists('displayname', $value) ) ? $value['displayname'][0] : NULL;
				// 				$sn =  ( array_key_exists('sn', $value) ) ? $value['sn'][0] : NULL;
				// 				$cn =  ( array_key_exists('cn', $value) ) ? $value['cn'][0] : NULL;
				// 				$gecos =  ( array_key_exists('gecos', $value) ) ? $value['gecos'][0] : NULL;
				// 				$matsiape =  ( array_key_exists('matsiape', $value) ) ? $value['matsiape'][0] : NULL;
				// 				$matgrad =  ( array_key_exists('matgrad', $value) ) ? $value['matgrad'][0] : NULL;
				// 				$cursograd =  ( array_key_exists('cursograd', $value) ) ? $value['cursograd'][0] : NULL;
				// 				$x121address =  ( array_key_exists('x121address', $value) ) ? $value['x121address'][0] : NULL;
				// 				//###############################################################################
				// 				$tmp[] = array(
				// 					'uid'=>$uid,
				// 					'displayname'=>$displayname,
				// 					//'sn'=>$sn,
				// 					//'cn'=>$cn,
				// 					//'gecos'=>$gecos,
				// 					'matsiape'=>$matsiape,
				// 					'matgrad'=>$matgrad,
				// 					'cursograd'=>$cursograd,
				// 					'x121address'=>$x121address,
				// 				);
				// 			}
				// 		}
				// 		$this->ldap_model->insert_many($tmp);
				// 		unset($tmp);
				// 	}
				// }
				//return true;
			}
		}
		public function salva_ldap($pg=null)
		{
			set_time_limit(0);

			$pg = (!$pg) ? 1 : $pg;
			$total_pg = count(range('a', 'z'));

			if($pg == $total_pg )
			{
				$data['status'] = 'concluido';
				echo json_encode($data);
			}
			else
			{
				$cont = 1;
				$letras = [];

				foreach(range('A', 'Z') as $letra)
				{
					$letras[$cont] = $letra;
					$cont++;
				}
				//########################################################
				$letras[$pg];
				$this->consulta_ldap_por_letra($letras[$pg]);
				//########################################################
				$pg++;

				$data['pg'] = $pg;
				$data['total_pg'] = $total_pg;
				$data['status'] = 'processando';
				echo json_encode($data);
			}
		}
		#################################################################################
		#################################################################################
		#################################################################################
		#################################################################################
		#################################################################################
		#################################################################################
		#################################################################################
		#################################################################################
		public function db_backup()
		{
			if($this->session->dados_usuario->grupo_id == 1)
			{
				// Carrega a classe DB utility 
				$this->load->dbutil();

				// $bkpcf = array(
				//         'tables'        => array('tabela', 'tabela_2'),   // Lista de tabelas que serão adicionadas ao backup
				//         'ignore'        => array(),                       // Lista de tabelas que serão omitidas do backup
				//         'format'        => 'txt',                         // Formato do backup: gzip, zip, txt
				//         'filename'      => 'backup.sql',                  // Nome do arquivo (utilizado somente se o formato for ZIP)
				//         'add_drop'      => TRUE,                          // Adição de DROP TABLE
				//         'add_insert'    => TRUE,                          // Adição de INSERT (útil caso queira fazer o backup não só da estrutura, mas também dos dados)
				//         'newline'       => "\n"                           // Caracter usado para definir quebra de linha 
				// );			
				// $this->dbutil->backup($bkpcf);

				// Executa o backup do banco de dados armazenado-o em uma variável
				$backup = $this->dbutil->backup();

				// carrega o helper File e cria um arquivo com o conteúdo do backup
				$this->load->helper('file');
				write_file('assets/backups/'.date("Y-m-d_H-i-s").'.zip', $backup);
				 
				// Carrega o helper Download e força o download do arquivo que foi criado com 'write_file'
				$this->load->helper('download');
				force_download('backup.gz', $backup);
			}
		}
	}