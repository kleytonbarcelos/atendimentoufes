$(function()
{
	$(':input').inputmask();
	$('body').on('focus', ':input', function(event)
	{
		$(this).inputmask();
	});

	$('.inputmask-data').inputmask({mask: '99/99/9999'});
	$('body').on('focus', '.inputmask-data', function(event)
	{
		$(this).inputmask({mask: '99/99/9999'});
	});

	$('.inputmask-celular').inputmask({mask: ['(99) 9999-9999', '(99) 99999-9999']});
	$('body').on('focus', '.inputmask-celular', function(event)
	{
		$(this).inputmask({mask: ['(99) 9999-9999', '(99) 99999-9999']});
	});

	$('.inputmask-telefone').inputmask({mask: '(99) 9999-9999'});
	$('body').on('focus', '.inputmask-telefone', function(event)
	{
		$(this).inputmask({mask: '(99) 9999-9999'});
	});

	$('.inputmask-cpf').inputmask({mask: '999.999.999-99'});
	$('body').on('focus', '.inputmask-cpf', function(event)
	{
		$(this).inputmask({mask: '999.999.999-99'});
	});

	$('.inputmask-cep').inputmask({mask: '99999-999'});
	$('body').on('focus', '.inputmask-cep', function(event)
	{
		$(this).inputmask({mask: '99999-999'});
	});
});