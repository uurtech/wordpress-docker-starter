<?php

class AddonMasterAdminNotice{
	// Constructor
    public function __construct() {
    	// Hooked CSS/JS
        add_action( 'admin_footer', array( $this, 'admin_footer_scripts' ) );
        // Admin Notice Hook
        add_action( 'admin_notices', array( $this, 'render_notices' ) );
        // Ajax action
        add_action( 'wp_ajax_am_dismiss_notice', array( $this, 'notice_dismiss_ajax_function' ) );
    }

    // Admin JS/CSS
	public function admin_footer_scripts(){ ?>
		<style>
			.am--message-inner {
			  display: -webkit-box;
			  display: -webkit-flex;
			  display: -ms-flexbox;
			  display: flex;
			  -webkit-box-align: center;
			  -webkit-align-items: center;
			  -ms-flex-align: center;
			  align-items: center;
			}
			.notice.am--message {
			  padding: 20px;
			}
			.am--message-icon {
			  height: 50px;
			  width: 50px;
			}
			.am--message-icon img {
			  max-width: 100%;
			  -o-object-fit: cover;
			     object-fit: cover;
			}
			.am--message-content {
			  padding: 0 20px;
			}
			.notice.am--message a {
			  text-decoration: none !important;
			  margin-right: 10px;
    		  position: relative;
			}
			.notice.am--message p {
			  padding: 0;
			  margin: 0;
			}
			.notice.am--message p.am--message-actions {
			  margin-top: 10px;
			}
			.am--message-actions span.amicon {
			    vertical-align: text-bottom;
			    vertical-align: middle;
			}
		</style>
		<script type="text/javascript">
			jQuery( function($) {
				$(document).ready( function(){

				    // Notice Dismiss Trigger
				    $(document).on('click','.am--message .am-notice-dismiss', function(e){
				    	e.preventDefault();
				    	$(this).closest('.am--message').find('.notice-dismiss').trigger('click');
				    });

				    // Notice Dismiss ajax
				    $(document).on('click','.am--message .notice-dismiss', function(e){
				    	e.preventDefault();
				    	var id = $(this).closest('.am--message').attr('id');

				    	console.log(id);

			            $.post(ajaxurl, {
			                action: "am_dismiss_notice",
			                dismiss: 1,
			                id: id,
			            }, function (data) {
			            });

			            $('.wi-notice').fadeOut();
				    })

				});
			});
		</script>
		<?php
	}

	// Ajax Dismiss Function
	public function notice_dismiss_ajax_function(){
		$am_dismiss_notice = (get_option('am_dismiss_notice')) ? get_option( 'am_dismiss_notice' ) : array();

		if ( isset( $_POST['dismiss'] ) && $_POST['dismiss'] == 1 ) {
			if ( isset( $_POST['id'] ) ) {
				$id = $_POST['id'];
				$am_dismiss_notice[$id] = 1;
			}
			update_option( "am_dismiss_notice", $am_dismiss_notice );
		}

		wp_die();
	}

	// Notice Display
	public function render_notices() {
		// addonmaster_admin_notice filter
        $args = apply_filters('addonmaster_admin_notice', $args = array());

        // Get dismissed notices id
        $am_dismiss_notice = (get_option('am_dismiss_notice')) ? get_option( 'am_dismiss_notice' ) : array();

		foreach ( $args as $key => $arg ) {
			// Classes
			$classes = "";

			// Check ID
			if( $arg['id'] ) {
				$id = 'notice-'.$arg['id'];
				$classes .= " notice-{$id}";
			} else {
				continue;
			}

			// Check Dismissed or not
			if( isset( $am_dismiss_notice[$id] ) && $am_dismiss_notice[$id] == 1){
				continue;
			}

			// Notice text
			$notice_text = ( $arg['text'] ) ? $arg['text'] : false;

			// Logo url
			$logo = ( $arg['logo'] ) ? $arg['logo'] : "";

			// Dismiss button text
			$dismiss_text = ( $arg['dismiss_text'] ) ? $arg['dismiss_text'] : null;

			// Check is_dismissable
			$is_dismissable = ( $arg['is_dismissable'] ) ? $arg['is_dismissable'] : false;
			if( $is_dismissable ) {
				$classes .= " is-dismissible";
			}
		    ?>
			<div id="<?php esc_attr_e($id);?>" class="notice am--message  <?php esc_attr_e($classes);?>" style="border-color:<?php echo sanitize_hex_color( $arg['border_color'] );?>">
				<div class="am--message-inner">
					<?php if( $logo ) : ?>
						<div class="am--message-icon">
							<div class="e-logo-wrapper">
								<img src="<?php echo esc_url($logo); ?>" alt="">
							</div>
						</div>
					<?php endif; ?>
					<div class="am--message-content">
						<p><?php _e($notice_text) ?></p>
						<p class="am--message-actions">
							<?php if( $arg['buttons'] ): ?>
								<?php foreach( (array)$arg['buttons'] as $btnkey => $btn ):
									$btn_text = ($btn['text']) ? $btn['text'] : "";
									$btn_link = ($btn['link']) ? $btn['link'] : "";
									$btn_class = ($btn['class']) ? $btn['class'] : "";
									$btn_target = ($btn['target']) ? $btn['target'] : "";

									$btn_icon = "amicon ";
									$btn_icon .= ($btn['icon']) ? $btn['icon'] : "";
									?>
									<a href="<?php echo esc_url($btn_link); ?>" target="<?php esc_attr_e($btn_target); ?>" class="<?php esc_attr_e($btn_class); ?>"><span class="<?php esc_attr_e($btn_icon); ?>"></span> <?php esc_html_e($btn_text); ?></a>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php if( $is_dismissable && $dismiss_text ) : ?>
								<a href="#" class="am-notice-dismiss"><span class="amicon dashicons dashicons-dismiss"></span> <?php esc_html_e($dismiss_text); ?></a>
							<?php endif; ?>
						</p>
					</div>
				</div>
			</div>
			<?php
		} // endforeach
	}
}
new AddonMasterAdminNotice;