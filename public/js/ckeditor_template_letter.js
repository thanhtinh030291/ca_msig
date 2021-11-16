
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
	config.extraPlugins = 'font';
	config.removePlugins = 'easyimage, cloudservices';
	config.removeButtons = 'HiddenField,ImageButton,Button,Select,Textarea,TextField,CopyFormatting,RemoveFormat,Link,Unlink,Anchor,Flash,Smiley,Iframe,About';
};

CKEDITOR.plugins.add( 'addButton', {
    icons: 'addButton',
    init: function( editor ) {
        editor.addCommand( 'insert1', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$applicantName]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext1', {
			label: 'applicantName',
			command: 'insert1',
			icon: null,
		});
		//1

		editor.addCommand( 'insert2', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$IOPDiag]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext2', {
			label: 'IOPDiag',
			command: 'insert2',
			icon: null,
		});
		//1
		editor.addCommand( 'insert3', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$PRefNo]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext3', {
			label: 'PRefNo',
			command: 'insert3',
			icon: null,
		});
		//3
		editor.addCommand( 'insert4', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$PhName]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext4', {
			label: 'PhName',
			command: 'insert4',
			icon: null,
		});
		//
		editor.addCommand( 'insert5', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$memberNameCap]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext5', {
			label: 'memberNameCap',
			command: 'insert5',
			icon: null,
		});
		//
		editor.addCommand( 'insert6', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$ltrDate]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext6', {
			label: 'ltrDate',
			command: 'insert6',
			icon: null,
		});
		//
		editor.addCommand( 'insert7', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$pstAmt]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext7', {
			label: 'pstAmt',
			command: 'insert7',
			icon: null,
		});
		//
		editor.addCommand( 'insert8', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$apvAmt]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext8', {
			label: 'apvAmt',
			command: 'insert8',
			icon: null,
		});
		//
		editor.addCommand( 'insert9', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$payMethod]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext9', {
			label: 'Insert Timestamp',
			command: 'insert9',
			icon: null,
		});
		//
		editor.addCommand( 'insert10', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$deniedAmt]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext10', {
			label: 'deniedAmt',
			command: 'insert10',
			icon: null,
		});
		//
		editor.addCommand( 'insert11', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$CSRRemark]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext11', {
			label: 'CSRRemark',
			command: 'insert11',
			icon: null,
		});
		//
		editor.addCommand( 'insert12', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$TermRemark]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext12', {
			label: 'TermRemark',
			command: 'insert12',
			icon: null,
		});
		//
		editor.addCommand( 'insert13', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$tableInfoPayment]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext13', {
			label: 'TermRemark',
			command: 'insert13',
			icon: null,
		});
		//
		editor.addCommand( 'insert14', {
			exec: function( editor ) {
				
				editor.insertHtml( '&nbsp;[[$benefitOfClaim]]&nbsp;' );
			}
		});
		editor.ui.addButton( 'buttontext14', {
			label: 'TermRemark',
			command: 'insert14',
			icon: null,
		});
		//
    }
});

