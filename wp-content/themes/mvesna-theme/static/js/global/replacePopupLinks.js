$(function() {
  // $(document).load(function() {
    var $popupAnchors = $('a[href*="' + window.location.hostname + '"][href*="/popup/"], a[href*="/popup/"]');

    $popupAnchors.each(function() {
      var $this = $(this),
          popupTarget = this.pathname.replace(/^\/+|\/+$/g, '').replace('/', '-');

      $this.attr('data-toggle', 'modal');
      $this.attr('data-target', '#' + popupTarget);
      $this.attr('href', '#');
    });
  // });
});