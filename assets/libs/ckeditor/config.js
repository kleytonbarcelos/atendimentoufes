//################################################################################################
/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

};
$(function()
{
	$('.ckeditor').ckeditor(function()
	{

	},
	{
		filebrowserBrowseUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserImageBrowseUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserUploadUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserImageUploadUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,
		//extraPlugins: 'autogrow',
	});
	$('.ckeditor-basic').ckeditor(function()
	{

	},
	{
		filebrowserBrowseUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserImageBrowseUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserUploadUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&folder_base='+folder_base,
		filebrowserImageUploadUrl: base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&folder_base='+folder_base,

		// filebrowserBrowseUrl : base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		// filebrowserUploadUrl : base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		// filebrowserImageBrowseUrl : base_url+'assets/libs/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',

		//extraPlugins: 'autogrow',
		//skin: 'office2013',
		//removePlugins : 'elementspath',
		//resize_enabled : false,
		toolbar : [
			['Image', 'Bold', 'Italic', 'Underline', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-','Table', '-', 'NumberedList', 'BulletedList', '-', 'Indent', 'Outdent', '-', 'Undo', 'Redo', '-', 'Maximize']
		],
	});
	$('.ckeditor-basic-public').ckeditor(function()
	{

	},
	{
		toolbar : [
			['Bold', 'Italic', 'Underline', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-','Table', '-', 'NumberedList', 'BulletedList', '-', 'Indent', 'Outdent', '-', 'Undo', 'Redo', '-', 'Maximize']
		],
	});
});
//################################################################################################ // FIX para atualizar conteúdo do CKEDITOR antes do submit
CKEDITOR.on('instanceReady', function(event)
{
	var editor = event.editor;
	editor.on('change', function(event)
	{
		// Sync textarea
		this.updateElement();
	});
});
//################################################################################################