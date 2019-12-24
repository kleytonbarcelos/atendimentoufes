$(function()
{
	$('body').on('test', '.quantidade', function()
	{
		//console.log('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
		$(this).TouchSpin();
	});
	$('body').trigger('test');
});