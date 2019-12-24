// ##########################################################################
// BOOTSTRAP-TABLE (Início)
window.icons =
{
	refresh: 'fa-refresh',
	toggle: 'fa-toggle-on',
	columns: 'fa-th-list',
	export: 'fa-download',
};
setTimeout(function()
{
	//$('.fixed-table-toolbar').find('.caret').remove();
}, 500);
// BOOTSTRAP-TABLE (fim)
// ##########################################################################
$(function()
{
	$('#checkAll').click(function()
	{
		$('.bootstrap-table').bootstrapTable('togglePagination').bootstrapTable('checkAll').bootstrapTable('togglePagination');
	});
	$('#uncheckAll').click(function()
	{
		$('.bootstrap-table').bootstrapTable('togglePagination').bootstrapTable('uncheckAll').bootstrapTable('togglePagination');
	});
	//$('.data').inputmask('dd/mm/yyyy', { "placeholder": "dd/mm/aaaa" });
});