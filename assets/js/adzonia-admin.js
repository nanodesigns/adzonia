/**!
 * AdZonia Admin Scripts
 * Scripts to decorate/manipulate in AdZonia admin-end.
 *
 * @author  nanodesigns
 * @package AdZonia
 */
jQuery(document).ready(function($) {

    /* ----------------------------------------------------------- */
    /*  WordPress Media Uploader for fields - reusable
    /*
    /*  @since 2.0.0    Initiated reusable code.
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
    /*  1. TRIGGER THE DATE-PICKER
    /*
    /*     @since 1.0.0 Introduced with datetimepicker.
    /*     @since 2.0.0 Modified things with core datepicker.
    /* ----------------------------------------------------------- */
    var adz_end_date_field = $('#adz-end-date');

    adz_end_date_field.datepicker();

    $('#call-adz-end-date').on('click', function() {
        adz_end_date_field.datepicker('show');
    });


    /* ----------------------------------------------------------- */
    /*  2. MANAGE TOGGLE BUTTONS AND RESPECTIVE FIELDS
    /*
    /*     @since 2.0.0 Changed code to trigger things on radio buttons.
    /* ----------------------------------------------------------- */
    var ad_type_chooser         = $('[name="_adz_ad_type"]'),
        ad_type_choice          = ad_type_chooser.filter(':checked').val(),
        conditional_to_image_ad = $('.adz-conditional-to-image-ad'),
        conditional_to_code_ad  = $('.adz-conditional-to-code-ad'),
        image_ad_fields         = conditional_to_image_ad.find('.adz-form-control'),
        code_ad_fields          = conditional_to_code_ad.find('.adz-form-control'),
        adz_ad_code             = $('#adz-ad-code'),
        adz_ad_image            = $('#adz-ad-image'),
        data_toggle_buttons     = $('[data-toggle="buttons"] .adz-btn');

    // Function to handle image fields.
    var _image_fields = function() {
        // load panel
        conditional_to_image_ad.show();
        conditional_to_code_ad.hide();
        // empty other panel's fields
        code_ad_fields.val('').attr('value', '');
        // set/unset required fields
        adz_ad_image.prop('required', true);
        adz_ad_code.removeAttr('required');
    };

    // Function to handle code fields.
    var _code_fields = function() {
        // load panel
        conditional_to_code_ad.show();
        conditional_to_image_ad.hide();
        // empty other panel's fields
        image_ad_fields.val('').attr('value', '');
        // set/unset required fields
        adz_ad_image.removeAttr('required');
        adz_ad_code.prop('required', true);
    };

    // remove current active state of the toggle buttons.
    ad_type_chooser.parent('.adz-btn').removeClass('active');

    if( 'image_ad' === ad_type_choice ) {
        ad_type_chooser.filter(':checked').parent('.adz-btn').addClass('active');
        _image_fields();
    } else if( 'code_ad' === ad_type_choice ) {
        ad_type_chooser.filter(':checked').parent('.adz-btn').addClass('active');
        _code_fields();
    }

    // Load fields based on toggle button selections.
    $('[name="_adz_ad_type"]').on('change', function() {
        var this_item   = $(this),
            this_val    = this_item.val();

        // Image Ad
        if( 'image_ad' === this_val ) {
            // make toggle button active/inactive
            data_toggle_buttons.toggleClass('active');
            // load image fields
            _image_fields();
        }
        // Code Ad
        else if( 'code_ad' === this_val ) {
            // make toggle button active/inactive
            data_toggle_buttons.toggleClass('active');
            // load code fields
            _code_fields();   
        }
    });


    /* ----------------------------------------------------------- */
    /*  3. FORM SUBMIT VALIDATION
    /*
    /*     @since 2.0.0 Strings made translation-ready.
    /* ----------------------------------------------------------- */
    $('form#post').submit(function () {
        var img_ad  = $.trim(adz_ad_image.val()),
            code_ad = $.trim(adz_ad_code.val());

        // Don't allow both the fields are empty.
        if( img_ad === '' && code_ad === '' ) {
            alert( adzonia.msg_both_empty );
            return false;
        }
        // Don't allow both the fields are filled.
        if( img_ad !== '' && code_ad !== '' ) {
            alert( adzonia.msg_both_filled );
            return false;   
        }
    });


    /* ----------------------------------------------------------- */
    /*  04. UPLOAD AD IMAGE
    /*
    /*      @since 1.2.0 Initiated.
    /*      @since 2.0.0 Updated with reUsable function.
    /* ----------------------------------------------------------- */
    var image_input_btn         = $('#adz-image-btn'),
        image_close_btn         = $('#close-ad-image'),
        image_id_field          = $('#adz-ad-image'),
        image_preview           = $('#ad-image-preview'),
        image_preview_holder    = $('.ad-image-preview');
    
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
