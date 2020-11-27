CKEDITOR.plugins.add( 'contactusPopUp',
{
	init: function( editor )
	{
        var base_url = window.location.origin; 
		//Plugin logic goes here.
        editor.addCommand( 'insertContactusLink',
    	{
    	   /*
    		exec : function( editor )
    		{    
    			var timestamp = new Date();
    			editor.insertHtml( 'The current date and time is: <em>' + timestamp.toString() + '</em>' );
    		}
            */
            
            exec : function( editor )
            { 
                var selectedText = editor.getSelection().getSelectedText();
                // selectedText = encodeURIComponent(selectedText);
                if(selectedText == ''){
                	selectedText = 'click here';
                }
                var HTML = '';
                HTML = HTML + '<a class="contactPop" href="javascript:void(0)">'+selectedText+'</a>';
                editor.insertHtml( HTML ); 
            }
    	});
        
        editor.ui.addButton( 'ContactUs',
        {
        	label: 'Contact-Us link',
        	command: 'insertContactusLink',
        	icon: this.path + 'images/icon.png'
        } );
	}
});