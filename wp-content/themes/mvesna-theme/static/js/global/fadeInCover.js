$(function() {
  var $fadeInCover = $('.fade-in-cover'),
      $links = $('a:not([target="_blank"]):not([href="#"])');

  $(window).on(draw_complete, function() {
    $fadeInCover.animate({
      opacity: 0
    }, 300, function() {
      $fadeInCover.hide();
    });
  });

  $links.on('click', function(event) {
    event.preventDefault;
    var href = $(this).attr('href');
    
    $fadeInCover.show().animate({
      opacity: 1
    }, 300, function() {
      window.location.href = href;
    });
  });
});