$(document).ready(function() {

  //$("body").css('border', '11px solid red');

});

$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
  event.preventDefault();
  $(this).ekkoLightbox();
}); 
