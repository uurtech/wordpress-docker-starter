( function( $ ) {
	'use strict';

	// Global
	var $win = $( window ),
		$doc = $( document ),
		$body = $( 'body' );


	function featuredBoxes() {
		$(document).on('panelsopen', function(e) {
			$('.featured-box-data').find('h3').click(function(event) {
				$(this).next('.featured-box-inner').slideToggle();
				$(this).parent().siblings().find('.featured-box-inner').slideUp();
			});
		});
	}


    function initChosen() {
        $(document).on('panelsopen', function(e) {
            $(".chosen-dropdown").chosen({
                disable_search_threshold: 10,
            });
        });         
    }

    function media_upload(number) {
        var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;

        $('body').on('click', '.custom_media_button' + number, function(e) {
            var button_id ='#'+$(this).attr('id');
            var self = $(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    $('.custom_media_id').val(attachment.id);
                    $('.custom_media_url' + number).val(attachment.url);
                    $('.custom_media_image' + number).attr('src',attachment.url).css('display','block');
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);
                return false;
        });
    }

    function media_upload2(number) {
        var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;
        
        var images = [];

        $('body').on('click', '.custom_media_button' + number, function(e) {
            var button_id ='#'+$(this).attr('id');
            var self = $(button_id);
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(button_id);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media  ) {
                    images.push(attachment.id);
                    $('.custom_media_url' + number).val(images);
                    $('.custom_media_image' + number).attr('src',attachment.url).css('display','block');
                } else {
                    return _orig_send_attachment.apply( button_id, [props, attachment] );
                }
            }
            wp.media.editor.open(button);
                return false;
        });

    }


	/* Function init */
	$doc.ready( function() {
		featuredBoxes();
		media_upload(1);
		media_upload(2);
		media_upload(3);
		media_upload(4);
        media_upload2(5);

        initChosen();
	} );


} )( jQuery );
