<?php
	$titulo_formulario = ( $this->router->fetch_method() == 'cadastrar' ) ? '<strong><i class="fa fa-plus-square"></i>&nbsp;Cadastro de tipos de atendimento</strong>' : '<strong><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar tipos de atendimento</strong>';
?>
<script type="text/javascript">
	$(function()
	{
		$('#formAtendimentostipos').bind('callback', function(event, data)
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
				form_status = {'id':'formAtendimentostipos','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
</script>
<?=form_open_multipart('atendimentostipos/salvar', array('id'=>'formAtendimentostipos', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
	<input type="hidden" id="id" name="id" data-field-db="id" value="<?=$dados->id?>">
	<h4><?=$titulo_formulario?></h4>
	<hr>
	<div class="msg"></div>
	<div class="row">
		<div class="col-md-8">
			<div class="form-group form-group-sm">
				<label for="txtNome" class="control-label">Tipo de atendimento</label>
				<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('atendimentos_tipos.nome')?>" placeholder="Tipo de atendimento">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtSetor" class="control-label">Setor</label>
				<select class="select2 form-control" id="txtSetor" name="txtSetor" data-field-db="<?=sha1('atendimentos_tipos.setor_id')?>"></select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'setores/setores_administrador',
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
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning">
				<div><strong><i class="fa fa-exclamation-triangle"></i> Alerta!</strong></div>
				<div class="margin-top-10">Inclua o CHECKLIST padrão ou adicione seu próprio modelo.</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="txtChecklist" class="control-label">Checklist</label>
				<textarea class="ckeditor" id="txtChecklist" rows="10" name="txtChecklist" data-field-db="<?=sha1('atendimentos_tipos.checklist')?>" placeholder="Checklist"></textarea>
				<small class="msg-erro text-danger"></small>
				<script type="text/javascript">
					setTimeout(function()
					{
						template = ''+
							'<h2>Lista de tarefas&nbsp;</h2>'+
							'<table align="center" border="0" cellpadding="5" cellspacing="5" style="width:100%">'+
								'<tbody>'+
									'<tr>'+
										'<td style="width:30px;">( &nbsp;)</td>'+
										'<td>1 - Tarefa a ser executada (1)</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="width:30px;">( &nbsp;)</td>'+
										'<td>2 - Tarefa a ser executada (2)</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="width:30px;">( &nbsp;)</td>'+
										'<td>3 - Tarefa a ser executada (3)</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="width:30px;">( &nbsp;)</td>'+
										'<td>...</td>'+
									'</tr>'+
								'</tbody>'+
							'</table>'+
						'';

						if(controller_method=='cadastrar')
						{
							$('#txtChecklist').val(template);
						}
					}, 500);
				</script>
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
<script type="text/javascript">setTimeout(function(){ getvaluesinputs('atendimentostipos', $('#id').val()); }, 500);</script>