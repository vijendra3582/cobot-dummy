CKEDITOR.plugins.add( 'advisorpopup',
{
	init: function( editor )
	{ 
		editor.addCommand( 'advisorpopupDialog', new CKEDITOR.dialogCommand( 'advisorpopupDialog' ) );
 
		editor.ui.addButton( 'Popup',
		{
			label: 'Insert a Advisor Site Popup',
			command: 'advisorpopupDialog',
			icon: this.path + 'images/icon.png'
		} );
 
		CKEDITOR.dialog.add( 'advisorpopupDialog', function( editor )
		{
			return {
				title : 'Advisor Site Popup',
				minWidth : 400,
				minHeight : 200,
				contents :
				[
					{
						id : 'general',
						label : 'Settings',
						elements :
						[
							{
								type : 'html',
								html : 'This dialog window lets you add advisor site popup'		
							},
							{
								type : 'text',
								id : 'url',
								label : 'URL',
								validate : CKEDITOR.dialog.validate.notEmpty( 'URL cannot be empty.' ),
								required : true,
								commit : function( data )
								{
									data.url = this.getValue();
								}
							},
							{
								type : 'text',
								id : 'linktitle',
								label : 'Link Title',
								validate : CKEDITOR.dialog.validate.notEmpty( 'Link Title cannot be blank' ),
								required : true,
								commit : function( data )
								{
									data.linktitle = this.getValue();
								}
							}  
						]
					}
				],
				onOk : function()
				{
				    var dialog = this, data = {};
					this.commitContent( data );
                    
				    var HTML = '';
				    HTML = HTML + '<a class="ext" data-src="advPop" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});
	}
});