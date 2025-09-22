/* ===================================================================
    
    Author          : Valid Theme
    Template Name   : Gixus - Business Consulting Template
    Version         : 1.0
    
* ================================================================= */
(function($) {
	"use strict";

	$(document).ready(function() {


		/* ==================================================
		    # Tooltip Init
		===============================================*/
		$('[data-toggle="tooltip"]').tooltip();


		/* ==================================================
		    # Youtube Video Init
		 ===============================================*/
		$('.player').mb_YTPlayer();


		/* ==================================================
		    # Wow active
		===============================================*/
		new WOW().init();


		/* ==================================================
		    # imagesLoaded active
		===============================================*/
		$('#gallery-masonary,.blog-masonry').imagesLoaded(function() {

			/* Filter menu */
			$('.mix-item-menu').on('click', 'button', function() {
				var filterValue = $(this).attr('data-filter');
				$grid.isotope({
					filter: filterValue
				});
			});

			/* filter menu active class  */
			$('.mix-item-menu button').on('click', function(event) {
				$(this).siblings('.active').removeClass('active');
				$(this).addClass('active');
				event.preventDefault();
			});

			/* Filter active */
			var $grid = $('#gallery-masonary').isotope({
				itemSelector: '.gallery-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.gallery-item',
				}
			});

			/* Filter active */
			$('.blog-masonry').isotope({
				itemSelector: '.blog-item',
				percentPosition: true,
				masonry: {
					columnWidth: '.blog-item',
				}
			});

		});


		/* ==================================================
		    # Fun Factor Init
		===============================================*/
		$('.timer').countTo();
		$('.fun-fact').appear(function() {
			$('.timer').countTo();
		}, {
			accY: -100
		});


		/* ==================================================
		    # Magnific popup init
		 ===============================================*/
		$(".popup-link").magnificPopup({
			type: 'image',
			// other options
		});

		$(".popup-gallery").magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			},
			// other options
		});

		$(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
			type: "iframe",
			mainClass: "mfp-fade",
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

		$('.magnific-mix-gallery').each(function() {
			var $container = $(this);
			var $imageLinks = $container.find('.item');

			var items = [];
			$imageLinks.each(function() {
				var $item = $(this);
				var type = 'image';
				if ($item.hasClass('magnific-iframe')) {
					type = 'iframe';
				}
				var magItem = {
					src: $item.attr('href'),
					type: type
				};
				magItem.title = $item.data('title');
				items.push(magItem);
			});

			$imageLinks.magnificPopup({
				mainClass: 'mfp-fade',
				items: items,
				gallery: {
					enabled: true,
					tPrev: $(this).data('prev-text'),
					tNext: $(this).data('next-text')
				},
				type: 'image',
				callbacks: {
					beforeOpen: function() {
						var index = $imageLinks.index(this.st.el);
						if (-1 !== index) {
							this.goTo(index);
						}
					}
				}
			});
		});


		/* ==================================================
		    _Progressbar Init
		 ===============================================*/
		function animateElements() {
			$('.progressbar').each(function() {
				var elementPos = $(this).offset().top;
				var topOfWindow = $(window).scrollTop();
				var percent = $(this).find('.circle').attr('data-percent');
				var animate = $(this).data('animate');
				if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
					$(this).data('animate', true);
					$(this).find('.circle').circleProgress({
						// startAngle: -Math.PI / 2,
						value: percent / 100,
						size: 130,
						thickness: 3,
						lineCap: 'round',
						emptyFill: '#e7e7e7',
						fill: {
							gradient: ['#2667FF', '#6c19ef']
						}
					}).on('circle-animation-progress', function(event, progress, stepValue) {
						$(this).find('strong').text((stepValue * 100).toFixed(0) + "%");
					}).stop();
				}
			});

		}

		animateElements();
		$(window).scroll(animateElements);



		/* ==================================================
            # Banner Carousel
         ===============================================*/
		 const bannerFade = new Swiper(".banner-fade", {
            // Optional parameters
            direction: "horizontal",
            loop: true,
            autoplay: true,
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
        

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }

            // And if we need scrollbar
            /*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
        });


		/* ==================================================
            # Banner Carousel
         ===============================================*/
		 const bannerStyleTwo = new Swiper(".banner-style-two-carousel", {
            // Optional parameters
            direction: "horizontal",
            loop: true,
            autoplay: true,
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            speed: 3000,
			autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }

            // And if we need scrollbar
            /*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
        });


		/* ==================================================
            # Banner Carousel
         ===============================================*/
		 const bannerStyleThree = new Swiper(".banner-style-three-carousel", {
            // Optional parameters
            direction: "horizontal",
            loop: true,
            autoplay: true,
            effect: "fade",
            fadeEffect: {
                crossFade: true
            },
            speed: 10,
			autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },


            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }

            // And if we need scrollbar
            /*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
        });


		/* ==================================================
            # Banner Carousel
         ===============================================*/
		 const bannerSlide = new Swiper(".banner-slide", {
            // Optional parameters
            direction: "horizontal",
            loop: true,
            grabCursor: true,
            autoplay: true,
            speed: 2000,
			autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }

            // And if we need scrollbar
            /*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
        });


		/* ==================================================
            # Testimonials Carousel
         ===============================================*/
		 const testimonialOneCarousel = new Swiper(".testimonial-style-one-carousel", {
			// Optional parameters
			direction: "horizontal",
			loop: true,
			autoplay: true,
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev"
			},
			
			// And if we need scrollbar
			/*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
		});

		/* ==================================================
            # Services Carousel
         ===============================================*/
		 const clientsTwoCarousel = new Swiper(".services-style-two-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 50,
			autoplay: true,
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev"
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 2,
				}
			},
		});


		/* ==================================================
            # Gallery Style One Carousel
         ===============================================*/
		 const galleryTwo = new Swiper(".gallery-style-one-carousel", {
			// Optional parameters
			loop: true,
			freeMode: true,
			grabCursor: true,
			slidesPerView: 1,
			spaceBetween: 50,
			autoplay: true,
			// If we need pagination
			pagination: {
				el: '.project-pagination',
				type: 'fraction',
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".project-button-next",
				prevEl: ".project-button-prev"
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 2,
				},
				1200: {
					slidesPerView: 2.5,
					centeredSlides: true,
				},
			},
		});


		/* ==================================================
            # Gallery Style Two Carousel
         ===============================================*/
		 const gallerytwo = new Swiper(".gallery-style-two-carousel", {
			// Optional parameters
			loop: true,
			freeMode: true,
			grabCursor: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoplay: true,
			// If we need pagination
			pagination: {
				el: '.project-pagination',
				type: 'fraction',
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".project-button-next",
				prevEl: ".project-button-prev"
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 2,
				},
				1200: {
					slidesPerView: 3,
					spaceBetween: 0,
					centeredSlides: true,
				},
				1500: {
					slidesPerView: 4,
					spaceBetween: 0,
					centeredSlides: true,
				},
			},
		});


		/* ==================================================
            # Brand Carousel
         ===============================================*/
		 const brandOne = new Swiper(".brand-style-one-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 2,
			spaceBetween: 15,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 3,
					spaceBetween: 30,
				},
				992: {
					slidesPerView: 3,
					spaceBetween: 30,
				}
			},
		});


		/* ==================================================
            # Testimonial Carousel
         ===============================================*/
		 const TestimonialTwo = new Swiper(".testimonial-style-two-carousel", {
			// Optional parameters
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoplay: true,
			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 30,
				}
			},
		});

		/* ==================================================
            # Testimonials Carousel
         ===============================================*/
		 const testimonialThreeCarousel = new Swiper(".testimonial-style-three-carousel", {
			// Optional parameters
			direction: "horizontal",
			loop: true,
			autoplay: true,
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev"
			},
			
			// And if we need scrollbar
			/*scrollbar: {
            el: '.swiper-scrollbar',
          },*/
		});


		/* ==================================================
            # Hover Active Init
        ===============================================*/
        $('.location-item').on('mouseenter', function() {
			var $this;
            $this = $(this);
			if ($this.hasClass('active')) {
				$this.addClass('active');
			} else {
				$this.addClass('active');
				$this.siblings().removeClass('active');
			}	
		})


		/* ===================================================================
			Accordion Hover
		* ================================================================= */
		let accordion_animation = document.querySelector(".services-style-one-items");
		if (accordion_animation) {
			var expand;
			var $accordion, $wideScreen;
			$accordion = $('.services-style-one-items').children('.services-style-one-item');
			$wideScreen = $(window).width() > 767;
			if ($wideScreen) {
				$accordion.on('mouseenter click', function(e) {
					var $this;
					e.stopPropagation();
					$this = $(this);
					if ($this.hasClass('out')) {
						$this.addClass('out');
					} else {
						$this.addClass('out');
						$this.siblings().removeClass('out');
					}
				});
			} else {
				$accordion.on('touchstart touchend', function(e) {
					var $this;
					e.stopPropagation();
					$this = $(this);
					if ($this.hasClass('out')) {
						$this.addClass('out');
					} else {
						$this.addClass('out');
						$this.siblings().removeClass('out');
					}
				});
			}
		}


		/* ==================================================
		    Circle Text
		================================================== */
		let circleTypeElm = $(".circle-text-item");
        if (circleTypeElm.length) {
            circleTypeElm.each(function () {
                let elm = $(this);
                let options = elm.data("circle-text-options");
                elm.circleType(
                    "object" === typeof options ? options : JSON.parse(options)
                );
            });
        }

		/* ==================================================
		    Splite Text
		================================================== */
		let text_split = document.querySelector(".split-text");
		if (text_split) {
			const animEls = document.querySelectorAll('.split-text');
			animEls.forEach(el => {
				var splitEl = new SplitText(el, {
					type: "lines, words",
					linesClass: "line"
				});
				var splitTl = gsap.timeline({
					duration: 0,
					ease: 'power4',
					scrollTrigger: {
						trigger: el,
						start: 'top 90%'
					}
				});

				splitTl.from(splitEl.words, {
					yPercent: "100",
					stagger: 0.025,
				});

			});
		}

		/* ==================================================
        	# Text Scroll Animation
        ===============================================*/
		let text_scroll = document.querySelector(".text-scroll-animation");
		if (text_scroll) {
			gsap.registerPlugin(ScrollTrigger);
			const textElements = gsap.utils.toArray('.text');
			textElements.forEach(text => {
				gsap.to(text, {
					backgroundSize: '100%',
					ease: 'none',
					scrollTrigger: {
						trigger: text,
						start: 'center 100%',
						end: 'center 50%',
						scrub: true,
					},
				});
			});
		}


		// Images parallax
		var width = $(window).width();
		if (width > 768) {
			let imageParallax = document.querySelector(".img-container");
			if (imageParallax) {
				gsap.utils.toArray('.img-container').forEach(container => {
					const img = container.querySelector('img');

					const t4 = gsap.timeline({
						scrollTrigger: {
							trigger: container,
							scrub: true,
							pin: false,
						}
					});

					t4.fromTo(img, {
						yPercent: -60,
						ease: 'none'
					}, {
						yPercent: 60,
						ease: 'none'
					});
				});
			}
		}

		/* ==================================================
		    Contact Form Validations
		================================================== */
		$('.contact-form').each(function() {
			var formInstance = $(this);
			formInstance.submit(function() {

				var action = $(this).attr('action');

				$("#message").slideUp(750, function() {
					$('#message').hide();

					$('#submit')
						.after('<img src="assets/img/ajax-loader.gif" class="loader" />')
						.attr('disabled', 'disabled');

					$.post(action, {
							name: $('#name').val(),
							email: $('#email').val(),
							phone: $('#phone').val(),
							comments: $('#comments').val()
						},
						function(data) {
							document.getElementById('message').innerHTML = data;
							$('#message').slideDown('slow');
							$('.contact-form img.loader').fadeOut('slow', function() {
								$(this).remove()
							});
							$('#submit').removeAttr('disabled');
						}
					);
				});
				return false;
			});
		});

	}); // end document ready function

	/* ==================================================
        Preloader Init
     ===============================================*/
	 function loader() {
		$(window).on('load', function() {
			$('#gixus-preloader').addClass('loaded');
			$("#loading").fadeOut(500);
			// Una vez haya terminado el preloader aparezca el scroll

			if ($('#gixus-preloader').hasClass('loaded')) {
				// Es para que una vez que se haya ido el preloader se elimine toda la seccion preloader
				$('#preloader').delay(900).queue(function() {
					$(this).remove();
				});
			}
		});
	}
	loader();
	
})(jQuery); // End jQuery