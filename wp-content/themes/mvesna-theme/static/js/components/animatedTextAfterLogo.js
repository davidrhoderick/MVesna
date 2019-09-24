$(function() {
  var $animatedText = $('.animated-text-after-logo');

  if($animatedText.length > 0) {
    var startLength = $animatedText.width(),
        animationScrollLength = $animatedText.attr('data-scroll-length'),
        animateLogo = function () {
          var animationProgress = this.scrollY / animationScrollLength;
    
          if(animationProgress < 1) {
            $animatedText.css('width', startLength * (1 - animationProgress));
          } else {
            $animatedText.css('width', 0);
          }
        };

    animateLogo();

    window.addEventListener('scroll', animateLogo);

    window.addEventListener('resize load', function() {
      startLength = $animatedText.width();
      animateLogo();
    });
  }
});