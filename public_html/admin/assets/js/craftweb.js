/**
 * CraftWeb JS Code
 * @author CraftWeb Software Team
 * @version 1.0
 * @copyright 2017 CraftWeb Software
 */

function SwSil(sayfa, id, slug, btn="") {
  if (btn != "") {
    btn.parents("tr").addClass("warning");
  }
  swal({
    title: "Emin misin?",
    text: "Yapmak istediğin işlemin geri dönüşü yoktur.\nYapmak istediğinden emin misin?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Evet, Sil !",
    cancelButtonText: "İptal Et",
    closeOnConfirm: false,
    closeOnCancel: true
  },
  function(isConfirm){
    if (isConfirm) {
      window.location.href = ADMIN_URL+"/?go="+sayfa+"&"+slug+"="+id;
    }else{
      if (btn != "") {
        btn.parents("tr").removeClass("warning");
      }
    }
  });
  return false;
}

// Summernote Code
$('#summernote').summernote({
    lang: 'tr-TR',
    minHeight: 200
});

// Tag Input
$("#tags-input").tagsinput();

// Nestable
var updateOutput = function(e) {
    var list = e.length ? e : $(e.target),
        output = list.data('output');
    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
    } else {
        output.val('JSON browser support required for this demo.');
    }
};


jQuery.fn.extend({
    live: function (event, callback) {
        if (this.selector) {
            jQuery(document).on(event, this.selector, callback);
        }
    }
});

$('.js-slider').each(function(){
    var $slider = jQuery(this);

    // Get each slider's init data
    var $sliderArrows       = $slider.data('slider-arrows') ? $slider.data('slider-arrows') : false;
    var $sliderDots         = $slider.data('slider-dots') ? $slider.data('slider-dots') : false;
    var $sliderNum          = $slider.data('slider-num') ? $slider.data('slider-num') : 1;
    var $sliderAuto         = $slider.data('slider-autoplay') ? $slider.data('slider-autoplay') : false;
    var $sliderAutoSpeed    = $slider.data('slider-autoplay-speed') ? $slider.data('slider-autoplay-speed') : 3000;

    // Init slick slider
    $slider.slick({
        arrows: $sliderArrows,
        dots: $sliderDots,
        slidesToShow: $sliderNum,
        autoplay: $sliderAuto,
        autoplaySpeed: $sliderAutoSpeed
    });
});