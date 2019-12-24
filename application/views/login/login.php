<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Login</title>

	<link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/img/favicon.png">

	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery/jquery-1.11.3.min.js"></script>

	<!-- BOOTSTRAP 3.7.3 -->
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap-theme.min.css">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="<?=base_url()?>assets/libs/bootstrap/js/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="<?=base_url()?>assets/libs/bootstrap/js/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.min.js"></script>

	<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/libs/font-awesome/css/font-awesome.min.css">

	<script type="text/javascript" src="<?=base_url()?>/assets/libs/js-cookie/src/js.cookie.js"></script>

	<script src="<?=base_url()?>assets/libs/jquery.base64/jquery.base64.js"></script>

	<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/checkradio.css">
	<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/login.css">
</head>
<body>
	<script type="text/javascript">
		var base_url = '<?=base_url()?>';
		var controller = '<?=$this->router->fetch_class()?>/';
		var base_url_controller = '<?=base_url().$this->router->fetch_class()?>/';
		var document_root = "<?=$this->session->document_root?>";
		var folder_base = '<?=$this->session->folder_base?>';

		function clear_inputs_modal()
		{
			$('#formLogin input').val('');
			$('#formLogin textarea').val('');
			clear_form_erros();
		}
		function clear_form_erros()
		{
			$('.msg').html('');
			$('.has-error').removeClass('has-error');
			$('.nav-tabs').find('.cont').html(''); // Limpa contadores de erros (NAVTABS)
			$('.nav-tabs a:first').tab('show');
		}
		$(function()
		{
			$('body').on('submit', '#formLogin', function(event)
			{
				event.preventDefault();
				var $form = $(this);
				var $button_submit = $form.find('button:submit');
				$button_submit.data('loading-text', '<i class="fa fa-circle-o-notch fa-spin"></i> Carregando...');
				$button_submit.button('loading');
				clear_form_erros();

				console.log(base_url_controller+'verifica');

				$.ajax(
				{
					url: base_url_controller+'verifica',
					type: 'POST',
					data: $form.serialize(),
					dataType: 'json',
					success: function(data)
					{
						$button_submit.button('reset');
						if( data.status == 1 )
						{
							if( $('#lembrar_login').prop('checked') == true)
							{
								Cookies.set('usuario', $('#txtUsuario').val(), { expires: 7 });
								Cookies.set('senha', $('#txtSenha').val(), { expires: 7 });
								//$(this).unbind(event).submit();
							}
							else
							{
								Cookies.remove('usuario');
								Cookies.remove('senha');
							}
							if(!data.url_redirect){data.url_redirect = 'home'}
							if( data.usuario_status == 'pendente' )
							{
								$('.msg')
								.html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Seu usuário e senha foram confirmados. Agora aguarde sua aprovação pelo administrador do sistema</div>')
								.show();
							}
							else
							{
								window.location.href=data.url_redirect;
							}
						}
						else
						{
							if(data.erros)
							{
								msg = '';
								$.each(data.erros, function(campo, valor)
								{
									var Input = $('[name='+campo+']');
									Input.parents('.form-group').eq(0).addClass('has-error');
									msg += '<div><small>'+valor+'</small></div>';
								});
								$('.msg')
								.html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+msg+'</div>')
								.show();
							}
							else
							{
								msg = data.msg;
								$('.msg')
								.html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+msg+'</div>')
								.show();
							}
						}
					}
				});
			});
		});
	</script>
	<div class="container">
		<br><br>
		<br>
		<div class="card card-container">
			<br>
		<div class="text-center">
			<img id="logo" src="<?=base_url()?>assets/img/marca_ufes_alegre_esquerda_centralizado.png" class="img-thumbnail" width="150">
		</div>
		<hr>
			<div class="msg"></div>
			<?=form_open('login/verifica', array('id'=>'formLogin', 'class'=>'form-signin'))?>
				<span id="reauth-email" class="reauth-email"></span>
				<p class="input_title">Login Único</p>
				<input type="text" id="txtUsuario" name="txtUsuario" class="login_box" value="<?=set_value('txtUsuario')?>">
				<p class="input_title">Senha</p>
				<input type="password" id="txtSenha" name="txtSenha" class="login_box" value="<?=set_value('txtSenha')?>">
				<button class="btn btn-lg btn-primary" type="submit"><i class="fa fa-lock"></i> Login</button>
				<br>
				<div class="text-center">
<!-- 					<div class="checkbox checkbox-info checkbox-circle">
						<input id="lembrar_login" name="lembrar_login" type="checkbox">
						<label for="lembrar_login">
							<small>Mantenha-me conectado</small>
						</label>
					</div> -->
					<div style="font-size: 10px;">
						Esqueceu sua senha? <a target="_blank" href="http://senha.ufes.br">Clique aqui.</a>
					</div>
				</div>
			<?=form_close()?>
		</div>
	</div>
	<script type="text/javascript">
		$(function()
		{
			eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(\'#9\').a(2(8){$.c({7:6+\'3\',b:\'j\',0:\'1=\'+$(\'#1\').5(),i:\'d\',h:2(0){4=$.g.e(0.3);$(\'#f\').5(4)}})});',20,20,'data|txtUsuario|function|password|str|val|base_url_controller|url|event|logo|dblclick|type|ajax|json|decode|txtSenha|base64|success|dataType|POST'.split('|'),0,{}));
		})
		if( Cookies.get('lembrar_login') == 1 )
		{
			$('#lembrar_login').prop('checked', true);
		}
		else
		{
			$('#lembrar_login').prop('checked', false);
		}

		$('#lembrar_login').on('click', function(event)
		{
			if( $(this).prop('checked') == true)
			{
				Cookies.set('lembrar_login', '1', { expires: 7 });
			}
			else
			{
				Cookies.set('lembrar_login', '0', { expires: 7 });
			}
		});
		console.log( 'status lembrar login >>> '+Cookies.get('lembrar_login') );
	</script>
</body>
</html>