$(function() {
  var $portfolioPieces = $('.portfolio-gallery-image'),
      hexToRGB = function (hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16),
            g = parseInt(hex.slice(3, 5), 16),
            b = parseInt(hex.slice(5, 7), 16);

        if(alpha) {
          return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
        } else {
          return "rgb(" + r + ", " + g + ", " + b + ")";
        }
      };

hexToRGB('#FF0000', 0.5);;

  if($portfolioPieces.length > 0) {
    $portfolioPieces.each(function() {
      var $piece = $(this),
          highlightColor = $piece.attr('data-highlight-color'),
          $description = $piece.find('.piece-description');

      $description.css('background-color', hexToRGB(highlightColor, 0.7));
    });
  }
});