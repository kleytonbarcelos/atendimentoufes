<form action="<?=base_url()?>rotinas/pesquisar" id="formPesquisar" role="form" method="post" accept-charset="utf-8">
	<div class="input-group stylish-input-group">
		<input type="text" class="form-control input-lg" name="search" id="search" data-timer="" placeholder="Pesquisar...">
		<span class="input-group-addon">
			<button type="submit">
				<span class="glyphicon glyphicon-search"></span>
			</button>  
		</span>
	</div>
</form>
<br>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Rotinas administrativas</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="modal fade" id="modalDadosRotina">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-body conteudo-rotina">

							</div>
						</div>
					</div>
				</div>
				<div class="optiscroll" style="height: 300px;margin-right: 20px;">
				<div class="list-group resultado_pesquisa">
					<script type="text/javascript">
						function get_rotinas()
						{
							$.ajax(
							{
								url: base_url+'rotinas/rotinassetores',
								type: 'POST',
								dataType: 'json',
								//data: 'idsetores=',
								success: function(data)
								{
									html = '';
									$.each(data.rotinas, function(index, val)
									{
										html += ''+
										'<div class="list-group-item font-10 cursor-pointer bnt-visualizar-rotina" data-id="'+val.id+'" data-toggle="modal" href="#modalDadosRotina">'+
											'<a>'+val.nome+'</a>'+
										'</div>'+
										'';
									});
									$('.resultado_pesquisa').html( html );
								}
							});
						}
						get_rotinas();
					</script>
				</div>
				</div>
				<script type="text/javascript">
					var tempo = null;
					function submit_search(search)
					{
						$.ajax(
						{
							url: base_url+'rotinas/pesquisar',
							type: 'POST',
							dataType: 'json',
							data: 'search='+search,
						})
						.done(function(data)
						{
							html = '';
							$.each(data.rotinas, function(index, val)
							{
								html += ''+
										'<div class="list-group-item">'+
											+val.nome+
											'<span class="pull-right">'+
												'<a class="btn btn-default btn-xs font-10 bnt-visualizar-rotina" data-toggle="modal" data-id="'+val.id+'" href="#modalDadosRotina">visualizar</a>'+
											'</span>'+
										'</div>'+
										'';
							});
							html = '<div class="list-group">'+html+'</div>';
							$('.resultado_pesquisa').html( html );
						})
						.fail(function()
						{

						});
					}
					$(function()
					{
						$('#formPesquisar').on('submit', function(event)
						{
							event.preventDefault();
							submit_search( $('#search').val() );
						});
						function clear()
						{
							$('.resultado_pesquisa').html('');
							//get_rotinas();
						}
						$('#search').keyup(function()
						{
							clearTimeout(tempo);
							var search = $(this).val();

							if ( search.length == 0 )
							{
								clear();
								get_rotinas();
								return false;
							}
							if (search.length >= 3)
							{
								clear();
								tempo = setTimeout(function()
								{
									console.log(search);
									submit_search(search);
								}, 500);
							}
						});
					});
				</script>
			</div>
		</div>
	</div>
</div>