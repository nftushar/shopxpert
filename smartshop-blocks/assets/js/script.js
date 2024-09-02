(function ($, window) {
  "use strict";

  var SmartShopBlocks = {
    /**
     * [init]
     * @return {[void]} Initial Function
     */
    init: function () {
      this.TabsMenu($(".ht-tab-menus"), ".ht-tab-pane");
      this.TabsMenu($(".smartshop-product-video-tabs"), ".video-cus-tab-pane");
      if ($("[class*='smartshopblock-'] .ht-product-image-slider").length > 0) {
        this.productImageThumbnailsSlider($(".ht-product-image-slider"));
      }
      this.thumbnailsimagescontroller();
      this.ThumbNailsTabs(
        ".smartshop-thumbanis-image",
        ".smartshop-advance-product-image-area"
      );
    },

    /**
     * [TabsMenu] Active first menu item
     */
    TabsMenu: function ($tabmenus, $tabpane) {
      $tabmenus.on("click", "a", function (e) {
        e.preventDefault();
        var $this = $(this),
          $target = $this.attr("href");
        $this
          .addClass("htactive")
          .parent()
          .siblings()
          .children("a")
          .removeClass("htactive");
        $($tabpane + $target)
          .addClass("htactive")
          .siblings()
          .removeClass("htactive");

        // slick refresh
        if ($(".slick-slider").length > 0) {
          var $id = $this.attr("href");
          $($id).find(".slick-slider").slick("refresh");
        }
      });
    },

    /**
     *
     * @param {TabMen area selector} $tabmenu
     * @param {Image Area} $area
     */
    ThumbNailsTabs: function ($tabmenu, $area) {
      $($tabmenu).on("click", "li", function (e) {
        e.preventDefault();
        var $image = $(this).data("wlimage");
        if ($image) {
          $($area)
            .find(".woocommerce-product-gallery__image .wp-post-image")
            .attr("src", $image);
          $($area)
            .find(".woocommerce-product-gallery__image .wp-post-image")
            .attr("srcset", $image);
        }
      });
    },

    /**
     * Slick Slider
     */
    initSlickSlider: function ($block) {
      $($block).css("display", "block");
      var settings = $($block).data("settings");
      if (settings) {
        var arrows = settings["arrows"];
        var dots = settings["dots"];
        var autoplay = settings["autoplay"];
        var rtl = settings["rtl"];
        var autoplay_speed = parseInt(settings["autoplay_speed"]) || 3000;
        var animation_speed = parseInt(settings["animation_speed"]) || 300;
        var fade = false;
        var pause_on_hover = settings["pause_on_hover"];
        var display_columns = parseInt(settings["product_items"]) || 4;
        var scroll_columns = parseInt(settings["scroll_columns"]) || 4;
        var tablet_width = parseInt(settings["tablet_width"]) || 800;
        var tablet_display_columns =
          parseInt(settings["tablet_display_columns"]) || 2;
        var tablet_scroll_columns =
          parseInt(settings["tablet_scroll_columns"]) || 2;
        var mobile_width = parseInt(settings["mobile_width"]) || 480;
        var mobile_display_columns =
          parseInt(settings["mobile_display_columns"]) || 1;
        var mobile_scroll_columns =
          parseInt(settings["mobile_scroll_columns"]) || 1;

        $($block)
          .not(".slick-initialized")
          .slick({
            arrows: arrows,
            prevArrow:
              '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow:
              '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
            dots: dots,
            infinite: true,
            autoplay: autoplay,
            autoplaySpeed: autoplay_speed,
            speed: animation_speed,
            fade: fade,
            pauseOnHover: pause_on_hover,
            slidesToShow: display_columns,
            slidesToScroll: scroll_columns,
            rtl: rtl,
            responsive: [
              {
                breakpoint: tablet_width,
                settings: {
                  slidesToShow: tablet_display_columns,
                  slidesToScroll: tablet_scroll_columns,
                },
              },
              {
                breakpoint: mobile_width,
                settings: {
                  slidesToShow: mobile_display_columns,
                  slidesToScroll: mobile_scroll_columns,
                },
              },
            ],
          });
      }
    },

    /**
     * Slick Nav For As Slider Initial
     * @param {*} $sliderwrap
     */
    initSlickNavForAsSlider: function ($sliderwrap) {
      $($sliderwrap).find(".smartshop-learg-img").css("display", "block");
      $($sliderwrap).find(".smartshop-thumbnails").css("display", "block");
      var settings = $($sliderwrap).data("settings");

      if (settings) {
        $($sliderwrap)
          .find(".smartshop-learg-img")
          .not(".slick-initialized")
          .slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: settings["mainslider"].dots,
            arrows: settings["mainslider"].arrows,
            fade: false,
            asNavFor: ".smartshop-thumbnails",
            prevArrow:
              '<button class="smartshop-slick-large-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow:
              '<button class="smartshop-slick-large-next"><i class="sli sli-arrow-right"></i></button>',
          });
        $($sliderwrap)
          .find(".smartshop-thumbnails")
          .not(".slick-initialized")
          .slick({
            slidesToShow: settings["thumbnailslider"].slider_items,
            slidesToScroll: 1,
            asNavFor: ".smartshop-learg-img",
            centerMode: false,
            dots: false,
            arrows: settings["thumbnailslider"].arrows,
            vertical: settings["thumbnailslider"].slidertype,
            focusOnSelect: true,
            prevArrow:
              '<button class="smartshop-slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow:
              '<button class="smartshop-slick-next"><i class="sli sli-arrow-right"></i></button>',
          });
      }
    },

    /**
     * Accordion
     */
    initAccordion: function ($block) {
      var settings = $($block).data("settings");
      if ($block.length > 0) {
        var $id = $block.attr("id");
        new Accordion("#" + $id, {
          duration: 500,
          showItem: settings.showitem,
          elementClass: "htsmartshop-faq-card",
          questionClass: "htsmartshop-faq-head",
          answerClass: "htsmartshop-faq-body",
        });
      }
    },

    /*
     * Tool Tip
     */
    smartshopToolTips: function (element, content) {
      if (content == "html") {
        var tipText = element.html();
      } else {
        var tipText = element.attr("title");
      }
      element.on("mouseover", function () {
        if ($(".smartshop-tip").length == 0) {
          element.before('<span class="smartshop-tip">' + tipText + "</span>");
          $(".smartshop-tip").css("transition", "all 0.5s ease 0s");
          $(".smartshop-tip").css("margin-left", 0);
        }
      });
      element.on("mouseleave", function () {
        $(".smartshop-tip").remove();
      });
    },

    smartshopToolTipHandler: function () {
      $("a.smartshop-compare").each(function () {
        SmartShopBlocks.smartshopToolTips($(this), "title");
      });
      $(
        ".smartshop-cart a.add_to_cart_button,.smartshop-cart a.added_to_cart,.smartshop-cart a.button"
      ).each(function () {
        SmartShopBlocks.smartshopToolTips($(this), "html");
      });
    },

    /*
     * Universal product
     */
    productImageThumbnailsSlider: function ($slider) {
      $slider.slick({
        dots: true,
        arrows: true,
        prevArrow:
          '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
        nextArrow:
          '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
      });
    },

    thumbnailsimagescontroller: function () {
      this.TabsMenu($(".ht-product-cus-tab-links"), ".ht-product-cus-tab-pane");
      this.TabsMenu($(".ht-tab-menus"), ".ht-tab-pane");

      // Countdown
      $(".ht-product-countdown").each(function () {
        var $this = $(this),
          finalDate = $(this).data("countdown");
        var customlavel = $(this).data("customlavel");
        $this.countdown(finalDate, function (event) {
          $this.html(
            event.strftime(
              '<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>' +
                customlavel.daytxt +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>' +
                customlavel.hourtxt +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>' +
                customlavel.minutestxt +
                '</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>' +
                customlavel.secondstxt +
                "</p></div></div>"
            )
          );
        });
      });
    },

    /**
     * Single Product Quantity Increase/decrease manager
     */
    quantityIncreaseDescrease: function ($area) {
      $area
        .find("form.cart")
        .on(
          "click",
          "span.wl-qunatity-plus, span.wl-qunatity-minus",
          function () {
            const poductType = $area.data("producttype");
            // Get current quantity values
            if ("grouped" != poductType) {
              var qty = $(this).closest("form.cart").find(".qty:visible");
              var val = parseFloat(qty.val());
              var min_val = 1;
            } else {
              var qty = $(this)
                .closest(".wl-quantity-grouped-cal")
                .find(".qty:visible");
              var val = !qty.val() ? 0 : parseFloat(qty.val());
              var min_val = 0;
            }

            var max = parseFloat(qty.attr("max"));
            var min = parseFloat(qty.attr("min"));
            var step = parseFloat(qty.attr("step"));

            // Change the value if plus or minus
            if ($(this).is(".wl-qunatity-plus")) {
              if (max && max <= val) {
                qty.val(max);
              } else {
                qty.val(val + step);
              }
            } else {
              if (min && min >= val) {
                qty.val(min);
              } else if (val > min_val) {
                qty.val(val - step);
              }
            }
          }
        );
    },

    CartTableHandler: function () {
      // Product Details Slide Toggle
      $('body').on("click", '.smartshop-cart-product-details-toggle', function (e) {
          e.preventDefault();
          
          const $target = $(this).data('target');

          if($(`[data-id="${$target}"]`).is(':hidden')) {
              $(`[data-id="${$target}"]`).slideDown();
          } else {
              $(`[data-id="${$target}"]`).slideUp();
          }
      });
  }
  };

  $(document).ready(function () {
    SmartShopBlocks.init();
    SmartShopBlocks.CartTableHandler();

    $("[class*='smartshopblock-'] .product-slider").each(function () {
      SmartShopBlocks.initSlickSlider($(this));
    });

    $("[class*='smartshopblock-'].smartshop-block-slider-navforas").each(
      function () {
        SmartShopBlocks.initSlickNavForAsSlider($(this));
      }
    );

    $("[class*='smartshopblock-'] .htsmartshop-faq").each(function () {
      SmartShopBlocks.initAccordion($(this));
    });

    $("[class*='smartshopblock-'].smartshop-product-addtocart").each(
      function () {
        SmartShopBlocks.quantityIncreaseDescrease($(this));
      }
    );

    /**
     * Tooltip Manager
     */
    SmartShopBlocks.smartshopToolTipHandler();
  });

  // For Editor Mode Slider
  document.addEventListener(
    "SmartshopEditorModeSlick",
    function (event) {
      let editorMainArea = $(".block-editor__container"),
        editorIframe = $("iframe.edit-site-visual-editor__editor-canvas"),
        productSliderDiv =
          editorIframe.length > 0
            ? editorIframe
                .contents()
                .find("body.block-editor-iframe__body")
                .find(
                  `.smartshopblock-editor-${event.detail.uniqid} .product-slider`
                )
            : editorMainArea.find(
                `.smartshopblock-editor-${event.detail.uniqid} .product-slider`
              );
      window.setTimeout(
        SmartShopBlocks.initSlickSlider(productSliderDiv),
        1000
      );
    },
    false
  );

  // For Editor Mode Nav For As Slider
  document.addEventListener(
    "SmartshopEditorModeNavForSlick",
    function (event) {
      let editorMainArea = $(".block-editor__container"),
        editorIframe = $("iframe.edit-site-visual-editor__editor-canvas"),
        navForAsSliderDiv =
          editorIframe.length > 0
            ? editorIframe
                .contents()
                .find("body.block-editor-iframe__body")
                .find(
                  `.smartshopblock-editor-${event.detail.uniqid} .smartshop-block-slider-navforas`
                )
            : editorMainArea.find(
                `.smartshopblock-editor-${event.detail.uniqid} .smartshop-block-slider-navforas`
              );
      window.setTimeout(
        SmartShopBlocks.initSlickNavForAsSlider(navForAsSliderDiv),
        1000
      );
    },
    false
  );
})(jQuery, window);
