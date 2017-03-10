// noConflict Wrapper for jQuery loads
jQuery(function($){

    /* ----------------------------------------------------------- */
    /*  00. WordPress Media Uploader for fields - reusable
    /*
    /*      @since 2.0.0    Initiated reusable code
    /* ----------------------------------------------------------- */
    function _nano_media_image_uploader_func( e, img_id_field, lib_head, btn_text, prev_img_wrap, prev_img, btn ){

        var nano_field_image_uploader;

        e.preventDefault();

        //if the uploader object has already been created, reopen the dialog
        if( nano_field_image_uploader ) {
            nano_field_image_uploader.open();
            return;
        }

        //extend the wp.media object
        nano_field_image_uploader = wp.media.frames.file_frame = wp.media( {
            title: lib_head,
            button:{
                text: btn_text
            },
            multiple: false
        } );

        //when a file is selected, grab the URL and set it as the text field's value
        nano_field_image_uploader.on( 'select', function() {
            attachment = nano_field_image_uploader.state().get('selection').first().toJSON();
            img_id_field.val(attachment.id).attr( 'value', attachment.id );
            prev_img.attr( 'src', attachment.url );
            prev_img_wrap.show();
            btn.addClass('has-image');
        });

        //Open the uploader dialog
        nano_field_image_uploader.open();
    }

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
    /*  05. UPLOAD AD IMAGE
    /*
    /*      @since 1.2.0 Initiated.
    /*      @since 2.0.0 Updated with reUsable function.
    /* ----------------------------------------------------------- */
    var image_input_btn         = $('#grm-banner-mini-input'),
        image_close_btn         = $('#close-offer-banner-mini'),
        image_id_field          = $('#grm_banner_mini'),
        image_preview           = $('#banner-mini-preview'),
        image_preview_holder    = $('.grm-offer-banner-mini-preview');
    
    image_input_btn.click(function(e){
        var lib_head = adzonia.img_lib_head,
            btn_text = adzonia.img_btn_text;

        _nano_media_image_uploader_func( e, image_id_field, lib_head, btn_text, image_preview_holder, image_preview, image_input_btn );
    });

    image_close_btn.on('click', function() {
        image_id_field.val('');
        image_preview_holder.hide();
        image_input_btn.removeClass('has-image');
    });

});
