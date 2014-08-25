// noConflict Wrapper for jQuery loads
jQuery(function($){

    $(document).ready( function($) {
        $('#wpadz_end_date').datetimepicker();
    });

    /* ----------------------------------------------------------- */
    /*  1. UPLOAD IMAGE
    /* ----------------------------------------------------------- */

    $(document).ready( function($) {

        var adzonia_uploader;

        $('#nano-ad-image').click( function(e) {
                e.preventDefault();

                //if the uploader object has already been created, reopen the dialog
                if( adzonia_uploader ) {
                    adzonia_uploader.open();
                    return;
                }

                //extend the wp.media object
                adzonia_uploader = wp.media.frames.file_frame = wp.media( {
                    title:"Choose Image",
                    button:{
                        text: "Choose Image"
                    },
                    multiple: false
                } );

                //when a file is selected, grab the URL and set it as the text field's value
                adzonia_uploader.on( 'select', function() {
                    attachment = adzonia_uploader.state().get('selection').first().toJSON();
                    $('#wpadz_ad_image').val(attachment.url);
                });

            //Open the uploader dialog
            adzonia_uploader.open();

        })

    });

});