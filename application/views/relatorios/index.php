<h3><i class="fa fa-line-chart"></i> Relatórios</h3>
<div class="msg"></div>
<div class="msg_chart"></div>
<div class="chart" style="border: 1px dotted #ccc;background-color:#fff;margin-top: 20px;padding:40px;"><canvas id="myChart" style="background-color: #fff;"></canvas></div>
<script>var canvas = document.getElementById("myChart")</script>
<hr>
<script type="text/javascript">
	var resetCanvas = function(){
		$('#results-graph').remove(); // this is my <canvas> element
		$('#graph-container').append('<canvas id="results-graph"><canvas>');
		canvas = document.querySelector('#results-graph');
		ctx = canvas.getContext('2d');
		ctx.canvas.width = $('#graph').width(); // resize to parent width
		ctx.canvas.height = $('#graph').height(); // resize to parent height
		var x = canvas.width/2;
		var y = canvas.height/2;
		ctx.font = '10pt Verdana';
		ctx.textAlign = 'center';
		ctx.fillText('This text is centered on the canvas', x, y);
	};
	$(function()
	{
		$('.chart').hide();
		$('#formRelatorios').bind('callback', function(event, data)
		{
			if(data.status == 1)
			{
				$.fancybox.close();
				if(data.atendimentos.length > 0)
				{
					$('.chart').show();
					$('#myChart').remove(); // this is my <canvas> element
					$('.chart').append('<canvas id="myChart"><canvas>');
					canvas = document.querySelector('#myChart');


					var chart_labels = [];
					var chart_datas = [];
					var total = 0;
					$.each(data.atendimentos, function(index, val)
					{
						chart_labels.push(val.data);
						chart_datas.push(val.value);
						total += parseInt(val.value);
					});

					$('.msg_chart').html('<h5>Total de atendimentos <strong>'+total+'</strong></h5>')
					

					var presets = window.chartColors;
					var utils = Samples.utils;
					utils.srand(8);
					var myChart = new Chart(canvas, {
						type: 'line',
						data: {
							labels: chart_labels,
							datasets: [{
								label: ' Atendimentos',
								data: chart_datas,
								backgroundColor: utils.transparentize(presets.blue),
								borderColor: presets.blue,
							}],
						},
					});
				}
				else
				{
					$('.msg_chart').html('<div class="alert alert-info">'+
						'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
						'<i class="fa fa-exclamation-triangle"></i> Nenhum resultado encontrado.'+
					'</div>');
				}
			}
			else
			{
				form_status = {'id':'formRelatorios','erros': data.erros};
				formajaxerros('#'+$(this).attr('id'), data.erros);
			}
		});
	});
	function loading_chart()
	{
		$('.chart').hide();
		$.fancybox.open(
			'<div class="alert alert-default">'+
				'<h3><i class="fa fa-spinner fa-spin"></i> Aguarde...</h3>'+
			'</div>',
		{
			modal:true,
			smallBtn : false,
		});
	}
</script>
<?=form_open_multipart('relatorios/search', array('id'=>'formRelatorios', 'class'=>'formajax', 'role'=>'form', 'data-callback'=>'true'))?>
	<div class="panel panel-default">
		<div class="panel-body" style="background-color:#fafafa;">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group form-group-sm">
						<label for="txtDataInicial" class="control-label">Data inicial</label>
						<div class="input-group date datetimepicker-data">
							<input type="text" class="form-control inputmask-data" id="txtDataInicial" name="txtDataInicial" placeholder="Período inicial" value="<?='01'.date('/m/Y')?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						<small class="msg-erro text-danger"></small>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group form-group-sm">
						<label for="txtDataFinal" class="control-label">Data final</label>
						<div class="input-group date datetimepicker-data">
							<input type="text" class="form-control inputmask-data" id="txtDataFinal" name="txtDataFinal" placeholder="Data final" value="<?=date('d/m/Y')?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						<small class="msg-erro text-danger"></small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group form-group-sm">
					    <label for="txtPeriodicidade" class="control-label">Períodicidade</label>
						<select class="select2" id="txtPeriodicidade" name="txtPeriodicidade">
							<option value="diario">Diário</option>
							<option value="semanal">Semanal</option>
							<option value="mensal">Mensal</option>
							<option value="anual">Anual</option>
						</select>
					    <small class="msg-erro text-danger"></small>
					</div>
				</div>
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
		<div class="col-md-6">
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
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtTipoUsuario" class="control-label">Tipo de usuário</label>
				<select class="select2" id="txtTipoUsuario" name="txtTipoUsuario" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('atendimentos.tipousuario')?>">
					<option value="">&nbsp;</option>
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
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtTipoAtendimento" class="control-label">Tipo de atendimento</label>
				<select class="select2" id="txtTipoAtendimento" name="txtTipoAtendimento" data-field-db="<?=sha1('atendimentos.atendimentotipo_id')?>">
					<option value="">&nbsp;</option>
					<option value="1">Geral</option>
				</select>
				<script type="text/javascript">
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
					});
				</script>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtAtendente" class="control-label">Atendente</label>
				<select class="select2" id="txtAtendente" name="txtAtendente">
					<option value="">&nbsp;</option>
				</select>
				<script type="text/javascript">
					$(function()
					{
						$.ajax(
						{
							url: base_url+'relatorios/get_atendentes',
							type: 'POST',
							dataType: 'json',
							success: function(data)
							{
								$.each(data.dados, function(index, val)
								{
									$('#txtAtendente').append('<option value="'+val.id+'">'+val.nome+'</option>');
								});
							}
						});
					});
				</script>
			</div>
		</div>
<!-- 		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtAtendente" class="control-label">Atendente</label>
				<input type="text" class="form-control" id="txtAtendente" name="txtAtendente" placeholder="Atendente">
				<small class="msg-erro text-danger"></small>
			</div>
		</div> -->
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtAssunto" class="control-label">Assunto</label>
				<input type="text" class="form-control" id="txtAssunto" name="txtAssunto" placeholder="Assunto">
				<small class="msg-erro text-danger"></small>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<label for="txtStatus" class="control-label">Status</label>
				<select class="select2" id="txtStatus" name="txtStatus" data-minimum-results-for-search="Infinity" data-field-db="<?=sha1('atendimentos.status')?>">
					<option value="">&nbsp;</option>
					<option value="concluido">Concluído</option>
					<option value="pendente">Pendente</option>
				</select>
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
				<button type="submit" class="btn btn-primary" onclick="loading_chart()"><i class="fa fa-save"></i>&nbsp;Gerar relatório</button>
			</div>
		</div>
	</div>
<?=form_close()?>