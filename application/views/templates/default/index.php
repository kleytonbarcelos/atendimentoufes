<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Sistema de Gestão do Conhecimento">
	<meta name="author" content="DSG - Departamento de Suporte à Gestão">

	<title>SGC - Sistema de Gestão do Conhecimento</title>

	<script type="text/javascript">
		var base_url = '<?=base_url()?>';
		var controller = '<?=$this->router->fetch_class()?>/';
		var base_url_controller = '<?=base_url().$this->router->fetch_class()?>/';
		var controller_method = '<?=$this->router->fetch_method()?>';
		var document_root = "<?=$this->session->document_root?>";
		var folder_base = '<?=$this->session->folder_base?>';
	</script>

	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery/jquery-1.11.3.min.js"></script>											<!-- JQUERY 1.11.3 -->
	
	<script src="<?=base_url()?>assets/js/functions.js"></script>
	<script src="<?=base_url()?>assets/js/script.js"></script>

	<script src="<?=base_url()?>assets/libs/moment/min/moment-with-locales.min.js"></script>														<!-- MOMENT JS -->

	<link rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap.min.css">														<!-- BOOTSTRAP 3 (CSS) -->
	<!-- <link rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap-theme.min.css"> -->										<!-- BOOTSTRAP 3 (CSS) -->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/sticky-footer-navbar.css">												<!-- CSS RODAPE (FOOTER) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.min.js"></script>											<!-- BOOTSTRAP 3 (JS) -->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap/js/html5shiv.min.js"></script>
		<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap/js/respond.min.js"></script>
	<![endif]-->

	<link rel="stylesheet" href="<?=base_url()?>assets/libs/font-awesome/css/font-awesome.min.css">													<!-- FONTAWESOME -->

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-table/dist/bootstrap-table.min.css">							<!-- BOOTSTRA-TABLE (CSS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-table/dist/bootstrap-table.min.js"></script>							<!-- BOOTSTRA-TABLE (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-table/dist/locale/bootstrap-table-pt-BR.min.js"></script>				<!-- BOOTSTRA-TABLE (PT-BR) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js"></script>	<!-- BOOTSTRA-TABLE (PLUGIN - EXPORT) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/tableExport.jquery.plugin/tableExport.js"></script>														<!-- TABLEEXPORT -->


	<link rel="stylesheet" href="<?=base_url()?>assets/libs/fancyBox-3.0/dist/jquery.fancybox.min.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/fancyBox-3.0/dist/jquery.fancybox.min.js"></script>
	


	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.md5/jquery.md5.js"></script>
	


	<link rel="stylesheet" href="<?=base_url()?>assets/libs/jquery.scrollbar-gh-pages/jquery.scrollbar.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.scrollbar-gh-pages/jquery.scrollbar.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.scrollbar-gh-pages/config.js"></script>

	<script type="text/javascript" src="<?=base_url()?>assets/libs/fancyBox-3.0/dist/jquery.fancybox.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/fancyBox-3.0/dist/jquery.fancybox.min.css">

	<link rel="stylesheet" href="<?=base_url()?>assets/libs/bootsidemenu/css/BootSideMenu.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootsidemenu/js/BootSideMenu.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootsidemenu/config.js"></script>

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/dropzone/dist/min/dropzone.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/dropzone/style.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/dropzone/dist/min/dropzone.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/libs/dropzone/config.js"></script>

	<link href="<?=base_url()?>assets/libs/select2/dist/css/select2.min.css" rel="stylesheet" />
	<link href="<?=base_url()?>assets/libs/select2/config.css" rel="stylesheet" />
	<script type="text/javascript" src="<?=base_url()?>assets/libs/select2/dist/js/select2.full.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/libs/select2/config.js"></script>
	

	<script type="text/javascript" src="<?=base_url()?>assets/libs/printThis/printThis.js"></script>


	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/jquery-toast-plugin/dist/jquery.toast.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/jquery-toast-plugin/config.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery-toast-plugin/dist/jquery.toast.min.js"></script>


	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-fileinput/css/fileinput.min.css">								<!-- FILEINPUT (UPLOAD) - CSS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>					<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/js/plugins/sortable.min.js"></script>						<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/js/plugins/purify.min.js"></script>							<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/js/fileinput.min.js"></script>								<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/themes/fa/theme.js"></script>								<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/js/locales/pt-BR.js"></script>								<!-- FILEINPUT (UPLOAD) - JS -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-fileinput/config.js"></script>											<!-- FILEINPUT (UPLOAD) - JS -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/ckeditor/ckeditor.js" type="text/javascript"></script>							<!-- CKEDITOR (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/ckeditor/adapters/jquery.js"></script>											<!-- CKEDITOR (ADAPTER - JQUERY) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/ckeditor/config.js"></script>													<!-- CKEDITOR (CONFIG) -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.print/jQuery.print.js"></script>											<!-- JQUERY PRINT (JS) -->
	
	<!-- <script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-ajax-typeahead/js/bootstrap-typeahead.min.js"></script>				<!-- TYPEAHEAD --> -->

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-ajax-typeahead/config.css">
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-ajax-typeahead/js/bootstrap-typeahead.min.js"></script>

	<link rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap-select/dist/css/bootstrap-select.min.css">									<!-- BOOTSTRAP-SELECT (CSS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-select/dist/js/bootstrap-select.min.js"></script>						<!-- BOOTSTRAP-SELECT (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-select/dist/js/i18n/defaults-pt_BR.min.js"></script>					<!-- BOOTSTRAP-SELECT (LANG) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-select/config.js"></script>											<!-- BOOTSTRAP-SELECT (CONFIG) -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/speakingurl/speakingurl.min.js"></script>										<!-- SPEAKINGURL (JS) -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.base64/jquery.base64.js"></script>										<!-- BASE64 (JS) -->
	
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery-price-format/jquery.price_format.min.js"></script>						<!-- PRINCE FORMAT (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery-price-format/config.js"></script>											<!-- PRINCE FORMAT (CONFIG) -->

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css">			<!-- BOOTSTRA-TOUCHSPIN (CSS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>			<!-- BOOTSTRA-TOUCHSPIN (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-touchspin/config.js"></script>											<!-- BOOTSTRA-TOUCHSPIN (CONFIG) -->

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">	<!-- BOOTSTRAP-DATETIMEPICKER (CSS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>		<!-- BOOTSTRAP-DATETIMEPICKER (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-datetimepicker/config.js"></script>									<!-- BOOTSTRAP-DATETIMEPICKER (JS - CONFIG) -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>				<!-- JQUERY INPUTMASK (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/jquery.inputmask/config.js"></script>											<!-- JQUERY INPUTMASK CONFIG (JS) -->

	<link href='<?=base_url()?>assets/libs/fullcalendar/2.9.1/fullcalendar.css' rel='stylesheet' />													<!-- FULL CALENDAR (CSS) -->
	<link href='<?=base_url()?>assets/libs/fullcalendar/2.9.1/fullcalendar.print.css' rel='stylesheet' media='print' />								<!-- FULL CALENDAR (CSS) -->
	<script src="<?=base_url()?>assets/libs/fullcalendar/2.9.1/fullcalendar.min.js"></script>														<!-- FULL CALENDAR (JS) -->
	<script src="<?=base_url()?>assets/libs/fullcalendar/2.9.1/lang/pt-br.js"></script>																<!-- FULL CALENDAR (PT-BR) -->
	<link href='<?=base_url()?>assets/libs/fullcalendar/style.css' rel='stylesheet' />																<!-- FULL CALENDAR (CSS-STYLE) -->

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/alertifyjs/css/alertify.min.css">										<!-- ALERTIFY (CSS) -->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/alertifyjs/css/themes/default.min.css">									<!-- ALERTIFY (CSS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/alertifyjs/alertify.min.js"></script>											<!-- ALERTIFY (JS) -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/alertifyjs/config.js"></script>													<!-- ALERTIFY (CONFIG) -->

	<script type="text/javascript" src="<?=base_url()?>/assets/libs/js-cookie/src/js.cookie.js"></script>											<!-- COOKIES -->

	<script type="text/javascript" src="<?=base_url()?>assets/libs/timer.jquery/dist/timer.jquery.min.js"></script>


	<script src="<?=base_url()?>assets/libs/chartjs/Chart.bundle.min.js"></script>
	<script src="<?=base_url()?>assets/libs/chartjs/utils.js"></script>


	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/css.css">																<!-- CSS LOCAL -->

	<script type="text/javascript">
		var base_url = '<?=base_url()?>';
		var controller = '<?=$this->router->fetch_class()?>/';
		var base_url_controller = '<?=base_url().$this->router->fetch_class()?>/';
		var document_root = "<?=$this->session->document_root?>";
		var folder_base = '<?=$this->session->folder_base?>';
	</script>

<link href="https://fonts.googleapis.com/css?family=Baloo+Bhaijaan" rel="stylesheet">



</head>
<body>
	<style type="text/css">
		body
		{
			/*padding-top: 20px;*/
		}
		.navbar-brand
		{
			margin-top: -4px !important;
			margin-left: -7px !important;
		}
		.main
		{
			padding: 10px 15px;
			/*padding-top: 12px;*/
		}
	</style>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=base_url()?>">
					<img class="logo_menu" src="<?=base_url()?>assets/img/logoufesmini.png">
				</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="<?php if( $this->controller=='home' || empty($this->controller)){echo ' active';} ?>"><a href="<?=base_url()?>">Home</a></li>
					<li class="<?php if( $this->controller=='atendimentos' ){echo ' active';} ?>"><a href="<?=base_url()?>atendimentos">Atendimentos</a></li>
					<?php
						if($this->session->dados_usuario->grupo_id == 1)
						{
							?>
							<li class="<?php if( $this->controller=='usuarios' ){echo ' active';} ?>"><a href="<?=base_url()?>usuarios">Usuários</a></li>
							<li class="<?php if( $this->controller=='setores' ){echo ' active';} ?>"><a href="<?=base_url()?>setores">Setores</a></li>
							<?php
						}
					?>
					<?php
						if($this->session->dados_usuario->grupo_id == 1 || $this->session->dados_usuario->grupo_id == 2)
						{
							?>
							<li class="<?php if( $this->controller=='rotinas'){echo ' active';} ?>"><a href="<?=base_url()?>rotinas">Rotinas</a></li>
							<li class="<?php if( $this->controller=='relatorios' ){echo ' active';} ?>"><a href="<?=base_url()?>relatorios">Relatórios</a></li>
							<?php
						}
					?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <?=$this->session->dados_usuario->nome?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<!--<li><a href="<?=base_url()?>perfil"><i class="fa fa-user"></i> Perfil</a></li>
							<li><a href="<?=base_url()?>configuracoes"><i class="fa fa-cog"></i> Configurações</a></li> -->
							<?php
								if($this->session->dados_usuario->grupo_id == 1)
								{
									?>
									<li><a href="<?=base_url()?>home/ldap"><i class="fa fa-database fa-fw"></i> Ldap</a></li>
									<li><a href="<?=base_url()?>grupos"><i class="fa fa-users fa-fw"></i> Grupos</a></li>
									<li><a href="<?=base_url()?>permissoes"><i class="fa fa-exchange fa-fw"></i> Permissões</a></li>
									<li role="separator" class="divider"></li>
									<?php
								}
							?>
							<li><a href="<?=base_url()?>logs"><i class="fa fa-th-list fa-fw"></i> Logs do sistema</a></li>
							<li><a onclick="logoff();" href="javascript:void(0);"><i class="fa fa-power-off fa-fw"></i> Sair</a></li>
							<script type="text/javascript">
								function logoff()
								{
									cookies_remove();
									setTimeout(function()
									{
										window.location.href = '<?=base_url()?>logoff';
									}, 1);
								}
							</script>
						</ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
	<div class="container">
		<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet">
		<h3 style="margin-left: 18px;margin-top: 0 !important;font-family: 'PT Sans Narrow', sans-serif;font-weight: bold;">SGC - Sistema de Gestão do Conhecimento</h3>
		<div class="msg-prontuario display-none">
			<div class="alert alert-info cursor-pointer" onclick="window.location.href=base_url+'home';">
				<i class="fa fa-exclamation-triangle"></i> Você possui um atendimento em aberto. Clique aqui e continue.
			</div>
		</div>
		<div class="main">
			<div class="row">
				<div class="col-md-12">
					<div class="pull-right font-14 font-bold" style="margin-top: 8px;margin-right: 15px;color:#337AC5;"><?=$this->session->dados_usuario->setor?></div>
					<?=breadcrumb($this->router->fetch_class(), $this->router->fetch_method())?>
				</div>
			</div>
			<?=$conteudo?>
		</div>
	</div><!-- /.container -->
	<footer class="footer">
		<div class="text-center margin-top-20 text-muted">© Copyright <?=date('Y')?> - Departamente de Suporte à Gestão - Seção de Tecnologia da Informação.</div>
	</footer>
	<!-- ####################################################################################################### -->
	<!-- ####################################################################################################### -->
	<script type="text/javascript" src="<?=base_url()?>assets/libs/bootstrap-table/config.js"></script>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/bootstrap-table/config.css">

	<script type="text/javascript">
		//#############################################################################
		status_roda_cronometro = true;
		function roda_cronometro()
		{
			cronometro = Cookies.get('cronometro');
			if( status_roda_cronometro === true )
			{
				cont = cronometro;
				cont = parseInt(cont) + 1;
				Cookies.set('cronometro', cont);

				setTimeout(function()
				{
					roda_cronometro();
				}, 1000);
			}
			else
			{
				Cookies.remove('cronometro');
			}
		}
		//#############################################################################
		if( Cookies.get('atendimento_id') )
		{
			roda_cronometro();
			if(controller.replace('/', '') != 'home')
			{
				$('.msg-prontuario').show();
			}
		}
		//#############################################################################
	</script>
</body>
</html>