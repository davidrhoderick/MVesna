$(function() {
  var $navbar = $('.navbar'),
      animateLogo = function () {
        if(this.scrollY > 10 && !$navbar.hasClass('show-background')) {
          $navbar.addClass('show-background');
        } else if(this.scrollY < 10 && $navbar.hasClass('show-background')) {
          $navbar.removeClass('show-background');
        }
      };

  window.addEventListener('scroll', animateLogo);
  $(window).on('load resize', animateLogo);
});