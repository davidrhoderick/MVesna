$(function() {
  var $animatedText = $('.animated-text-after-logo');

  if($animatedText.length > 0) {
    var startLength = $animatedText.width() + 'px',
        animationTrigger = $animatedText.attr('data-animation-trigger'),
        textShown = true,
        animateLogo = function () {
          if(this.scrollY > animationTrigger) {
            if(textShown) {
              $animatedText.animate({
                width: '0'
              }, 300);
              
              textShown = false;
            }
          } else {
            if(!textShown) {
              $animatedText.animate({
                width: startLength
              }, 300);
              
              textShown = true;
            }
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