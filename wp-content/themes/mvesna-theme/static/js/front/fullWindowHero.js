$(function() {
  var $hero = $('#hero'),
      makeHeroFullWindow = function() {
        var windowHeight = $(window).innerHeight(),
            windowWidth = $(window).innerWidth(),
            $content = $hero.find('.container'),
            contentHeight = $content.outerHeight();

        $hero.css({
          height: windowHeight,
          width: windowWidth
        });

        $content.css('top', (windowHeight - contentHeight) / 2);
      };

  if($hero.length > 0) {
    $(window).on('load resize', makeHeroFullWindow);
  }
});