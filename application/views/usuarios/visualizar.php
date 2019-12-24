<div class="row">
	<div class="col-md-12 dadosvisualizacao">
		<h4><i class="fa fa-tags"></i> <strong>Usuário</strong></h4>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td width="150"><strong>Nome</strong></td>
					<td><?=$dados->nome?></td>
				</tr>
				<tr>
					<td><strong>Usuário</strong></td>
					<td><?=$dados->usuario?></td>
				</tr>
				<tr>
					<td><strong>E-mail</strong></td>
					<td><?=$dados->email?></td>
				</tr>
				<tr>
					<td><strong>Mat. Siape</strong></td>
					<td><?=$dados->matsiape?></td>
				</tr>
				<tr>
					<td><strong>Mat. Grad.</strong></td>
					<td><?=$dados->matgrad?></td>
				</tr>
				<tr>
					<td><strong>Status</strong></td>
					<td><?=$dados->status?></td>
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