<div class="row">
	<div class="col-md-12 dadosvisualizacao">
		<h4><i class="fa fa-tags"></i> <strong>Atendimento</strong></h4>
		<br>
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td><strong>Setor</strong></td>
					<td><?=$setor->nome?></td>
				</tr>
				<tr>
					<td width="250"><strong>Usuário</strong></td>
					<td><?=$dados->nome?></td>
				</tr>
				<tr>
					<td width="250"><strong>Tipo Usuário</strong></td>
					<td>
						<span class="label label-info">
						<?php
							if($dados->tipousuario=='Discente'){echo 'DISCENTE';}
							if($dados->tipousuario=='docente'){echo 'DOCENTE';}
							if($dados->tipousuario=='tecnicoadministrativo'){echo 'TÉCNICO ADMINISTRATIVO';}
							if($dados->tipousuario=='outro'){echo 'OUTRO';}
						?>
						</span>
					</td>
				</tr>
				<tr>
					<td><strong>Tipo de atendimento</strong></td>
					<td><?=$tipo->nome?></td>
				</tr>
				<tr>
					<td><strong>Assunto</strong></td>
					<td><?=$dados->assunto?></td>
				</tr>
				<tr>
					<td><strong>Descrição</strong></td>
					<td><?=$dados->descricao?></td>
				</tr>
				<tr>
					<td><strong>Tempo total do atendimento</strong></td>
					<td class="time"><?=$dados->time?></td>
					<script type="text/javascript">
						$(function()
						{
							setTimeout(function()
							{
								$('.time').text( moment.utc( $('.time').text() * 1000 ).format('HH:mm:ss') );
							}, 500);
						});
					</script>
				</tr>
				<tr>
					<td><strong>Data</strong></td>
					<td><?=datetime_to_br($dados->data)?></td>
				</tr>
				<tr>
					<td><strong>Data encerramento</strong></td>
					<td>
						<?php
							if($dados->data_encerramento)
							{
								echo datetime_to_br($dados->data_encerramento);
							}
						?>
					</td>
				</tr>
				<tr>
					<td><strong>Status</strong></td>
					<td>
						<?php
							if($dados->status=='concluido')
							{
								echo '<span class="label label-success text-uppercase">'.$dados->status.'</span>';
							}
							else
							{
								echo '<span class="label label-warning text-uppercase">'.$dados->status.'</span>';
							}
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<hr>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title hand" onclick="$('.table_historico').toggle('slow');"><i class="fa fa-list"></i> Histórico de atividades</h3>
	</div>
	<table class="table table-hover table-striped table-bordered table-condensed none table_historico">
		<thead>
			<tr>
				<th class="col-md-6">Atendente</th>
				<th class="col-md-3 text-center">Ação</th>
				<th class="col-md-3 text-center">Data</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($logs as $log)
				{
					echo '
					<tr>
						<td>'.$log->atendente.'</td>
						<td class="text-center">'.$log->acao.'</td>
						<td class="text-center">'.date_to_br($log->data).' as '.time_to_br($log->data).'</td>
					</tr>';
				}
			?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="btn-group">
			<button onclick="window.history.go(-1)" class="btn btn-sm"><i class="fa fa-reply"></i> Voltar</button>
			<button onclick="window.location.href=base_url_controller+'editar/'+$.md5(<?=$dados->id?>)" class="btn btn-sm"><span class="glyphicon glyphicon-edit"></span> Editar</button>
			<button onclick="$.print('.dadosvisualizacao');" class="btn btn-sm"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
		</div>
	</div>
</div>