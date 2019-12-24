$(function()
{
	$('body').on('mouseover', '.datetimepicker-data', function(event)
	{
		$(this).datetimepicker(
		{
			format: 'DD/MM/YYYY',
			locale: 'pt-br',
		}).on('dp.show', function(e)
		{
			$(this).children().val('');
		});
	});
});