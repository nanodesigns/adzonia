// noConflict Wrapper for jQuery loads
jQuery(function($){

    /* ----------------------------------------------------------- */
    /*  0. HIDE SOMETHING WHEN js IS ENABLED
    /* ----------------------------------------------------------- */
    $('p.non-js-directions').hide();

    /* ----------------------------------------------------------- */
    /*  1. TRIGGER THE DATE TIME PICKER
    /* ----------------------------------------------------------- */
    $('#wpadz_end_date').datetimepicker();

    /* ----------------------------------------------------------- */
    /*  2. TRIGGER TABS
    /* ----------------------------------------------------------- */
    $('#adzonia-tabs').tabs();

    /* ----------------------------------------------------------- */
    /*  3. FORM SUBMIT VALIDATION
    /* ----------------------------------------------------------- */
    $('form#post').submit(function () {
        var img_ad = $.trim($('#wpadz_ad_image').val());
        var code_ad = $.trim($('#wpadz_ad_code').val());

        //don't allow both the fields are empty
        if( img_ad === '' && code_ad === '' ) {
            alert( 'Oops! Both image and code fields are empty' );
            return false;
        }
        //don't allow both the fields are filled
        if( img_ad !== '' && code_ad !== '' ) {
            alert( 'Oops! You can\'t fill both Ad Image and Code field!' );
            return false;   
        }
    });

    /* ----------------------------------------------------------- */
    /*  4. LOAD IMAGE AD BLOCK or CODE AD BLOCK (only on edit page)
    /* ----------------------------------------------------------- */
    if( typeof( adzonia ) != 'undefined' && adzonia !== null ) {
        if( adzonia.is_img_ad !== '' ) {
            $('#code-ad-tab-content').hide();
        } else if( adzonia.is_code_ad !== '' ) {
            $('#image-ad-tab-content').hide();
        }
    }

    /* ----------------------------------------------------------- */
    /*  5. UPLOAD IMAGE
    /* ----------------------------------------------------------- */
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
                title:"Choose Ad Image",
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

    });

});