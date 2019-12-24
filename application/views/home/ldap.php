<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icone"></span> Preenchendo banco de dados - LDAP (ufes)</h3>
			</div>
			<div class="panel-body">
				<br>
				<a class="btn btn-success btn_cadastra_usuarios_ldap"><i class="fa fa-play fa-fw"></i> Iniciar</a>
				<div class="progress barra_carregamento display-none">
					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
						<!-- <span class="sr-only"> % Completo</span> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var pg = 1;
	$(function()
	{
		$('.btn_cadastra_usuarios_ldap').click(function(event)
		{
			$(this).hide();
			$('.barra_carregamento').show();
			$('.icone').html('<i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;');
			cron();
		});
	});
	function cron()
	{
		$.ajax(
		{
			type: 'POST',
			url: base_url_controller+'salva_ldap/'+pg,
			dataType: 'json',
			success: function(data)
			{
				if(data.status == 'processando')
				{
					pg = data.pg;
					total_pg = data.total_pg;
					//########################################################################################
					porcentagem = (parseInt(pg) * 100) / parseInt(total_pg);
					$('.progress-bar').css({'width':porcentagem+'%'}).data('aria-valuenow', porcentagem).html( Math.ceil(porcentagem) +'%');

					if( pg == total_pg )
					{
						$('.icone').html('<i class="fa fa-check"></i>&nbsp;&nbsp;');
						$('.progress-bar').removeClass('active');
					}
					else
					{
						cron();
					}
				}
				else
				{
					console.log('100% CONCLUÍDO!!!');
				}
			}
		});
	}
</script>