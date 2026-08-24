(function ($) {

	'use strict';

	$(document).ready(function() {

		// Hero Slider
		var heroSlider = new Swiper('.hero-slider', {
			loop: true,
			spaceBetween: 20,
			allowTouchMove: false,
			navigation: {
				nextEl: '.nav-next',
				prevEl: '.nav-prev',
			},
			speed: 5000,
			autoHeight: true,
			autoplay: {
				delay: 6000,
				disableOnInteraction: false,
			}
		});

		// Testimonial Slider
		var testimonialSlider = new Swiper('.testimonial-slider', {
			loop: true,
			//spaceBetween: 30,
			navigation: {
				nextEl: '.test-nav-next',
				prevEl: '.test-nav-prev',
			},
			speed: 2000,
			autoHeight: true,
			slidesPerView: "auto",
			autoplay: false,
		});

		// Product Slider
		var productSlider = new Swiper('.product-slider', {
			loop: true,
			spaceBetween: 30,
			navigation: {
				nextEl: '.test-nav-next',
				prevEl: '.test-nav-prev',
			},
			speed: 2000,
			autoHeight: true,
			slidesPerView: "4",
			autoplay: false,
			breakpoints: {
				575: {
				  slidesPerView: "1",
				  spaceBetween: 15,
				},
				768: {
				  slidesPerView: "2",
				  spaceBetween: 20,
				},
				1200: {
				  slidesPerView: "3",
				  spaceBetween: 30,
				},
			},
		});
		
		// Gallery Lightbox
		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
			event.preventDefault();
			$(this).ekkoLightbox({
				alwaysShowClose: true,
				maxHeight: "600",
				maxWidth: "900",
				wrapping: "false",
			});
		});

		// OTM Forms
		const form = otmForms({
			apiKey: '8cf47e80-8536-11ec-b3b8-f1538cc60a66',
			selector: '.OTMForm',
			honeypot: true,
			honeypotSelector: 'your-url',
			onSubmit: function (el) {
				$(el)
				.find('button[type="submit"]')
				.prop("disabled", true)
				.html('Sending...');
			},
			onSuccess: function (response, formElement) {
				$(formElement).find('button[type="submit"]').prop("disabled", false).html('Submit Message');
				$(formElement).find('.alert-danger').addClass('d-none');
				$(formElement).find('.alert-success').removeClass('d-none');
			},
			onError: function (error, formElement) {
				$(formElement).find('button[type="submit"]').prop("disabled", false).html('Submit Message');
				$(formElement).find('.alert-success').addClass('d-none');
				$(formElement).find('.alert-danger').removeClass('d-none');
			}
		});
		form.init();

		// Career Form
		$('#mfcf7_zl_add_file').addClass('txt-btn');

		// Sticky Header
		$(window).scroll(function () {
			var scroll = $(window).scrollTop();
			if (scroll > 5) {
				$("header").addClass("shadow");
			} else {
				$("header").removeClass("shadow");
			}
		});

		// Disable Image Dragging
		$('img').attr('draggable', false);
	});


	 //Responsive Navigation
	$(document).ready(function () {
	  $('body').addClass('js');
	  var $menu = $('.site-nav-container'),
	    $menulink = $('.menu-link'),

	    $siteWrap = $('body');


	  $menulink.click(function (e) {
	    e.preventDefault();
	    $menulink.toggleClass('active');
	    $menu.toggleClass('active');
	    $siteWrap.toggleClass('nav-active');
	  });

	  $('.menu-item-has-children').append("<span class='m-subnav-arrow'></span>");

	  $('.m-subnav-arrow').click(function () {
	    $(this).toggleClass('active');
	    var $this = $(this).prev(".sn-level-2");
	    $this.toggleClass('active').next('ul').toggleClass('active');
	  });

	});


	// Bulk Order - Parts & Components (custom-b2b plugin) fixes
	$(document).ready(function () {

		if (!$('.b2bking_bulkorder_container_final').length) {
			return;
		}

		// Highlight selected part cards with a border (delegated: cards load via AJAX)
		$(document).on('change', '.part-item input[type="checkbox"]', function () {
			$(this).closest('.part-item').toggleClass('part-item-selected', this.checked);
		});

		// Both b2bking (core) and custom-b2b register a PHP handler for the exact same
		// action "b2bking_bulkorder_add_multiple" - whichever runs first calls
		// wp_die()/exit() and the browser gets that response, so which of the two
		// response formats actually comes back is not reliable (core just echoes a raw
		// cart-subtotal string; custom-b2b sends JSON). Parsing that response to figure
		// out what got added is therefore fragile either way. Simplest robust fix: once
		// the request finishes, ask WooCommerce's own Store API what's actually in the
		// cart right now and show that - no response-format guessing needed at all.
		var fetchCart = function () {
			return $.getJSON('/wp-json/wc/store/v1/cart');
		};

		var updateCartBadge = function (cart) {
			if (!cart || typeof cart.items_count !== 'number') {
				return;
			}

			var mode = (typeof b2bking_display_settings !== 'undefined') ? b2bking_display_settings.cream_form_cart_button : 'carticon';

			if (mode === 'cart') {
				$('.b2bking_cream_cart_button_items_qty').text(cart.items_count);
				$('.b2bking_orderform_cart').removeClass('b2bking_orderform_cart_inactive');
			} else if (mode === 'carticon') {
				$('#b2bking_bulkorder_cream_filter_cart_text').text(cart.items_count);
				$('.b2bking_orderform_carticon').removeClass('b2bking_orderform_carticon_inactive');
			}

			$(document.body).trigger('wc_fragment_refresh');
			$(document.body).trigger('b2bking_added_item_cart');
			$(document.body).trigger('b2bking_multiadded_item_cart');
		};

		$(document).ajaxComplete(function (event, xhr, settings) {
			if (!settings.data || settings.data.indexOf('action=b2bking_bulkorder_add_multiple') === -1) {
				return;
			}

			fetchCart().done(updateCartBadge);
		});

		// Fetch the live cart (WooCommerce's own Store API - no custom endpoint needed)
		// and, for every part card currently rendered, check its box / highlight it /
		// fill in its real cart quantity if that product is already in the cart, or
		// reset it to unselected + 0 if it isn't.
		var syncPartCardsWithCart = function () {
			if (!$('.part-item').length) {
				return;
			}

			fetchCart().done(function (cart) {
				var cartQty = {};
				(cart && cart.items ? cart.items : []).forEach(function (item) {
					cartQty[String(item.id)] = item.quantity;
				});

				$('.part-item').each(function () {
					var $item = $(this);
					var productId = String($item.data('productid'));
					var $checkbox = $item.find('input[type="checkbox"]').first();
					var $qty = $item.find('.b2bking_bulkorder_form_container_content_line_qty').first();
					var inCart = Object.prototype.hasOwnProperty.call(cartQty, productId);

					// Card is marked selected and shows the real cart qty, but the
					// checkbox itself stays unchecked - checking it is what re-adds
					// it via "Add all selected items to Order", so a product already
					// in the cart shouldn't come back pre-armed to be re-added.
					$checkbox.prop('checked', false);
					$item.toggleClass('part-item-selected', inCart);
					$qty.val(inCart ? cartQty[productId] : 0);
				});
			});
		};

		// "Add all selected items to Order" - compact floating icon while its natural
		// spot (just below the last .finished-product-row) is out of view; full
		// button, in place, once that spot is reached. The whole content block
		// (rows + button) gets replaced wholesale whenever a Parts & Components
		// category is clicked, so the button is a new DOM node each time - re-wire
		// on every such replacement instead of once on page load.
		var $partsContent = $('.b2bking_bulkorder_form_container_content');

		if ($partsContent.length) {

			// action=b2bking_ajax_search is used for two different things that both
			// render into this same container: an actual product search (searchValue
			// set), and the "Finished Products" left-menu clicks, which reuse the same
			// action with searchValue empty and rely on custom-b2b's
			// pass_single_product_id_to_display_only_that_product filter to force the
			// result down to that one product's own Product/Qty/Cart configurator row
			// - that single-product view must stay exactly as-is. Tried tracking which
			// kind the most recent request was via ajaxSend, but B2BKing fires more
			// than one of these requests around a single user action (live-search
			// keystrokes, background refreshes) and they don't always resolve in send
			// order, so that flag could get overwritten by an unrelated request before
			// the real search's response ever finished rendering. Reading the search
			// box's own current value once the DOM settles isn't subject to any of
			// that request-ordering, so it's what the transform below is gated on.
			var hasActiveSearchTerm = function () {
				return $.trim($('#b2bking_bulkorder_search_text_indigoid').val() || '') !== '';
			};

			// Product search renders as B2BKing's own native row list - each row with
			// its own immediate "Add to cart" button - not as .part-item cards. Convert
			// every native row into a real .part-item card (checkbox + qty stepper,
			// same classes/structure render_parts_products_html() uses for Parts &
			// Components) so search results get the same checkbox-multiple-select +
			// shared "Add all selected items to Order" flow, for free, through the
			// SAME delegated handlers already wired for those cards (the click
			// handler, the qty +/- buttons, the selected-card highlight, the cart
			// sync below) - nothing here needs its own new event handlers. Converted
			// rows get removed outright rather than hidden: B2BKing's own
			// set_table_visible_variations() runs after every search and, whenever
			// there are no variation-attribute <select>s on the page to filter by
			// (the normal case here), force-shows every single
			// .b2bking_bulkorder_form_container_content_line on the page with
			// `style="display: flex !important"` - which clobbers a plain .hide().
			// Removing the row is the only thing that global selector can't
			// resurrect.
			var transformSearchResultsToCards = function () {
				if (!hasActiveSearchTerm()) {
					return;
				}

				var $rows = $partsContent.find('.b2bking_bulkorder_form_container_content_line').not('.part-item-unsupported-row');

				if (!$rows.length) {
					return;
				}

				// Wrapped in a real .finished-product-row (empty of the usual h3/dropdown)
				// purely so the plugin's own card CSS - all scoped under
				// ".finished-product-row .parts-products .part-item" - applies without
				// having to duplicate every one of those rules here.
				var $wrap = $partsContent.children('.search-results-wrap');
				if (!$wrap.length) {
					$wrap = $('<div class="finished-product-row search-results-wrap"></div>').prependTo($partsContent);
				}
				var $grid = $wrap.find('.search-results-grid');
				if (!$grid.length) {
					$grid = $('<div class="parts-products search-results-grid"></div>').appendTo($wrap);
				}

				$rows.each(function () {
					var $row = $(this);

					var idClass = ($row.find('[class*="b2bking_selected_product_id_"]').attr('class') || '').match(/b2bking_selected_product_id_(\d+)/);
					var productId = idClass ? idClass[1] : '';
					var $qtyInput = $row.find('input.b2bking_bulkorder_form_container_content_line_qty').first();

					// Leave rows we don't know how to convert (e.g. variable products
					// shown with a "View Options" button instead of qty + add) visible
					// in their native row form - just mark them so they're not
					// re-checked on every subsequent debounced pass.
					if (!productId || !$qtyInput.length) {
						$row.addClass('part-item-unsupported-row');
						return;
					}

					var imgSrc = $row.find('img.b2bking_bulkorder_cream_image').first().attr('src') || '';
					var name = $.trim($row.find('.b2bking_bulkorder_cream_name').first().text());

					var $card = $('<div class="part-item" data-productid="' + productId + '"></div>');
					var $left = $('<div class="part-box-left"></div>').appendTo($card);
					if (imgSrc) {
						$('<img alt="">').attr('src', imgSrc).appendTo($left);
					}

					var $right = $('<div class="part-box-right"></div>').appendTo($card);
					$('<h4></h4>').text(name).appendTo($right);
					$('<input type="checkbox">').val(productId).appendTo($right);

					var $qtyGroup = $('<div class="b2bking_cream_input_group"></div>').attr('data-product-id', productId).appendTo($right);
					$('<button type="button" class="b2bking_cream_input_minus_button b2bking_cream_input_button">-</button>').appendTo($qtyGroup);
					$('<input type="number" class="b2bking_bulkorder_form_container_content_line_qty b2bking_bulkorder_form_container_content_line_qty_cream">')
						.attr({
							value: $qtyInput.val() || 0,
							min: $qtyInput.attr('min') || 0,
							max: $qtyInput.attr('max') || 999999,
							step: $qtyInput.attr('step') || 1,
							'data-product-id': productId
						})
						.appendTo($qtyGroup);
					$('<button type="button" class="b2bking_cream_input_plus_button b2bking_cream_input_button">+</button>').appendTo($qtyGroup);

					$grid.append($card);
					$row.remove();
				});

				// Search results don't come with the bulk "Add all" button - add one
				// (same id the existing click handler and CSS already target).
				if (!$partsContent.children('#add-all-to-cart-btn').length) {
					$('<button type="button" id="add-all-to-cart-btn">Add all selected items to Order</button>').insertAfter($wrap);
				}
			};


		

			var addAllBtnObserver = null;

			var wireAddAllButton = function () {
				if (!window.IntersectionObserver) {
					return;
				}

				var $btn = $('#add-all-to-cart-btn');

				if (!$btn.length) {
					return;
				}

				// NOTE: deliberately not touching the button's own icon/text markup here.
				// The plugin's own click handler does `.text('Adding...')` on beforeSend and
				// `.text('Add all selected items to Order')` on complete - either call wipes
				// out any wrapper we put around the icon (jQuery .text() replaces all child
				// nodes), which was destroying the real <img> for good on every add-to-cart
				// click. The floating icon-only look is done in CSS instead (an ::after with
				// the same cart icon, real content hidden via color:transparent), so it no
				// longer depends on markup the plugin can and does rewrite mid-interaction.

				// Zero-height marker left in normal flow at the button's original spot
				var $sentinel = $btn.prev('.add-all-btn-sentinel');
				if (!$sentinel.length) {
					$sentinel = $('<span class="add-all-btn-sentinel" aria-hidden="true"></span>');
					$btn.before($sentinel);
				}

				if (addAllBtnObserver) {
					addAllBtnObserver.disconnect();
				}

				addAllBtnObserver = new IntersectionObserver(function (entries) {
					var entry = entries[0];
					var reachedOriginalSpot = entry.isIntersecting || entry.boundingClientRect.top < 0;
					$btn.toggleClass('add-all-btn-floating', !reachedOriginalSpot);
				}, { threshold: 0 });

				addAllBtnObserver.observe($sentinel[0]);
			};

			// Cards (re)render via AJAX (initial category load, left-menu clicks, the
			// per-row category dropdown) - re-wire the button and re-sync the cart
			// state each time the content actually settles, debounced so the
			// transient "Loading parts..." swap doesn't trigger a redundant pass.
			var refreshTimer = null;
			var refreshPartsUI = function () {
				transformSearchResultsToCards();
				wireAddAllButton();
				syncPartCardsWithCart();
			};

			refreshPartsUI();

			new MutationObserver(function () {
				clearTimeout(refreshTimer);
				refreshTimer = setTimeout(refreshPartsUI, 150);
			}).observe($partsContent[0], { childList: true, subtree: true });
		}

	});


}(jQuery));

// Back to Top
function scrolltoTop() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
}



