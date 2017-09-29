// buddypress messages main js

jQuery( document ).ready( function () {

    //Automatic Update the Textarea when Visual textarea updates
    if(jQuery('body').hasClass("messages") && jQuery('body').hasClass("bp-user")) {

        // jQuery("#send-reply").submit(function(){


        // });
        // jQuery("#send-reply").find("input[type=submit]").focus(function(){
        //     jQuery("#message_content").val(get_tinymce_content("message_content"));
        // });
        // jQuery("#send-reply").find("input[type=submit]").click(function(){
        //     jQuery("#message_content").val(get_tinymce_content("message_content"));
        // });
        //

        window.bbm_visual_editor_event = function(){
            if(tinymce.editors.length > 0) {

                if ( jQuery( "#wp-message_content-wrap" ).hasClass( "tmce-active" ) ) {

                    jQuery( "#wp-message_content-wrap" ).find("iframe").contents().find("body").on("keyup",function(){
                        jQuery("#message_content").val(get_tinymce_content("message_content"));
                    });

                }
            } else {
                setTimeout(function () {window.bbm_visual_editor_event(); },100);
            }
        }

        if( typeof tinymce == 'object' ){
            window.bbm_visual_editor_event();
        }

    }


    // all about magnific popup
    jQuery( '.bbm-modal' ).magnificPopup( {
        type: 'inline',
        preloader: false,
        callbacks: {
            open: function () {
                if ( jQuery( '.popup_error_message' ).length ) {
                    jQuery( '.popup_error_message' ).html( '' );
                }
            }
        }
    } );
    jQuery( document ).on( 'click', '.bbm-modal-cancel', function ( e ) {
        e.preventDefault();
        jQuery.magnificPopup.close();
    } );

    // popup label entry ajax
    jQuery( '.add-bbm-label-details #label_add' ).on( 'click', function () {
        var thread_id = jQuery( '#bbm_current_thread_id' ).val();
        var label_name = jQuery( '.add-bbm-label-details #bbm_label_name' ).val();
        var data = {
            action: 'bbm_label_ajax',
            task: 'add_new_label',
            thread_id: thread_id,
            label_name: label_name
        };
        jQuery.post( ajaxurl, data, function ( response ) {
            var response = jQuery.parseJSON( response );
            jQuery.magnificPopup.close();
            jQuery( '#buddypress #item-body .bp-template-notice' ).remove();
            if ( response.label_id != '' ) {

                jQuery( ".thread-subject-label" ).load( window.location.href + " .thread-subject-label", function () {
                    jQuery( ".thread-subject-label > .thread-subject-label" ).attr( "class", "" );
                } );

                jQuery( '.bbm_label_dropdown .multiSelect li:first' ).after( '<li><span class="bbm-label-input"><input name="bbm_label_id_' + response.label_id + '" type="checkbox" checked="1" value="' + response.label_id + '" class="styled"><strong></strong></span><div class="bbm-label-wrap">' + label_name + '<i class="fa fa-times-circle bbm_label_delete"></i></div></li>' );
            }
            if ( response.message != '' ) {
                alert( response.message );
            }

            jQuery( '.add-bbm-label-details #bbm_label_name' ).val( '' );
        } );
    } );

    //add label
    jQuery( ".bbm_label_dropdown input:checkbox" ).on( 'change', function () {
        var label_id = jQuery( this ).val();
        var thread_id = jQuery( '#bbm_current_thread_id' ).val();
        var ischecked = jQuery( this ).is( ':checked' );
        var label_task = ischecked ? 'bbm_label_add_message' : 'bbm_label_remove_message';
        var data = {
            action: 'bbm_label_ajax',
            task: label_task,
            label_id: label_id,
            thread_id: thread_id
        };
        jQuery.post( ajaxurl, data, function ( response ) {
            var response = jQuery.parseJSON( response );
            if ( label_task == 'bbm_label_add_message' ) {
                if ( jQuery( '#message-subject .thread-subject-label' ).length ) {
                    jQuery( '#message-subject .thread-subject-label' ).append( response.label_html );
                } else {
                    jQuery( '#message-subject' ).append( '<span class="thread-subject-label">' + response.label_html + '</span>' );
                }
            }
            if ( label_task == 'bbm_label_remove_message' ) {
                jQuery( '#message-subject .thread-subject-label .' + response.label_class ).remove();
            }
        } );
    } );

    //Delete a label
    jQuery( '.bbm_label_dropdown' ).on( 'click', '.bbm_label_delete', function () {

        var curr_li = jQuery( this ).closest( "li" );

        if ( !confirm( 'Are you sure you want to delete this label? It will be removed from all conversations.' ) ) {
            return false;
        }

        var label_id = jQuery( this ).closest( "li" ).find( 'input' ).val();
        var data = {
            action: 'bbm_delete_label_ajax',
            label_id: label_id,
        };
        jQuery.post( ajaxurl, data, function ( response ) {
            var response = jQuery.parseJSON( response );
            if ( response ) {
                jQuery( ".thread-subject-label" ).load( window.location.href + " .thread-subject-label" );
                jQuery( curr_li ).fadeOut();
                alert( response.message );
            }
        } );
    } );

    jQuery( "#send-reply #save_as_draft" ).on( 'click', function () {
        draft_save_notification_s();
        draft_reply_screen();
    } );

    // auto save active
    if ( bbm_object.current_action == 'view' && bbm_object.draft_autosave == 'on' ) {
        draft_screen_auto_save();
    }

    jQuery( "#send_message_form #save_as_draft" ).on( 'click', function () {
        draft_save_notification_s();
        draft_compose_screen();
    } );

    // auto save active
    if ( bbm_object.current_action == 'compose' && bbm_object.draft_feature == 'on' && bbm_object.draft_autosave == 'on' ) {
        draft_screen_auto_save();
    }

    // drafts list select/deselect all items
    jQuery( '#message-type-select' ).on( 'change', function ( e ) {
        var chosen_value = this.value;
        var items_checkbox = jQuery( '#message-threads td.bulk-select-check :checkbox' );
        if ( chosen_value == 'all' ) {
            items_checkbox.prop( 'checked', 'true' );
        } else {
            items_checkbox.prop( 'checked', false );
        }
    } );

    // drafts list bulk delete all items
    jQuery( '#messages-options-nav-drafts, .messages-options-nav' ).on( 'click', '#delete_drafts_messages', function () {

        var get_ids = [ ];
        var items_checkbox = jQuery( '#message-threads td.bulk-select-check :checkbox:checked' );
        items_checkbox.each( function () {
            get_ids.push( jQuery( this ).attr( 'value' ) );
        } );
        var data = {
            action: 'bbm_draft_ajax',
            task: 'bulk_delete_drafts',
            draft_ids: get_ids.join( ',' )
        };

        jQuery.post( ajaxurl, data, function ( response ) {
            location.reload();
        } );
    } );

    //Dropdown with Multiple checkbox select
    jQuery( ".bbm_label_dropdown dt a" ).on( 'click', function () {
        jQuery( ".bbm_label_dropdown dd ul" ).slideToggle( 'fast' );
    } );

    jQuery( ".bbm_label_dropdown dd ul li a" ).on( 'click', function () {
        jQuery( ".bbm_label_dropdown dd ul" ).hide();
    } );

    function getSelectedValue( id ) {
        return jQuery( "#" + id ).find( "dt a span.value" ).html();
    }

    jQuery( document ).bind( 'click', function ( e ) {
        var $clicked = jQuery( e.target );
        if ( !$clicked.parents().hasClass( "bbm_label_dropdown" ) )
            jQuery( ".bbm_label_dropdown dd ul" ).hide();
    } );

    jQuery( '.mutliSelect input[type="checkbox"]' ).on( 'click', function () {

        var title = jQuery( this ).closest( '.mutliSelect' ).find( 'input[type="checkbox"]' ).val(),
            title = jQuery( this ).val() + ",";
    } );

    //hide attachment upload if user is sending a notice to all users
    var compose_form = jQuery( '#send_message_form' );
    var send_notice_checked = compose_form.find( 'input[name="send-notice"]' );
    if ( send_notice_checked.length !== 0 ) {
        send_notice_checked.change( function () {
            if ( jQuery( this ).is( ':checked' ) ) {
                compose_form.find( ".bbm-attachment-wrapper" ).hide();
                compose_form.find( "#save_as_draft" ).hide();
            } else {
                compose_form.find( ".bbm-attachment-wrapper" ).show();
                compose_form.find( "#save_as_draft" ).show();
            }
        } );
    }

    if ( bbm_object.attachment_feature != null && bbm_object.attachment_feature == 'on' ) {

        var _uploader = bbm_object.uploader || { },
            lang = bbm_object.lang,
            selectors = bbm_object.selectors;

        if ( bbm_object.current_action == 'compose' ) {
            var message_form = jQuery( selectors.form_message );
            var container_id = 'send_message_form';
        } else {
            var message_form = jQuery( selectors.form_reply );
            var container_id = 'send-reply';
        }

        if ( message_form.length === 0 ) {
            return false;
        }

        var submit_button = message_form.find( 'input[type="submit"],button[type="submit"]' );
        var attachment_uri = message_form.find( 'input[name="bbm-attachment-uri"]' );
        var attachment_id = message_form.find( 'input[name="bbm-attachment-id"]' );
        var upload_button = jQuery( '#bb-buddyboss-attachment' );

        // plupload works
        var uploader = new plupload.Uploader( {
            runtimes: 'html5,silverlight,flash,html4',
            browse_button: 'bb-buddyboss-attachment', // you can pass in id...
            container: document.getElementById( container_id ), // ... or DOM Element itself
            dragdrop: false,
            max_file_size: _uploader.max_file_size || '5mb',
            multi_selection: _uploader.multiselect || false,
            url: ajaxurl,
            multipart: true,
            multipart_params: {
                action: 'bb_buddyboss_message_attachment',
                'cookie': encodeURIComponent( document.cookie ),
            },
            flash_swf_url: _uploader.flash_swf_url || '',
            silverlight_xap_url: _uploader.silverlight_xap_url || '',
            filters: _uploader.filters,
            init: {
                FilesAdded: function ( up, files ) {
                    //disable submit button
                    submit_button.attr( 'disabled', 'disabled' ).addClass( 'loading' );
                    //disable browse button
                    var org_text = upload_button.html();
                    upload_button.attr( 'disabled', 'disabled' ).addClass( 'loading' ).data( 'org_text', org_text ).html( lang['uploading'] );

                    up.start();
                },
                FileUploaded: function ( up, file, info ) {
                    //enable submit button
                    submit_button.removeAttr( 'disabled' ).removeClass( 'loading' );

                    //enable browse button
                    upload_button.removeAttr( 'disabled' ).removeClass( 'loading' ).html( upload_button.data( 'org_text' ) );

                    var responseJSON = jQuery.parseJSON( info.response );
                    var new_attachment_data = JSON.stringify( responseJSON );

                    if ( _uploader.multiselect ) {
                        //@todo: later
                    } else {
                        var get_attachment_id = responseJSON.attachment_id;
                        var get_attachment_url = responseJSON.url;

                        //add new attachment
                        var new_att = "<span class='bbm-uploaded-file'>" + responseJSON.name
                            + "<a href='#' data-attachment_id='" + responseJSON.file + "' class='remove-uploaded-file' "
                            + " title='" + lang['remove'] + "'>x</a>"
                            + "</span>";
                        upload_button.after( new_att );
                    }
                    old_data = attachment_uri.val();
                    old_data = old_data.split( "||||||||" );
                    old_data[old_data.length] = new_attachment_data;
                    new_attachment_data = old_data.join( "||||||||" );
                    attachment_uri.val( new_attachment_data );
                    attachment_id.val( get_attachment_id ).change();

                    window.bbm_attachment_btn_status();
                },
                Error: function ( up, err ) {
                    //enable submit button
                    submit_button.removeAttr( 'disabled' ).removeClass( 'loading' );
                    //enable browse button
                    upload_button.removeAttr( 'disabled' ).removeClass( 'loading' ).html( upload_button.data( 'org_text' ) );

                    //self.upload_error(err.file, err.code, err.message, up);
                    show_upload_error( err.code );
                    up.refresh();

                    if ( _uploader.multiselect ) {
                        //@todo: later
                    } else {
                        attachment_uri.val( '' );
                        attachment_id.val( '' ).change();
                    }
                }
            }
        } );

        uploader.init();

        window.bbinboxuploader = uploader;

        jQuery( window ).load( function () {
            window.bbinboxuploader.refresh();
        } );

        jQuery( "body" ).click( function () {
            window.bbinboxuploader.refresh();
        } );

        jQuery.ajaxPrefilter( function ( options, origOptions, jqXHR ) {
            var action = get_query_variable( options.data, 'action' );
            if ( typeof action == 'undefined' || action != 'messages_send_reply' )
                return;

            var attachment_uri = jQuery( '#bbm-attachment-uri' ).val();
            //var attachment_id = jQuery('#bbm-attachment-id').val();

            var new_data = jQuery.extend( { }, origOptions.data, {
                attachment_uri: attachment_uri,
                //attachment_id: attachment_id
            } );

            options.data = jQuery.param( new_data );

            options.success = ( function ( old_success ) {
                /*if(attachment_uri != ''){
                 var attachment_html = '<a class="bbm_attachment_display" target="_blank" href="'+attachment_uri+'">'+bbm_object.download_attach+'</a>';
                 jQuery('#send_reply_button').parents('form:first').prev().find('div.message-content').after(attachment_html);
                 }*/

                return function ( response, txt, xhr ) {

                    if ( parseInt( response ) != -1 ) {

                        jQuery( '.bbm-uploaded-file' ).remove();
                        jQuery( '#bbm-attachment-uri' ).val( '' );
                        jQuery( '#bbm-attachment-id' ).val( '' );

                        try {
                            tinyMCE.activeEditor.setContent( '' );
                        } catch ( e ) {
                        }

                    }

                    if ( jQuery.isFunction( old_success ) ) {
                        old_success( response, txt, xhr );
                    }
                }
            } )( options.success );
        } );

        jQuery( '.remove-uploaded-file' ).on( 'click', function () {
            _this = this;
            jQuery( this ).closest( '.bbm-uploaded-file' ).remove();
            var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
            var bbm_draft_id = jQuery( '#bbm_draft_id' ).val();
            var thread_id = jQuery( '#thread_id' ).val();
            var data = {
                action: 'bbm_attachment_ajax',
                task: 'remove_attachment',
                attachment_id: attachment_id,
                bbm_draft_id: bbm_draft_id,
                thread_id: thread_id,
            };
            jQuery.post( ajaxurl, data, function ( response ) {
                if ( typeof response != 'undefined' && response != '' ) {
                    jQuery( _this ).parent().remove();
                    alert( response );
                    window.bbm_attachment_btn_status();
                }
            } );
            return false;
        } );

        var show_upload_error = function ( errorCode ) {
            switch ( errorCode ) {
                case plupload.FILE_EXTENSION_ERROR:
                    alert( lang.upload_error.file_type );
                    break;
                case plupload.FILE_SIZE_ERROR:
                    alert( lang.upload_error.file_size );
                    break;
                default:
                    alert( lang.upload_error.generic );
                    break;
            }
        }


    }


} );


function get_parameter_by_name( name ) {
    name = name.replace( /[\[]/, "\\[" ).replace( /[\]]/, "\\]" );
    var regex = new RegExp( "[\\?&]" + name + "=([^&#]*)" ),
        results = regex.exec( location.search );
    return results === null ? "" : decodeURIComponent( results[1].replace( /\+/g, " " ) );
}

// get query var
function get_query_variable( query, variable ) {
    if ( typeof query == 'undefined' || query == '' || typeof variable == 'undefined' || variable == '' )
        return '';
    var vars = query.split( "&" );
    for ( var i = 0; i < vars.length; i++ ) {
        var pair = vars[i].split( "=" );
        if ( pair[0] == variable )
            return pair[1];
    }
    return( false );
}

// get tinymce content
function get_tinymce_content( textarea_id ) {
    if ( jQuery( "#wp-" + textarea_id + "-wrap" ).hasClass( "tmce-active" ) ) {
        return tinyMCE.activeEditor.getContent();
    } else {
        return jQuery( '#' + textarea_id ).val();
    }
}

window.bbm_autosave_secout = null;

// draft idle handle for reply screen
function draft_screen_auto_save() {

    leavetype = function () {

        clearTimeout( window.bbm_autosave_secout );
        window.bbm_autosave_secout = setTimeout( function () {

            if ( bbm_object.current_action == 'view' ) {
                draft_save_notification_s();
                draft_reply_screen();
            }

            if ( bbm_object.current_action == 'compose' ) {
                draft_save_notification_s();
                draft_compose_screen();
            }

        }, 3000 );

    }

    entertype = function () {

        clearTimeout( window.bbm_autosave_secout ); //clear the timeout if there.

    }

    if ( bbm_object.current_action == 'view' ) {

        jQuery( document ).on( "keyup", "#message_content", leavetype );
        jQuery( document ).on( "keydown", "#message_content", entertype );

        if ( bbm_object.editor_feature == "on" ) {
            window.bbm_draft_leavetype = leavetype;
            window.bbm_draft_entertype = entertype;
        }
    }

    if ( bbm_object.current_action == 'compose' ) {

        jQuery( document ).on( "keyup", "#message_content", leavetype );
        jQuery( document ).on( "keydown", "#message_content", entertype );

        if ( bbm_object.editor_feature == "on" ) {
            window.bbm_draft_leavetype = leavetype;
            window.bbm_draft_entertype = entertype;
        }
    }

}


function draft_save_notification_s() {

    clearTimeout( window.draft_save_notification );

    lang = bbm_object.lang;

    afterdom = false;

    if ( bbm_object.current_action == 'view' ) {
        afterdom = jQuery( "#message_content" );
    }

    if ( bbm_object.current_action == 'compose' ) {
        afterdom = jQuery( "#message_content" );
    }

    if ( bbm_object.editor_feature == "on" ) {
        afterdom = jQuery( "#wp-message_content-wrap" );
    }

    jQuery( ".autodraftnotify" ).remove(); //remove old if any.

    afterdom.after( '<div class="autodraftnotify"><i class="fa fa-spinner fa-spin"></i> ' + lang['auto_draft_saving'] + '</div>' );

    jQuery( "#save_as_draft" ).prop( "disable", true );
}

function draft_save_notification_e( response ) {
    jQuery( ".autodraftnotify" ).html( response );
    jQuery( "#save_as_draft" ).prop( "disable", false );
}

//add draft for reply screen
function draft_reply_screen() {
    var draft_content = get_tinymce_content( 'message_content' );
    var attachment_id = jQuery( '#bbm-attachment-id' ).val();
    if ( draft_content == '' && attachment_id == '' )
        return;
    var thread_id = jQuery( '#thread_id' ).val();
    var data = {
        action: 'bbm_draft_ajax',
        task: 'save_as_draft',
        thread_id: thread_id,
        draft_content: draft_content,
        bbm_attachment: jQuery( '#bbm-attachment-uri' ).val(),
    };
    jQuery.post( ajaxurl, data, function ( response ) {

        draft_save_notification_e();
        draft_save_notification_e( response );

        if ( response ) {
            jQuery( '#buddypress #item-header' ).find( '#message' ).remove();
        }
    } );
}



//add draft for compose screen
function draft_compose_screen() {
    var draft_content = get_tinymce_content( 'message_content' );
    var data = {
        action: 'bbm_draft_ajax',
        task: 'compose_save_as_draft',
        form_data: jQuery( 'form#send_message_form' ).serialize(),
        draft_content: draft_content,
        recipients: jQuery( '#send-to-usernames' ).attr( 'class' ),
        draft_uniqid: jQuery( '#draft_uniqid' ).val(),
        draft_id: get_parameter_by_name( 'draft_id' )
    };
    jQuery.post( ajaxurl, data, function ( response ) {

        draft_save_notification_e();
        draft_save_notification_e( response );

        if ( response ) {
            jQuery( '#buddypress #item-header' ).find( '#message' ).remove();

        }
    } );
}

window.bbm_attachment_btn_status = function () {
    if ( jQuery( ".bbm-uploaded-file" ).length != '0' ) {
        jQuery( "#bb-buddyboss-attachment" ).html( jQuery( "#bb-buddyboss-attachment" ).data( "another-txt" ) + ' <i class="fa fa-paperclip"></i>' );
    } else {
        jQuery( "#bb-buddyboss-attachment" ).html( jQuery( "#bb-buddyboss-attachment" ).data( "txt" ) + ' <i class="fa fa-paperclip"></i>' );
    }
}
