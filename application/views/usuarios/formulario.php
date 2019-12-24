<?php
	$titulo_formulario = ( $this->router->fetch_method() == 'cadastrar' ) ? '<strong><i class="fa fa-plus-square"></i>&nbsp;Cadastro de Usuário</strong>' : '<strong><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Usuário</strong>';
?>
<script type="text/javascript">
	$(function()
	{
		$('#formUsuarios').bind('callback', function(event, data)
		{
			if(data.status == 1)
			{
				if(data.action=='insert')
				{
					window.location.href=base_url_controller;
				}
				else
				{
					$.toast(
					{
						text: data.msg,
						icon: 'success',
						position: 'top-right',
						//hideAfter: 10000,
						loader: false,
					});
				}
			}
			else
			{
				form_status = {'id':'formUsuarios','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
</script>
<?=form_open_multipart('usuarios/salvar', array('id'=>'formUsuarios', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
	<input type="hidden" id="id" name="id" data-field-db="id" value="<?=$dados->id?>">
	<h4><?=$titulo_formulario?></h4>
	<hr>
	<div class="msg"></div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtNome" class="control-label">Nome</label>
				<input type="text" class="form-control" id="txtNome" name="txtNome" readonly placeholder="Nome" data-field-db="<?=sha1('usuarios.nome')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group form-group-sm">
				<label for="txtUsuario" class="control-label">Usuário</label>
				<input type="text" class="form-control" id="txtUsuario" name="txtUsuario" readonly placeholder="Usuário" data-field-db="<?=sha1('usuarios.usuario')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtEmail" class="control-label">E-mail</label>
				<input type="text" class="form-control" id="txtEmail" name="txtEmail" readonly placeholder="E-mail" data-field-db="<?=sha1('usuarios.email')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group form-group-sm">
				<label for="txtSiape" class="control-label">Mat. Siape</label>
				<input type="text" class="form-control" id="txtSiape" name="txtSiape" readonly placeholder="Mat. Siape" data-field-db="<?=sha1('usuarios.matsiape')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group form-group-sm">
				<label for="txtMatGrad" class="control-label">Mat. Grad.</label>
				<input type="text" class="form-control" id="txtMatGrad" name="txtMatGrad" readonly placeholder="Mat. Grad." data-field-db="<?=sha1('usuarios.matgrad')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group form-group-sm">
				<label for="txtStatus" class="control-label">Status</label>
				<select class="select2" id="txtStatus" name="txtStatus" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('usuarios.status')?>">
					<option value="pendente">Pendente</option>
					<option value="ativo">Ativo</option>
				</select>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group form-group-sm">
				<label for="txtGrupoUsuario" class="control-label">Grupo de Usuário</label>
				<select class="select2" id="txtGrupoUsuario" name="txtGrupoUsuario" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('usuarios.grupo_id')?>">
				</select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'grupos/get',
							type: 'POST',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtGrupoUsuario').append('<option value="'+val.id+'">'+val.nome+'</option>');//.selectpicker('refresh');
								});
							}
						});
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtUsuarioDoSetor" class="control-label">Usuário do Setor</label>
				<select class="selectpicker form-control" id="txtUsuarioDoSetor" name="txtUsuarioDoSetor[]" multiple data-live-search="true" data-style="btn-default btn-sm">
				</select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'setores/get',
							type: 'POST',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtUsuarioDoSetor').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
								$('#txtUsuarioDoSetor').selectpicker('refresh');
							}
						});
						<?php
							unset($tmp);
							$tmp = array();
							foreach ($usuario_setores as $key => $value)
							{
								$tmp[] = $value;
							}
							$tmp = implode(',', $tmp);
						?>
						setTimeout(function()
						{
							$('#txtUsuarioDoSetor').selectpicker('val', [<?=$tmp?>]);
							$('#txtUsuarioDoSetor').selectpicker('refresh');
							//$('#txtUsuarioDoSetor').val([<?=$tmp?>]).trigger('change');
						}, 500);
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtAdministradorDoSetor" class="control-label">Administrador do(s) Setor(es)</label>
				<select class="selectpicker form-control" id="txtAdministradorDoSetor" name="txtAdministradorDoSetor[]" multiple data-live-search="true" data-style="btn-default btn-sm">
				</select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'setores/get',
							type: 'POST',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtAdministradorDoSetor').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
								$('#txtAdministradorDoSetor').selectpicker('refresh');
							}
						});
						$('#txtAdministradorDoSetor').on('change', function(event)
						{
							if( sizeof($('#txtAdministradorDoSetor').val()) == 1 && $('#txtGrupoUsuario').val() == 3 )
							{
								$.fancybox.open(''+
									'<div class="alert alert-danger">'+
										'<h3><i class="fa fa-exclamation-triangle"></i> Alerta!</h3>'+
										'<p style="max-width:500px !important;">Ao adicionar setores administrativos para esse usuário, ele(a) deixará de ser do grupo (Usuários de Sessão), e será inserido no grupo (Administrador de Sessão)</p>'+
										'<br>'+
										'<div class="pull-right"><a class="btn btn-default btn-sm" onclick="$.fancybox.close();">&nbsp;OK&nbsp;</a></div>'+
									'</div>'+
								'');
								$('#txtGrupoUsuario').val(2).trigger('change');
							}
							if( sizeof($('#txtAdministradorDoSetor').val()) == 0 )
							{
								$('#txtGrupoUsuario').val(3).trigger('change');
							}
						});
						<?php
							unset($tmp);
							$tmp = array();
							foreach ($administrador_setores as $key => $value)
							{
								$tmp[] = $value;
							}
							$tmp = implode(',', $tmp);
						?>
						setTimeout(function()
						{
							$('#txtAdministradorDoSetor').selectpicker('val', [<?=$tmp?>]);
							$('#txtAdministradorDoSetor').selectpicker('refresh');
							//$('#txtAdministradorDoSetor').val([<?=$tmp?>]).trigger('change');
						}, 500);
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="pull-left">				
				<button type="button" class="btn" onclick="window.history.go(-1);"><i class="fa fa-reply-all"></i> Voltar</button>
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Salvar</button>
			</div>
		</div>
	</div>
<?=form_close()?>
<script type="text/javascript">$(function(){getvaluesinputs('usuarios', $('#id').val())});</script>