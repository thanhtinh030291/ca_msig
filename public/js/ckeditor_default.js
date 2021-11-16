
CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
	config.removePlugins = 'easyimage, cloudservices';
	config.extraPlugins = 'addButton';
	config.removeButtons = 'Source,HiddenField,ImageButton,Button,Select,Textarea,TextField,CopyFormatting,RemoveFormat,Link,Unlink,Anchor,Flash,Smiley,Iframe,About';
};
CKEDITOR.plugins.add( 'addButton', {
    icons: 'addButton',
    init: function( editor ) {
        editor.addCommand( 'insert1', {
			exec: function( editor ) {
				editor.insertHtml( '&nbsp;<div style="width:340px ; border: 3px solid red; color:red ; background-color: #d4d4d4"> Enter text here...</div> &nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext1', {
			label: 'Note',
			command: 'insert1',
			icon: null,
		});
		//1
		
    }
});