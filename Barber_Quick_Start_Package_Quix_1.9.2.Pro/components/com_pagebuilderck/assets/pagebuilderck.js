/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

jQuery(document).ready(function($){
	var hashtag = window.location.hash;
	$('.accordionsck').each(function() {
		var activetab = typeof($(this).attr('activetab')) != 'undefined' ? parseInt($(this).attr('activetab')) : false;
		$(this).accordionck({
			collapsible: true,
			active: activetab,
			heightStyle: "content",
			scrollToActive: false
		});
	});
	$('.tabsck').each(function() {
		var activetab = parseInt($(this).attr('activetab'));
		if (window.location.hash) {
			if (hashtag.substr(0, 5) == '#tab-') {
				var tabindex = 0;
				$('> ol > li a', $(this)).each(function() {
					if ('#tab-' + $(this).text().toLowerCase().replace(/ /g, '-') == hashtag) {
						activetab = tabindex;
					}
					tabindex++;
				});
			}
		}
		$(this).tabsck({
			active: activetab
		});
		$('.ui-tabs-nav > li', $(this)).click(function() {
			$(window).trigger('resize');
		});
	});
	$('.blockck, .rowck').each(function() {
		var $this = $(this);
		$window = $(window);

		// function to be called whenever the window is scrolled or resized
		function update($this){
				var top = $this.offset().top;
				var pos = $window.scrollTop();
				var height = $this.height();
				var windowheight = $(window).height();

			// check if totally above or totally below viewport
			if (top + height < pos || top > pos + windowheight) {
				if (! $this.hasClass('noreplayck')) $this.removeClass('animateck');
				return;
			}
			// animate the content if found
			if (top < pos || top > pos + windowheight-30) {
				// is out of the screen
			} else {
				$this.addClass('animateck');
			}
		}
		update($this);
		$window.scroll(function() { update($this); });
	});
	$('.rowckfullwidth').each(function() {
		var $this = $(this);
		$window = $(window);
		var clone = $this.clone();
		clone.css('visibility', 'hidden');
		$this.css('position', 'fixed').after(clone);
		$this.css('left', '0')
			.css('right', '0');
		function update($this, clone){
			clone.css('height', $this.height());
			$this.css('top', clone.offset().top - $window.scrollTop());
		}
		update($this, clone);
		$window.on('scroll resize load', function() { update($this, clone); });
	});
});
