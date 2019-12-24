<?php
	$titulo_formulario = ( $this->router->fetch_method() == 'cadastrar' ) ? '<strong><i class="fa fa-plus-square"></i>&nbsp;Cadastro de Atendimentos</strong>' : '<strong><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Atendimentos</strong>';
?>
<script type="text/javascript">
	$(function()
	{
		$('#formAtendimentos').bind('callback', function(event, data)
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
				form_status = {'id':'formAtendimentos','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
</script>
<?=form_open_multipart('atendimentos/salvareditar', array('id'=>'formAtendimentos', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
	<input type="hidden" id="id" name="id" data-field-db="id" value="<?=$dados->id?>">
	<h4><?=$titulo_formulario?></h4>
	<hr>
	<div class="msg"></div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group form-group-sm">
				<label for="txtNome" class="control-label">Nome do usuário</label>
				<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('atendimentos.nome')?>" placeholder="Nome">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtTipoUsuario" class="control-label">TIpo de usuário</label>
				<select class="select2" id="txtTipoUsuario" name="txtTipoUsuario" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('atendimentos.tipousuario')?>">
					<option value="discente">Discente</option>
					<option value="docente">Docente</option>
					<option value="tecnicoadministrativo">Técnico Administrativo</option>
					<option value="outro">Outro</option>
				</select>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group form-group-sm">
				<label for="txtAssunto" class="control-label">Assunto</label>
				<input type="text" class="form-control" id="txtAssunto" name="txtAssunto" placeholder="Assunto" data-field-db="<?=sha1('atendimentos.assunto')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtSetor" class="control-label">Setor</label>
				<select class="select2 form-control" id="txtSetor" name="txtSetor" data-field-db="<?=sha1('atendimentos.setor_id')?>"></select>
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
									$('#txtSetor').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
							}
						});
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtUsuario" class="control-label">Atendente</label>
				<select class="select2 form-control" id="txtUsuario" name="txtUsuario" data-field-db="<?=sha1('atendimentos.usuario_id')?>"></select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'usuarios/get',
							type: 'POST',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtUsuario').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
							}
						});
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtTipoAtendimento" class="control-label">Tipo</label>
				<select class="select2 form-control" id="txtTipoAtendimento" name="txtTipoAtendimento" data-field-db="<?=sha1('atendimentos.atendimentotipo_id')?>"></select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'Atendimentostipos/get_all',
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
					});
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="form-group form-group-sm">
				<label for="txtTempo" class="control-label">Tempo</label>
				<input type="text" class="form-control" id="txtTempo" name="txtTempo" readonly placeholder="Tempo" data-field-db="<?=sha1('atendimentos.time')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group form-group-sm">
				<label for="txtData" class="control-label">Data</label>
				<input type="text" class="form-control" id="txtData" name="txtData" readonly placeholder="Data" data-field-db="<?=sha1('atendimentos.data')?>">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtStatus" class="control-label">Status</label>
				<select class="select2" id="txtStatus" name="txtStatus" data-field-db="<?=sha1('atendimentos.status')?>" data-minimum-results-for-search="Infinity">
					<option value="concluido">Concluído</option>
					<option value="pendente">Pendente</option>
				</select>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtDescricaoAtendimento" class="control-label">Descrição</label>
				<textarea class="ckeditor" rows="5" id="txtDescricaoAtendimento" name="txtDescricaoAtendimento" data-field-db="<?=sha1('atendimentos.descricao')?>" placeholder="Descrição"></textarea>
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
<script type="text/javascript">
	$(function()
	{
		setTimeout(function()
		{
			getvaluesinputs('atendimentos', $('#id').val());
			setTimeout(function()
			{
				$('#txtTempo').val( moment.utc( $('#txtTempo').val() * 1000 ).format('HH:mm:ss') );
			}, 500);
		}, 500);
	});
</script>