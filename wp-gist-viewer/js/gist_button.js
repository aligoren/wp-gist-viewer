( function() {
	tinymce.PluginManager.add( 'gist_add', function( editor, url ) {
		
		// Add a button that opens a window
		editor.addButton( 'gist_add_open_button', {
			
			text: false,
			title: 'Add Gist Code',
			icon: false,
			image: url + '/img/gist.png',
			onclick: function() {
				// Open window
				editor.windowManager.open( {
					title: 'Add Gist',
					body: [{
						type: 'textbox',
						name: 'gist_id',
						label: 'Gist ID:'
					}],
					onsubmit: function( e ) {
						// Insert content when the window form is submitted
						
						if(e.data.gist_id == '') {
							tinyMCE.activeEditor.windowManager.alert('Gist Source Can Not Be Blank!');
							tinymce.get('gist_add_open_button').getContent();
						} else {
							editor.insertContent( '[gist source=' + e.data.gist_id+']' );
						}
					}
					
				} );
			}
			
		} );
		
	} );
	
} )();