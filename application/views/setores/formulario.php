<?php
	$titulo_formulario = ( $this->router->fetch_method() == 'cadastrar' ) ? '<strong><i class="fa fa-plus-square"></i>&nbsp;Cadastro de Setores</strong>' : '<strong><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Setores</strong>';
?>
<script type="text/javascript">
	$(function()
	{
		$('#formSetores').bind('callback', function(event, data)
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
				form_status = {'id':'formSetores','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
</script>
<?=form_open_multipart('setores/salvar', array('id'=>'formSetores', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
	<input type="hidden" id="id" name="id" data-field-db="id" value="<?=$dados->id?>">
	<h4><?=$titulo_formulario?></h4>
	<hr>
	<div class="msg"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtNome" class="control-label">Setor</label>
				<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('setores.nome')?>" placeholder="Setor">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtDescricao" class="control-label">Descrição</label>
				<textarea class="ckeditor-basic" rows="5" id="txtDescricao" name="txtDescricao" data-field-db="<?=sha1('setores.descricao')?>"></textarea>
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
<script type="text/javascript">setTimeout(function(){ getvaluesinputs('setores', $('#id').val()); }, 500);</script>