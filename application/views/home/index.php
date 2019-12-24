<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/aside/aside.css">
<script type="text/javascript" src="<?=base_url()?>assets/libs/aside/aside.js"></script>
<div class="aside-toggle-right">&laquo;</div>
<div class="aside" id="modalHorizontal" data-bg="true" data-bg-close="true">
	<div class="aside-header">
		<!-- <span class="btn_close" data-dismiss="aside" aria-hidden="true">&times;</span> -->
		<div class="title"></div>
		<div class="btn_close pull-right"><img src="<?=base_url()?>assets/img/iconeclose.png"></div>
	</div>
	<div class="aside-contents">
		<div class="scrollbar-chrome">
		</div>
	</div>
</div>
<div class="aside-backdrop"></div>
<script type="text/javascript">
	$(function()
	{
		$('.scrollbar-chrome').scrollbar().css({'padding-right':'15px','width':'auto','max-height':$(window).height()+'px'});
	});
</script>
<script type="text/javascript">
	function label_tipo(label)
	{
		switch(label)
		{
			case 'interno':
				return '<span class="label label-primary">Interno</span>';
				break;
			case 'protocolado':
				return '<span class="label label-default">Protocolado</span>';
				break;
			case 'processo':
				return '<span class="label label-info">Processo</span>';
				break;
		}
	}
	$('body').on('click', '.bnt-visualizar-rotina', function(event)
	{
		event.preventDefault();
		$('#modalHorizontal').asidebar('open');
		$scrollbar = $('.scrollbar-chrome').find('.scroll-content');
		$scrollbar.html( '<i class="fa fa-spin fa-spinner"></i> Carregando...' );
		$.ajax(
		{
			url: base_url+'rotinas/get',
			type: 'POST',
			data: 'id='+$(this).data('id'),
			dataType: 'json',
			success: function(data)
			{
				console.log(data.rotina.tipo);
				html =  '<div class="text-right margin-bottom-20">'+
							'<button type="button" class="btn btn-default btn-sm" onclick="$.print(\'.scroll-content\')"><i class="fa fa-print"></i> imprimir</button>'+
							'&nbsp;'+
							'<button type="button" class="btn btn-default btn-sm" onclick="editar_rotina('+data.rotina.id+');"><i class="fa fa-pencil"></i> editar rotina</button>'+
						'</div>'+
						'<div class="panel panel-default">'+
							'<div class="panel-heading">'+
								'<h3 class="panel-title"><i class="fa fa-file-text-o"></i> Rotina</h3>'+
							'</div>'+
							'<div class="panel-body">'+
								''+data.rotina.nome+''+
							'</div>'+
						'</div>'+
				'';
				if( data.rotina.definicao )
				{
					html += ''+
							'<div class="panel panel-default">'+
								'<div class="panel-heading">'+
									'<h3 class="panel-title"><i class="fa fa-th-list"></i> Definição</h3>'+
								'</div>'+
								'<div class="panel-body">'+
									''+data.rotina.definicao+''+
								'</div>'+
							'</div>'+
							'';
				}
				html += ''+
						'<div class="panel panel-default">'+
							'<div class="panel-heading">'+
								'<h3 class="panel-title"><i class="fa fa-filter"></i> Tipo</h3>'+
							'</div>'+
							'<div class="panel-body">'+
								''+label_tipo(data.rotina.tipo)+''+
							'</div>'+
						'</div>'+
				'';
				if( data.rotina.tipo != 'interno' )
				{
					html += ''+
							'<div class="panel panel-default">'+
								'<div class="panel-heading">'+
									'<h3 class="panel-title"><i class="fa fa-exchange"></i> Fluxo</h3>'+
								'</div>'+
								'<div class="panel-body">'+
									''+data.rotina.fluxo+''+
								'</div>'+
							'</div>'+
							'';
				}
				if( data.rotina.mapamental )
				{
					html += ''+
							'<div class="panel panel-default">'+
								'<div class="panel-heading">'+
									'<h3 class="panel-title"><i class="fa fa-image"></i> Mapa mental</h3>'+
								'</div>'+
								'<div class="panel-body">'+
									'<a href="'+base_url+'assets/uploads/'+data.rotina.mapamental+'" data-fancybox>'+
										'<img class="img-responsive" src="'+base_url+'assets/uploads/'+data.rotina.mapamental+'">'+
									'</a>'+
								'</div>'+
							'</div>'+
							'';
				}
				html += ''+
						'<div class="panel panel-default">'+
							'<div class="panel-heading">'+
								'<h3 class="panel-title"><i class="fa fa-wpforms"></i> Descrição</h3>'+
							'</div>'+
							'<div class="panel-body">'+
								''+data.rotina.descricao+''+
							'</div>'+
						'</div>'+
						'';
				setTimeout(function()
				{
					$('.aside').find('.aside-header').find('.title').html(data.rotina.nome);
					$scrollbar.html(html);
					$('.sidenav-body').html(html);
					$scrollbar.find('img').addClass('img-responsive').removeAttr('style');

					setTimeout(function()
					{
						window_height = $(window).height();
						aside_header_height = $('.aside').find('.aside-header').outerHeight();
						aside_contents_padding = $('.aside').find('.aside-contents').css('padding').replace('px', '')*2;
						height_scroll = window_height - aside_header_height - aside_contents_padding;
						$('.aside').find('.scrollbar-chrome').css({'max-height':height_scroll+'px'});
					}, 500);
				}, 500);
			}
		});
	});
</script>
<div class="row">
	<div class="col-md-8">
		<?php
			// echo '<pre>';
			// 	print_r($this->session->dados_usuario);
			// echo '</pre>';
		?>
		<div class="panel panel-default panel_atendimento">
			<div class="panel-heading">
				<h3 class="panel-title font-bold"><i class="fa fa-headphones"></i> Atendimento</h3>
			</div>
			<div class="panel-body padding-top-20">
				<script type="text/javascript">
					$(function()
					{
						$('#formAtendimento').bind('callback', function(event, data)
						{
							if(data.status == 1)
							{
								//if( $('#formAtendimento').find('#status').val() == 'concluido' || $('#formAtendimento').find('#status').val() == 'pendente' )
								if( $('#formAtendimento').find('#action').val() == 'finalizar' )
								{
									Cookies.remove('cronometro');
									Cookies.remove('atendimento_id');

									$('#modalFinalizaAtendimento').modal('hide');

									window.location.href=base_url;
								}
								else
								{
									alertify.success('<i class="fa fa-check-circle-o"></i> '+data.msg);
								}
							}
							else
							{
								form_status = {'id':'formAtendimento','erros': data.erros};
								formajaxerros('#'+$(this).attr('id'), data.erros);
							}
						});
					});
				</script>
				<?=form_open_multipart('atendimentos/salvar', array('id'=>'formAtendimento', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
				<input type="hidden" id="id" name="id" data-field-db="id" value="">
				<input type="hidden" id="action" name="action">
				<input type="hidden" id="status" name="status" data-field-db="<?=sha1('atendimentos.status')?>" value="">
				<input type="hidden" id="time" name="time" data-field-db="<?=sha1('atendimentos.time')?>" value="">
				<div class="msg"></div>
				<div role="tabpanel">
					<ul class="nav nav-tabs nav-justified" role="tablist">
						<li role="presentation" class="active">
							<a href="#atendimento_novo" aria-controls="atendimento_novo" role="tab" data-toggle="tab"><strong><i class="fa fa-play"></i> Novo atendimento</strong> <small class="cont pull-right"></small></a>
						</li>
						<li role="presentation">
							<a href="#atendimentos_pendentes" aria-controls="atendimentos_pendentes" role="tab" data-toggle="tab"><strong><i class="fa fa-list"></i> Atendimento(s) pendente(s)</strong> <small class="cont pull-right"></small></a>
						</li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="atendimento_novo">
							<br>
							<div class="row">
								<div class="col-md-6">
									<div class="well" style="font-size: 40px;color:#ccc;text-align: center;">
										<i class="fa fa-clock-o"></i> <span class="cronometro">00:00:00</span>
									</div>
									<!-- <div class="margin-bottom-20">
										<a href="#" class="btn btn-block btn-success btn_iniciar_atendimento font-bold"><i class="fa fa-play"></i> Iniciar atendimento</a>
									</div> -->
								</div>
								<div class="col-md-6">
									<a href="#" class="btn btn-block btn-success btn_iniciar_atendimento font-bold"><span class="pull-left"><i class="fa fa-play fa-fw"></i></span>Iniciar atendimento</a>
									<button class="btn btn-block btn-warning btn_continuar_mais_tarde font-bold margin-top-20" disabled><span class="pull-left"><i class="fa fa-pause fa-fw"></i></span> Continuar mais tarde</button>
								</div>
								<script type="text/javascript">
									var contador_salvamento_automatico = 0;
									var tempo_salvamento_automatico = 5; // em segundos
									var status_form_erros = 0;
									var auto_save = 1;

									function registra_cronometro()
									{
										setTimeout(function()
										{
											contador_salvamento_automatico = contador_salvamento_automatico + 1;
											if(contador_salvamento_automatico == tempo_salvamento_automatico)
											{
												if(auto_save)
												{
													auto_save = 0;
													//salva_atendimento();
													//$('#formAtendimento').trigger('submit');
												}
												contador_salvamento_automatico=0;
											}
											Cookies.set('cronometro', $('.cronometro').data('seconds'));
											//console.log( 'TEMPO DE ATENDIMENTO>>>> '+moment.utc( Cookies.get('cronometro') * 1000 ).format('HH:mm:ss') );
											registra_cronometro();
										}, 1000);
									}
									function salva_atendimento(retornarerros=true)
									{
										return $.ajax(
										{
											url: base_url+'atendimentos/salvar',
											type: 'POST',
											data: $('#formAtendimento').serialize(),
											dataType: 'json',
											success:function(data)
											{
												if(retornarerros)
												{
													if(data.status==1)
													{
														$('.msg').html('');
													}
													else
													{
														msg = '';
														$.each(data.erros, function(campo, valor)
														{
															msg += '<small>'+valor+'</small>';
														});
														$('.msg')
														.html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+msg+'</div>')
														.show();
													}
												}
											}
										});
									}
									$(function()
									{
										$('body').on('click', '.btn_continuar_mais_tarde', function(event)
										{
											event.preventDefault();
											
											Cookies.set('action', 'continuar_mais_tarde');
											$('#modalFinalizaAtendimento').modal('show');
											$('#modalFinalizaAtendimento').find('.modal-title').html('Continuar mais tarde');
											$('#modalFinalizaAtendimento').find('.modal-body').html('Você realmente deseja continuar esse mais tarde ?');
										});
										$('body').on('click', '.btn_finaliza_atendimento', function(event)
										{
											event.preventDefault();

											Cookies.set('action', 'finaliza_atendimento');
											$('#modalFinalizaAtendimento').modal('show');
											$('#modalFinalizaAtendimento').find('.modal-title').html('Finalizar atendimento');
											$('#modalFinalizaAtendimento').find('.modal-body').html('Ao finalizar um atendimento, você não poderá alterá-lo novamente. Deseja prosseguir ?');
										});
										$('body').on('click', '.btn_iniciar_atendimento', function(event)
										{
											event.preventDefault();

											$('.cronometro').timer( // Inicia CRONOMETRO
											{
												action: 'start',
												format: '%H:%M:%S'
											});
											//############################################################################ // Cria Atendimento
											$.ajax(
											{
												url: base_url+'atendimentos/criaatendimento',
												type: 'POST',
												//data: 'txtCampo='+$('txtCampo').val(),
												dataType: 'json',
												success: function(data)
												{
													console.log('ID CRIADO>>> '+data.id);
													$('#formAtendimento').find('#id').val(data.id);
													Cookies.set('atendimento_id', data.id);
													registra_cronometro();
												}
											});
											//############################################################################
											$('.btn_continuar_mais_tarde').prop('disabled', false);
											$('.cronometro').css({'color':'#2980B9'});
											$(this).removeClass('btn-success btn_iniciar_atendimento').addClass('btn-danger btn_finaliza_atendimento').html('<span class="pull-left"><i class="fa fa-stop"></i></span> Finalizar atendimento');
											$('.row_atendimento').slideDown();
										});
										$('.btn_confirma_finalizar_atendimento').on('click', function(event)
										{
											event.preventDefault();

											if( Cookies.get('action') == 'finaliza_atendimento' )
											{
												$('#formAtendimento').find('#status').val('concluido');
												$('#formAtendimento').find('#action').val('finalizar');
											}
											else if( Cookies.get('action') == 'continuar_mais_tarde' )
											{
												$('#formAtendimento').find('#status').val('pendente');
												$('#formAtendimento').find('#action').val('finalizar');
											}

											$('#formAtendimento').find('#time').val( Cookies.get('cronometro') );
											$('#formAtendimento').trigger('submit');

											setTimeout(function()
											{
												if( form_status.id == 'formAtendimento' )
												{
													if(form_status.erros)
													{
														alertify.alert('<strong><i class="fa fa-info-circle"></i>&nbsp;Atenção</strong>', '<strong>Para finalizar, é necessário corrigir alguns erros.</strong>');
														$('#modalFinalizaAtendimento').modal('hide');
													}
												}
											}, 500);
										});
									});
								</script>
							</div>
							<div class="row display-none row_atendimento">
								<div class="col-md-12">
									<div class="row">
 										<div class="col-md-7">
											<div class="form-group form-group-sm">
												<label for="txtNome" class="control-label">Nome do usuário</label>
												<div class="input-group">
													<input type="hidden" id="usuario_uid" name="usuario_uid" data-callback="true" data-field-db="<?=sha1('tiss.usuario_uid')?>">
													<input type="text" id="txtNome" name="txtNome" data-field-db="<?=sha1('atendimentos.nome')?>" class="search-query form-control" aria-describedby="basic-addon2">
													<span class="input-group-addon" id="basic-addon2">
														<i class="fa fa-search"></i>
													</span>
												</div>
												<script type="text/javascript">
													$(function()
													{
														var icone_carregando_typeahead = null;
														$('#txtNome').on('keyup', function(event)
														{
															$element = $(this);
															if( $element.val().length <= 3 ){$('#usuario_uid').val('')}

															if(!icone_carregando_typeahead)
															{
																$element.parent('.input-group').find('.input-group-addon').html('<i class="fa fa-spinner fa-pulse"></i>');
																icone_carregando_typeahead = setTimeout(function()
																{
																	$element.parent('.input-group').find('.input-group-addon').html('<i class="fa fa-search"></i>');
																	icone_carregando_typeahead=null;
																}, 1000);
															}
														});
														$('#txtNome').typeahead(
														{
															scrollBar:true,
															ajax:
															{
																url: base_url+'home/typeahead_ldap',
																triggerLength: 3,
																//items: 20,
																method:'POST',
																displayField: 'text',
																preProcess: function (data)
																{
																	if (data.status == 1)
																	{
																		var list = [];
																		$.each(data.dados, function(index, val)
																		{
																			//list[index] = {'id':val.id, 'text':val.nome.toUpperCase()};
																			list[index] = {'id':val.uid, 'text':val.displayname};
																		});
																		return list;
																	}
																	else
																	{
																		return false;
																	}
																}
															},
															onSelect: function(data)
															{
																//$('#usuario_uid').val(data.value);
															}
														});
													});
												</script>
<!-- 												<label for="txtNome" class="control-label">Nome do usuário</label>
												<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('atendimentos.nome')?>" placeholder="Nome do usuário">
												<small class="msg-erro text-danger"></small> -->
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group form-group-sm">
												<label for="txtTipoUsuario" class="control-label">Tipo de usuário</label>
												<select class="select2" id="txtTipoUsuario" name="txtTipoUsuario" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('atendimentos.tipousuario')?>">
													<option value="outro">Outro</option>
													<option value="discente">Discente</option>
													<option value="docente">Docente</option>
													<option value="tecnicoadministrativo">Técnico Administrativo</option>
												</select>
												<small class="msg-erro text-danger"></small>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-7">
											<div class="form-group form-group-sm">
												<label for="txtAssunto" class="control-label">Assunto</label>
												<input type="text" class="form-control" id="txtAssunto" name="txtAssunto" data-field-db="<?=sha1('atendimentos.assunto')?>" placeholder="Assunto">
												<small class="msg-erro text-danger"></small>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group form-group-sm">
												<label for="txtTipoAtendimento" class="control-label">Tipo de atendimento</label>
												<select class="select2" id="txtTipoAtendimento" name="txtTipoAtendimento" data-field-db="<?=sha1('atendimentos.atendimentotipo_id')?>">
													<option value="1" selected>Geral</option>
												</select>
												<script type="text/javascript">
													var checklist = '';
													function add_checklist()
													{
														$('#txtDescricaoAtendimento').val( checklist );
													}
													$(function()
													{
														$.ajax(
														{
															url: base_url+'atendimentostipos/get',
															type: 'POST',
															dataType: 'json',
															success: function(data)
															{
																$.each(data.dados, function(index, val)
																{
																	$('#txtTipoAtendimento').append('<option value="'+val.id+'">'+val.nome+'</option>');
																});
															}
														});
														$('#txtTipoAtendimento').on('change', function(event)
														{
															event.preventDefault();

															$('.alert_checklist').html('');
															var id = $(this).val();

															if(id)
															{
																$.ajax(
																{
																	url: base_url+'atendimentostipos/get',
																	type: 'POST',
																	data: 'id='+id,
																	dataType: 'json',
																	success: function(data)
																	{
																		if(data.atendimentostipos.checklist)
																		{
																			checklist = data.atendimentostipos.checklist;
																			$('.alert_checklist').html('Para adicionar checklist pré-definido, <a href="javascript:void(0);" onclick="add_checklist();">clique aqui</a>.')
																		}
																		else
																		{
																			$('.alert_checklist').html('');
																		}
																	}
																});
															}
														});
													});
												</script>
												<small class="msg-erro text-danger"></small>
												<small class="alert_checklist"></small>
											</div>
										</div>
									</div>
									<div class="row row_setor display-none">
										<div class="col-md-12">
											<div class="form-group form-group-sm">
												<label for="txtSetorAtendimento" class="control-label">Setor</label>
												<select class="select2" id="txtSetorAtendimento" name="txtSetorAtendimento" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('atendimentos.setor_id')?>"></select>
												<script type="text/javascript">
													<?php
														if( sizeof($this->session->dados_usuario->setores_administrador) > 1 )
														{
															?>
															$(function()
															{
																$.ajax(
																{
																	url: base_url+'setores/setores_usuario_administrador',
																	type: 'POST',
																	dataType: 'json',
																	success: function(data)
																	{
																		$.each(data.dados, function(index, val)
																		{
																			$('#txtSetorAtendimento').append('<option value="'+val.id+'">'+val.nome+'</option>');
																		});
																	}
																});
																$('.row_setor').show();
															});
															<?php
														}
														else
														{
															?>
															$(function()
															{
																$('#txtSetorAtendimento').append('<option value="<?=$this->session->dados_usuario->setores[0]?>"><?=$this->session->dados_usuario->setor?></option>');
															});
															<?php
														}
													?>
												</script>
												<small class="msg-erro text-danger"></small>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group form-group-sm">
												<label for="txtDescricaoAtendimento" class="control-label">Descrição</label>
												<textarea class="ckeditor-basic-public" id="txtDescricaoAtendimento" rows="5" name="txtDescricaoAtendimento" data-field-db="<?=sha1('atendimentos.descricao')?>" placeholder="Descrição"></textarea>
												<small class="msg-erro text-danger"></small>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<a class="btn btn-danger btn_cancelar_atendimento"><i class="fa fa-trash"></i> &nbsp;Cancelar</a>
											<script type="text/javascript">
												$(function()
												{
													$('body').on('click', '.btn_cancelar_atendimento', function(event)
													{
														event.preventDefault();
														alertify.confirm('<strong><i class="fa fa-exclamation-circle"></i>&nbsp;Confirmação</strong>', '<strong>Você realmente deseja excluir este atendimento ?</strong>', function()
														{
															$.ajax(
															{
																url: base_url+'atendimentos/excluiratendimento',
																type: 'POST',
																data: 'id='+Cookies.get('atendimento_id'),
																dataType: 'json',
																success: function(data)
																{
																	if(data.status==1)
																	{
																		cookies_remove();
																		window.location.href=base_url_controller;
																	}
																}
															});
														}, function()
														{

														});
													});
												})
											</script>
											<button type="submit" onmouseover="$('#formAtendimento').find('#time').val( Cookies.get('cronometro') )" class="btn btn-primary pull-right"><i class="fa fa-save"></i>&nbsp;Salvar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpane2" class="tab-pane" id="atendimentos_pendentes">
							<br>
							<table class="table table-striped table-hover table-bordered" id="tableAtendimentosPendentes">
								<thead>
									<tr>
										<th>Usuário</th>
										<th>Assunto</th>
										<th>Tempo</th>
										<th class="text-center"><i class="fa fa-cogs"></i></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<script type="text/javascript">
								$(function()
								{
									$.ajax(
									{
										url: base_url+'atendimentos/atendimentos_pendentes',
										type: 'POST',
										dataType: 'json',
										success: function(data)
										{
											html = '';
											$.each(data.dados, function(index, val)
											{
												html += ''+
														'<tr>'+
															'<td>'+val.nome+'</td>'+
															'<td>'+val.assunto+'</td>'+
															'<td>'+moment.utc( val.time * 1000 ).format('HH:mm:ss')+'</td>'+
															'<td class="text-center"><button class="btn btn-success btn-xs btn_continuar_atendimento" data-id="'+val.id+'" data-time="'+val.time+'"><i class="fa fa-play"></i> continuar</button></td>'+
														'</tr>'+
														'';
											});
											$('.nav-tabs a[aria-controls="atendimentos_pendentes"]').find('.cont').html('<span class="badge">'+sizeof(data.dados)+'</span>');
											$('#tableAtendimentosPendentes').find('tbody').html(html);
										}
									});
									$('body').on('click', '.btn_continuar_atendimento', function(event)
									{
										event.preventDefault();
										Cookies.set('atendimento_id', $(this).data('id'));
										Cookies.set('cronometro', $(this).data('time'));

										window.location.reload(true);
									});
								})
							</script>
							<!-- <br><br>
							<a class="btn btn-sm btn-default btn_showcookie"><i class="fa fa-edit"></i> Cookies (show)</a>
							<a class="btn btn-sm btn-warning btn_clearcookie"><i class="fa fa-edit"></i> Cookies (clear)</a>
							<script type="text/javascript">
								$('body').on('click', '.btn_showcookie', function(event)
								{
									event.preventDefault();
									cookies_show();
								});
								$('body').on('click', '.btn_clearcookie', function(event)
								{
									event.preventDefault();
									cookies_remove();
								});
							</script> -->
						</div>
					</div>
				</div>
				<?=form_close()?>
			</div>
		</div>  
	</div>
	<script type="text/javascript">
		setTimeout(function()
		{
			$('#tableRotinas').find('.search').removeClass('pull-right').find('input')
			.removeClass('input-sm')
			.addClass('input-lg');
		}, 500);
	</script>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-sort-alpha-asc"></i> Rotinas administrativas</h3>
			</div>
			<div class="panel-body">
				<style type="text/css">.pagination-info, .pagination-detail{ display: none !important; }</style>
				<div id="tableRotinas">
					<table
						id="tableRotinas"
						data-toggle="table"
						data-toolbar="#toolbar"
						data-click-to-select="true"
						data-classes="table table-striped table-hover bootstrap-table"

						data-url="<?=$this->base_url?>rotinas/bootstrap_table_public"

						data-icons-prefix="fa"
						data-icons="icons"
						data-icon-size="sm"

						data-pagination="true"
						data-side-pagination="server"
						data-page-list="[5, 10, 20, 50, 100, 200]"
						data-search="true"
						data-query-params="queryParams"

						data-sort-name="nome"
						>
						<thead>
							<tr>
								<th data-field="nome" data-sortable="true" data-formatter="formatter_nome">Nome</th>
							</tr>
						</thead>
					</table>
				</div>
				<script type="text/javascript">
					function queryParams(params)
					{
						params.like_search = 'nome'; // 'all' ou 'nome|cpf|celular'
						return params;
					}
					function formatter_nome(value, row, index)
					{
						return [
							'<div class="cursor-pointer bnt-visualizar-rotina" data-id="'+row.id+'" data-toggle="modal" href="#modalDadosRotina">',
							''+row.nome+'',
							'</div>',
						].join('');
					}
				</script>
			</div>
		</div>
		<script type="text/javascript">
			function editar_rotina(id)
			{
				$('.aside').asidebar().close();
				$.when(editar(id)).done(function()
				{
					$('#status').val('pendente');
				});
			}
			function editar(id) //Preenche os campos do formulário apartir do DB
			{
				$('#acao').val('editar_pendente');
				$('#formRotinas').find('input[name="id"]').val(id);
				$('#modalRotinas').modal('show');
				getvaluesinputs('rotinas', id);
			}
		</script>
		<div class="modal fade" id="modalRotinas">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><i class="fa fa-book"></i> Rotinas (Sugerir Edição)</h4>
					</div>
					<div class="modal-body">
						<script type="text/javascript">
							$(function()
							{
								$('#formRotinas').bind('callback', function(event, data)
								{
									if(data.status == 1)
									{
										$.fancybox.open(
											'<div class="alert alert-success" style="max-width:450px !important;">'+
												'<h3><i class="fa fa-exclamation-triangle"></i> Alerta!</h3>'+
												'<p>Você sugeriu uma edição de rotina, aguarde a homologação do Administrador da Seção.</p>'+
												'<div class="pull-right"><a class="btn btn-default btn-sm" onclick="$.fancybox.close();">&nbsp;OK&nbsp;</a></div>'+
											'</div>',
										{
											modal:true,
											smallBtn : false,
											afterClose: function()
											{
												window.location.href=base_url_controller;
											}
										});
									}
									else
									{
										form_status = {'id':'formRotinas','erros': data.erros};
										formajaxerros('#'+$(this).attr('id'), data.erros);
									}
								});
							});
						</script>
						<?=form_open_multipart('rotinas/sugerir_edicao_salvar', array('id'=>'formRotinas', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
							<input type="hidden" id="id" name="id" data-field-db="id">
							<input type="hidden" id="mapamental" name="mapamental" data-field-db="<?=sha1('rotinas.mapamental')?>">
							<input type="hidden" id="parent_id" name="parent_id" data-field-db="<?=sha1('rotinas.id')?>">
							<input type="hidden" id="setor_id" name="setor_id" data-field-db="<?=sha1('rotinas.setor_id')?>">
							<div class="msg"></div>
							<div class="alert alert-warning"><strong><i class="fa fa-exclamation-triangle"></i> </strong>Faça a edição desta rotina, e após, espere a aprovação de sua edição pelo adminitrador do setor.</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-group-sm">
										<label for="txtNome" class="control-label">Nome</label>
										<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('rotinas.nome')?>" placeholder="Nome">
										<small class="msg-erro text-danger"></small>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-group-sm">
										<label for="txtTipo" class="control-label">Tipo</label>
										<select class="select2 form-control" style="width: 100%;" id="txtTipo" name="txtTipo" data-field-db="<?=sha1('rotinas.tipo')?>" data-minimum-results-for-search="Infinity">
											<option value="interno">Interno</option>
											<option value="protocolado">Protocolado</option>
											<option value="processo">Processo</option>
										</select>
										<script type="text/javascript">
											function set_tipo(valor)
											{
												if( valor == 'interno' )
												{
													$('.row-fluxo').hide();
												}
												else if( valor == '' )
												{
													$('.row-fluxo').hide();
												}
												else
												{
													$('.row-fluxo').show();
												}
											}
											$('#txtTipo').on('change', function(event)
											{
												event.preventDefault();
												set_tipo( $(this).val() );
											});
										</script>
										<small class="msg-erro text-danger"></small>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group form-group-sm">
										<label for="txtDefinicao" class="control-label">Definição</label>
										<textarea class="form-control" id="txtDefinicao" rows="5" name="txtDefinicao" data-field-db="<?=sha1('rotinas.definicao')?>" placeholder="Definição"></textarea>
										<small class="msg-erro text-danger"></small>
									</div>
								</div>
							</div>
							<div class="row row-fluxo" style="display: none;">
								<div class="col-md-12">
									<div class="form-group form-group-sm">
										<label for="txtFluxo" class="control-label">Fluxo</label>
										<textarea class="ckeditor-basic-public" rows="5" id="txtFluxo" name="txtFluxo" data-field-db="<?=sha1('rotinas.fluxo')?>"><strong>DE</strong>: Origem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>PARA:</strong> Destino<br><strong>DE</strong>: Origem&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>PARA</strong>: Destino</textarea>
										<small class="msg-erro text-danger"></small>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group form-group-sm">
										<label for="txtDescricao" class="control-label">Descrição</label>
										<textarea class="ckeditor-basic-public" rows="5" id="txtDescricao" name="txtDescricao" style="height:300px;" data-field-db="<?=sha1('rotinas.descricao')?>"></textarea>
										<small class="msg-erro text-danger"></small>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="pull-right">
										<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp;Salvar</button>
									</div>
								</div>
							</div>
						<?=form_close()?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalFinalizaAtendimento">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Encerrar atendimento</h4>
			</div>
			<div class="modal-body">
				Ao finalizar um atendimento, você não poderá alterá-lo novamente. Deseja prosseguir ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
				<button type="button" class="btn btn-success btn_confirma_finalizar_atendimento">Sim</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	if( Cookies.get('atendimento_id') )
	{
		console.log('CRONOMETRO>>> '+Cookies.get('cronometro') );
		console.log('ATENDIMENTO ID>>> '+Cookies.get('atendimento_id') );

		$('#formAtendimento').find('#id').val( Cookies.get('atendimento_id') );
		$('.row_atendimento').slideDown();
		$('.cronometro').css({'color':'#2980B9'});
		$('.btn_iniciar_atendimento').removeClass('btn-success btn_iniciar_atendimento').addClass('btn-danger btn_finaliza_atendimento').html('<span class="pull-left"><i class="fa fa-stop"></i></span> Finalizar atendimento');
		$('.btn_continuar_mais_tarde').prop('disabled', false);

		$('.cronometro').timer(
		{
			action: 'start',
			format: '%H:%M:%S',
			seconds: Cookies.get('cronometro'),
		});
		registra_cronometro();
		//############################################################################
		getvaluesinputs('atendimentos', Cookies.get('atendimento_id'));
	}
</script>