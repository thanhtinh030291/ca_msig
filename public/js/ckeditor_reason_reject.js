
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
	config.extraPlugins = 'addButton';
	config.removePlugins = 'easyimage, cloudservices';
	config.removeButtons = 'Source,HiddenField,ImageButton,Button,Select,Textarea,TextField,CopyFormatting,RemoveFormat,Link,Unlink,Anchor,Flash,Smiley,Iframe,About';
};

CKEDITOR.plugins.add( 'addButton', {
    icons: 'addButton',
    init: function( editor ) {
        editor.addCommand( 'insert1', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[##nameItem##]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext1', {
			label: 'nameItem',
			command: 'insert1',
			icon: null,
		});
		//1

		editor.addCommand( 'insert2', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[##amountItem##]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext2', {
			label: 'amountItem',
			command: 'insert2',
			icon: null,
		});
		//1
		editor.addCommand( 'insert3', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[##Date##]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext3', {
			label: 'Date',
			command: 'insert3',
			icon: null,
		});
		//3
		editor.addCommand( 'insert4', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[##Text##]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext4', {
			label: 'Text',
			command: 'insert4',
			icon: null,
		});
		//
		editor.addCommand( 'insert5', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[Begin]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext5', {
			label: 'Begin',
			command: 'insert5',
			icon: null,
		});
		//
		editor.addCommand( 'insert6', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[End]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext6', {
			label: 'End',
			command: 'insert6',
			icon: null,
		});
		
    }
});

