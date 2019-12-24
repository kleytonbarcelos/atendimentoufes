$(function()
{
	$('.moeda').priceFormat(
	{
		prefix: '',
		centsSeparator: ',',
		thousandsSeparator: '.'
	});
	$('body').on('focus', '.moeda', function(event)
	{
		$(this).priceFormat(
		{
			prefix: '',
			centsSeparator: ',',
			thousandsSeparator: '.'
		});
	});
});