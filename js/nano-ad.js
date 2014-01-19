jQuery(document).ready( function($) {

    var custom_uploader;

    $('#nano-ad-image').click( function(e) {
            e.preventDefault();

            //if the uploader object has already been created, reopen the dialog
            if( custom_uploader ) {
                custom_uploader.open();
                return;
            }

            //extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media( {
                title:"Choose Image",
                button:{
                    text: "Choose Image"
                },
                multiple: false
            } );

            //when a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on( 'select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#nano-ad-image-url').val(attachment.url);
            });

        //Open the uploader dialog
        custom_uploader.open();

        })

    })