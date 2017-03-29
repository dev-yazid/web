/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
config.toolbar = [
	
	{  items: [ 'Bold', 'Italic', 'Strike'] },
	{  items: [  'BulletedList','NumberedList', 'HorizontalRule',  'Blockquote',  'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
	{  items: [ 'Link', 'Unlink' ] },
        {  items: [ 'Source' ] },
	{  items: [  'Format' ] },
	{  items: [ 'Underline','JustifyBlock','TextColor','PasteText','SpecialChar','Outdent', 'Indent','Undo', 'Redo' ] },
    ];
};
CKEDITOR.config.allowedContent = true; 