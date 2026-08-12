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

	
}(jQuery));

// Back to Top
function scrolltoTop() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
}



