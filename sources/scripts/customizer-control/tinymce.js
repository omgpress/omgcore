export default function initTinyMCE() {
	$( document ).on( 'tinymce-editor-init', function( event, editor ) {
		editor.on( 'change', function() {
			tinyMCE.triggerSave();

			$( '#' . concat( editor.id ) ).trigger( 'change' );
		});
	});

	$( '.wpt-tinymce' ).each( function() {
		const tinyMCEToolbar1 = wp.customize.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_toolbar1;
		const tinyMCEToolbar2 = wp.customize.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_toolbar2;
		const tinyMCEMediaButtons = wp.customize.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_mediabuttons;
		const tinyMCEHeight = wp.customize.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_height;

		wp.editor.initialize( $( this ).attr( 'id' ), {
			tinymce: {
				wpautop: true,
				toolbar1: tinyMCEToolbar1,
				toolbar2: tinyMCEToolbar2,
				height: tinyMCEHeight
			},
			quicktags: true,
			mediaButtons: tinyMCEMediaButtons
		});
	});
}
