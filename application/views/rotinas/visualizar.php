<div class="row">
	<div class="col-md-12 dadosvisualizacao">
		<h4><i class="fa fa-tags"></i> <strong>Rotina</strong></h4>
		<br>
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td><strong>Setor</strong></td>
					<td><?=$setor->nome?></td>
				</tr>
				<?php
					$mapamental = ($dados->mapamental) ? '<img src="'.base_url().'assets/uploads/'.$dados->mapamental.'" class="img-thumbnail">' : '';
					if($mapamental)
					{
						echo '
							<tr>
								<td><strong>Mapa mental</strong></td>
								<td>'.$mapamental.'</td>
							</tr>
						';
					}
				?>
				<tr>
					<td width="250"><strong>Rotina</strong></td>
					<td><?=$dados->nome?></td>
				</tr>
				<tr>
					<td><strong>Definição</strong></td>
					<td><?=$dados->definicao?></td>
				</tr>
				<tr>
					<td><strong>Tipo</strong></td>
					<td>
						<span class="label label-info">
						<?php
							if($dados->tipo=='protocolado'){echo 'PROTOCOLADO';}
							if($dados->tipo=='processo'){echo 'PROCESSO';}
							if($dados->tipo=='interno'){echo 'INTERNO';}
						?>
						</span>
					</td>
				</tr>
				<?php
					if($dados->tipo!='interno')
					{
						echo '
							<tr>
								<td><strong>Fluxo</strong></td>
								<td>'.$dados->fluxo.'</td>
							</tr>
						';
					}
				?>
				<tr>
					<td><strong>Descrição</strong></td>
					<td><?=$dados->descricao?></td>
				</tr>
			</tbody>
		</table>
	</div>
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