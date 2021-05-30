( function( $ ) {
	'use strict';

	// Global
	var $win = $( window ),
		$doc = $( document ),
		$body = $( 'body' );

	// AnimationFrame
	window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame || function(f){setTimeout(f, 1000/60)};

	// Parallax
	function cainParallax() {
		$( '.cain-parallax' ).each( function( el ) {
			var i = el + 1;
			var moveImage = $(this);
			var viewableOffset = moveImage.offset().top - $win.scrollTop();
			moveImage.css("transform", "translateY(" + viewableOffset * .1 + "px" + ") translateZ(0px)");
		} );
	}

	function mainPreloader() {
		var $mainPreloader = $( '.main-preloader' );
		if ( $mainPreloader.length ) {
			$mainPreloader.addClass( 'window-is-loaded' );
			setTimeout(function () {
				$mainPreloader.remove();
			}, 650);
		}
	}

	function socialLinks() {
		$( '.social-media-list li a' ).attr( 'target', '_blank' );
	}

	// Header sticky
	function headerSticky() {
		if ( $( '.header-navigation' ).hasClass( 'header-floating' ) ) {
			var $headerNavOffset = $( '.header-floating-trigger' ).offset().top;
			var $headerNav = $( '.header-navigation' );
			var $headerNavHeight = $headerNav.outerHeight( true );

			if ( $win.scrollTop() > $headerNavOffset - $body.offset().top ) {
				if ( $headerNav.css( 'position' ) == 'relative' || $headerNav.css( 'position' ) == 'static' ) {
					$headerNav.before( '<div class="clearfix header-floating-helper" id="header-floating-helper" style="height:' + $headerNavHeight + 'px"></div>' );
				}
				$( '.header-navigation' ).addClass( 'floating' ).css( 'top', $body.offset().top );
			} else {
				$( '.header-navigation' ).removeClass( 'floating' ).css( 'top', '' );
				$( '#header-floating-helper' ).remove();
			}
		}
	}

	function tabPaneFilter() {
		$( '.filter-tab-pane' ).on( 'click', function( e ) {
			e.preventDefault();
			$( this ).tab( 'show' );
		} );

		// Login/register form
		$( '.tabs-nav' ).on( 'click', function( e ) {
			e.preventDefault();
			$( this ).tab( 'show' );
		} );
	}


	// Section fullwidth based on siteorigin page builder
	function sectionStretch() {
		var e = $("body");

		$(".panels-stretch.panel-row-style").each(function() {
			var r = $(this);
			r.css({
				"margin-left": 0,
				"margin-right": 0,
				"padding-left": 0,
				"padding-right": 0
			});

			var i = r.offset().left - e.offset().left,
			n = e.outerWidth() - i - r.parent().outerWidth();

			r.css({
				"margin-left": -i,
				"margin-right": -n,
				"padding-left": "full" === r.data("stretch-type") ? i : 0,
				"padding-right": "full" === r.data("stretch-type") ? n : 0
			});

			var a = r.find("> .panel-grid-cell");
			"full-stretched" === r.data("stretch-type") && 1 === a.length && a.css({
				"padding-left": 0,
				"padding-right": 0
			}), r.css({
				"border-left": 0,
				"border-right": 0
			})
		}), $(".panels-stretch.panel-row-style").length && $(window).trigger("panelsStretchRows")
	}

	// Search box
	function searchBox() {
		$( '.site-header' ).on( 'click', '.toggle-search-box', function( e ) {
			e.preventDefault();
			$( 'body' ).addClass( 'search-box-active' );
		} );

		$( '.search-box' ).on( 'click', '.close-search-box', function() {
			$( 'body' ).removeClass( 'search-box-active' );
		} );
	}

	// Canvas menu
	function sideMenu() {
		$( '.site-header' ).on( 'click', '.toggle-side-menu', function( e ) {
			e.preventDefault();
			$( 'body' ).addClass( 'side-menu-active' );
		} );

		$( '.side-menu-close, .side-menu-overlay' ).on( 'click', function() {
			$( 'body' ).removeClass( 'side-menu-active' );
		} );

		// Side menu navigation toggle
		$( '.side-menu' ).on( 'click', '.subnav-toggle', function( e ) {
			e.preventDefault();
			$( this ).toggleClass( 'open' ).next( '.sub-menu' ).slideToggle();
		} );
	}

	// Mobile Menu
	function mobileMenu() {
		$( '.site-header' ).on( 'click', '.toggle-mobile-menu', function( e ) {
			e.preventDefault();
			$( 'body' ).toggleClass( 'mobile-menu-active' );

			var headerHeight = $( '.site-header' ).outerHeight();
			$( '.mobile-menu' ).css( 'top', headerHeight );
		} );


		var hasChildMenu = $( '.mobile-menu' ).find('li:has(ul)');
		hasChildMenu.children('a').after('<span class="subnav-toggle"></span>');

		// Mobile menu navigation toggle
		$( '.mobile-menu' ).on( 'click', '.subnav-toggle', function( e ) {
			e.preventDefault();
			$( this ).toggleClass( 'open' ).next( '.sub-menu' ).slideToggle();
		} );
	}
	

	// Product quickview
	function productQuickView() {
		if ( $.fn.magnificPopup ) {

			$( '.woocommerce .products li.product, .filter-product-section .product, .best-seller-products .product' ).each(function(i) {
				$( this ).find( '#modal-quickview' ).attr('id', 'modal-quickview-' + i);
				$( this ).find( '.product-quickview' ).attr('href', '#modal-quickview-' + i);
			});

			$( '.product-quickview' ).magnificPopup( {
				type: 'inline', 
				preloader: true, 
				tLoading: '',
				closeBtnInside: true,
				removalDelay: 200,
				mainClass: 'modal-quickview',
			  	callbacks: {
				    open: function() {
						$( '.quickview-gallery' ).slick("refresh");
				    },	
			    }			
			} );
		}
	}

	// inc and dec product input
	function productIncDec() {
		$( 'body' ).on( 'click', '.q-plus, .q-min', function( e ) {
			e.preventDefault();
			var $qty = $( this ).closest( '.quantity' ).find( '.qty' ),
				currentVal = parseInt( $qty.val() ),
				isAdd = $( this ).hasClass( 'add' );

			!isNaN( currentVal ) && $qty.val( isAdd ? ++currentVal : ( currentVal > 0 ? --currentVal : currentVal ) );
		} );
	}

	// show product filter
	function showFilterContent() {
		// Top toolbar
		$( '.shop-filter-toolbar' ).on( 'click', '.show-filters', function( e ) {
			e.preventDefault();

			$( this ).toggleClass( 'filter-active' );
			$( '.shop-filter-content' ).toggleClass( 'active' ).slideToggle();
		} );

		// Filter popup
		$( '.shop-filter-bottom' ).on( 'click', '.toggle-filter-popup', function( e ) {
			e.preventDefault();
			$body.addClass( 'filter-popup-active' );

			var paddingTop = $( '.site-header' ).outerHeight( true );
			$( '.shop-filter-popup' ).css( { 'padding-top': paddingTop } );
		} );

		$( '.shop-filter-popup' ).on( 'click', '.close-filter-popup', function() {
			$body.removeClass( 'filter-popup-active' );
		} );
	}

	function accordionFilter() {
		// Shop filter content
		$( '.shop-filter-content' ).on( 'click', '.widget-title', function( e ) {
			if ( $( '.shop-filter-content' ).hasClass( 'mobile-active' )  ) {
				e.preventDefault();
				$(this).closest('.widget').siblings().removeClass('active');
				$(this).closest('.widget').toggleClass('active');
			}
		} );

		// Shop sidebar filter
		$( '.shop-sidebar' ).on( 'click', '.widget-title', function( e ) {
			if ( $( '.shop-sidebar' ).hasClass( 'mobile-active' )  ) {
				e.preventDefault();
				$(this).closest('.widget').siblings().removeClass('active');
				$(this).closest('.widget').toggleClass('active');
			}
		} );

		// Popup filter
		$( '.shop-filter-popup' ).on( 'click', '.widget-title', function( e ) {
			if ( $( '.shop-filter-popup' ).hasClass( 'mobile-active' )  ) {
				e.preventDefault();
				$(this).closest('.widget').siblings().removeClass('active');
				$(this).closest('.widget').toggleClass('active');
			}
		} );
	}

/*
	function priceFilter() {
		$( '.price_slider' ).slider( {
			range: true,
			min: 0,
			max: 490,
			values: [ 0, 490 ],
			slide: function( event, ui ) {
				$( '.price_label' ).html( '<span class="from">$' + ui.values[ 0 ] + '</span> - <span class="to">$' + ui.values[ 1 ] + '</span>' );
			}
		} );

		$( '.price_label' ).html( '<span class="from">$' + $( '.price_slider' ).slider( "values", 0 ) + '</span> - <span class="to">$' + $( '.price_slider' ).slider( "values", 1 ) + '</span>' );
	}
	*/

	function selectFilterList() {
		$( '.shop-filter-popup' ).on( 'click', 'ul li', function( e ) {
			$( this ).toggleClass( 'selected' );
		} );
	}

	function mobileActive() {
		$.each( {
			'.shop-filter-content': '',
			'.shop-sidebar': '',
			'.shop-filter-popup': '',
		}, function( element, delegate ) {
			$( element ).each( function() {
				if ( $win.width() <= 991  ) {
					$( this ).addClass( 'mobile-active' );
				} else {
					$( this ).removeClass( 'mobile-active' );
				}
			} );
		} );
	}

	// Image popup
	function imagePopup() {
		if ( $.fn.magnificPopup ) {
			$( '.entry-content .mfp-image' ).closest( 'a' ).magnificPopup( {
				type: 'image', 
				closeOnContentClick: true, 
				image: {
					verticalFit: true
				}
			} );
		}
	}

	// cainIsotope
	function cainIsotope() {
		if ( $.fn.isotope ) {

	    if ( $('.best-seller-section').length ) {

	      $('.best-seller-section:not(.wrap-best-seller-slider)').each(function() {

	        var self       = $(this);
	        var filterNav  = self.find('.best-seller-categories').find('a');

	        var projectIsotope = function($selector){

	          $selector.isotope({
	            filter: '*',
	            itemSelector: '.product.col-md-3',
	            percentPosition: true,
	            animationOptions: {
	                duration: 750,
	                easing: 'liniar',
	                queue: false,
	            }
	          });

	        }

	        self.children().find('.best-seller-products').imagesLoaded( function() {
	          projectIsotope(self.find('.best-seller-products'));
	        });

	        filterNav.click(function(){
	            var selector = $(this).attr('data-filter');
	            filterNav.parents('li').removeClass('active');
	            $(this).parents('li').addClass('active');

	            self.find('.best-seller-products').isotope({
	                filter: selector,
	                animationOptions: {
	                    duration: 750,
	                    easing: 'liniar',
	                    queue: false,
	                }
	            });

	            return false;

	        });
	      });
	    }

		// Categories
		var $categoryWrap = $( '.categories-section .list-categories-section.not-two-cols' ),
			$categoryIsotope = function() {
				$categoryWrap.isotope( {
					itemSelector: '.category-item',
					masonry: {
						columnWidth: '.category-item'
					}
				} );
			};

		if ( $.fn.imagesLoaded ) {
			$categoryWrap.imagesLoaded( $categoryIsotope );
		} else {
			$categoryIsotope.apply( this );
		}



	}
	}

	// slickSlider
	function cainSlickSlider() {
		if ( $.fn.slick ) {
			
			$( '.quickview-gallery' ).each( function() {
				$( this ).slick( {
					infinite: false,
				} );
			} );

			// Hero slider
			$( '.hero-slider' ).each( function() {
				$( this ).slick( {
					infinite: true,
					fade: true,
					cssEase: 'ease-in-out'
				} );
			} );

			// New arrival slider
			$( '.new-arrival-slider' ).each( function() {
				$( this ).slick( {
					infinite: true,
					dots: false,
					responsive: [
					{
						breakpoint: 992,
						settings: {
							dots: true,
							arrows: false,
					  	}
					},
				  ]
				} );
			} );

			// Best seller
			$( '.best-seller-slider' ).each( function() {
				$( this ).slick( {
					infinite: true,
					slidesToShow: 4,
					slidesToScroll: 4,
					dots: false,
					arrows: true,
					responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3
					  	}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
					  	}
					}
				  ]
				} );
			} );

			// Brand slider
			$( '.brand-slider' ).each( function() {
				$( this ).slick( {
					slidesToShow: 5,
					slidesToScroll: 1,
					infinite: false,
					dots: true,
					arrows: false,
					responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3
					  	}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2
					  	}
					}
				  ]
				} );
			} );

			// Instagram slider
			$( '.instagram-slider' ).each( function() {
				$( this ).slick( {
					slidesToShow: 8,
					slidesToScroll: 8,
					infinite: false,
					dots: false,
					arrows: false,
					responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 8,
							slidesToScroll: 8
					  	}
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							dots: true,
					  	}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2,
							dots: true,
					  	}
					}
				  ]
				} );
			} );

			// Product slider
			var $productGallery = $( '.woocommerce-product-gallery__wrapper' ).not( '.product-layout-2 .woocommerce-product-gallery__wrapper' ),
				$productGalleryThumb = $( '.product-thumbnails' ),
				sliderVertical = ($productGalleryThumb.data( "vertical" ) == true) ? true : false;

			$productGallery.slick( {
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: false,
				dots: false,
				asNavFor: $productGalleryThumb
			} );

			$productGalleryThumb.slick( {
				slidesToShow: 4,
				slidesToScroll: 1,
				dots: false,
				focusOnSelect: true,
				vertical: sliderVertical,
				infinite: true,
				arrows: false,
				asNavFor: $productGallery,
				responsive: [
					{
						breakpoint: 992,
						settings: {
							vertical: false,
					  	}
					}
				]
			} );

		}
	}

	// Move modal quantities
	function modalQuantities() {

		$( '.modal-cart' ).each( function() {
			var qplus = $( this ).find( '.q-plus' );
			var qmin = $( this ).find( '.q-min' );
			
			$( this ).find( '.quantity' ).append(qplus);
			$( this ).find( '.quantity' ).append(qmin);		
		} );

		var singleqplus = $( '.single-product .summary .q-plus' );
		var singleqmin = $( '.single-product .summary .q-min' );
		$( '.single-product .summary ').find( '.quantity' ).append(singleqplus);
		$( '.single-product .summary ').find( '.quantity' ).append(singleqmin);
	}

	function stickyElement() {
		if ( $.fn.theiaStickySidebar ) {
			var $heightTop = 30,
				$hasAdminbar = $( '#wpadminbar' ),
				$hasSticky = $( '.header-navigation.header-floating' );

			if ( $hasAdminbar.length && $hasSticky.length ) {
				$heightTop = 100 + $hasAdminbar.height();
			} else if ( $hasAdminbar.length && ! $hasSticky.length ) {
				$heightTop = 30 + $hasAdminbar.height();
			} else if ( ! $hasAdminbar.length && $hasSticky.length ) {
				$heightTop = 100;
			} else {
				$heightTop = $heightTop;
			}

			// Sidebar default
			$( '.product-detail-summary.sticky-element' ).theiaStickySidebar( {
				additionalMarginTop: $heightTop,
				minWidth: 992
			} );
		}
	}

	//Fitvids
	function initFitvids() {
		$( 'body' ).fitVids();
	}

	/* Function init */
	$doc.ready( function() {
		sectionStretch();
		searchBox();
		sideMenu();
		mobileMenu();
		initFitvids();
		socialLinks();
		imagePopup();
		stickyElement();

		productIncDec();
		tabPaneFilter();
		showFilterContent();
		accordionFilter();
		mobileActive();
		//priceFilter();
		selectFilterList();
		modalQuantities();
	} );

	$win.load( function() {
		mainPreloader();
		cainIsotope();
		cainSlickSlider();
		productQuickView();
		initFitvids();
	} );

	$win.scroll( function( e ) {
		headerSticky();
		requestAnimationFrame( cainParallax );
	} );

	$win.resize(function( e ) {
		sectionStretch();
		headerSticky();
		mobileActive();
	} );

} )( jQuery );
