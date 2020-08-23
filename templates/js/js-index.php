<script>
/*--------------------------------------------------
Raty
--------------------------------------------------*/
$.fn.raty.defaults.path = '<?= $baseurl; ?>/templates/lib/raty/images';
$('.featured-item-rating').raty({
	readOnly: true,
	score: function(){
		return this.getAttribute('data-rating');
	}
});

/*--------------------------------------------------
Owlcarousel
--------------------------------------------------*/
(function(){
	// get carousel element
	var owl = $('.owl-carousel');

	// carousel options
	owl.owlCarousel({
		loop: true,
		margin: 30,
		//autoplay: true,
		//autoplayTimeout: 3000,
		//autoplaySpeed: 1000,
		responsive: {
			0:{
				items:1
			},
			465:{
				items:2
			},
			715:{
				items:3
			},
			1000:{
				items:5,
			}
		}
	});

	// carousel navigation buttons
	$('.slideNext').on('click', function() {
		owl.trigger('next.owl.carousel');
	})

	$('.slidePrev').on('click', function() {
		// With optional speed parameter
		// Parameters has to be in square bracket '[]'
		owl.trigger('prev.owl.carousel', [300]);
	})
}());
</script>