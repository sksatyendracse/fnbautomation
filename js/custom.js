$(document).ready(function() {
    "use strict";

	//COPYRIGHR YEAR UPDATE
	$("#cryear").text("2020");
  $('#carouselAbout').carousel();

  $('.materialboxed').materialbox();

    //LEFT MOBILE MENU OPEN
    $(".ts-menu-5").on('click', function() {
        $(".mob-right-nav").css('right', '0px');
    });

    //LEFT MOBILE MENU OPEN
    $(".mob-right-nav-close").on('click', function() {
        $(".mob-right-nav").css('right', '-270px');
    });

    //LEFT MOBILE MENU CLOSE
    $(".mob-close").on('click', function() {
        $(".mob-close").hide("fast");
        $(".menu").css('left', '-92px');
        $(".mob-menu").show("slow");
    });

    //mega menu
    $(".t-bb").hover(function() {
        $(".cat-menu").fadeIn(50);
    });
    $(".ts-menu").mouseleave(function() {
        $(".cat-menu").fadeOut(50);
    });

    //mega menu
    $(".sea-drop").on('click', function() {
        $(".sea-drop-1").fadeIn(100);
    });
    $(".sea-drop-1").mouseleave(function() {
        $(".sea-drop-1").fadeOut(50);
    });
    $(".dir-ho-t-sp").mouseleave(function() {
        $(".sea-drop-1").fadeOut(50);
    });

    //mega menu top menu
    $(".sea-drop-top").on('click', function() {
        $(".sea-drop-2").fadeIn(100);
    });
    $(".sea-drop-1").mouseleave(function() {
        $(".sea-drop-2").fadeOut(50);
    });
    $(".top-search").mouseleave(function() {
        $(".sea-drop-2").fadeOut(50);
    });

    //ADMIN LEFT MOBILE MENU OPEN
    $(".atab-menu").on('click', function() {
        $(".sb2-1").css("left", "0");
        $(".btn-close-menu").css("display", "inline-block");
    });

    //ADMIN LEFT MOBILE MENU CLOSE
    $(".btn-close-menu").on('click', function() {
        $(".sb2-1").css("left", "-350px");
        $(".btn-close-menu").css("display", "none");
    });

    //mega menu
    $(".t-bb").hover(function() {
        $(".cat-menu").fadeIn(50);
    });
    $(".ts-menu").mouseleave(function() {
        $(".cat-menu").fadeOut(50);
    });

    //review replay
    $(".edit-replay").on('click', function() {
        $(".hide-box").show();
    });

	//What you looking for checkbox
	$('.req-pop-sec-1 input:checkbox').on('change', function(){
		if($(this).is(":checked")) {
			$(".req-nxt-1").addClass("nxt-act");
		}
		var check = $('.req-pop-sec-1').find('input[type=checkbox]:checked').length;
		if(check <= 0){
			$(".req-nxt-1").removeClass("nxt-act");
		}
	});
	//What you looking for - Next button
    $(".req-nxt-1").on('click', function() {
		$(".req-nxt-1").hide();
        $(".req-pop-sec-1").hide();
		$(".req-pop-sec-2").show();
    });

	//SET TIME FOR SHOWING "What you looking for" POPUP
	setTimeout(function(){
      $(".req-pop").fadeIn();
	},5000);

	//POPUP CLOSED EVENT
    $(".req-pop-clo").on('click', function() {
		$(".req-pop").fadeOut();
    });

	//POPUP SUBMIT BUTTON EVENT
    $(".rer-sub-btn").on('click', function() {
		$(".req-pop-sec-1, .req-pop-sec-2").hide();
		$(".req-pop-sec-3").show();
    });



    //PRE LOADING
    $('#status').fadeOut();
    $('#preloader').delay(350).fadeOut('slow');
    $('body').delay(350).css({
        'overflow': 'visible'
    });

    $('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrainWidth: 400, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left', // Displays dropdown with edge aligned to the left of button
        stopPropagation: false // Stops event propagation
    });
    $('.dropdown-button2').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: ($('.dropdown-content').width() * 3) / 2.5 + 5, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });

    //Collapsible
    $('.collapsible').collapsible();

    //material select
    $('select').material_select();

    //AUTO COMPLETE CATEGORY SELECT
    $('#select-category.autocomplete, #select-category1.autocomplete').autocomplete({
        data: {
            "All Category": null,
            "Entertainment": null,
            "Food & Drink": null,
            "Hotel & Hostel": null,
			"OutDoor": null,
			"Parking": null,
			"Shop & Store": null,
			"Events": null,
			"Beauty arlour": null,
            "Jersey City": null
        },
        limit: 8, // The max amount of results that can be shown at once. Default: Infinity.
        onAutocomplete: function(val) {
            // Callback function when value is autcompleted.
        },
        minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });

//AUTO COMPLETE CITY SELECT
    $('#select-city.autocomplete, #top-select-city.autocomplete').autocomplete({
        data: JSON.parse(localStorage.getItem('cities')),
        limit: 8, // The max amount of results that can be shown at once. Default: Infinity.
        onAutocomplete: function(val) {
            // Callback function when value is autcompleted.
        },
        minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });

	//AUTO COMPLETE SEARCH SELECT
    $('#select-search.autocomplete, #top-select-search.autocomplete').autocomplete({
        data: JSON.parse(localStorage.getItem('categories')),
        limit: 8, // The max amount of results that can be shown at once. Default: Infinity.
        onAutocomplete: function(val) {
            // Callback function when value is autcompleted.
        },
        minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });

    //HOME PAGE FIXED MENU
    $(window).scroll(function() {

        if ($(this).scrollTop() > 450) {
            $('.hom-top-menu').fadeIn();
            $('.cat-menu').hide();
        } else {
            $('.hom-top-menu').fadeOut();
        }
    });

    //HOME PAGE FIXED MENU
    $(window).scroll(function() {

        if ($(this).scrollTop() > 450) {
            $('.hom3-top-menu').addClass("top-menu-down");
        } else {
            $('.hom3-top-menu').removeClass("top-menu-down");
        }
    });
});

function scrollNav() {
    $('.v3-list-ql-inn a').click(function() {
        //Toggle Class
        $(".active-list").removeClass("active-list");
        $(this).closest('li').addClass("active-list");
        var theClass = $(this).attr("class");
        $('.' + theClass).parent('li').addClass('active-list');
        //Animate
        $('html, body').stop().animate({
            scrollTop: $($(this).attr('href')).offset().top - 130
        }, 400);
        return false;
    });
    $('.scrollTop a').scrollTop();
}


$('.confirm').click(function() {
    (new PNotify({
      text: 'Notice 1.',
      title: $(this).data("title"),
      icon: 'glyphicon glyphicon-question-sign',
      hide: false,
      confirm: {
        confirm: true
      },
      buttons: {
        closer: false,
        sticker: false
      },
      history: {
        history: false
      },
      addclass: 'stack-modal',
      stack: {
        'dir1': 'down',
        'dir2': 'right',
        'modal': true
      }
    })).get().on('pnotify.confirm', function() {
    }).on('pnotify.cancel', function() {
      event.preventDefault();
    });
    return false;
  });
scrollNav();
