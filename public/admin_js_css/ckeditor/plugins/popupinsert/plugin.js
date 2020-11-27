CKEDITOR.plugins.add('popupinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		/*** advisor dialog ****/
		editor.addCommand( 'advisorPopup', new CKEDITOR.dialogCommand('advisorPopup') );
		CKEDITOR.dialog.add( 'advisorPopup', function( editor )
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


		/********* external popup ***********/
		 
		editor.addCommand( 'externalPopup', new CKEDITOR.dialogCommand('externalPopup') );
		CKEDITOR.dialog.add( 'externalPopup', function( editor )
		{
			return {
				title : 'External Site Popup',
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
								html : 'This dialog window lets you add external site popup'		
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
				    HTML = HTML + '<a class="ext" data-src="extPop" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});


		/********* news popup ***********/
		 
		editor.addCommand( 'newsPopup', new CKEDITOR.dialogCommand('newsPopup') );
		CKEDITOR.dialog.add( 'newsPopup', function( editor )
		{
			return {
				title : 'News Site Popup',
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
								html : 'This dialog window lets you add news site popup'		
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
				    HTML = HTML + '<a class="ext" data-src="newsPop" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});


		/********* news popup ***********/
		 
		editor.addCommand( 'facebookPopup', new CKEDITOR.dialogCommand('facebookPopup') );
		CKEDITOR.dialog.add( 'facebookPopup', function( editor )
		{
			return {
				title : 'facebook Site Popup',
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
								html : 'This dialog window lets you add facebook site popup'		
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
				    HTML = HTML + '<a class="ext" data-src="socialPop" data-title="facebook" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});

		/********* twitter popup ***********/
		 
		editor.addCommand( 'twitterPopup', new CKEDITOR.dialogCommand('twitterPopup') );
		CKEDITOR.dialog.add( 'twitterPopup', function( editor )
		{
			return {
				title : 'twitter Site Popup',
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
								html : 'This dialog window lets you add twitter site popup'		
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
				    HTML = HTML + '<a class="ext" data-src="socialPop" data-title="twitter" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});

		/********* Linkedin popup ***********/
		 
		editor.addCommand( 'linkedInPopup', new CKEDITOR.dialogCommand('linkedInPopup') );
		CKEDITOR.dialog.add( 'linkedInPopup', function( editor )
		{
			return {
				title : 'LinkedIn Site Popup',
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
								html : 'This dialog window lets you add linkedIn site popup'		
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
				    HTML = HTML + '<a class="ext" data-src="socialPop" data-title="linkedIn" href="' + data.url + '"><strong><em><u>' + data.linktitle + '</u></em></strong></a>';
                    editor.insertHtml( HTML );    
				}
			};
		});




		// array of strings to choose from that'll be inserted into the editor
		var strings = [];
		strings.push(['advisor_site', 'Advisor Site Popup', 'Advisor Site Popup']);
		strings.push(['external_site', 'External Site Popup', 'External Site Popup']);
		strings.push(['news_site', 'News Site Popup', 'News Site Popup']);
		strings.push(['facebook_site', 'Facebook Site Popup', 'Facebook Site Popup']);
		strings.push(['twitter_site', 'Twitter Site Popup', 'Twitter Site Popup']);
		strings.push(['linkedin_site', 'LinkedIn Site Popup', 'LinkedIn Site Popup']);

		// add the menu to the editor
		editor.ui.addRichCombo('popupinsert',
		{
			label: 'Popups',
			title: 'Site Popups',
			voiceLabel: 'Insert Site Popup',
			className: 'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Site Popups" );
				for (var i in strings)
				{
					this.add(strings[i][0], strings[i][1], strings[i][2],strings[i][3],strings[i][4],strings[i][5]);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				// editor.fire( 'saveSnapshot' );
				//alert(value);
				// editor.insertHtml(value);
				// editor.fire( 'saveSnapshot' );
				if(value == 'advisor_site'){
					editor.execCommand('advisorPopup');
				}
				if(value == 'external_site'){
					editor.execCommand('externalPopup');
				}
				if(value == 'news_site'){
					editor.execCommand('newsPopup');
				}
				if(value == 'facebook_site'){
					editor.execCommand('facebookPopup');
				}
				if(value == 'twitter_site'){
					editor.execCommand('twitterPopup');
				}
				if(value == 'linkedin_site'){
					editor.execCommand('linkedInPopup');
				}




			}
		});
	}
});

