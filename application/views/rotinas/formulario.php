<?php
	$titulo_formulario = ( $this->router->fetch_method() == 'cadastrar' ) ? '<strong><i class="fa fa-plus-square"></i>&nbsp;Cadastro de Rotina</strong>' : '<strong><i class="glyphicon glyphicon-edit"></i>&nbsp;Editar Rotina</strong>';
?>
<script type="text/javascript">
	setTimeout(function()
	{
		if( !$('#id').val() )
		{
			$('#txtFluxo').val('<table width="100%"><tbody><tr><td><strong>DE</strong>: Origem</td><td><strong>PARA</strong>: Destino</td></tr><tr><td><strong>DE</strong>: Origem</td><td><strong>PARA</strong>: Destino</td></tr></tbody></table>');
		}
	}, 500);
	$(function()
	{
		$('#formRotinas').bind('callback', function(event, data)
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
				form_status = {'id':'formRotinas','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
</script>
<?=form_open_multipart('rotinas/salvar', array('id'=>'formRotinas', 'role'=>'form', 'class'=>'formajax', 'data-callback'=>'true'))?>
	<input type="hidden" id="id" name="id" data-field-db="id" value="<?=$dados->id?>">
	<h4><?=$titulo_formulario?></h4>
	<hr>
	<div class="msg"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="fileinput-group">				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Mapa Mental <div class="pull-right"><a class="btn btn-danger btn-xs btn_excluir_file" data-field="mapamental" href="javascript:void(0);"><i class="fa fa-trash"></i></a></div></h3>
					</div>
					<div class="panel-body">
						<div class="file_default display-none">
							<div class="file"></div>
						</div>
						<div class="file_new display-none">
							<div class="form-group">
								<input type="file" class="fileinput" id="txtMapaMental" name="txtMapaMental" data-field-db="<?=sha1('rotinas.mapamental')?>" data-show-preview="false" data-show-upload="false" data-show-caption="true" data-preview-file-type="image">
								<small class="msg-erro text-danger"></small>
							</div>
							<div class="alert alert-warning">
								<small>A imagem escolhida deve estar em formato <strong>JPG, GIF, ou PNG</strong> e ter no máximo <strong>2MB</strong>.</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtNome" class="control-label">Rotina</label>
				<input type="text" class="form-control" id="txtNome" name="txtNome" autocomplete="off" spellcheck="false" dir="auto" data-field-db="<?=sha1('rotinas.nome')?>" placeholder="Rotina">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtTipo" class="control-label">Tipo</label>
				<select class="select2 form-control" id="txtTipo" name="txtTipo" data-field-db="<?=sha1('rotinas.tipo')?>" data-minimum-results-for-search="Infinity">
					<option value="interno"<?php if($dados->tipo=='interno'){echo ' selected';} ?>>Interno</option>
					<option value="protocolado"<?php if($dados->tipo=='protocolado'){echo ' selected';} ?>>Protocolado</option>
					<option value="processo"<?php if($dados->tipo=='processo'){echo ' selected';} ?>>Processo</option>
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
					setTimeout(function()
					{
						set_tipo( $('#txtTipo').val() );
					}, 500);
				</script>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group form-group-sm">
				<label for="txtSetor" class="control-label">Setor</label>
				<select class="select2 form-control" id="txtSetor" name="txtSetor" data-field-db="<?=sha1('rotinas.setor_id')?>">
				</select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'setores/setorusuario',
							type: 'POST',
							data: 'usuario_id=<?=$this->session->dados_usuario->id?>',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtSetor').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
								
								value = '<?=$dados->setor_id?>';
								if(value)
								{
									$('#txtSetor').val(value).trigger('change');
								}
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
				<textarea class="ckeditor-basic" rows="5" id="txtFluxo" name="txtFluxo" data-field-db="<?=sha1('rotinas.fluxo')?>"></textarea>
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group form-group-sm">
				<label for="txtDescricao" class="control-label">Descrição</label>
				<textarea class="ckeditor" rows="5" id="txtDescricao" name="txtDescricao" data-field-db="<?=sha1('rotinas.descricao')?>"></textarea>
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
<script type="text/javascript">setTimeout(function(){ getvaluesinputs('rotinas', $('#id').val()); }, 500);</script>