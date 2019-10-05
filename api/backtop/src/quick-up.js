// Callbacks - isScrollingDown, isScrollingUp
// Properties - isAtTop, isAtBottom
(function($) {
  'use strict';
     $.quickup = function (options) {
	 
		// Plugin defaults		
        $.quickup.defaults = {
			quId: 'quickup',
			quScrollElement: undefined,
			quScrollText: 'Scroll Up',
			quScrollLength: 350,
			quScrollSpeed: 250,
			quRightAlign: '25px',
			quBottomAlign: '25px',
			quDispAnimationSpeed: 250,
			quScrollTopTo: 0,
			// Registering Callbacks.
			scrollingDown: $.noop,
            scrollingUp: $.noop     
	    }
		
        // Fetching Plugin settings
	    var $settings = $.quickup.settings = $.extend($.quickup.defaults, options);
		var $ele;
		// Checking if Scroll Element is undefined. If yes, set default element, else assign the passed element.
		if(undefined != $settings.quScrollElement) {
			$ele = $($settings.quScrollElement);
		} else {
			$ele = $('<a/>', {
                id: $settings.quId,
                href: '#'
            });
			$ele.html($settings.quScrollText);
			$ele.css({
				color: 'black',
				'text-decoration': 'none',
				'font-size': 'initial'
			});
		}
		$ele.appendTo('body');
		$ele.css({
            display: 'none',
            position: 'fixed',
			right: $settings.quRightAlign,
			bottom: $settings.quBottomAlign,
            zIndex: 2147483647
        });
	   
		var $win = $(window);
		var $doc = $(document);
		var lastScrollPos = 0;
		
		$win.on('scroll', function() {
		
			// Monitoring Scrolling Events
			var currScroll = $(this).scrollTop();
			if(options != undefined) {
				if (currScroll > lastScrollPos && options.scrollingDown != undefined){
					// Callback Scrolling Down
					options.scrollingDown();
				} else if (options.scrollingUp != undefined) {
					// Callback Scrolling Up
					options.scrollingUp();
				}
			}
			lastScrollPos = currScroll;
			
			// Setting Scroll Properties
			if($win.scrollTop() == 0) {
			    $.quickup.scrollAtTop = true;
				$.quickup.scrollAtBottom = false;
			} else if( ($win.scrollTop() + $win.height()) == $doc.height() ){
				$.quickup.scrollAtBottom = true;
				$.quickup.scrollAtTop = false;
			} else {
				$.quickup.scrollAtTop = false;
				$.quickup.scrollAtBottom = false;
			}
			
			// Show/ Hide Scroll based on height from top
			if(currScroll > $settings.quScrollLength) {
				$ele['show']($settings.quDispAnimationSpeed);
			} else {
				$ele['hide']($settings.quDispAnimationSpeed);
			}		   
		 }); 
		 
		 // Move to the Top.
        $ele.click(function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $settings.quScrollTopTo
            }, $settings.quScrollSpeed, 'linear');
        });
  }
})(jQuery);