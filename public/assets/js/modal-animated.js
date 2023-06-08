(function ($) {
  function testAnim(x) {
    $(".modal .modal-dialog").attr(
      "class",
      "modal-dialog  " + x + "  animated"
    );
  }
  var modal_animate_custom = {
    init: function () {
      $("#ajaxModel").on("show.bs.modal", function (e) {
        testAnim('bounceIn');
      });
      $("#ajaxModel").on("hide.bs.modal", function (e) {
        testAnim('bounceOut');
      });
    },
  };
  modal_animate_custom.init();
})(jQuery);
