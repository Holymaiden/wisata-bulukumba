!function(t,n,s){"use strict";s("html");s(".touchspin").TouchSpin({buttondown_class:"btn btn-primary btn-square",buttonup_class:"btn btn-primary btn-square",buttondown_txt:'<i class="fa fa-minus"></i>',buttonup_txt:'<i class="fa fa-plus"></i>'}),s(".touchspin-vertical").TouchSpin({verticalbuttons:!0,verticalupclass:"fa fa-angle-up",verticaldownclass:"fa fa-angle-down",buttondown_class:"btn btn-primary btn-square",buttonup_class:"btn btn-primary btn-square"}),s(".touchspin-stop-mousewheel").TouchSpin({mousewheel:!1,buttondown_class:"btn btn-primary btn-square",buttonup_class:"btn btn-primary btn-square",buttondown_txt:'<i class="fa fa-minus"></i>',buttonup_txt:'<i class="fa fa-plus"></i>'}),s(".touchspin-color").each(function(t){var n="btn btn-primary btn-square",u="btn btn-primary btn-square",a=s(this);a.data("bts-button-down-class")&&(n=a.data("bts-button-down-class")),a.data("bts-button-up-class")&&(u=a.data("bts-button-up-class")),a.TouchSpin({mousewheel:!1,buttondown_class:n,buttonup_class:u,buttondown_txt:'<i class="fa fa-minus"></i>',buttonup_txt:'<i class="fa fa-plus"></i>'})})}(window,document,jQuery);