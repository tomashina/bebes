// Copyright 2017-present, Facebook, Inc.
// All rights reserved.

// This source code is licensed under the license found in the
// LICENSE file in the root directory of this source tree.

; (function(facebookAdsExtension, window, document, undefined) {
  function hasMarketingConsent() {
    return window.CookieConsent && window.CookieConsent.acceptedCategory('marketing');
  }

  var facebookPixel =
    facebookAdsExtension.facebookPixel =
    facebookAdsExtension.facebookPixel || (function () {
    var init = function(facebook_pixel_id, pii, params) {
      function initWhenAllowed() {
        if (!hasMarketingConsent()) {
          // Consent may have been withdrawn while this script was loading.
          window.addEventListener('cc:onChange', initWhenAllowed);
          return;
        }
        window.removeEventListener('cc:onChange', initWhenAllowed);
        fbq('init', facebook_pixel_id, pii, params);
        fbq('trackSingle', facebook_pixel_id, 'PageView');
      }
      initWhenAllowed();
    };

    var firePixel = function(facebook_pixel_event_params) {
      if (!hasMarketingConsent()) return;
      var event_name = facebook_pixel_event_params.event_name;
      delete facebook_pixel_event_params.event_name;
      var track_param =
        facebook_pixel_event_params.is_custom_event ? 'trackCustom' : 'track';
      delete facebook_pixel_event_params.is_custom_event;
      fbq(track_param, event_name, facebook_pixel_event_params);
    };

    return {
      init: init,
      firePixel: firePixel
    };
  }());

  function fireProductInfoEvent(eventName, productID, quantity) {
    if (!hasMarketingConsent()) return;
    jQuery.get(
      'index.php?route=extension/facebookproduct/getproductinfoforfacebookpixel',
      {
        event_name: eventName,
        product_id: productID,
        quantity: quantity,
      },
        function (json) {
        if (json.facebook_pixel_event_params_FAE) {
          facebookPixel.firePixel(
            json.facebook_pixel_event_params_FAE);
        }
      }
    );
  }

  jQuery(function ($) {
    $('#button-cart').on('click', function() {
      var quantity = Number(
        $("input[name*='quantity'], input[id*='quantity']").first().val()
      );
      quantity = isNaN(quantity) || quantity <= 0 ? 1 : quantity;
      var productID = $('#fbProductID').val();
      fireProductInfoEvent('AddToCart', productID, quantity);
    });

    // catalog/view/javascript/common.js has a cart variable which is used by
    // various product listing pages to add product to cart directly.
    // We will like to modify and inject AddToCart event in the cart.add() method.
    // However, OpenCart's modification system does not support js and css files
    // as they are not accessible from the front end.
    // https://github.com/vqmod/vqmod/wiki/About-vQmod
    // Instead, we modify the existing cart.add method and fire off addToCart.
    // A ajax call is fire to the URl endpoint /getproductinfoforfacebookpixel
    // to get the price and name of the product
    // added defensive checks to ensure the cart is loaded
    if (typeof cart !== 'undefined') {
      var oldCartAdd = cart.add;
      cart.add = function(productID, quantity) {
        fireProductInfoEvent('AddToCart', productID, quantity);
        oldCartAdd.apply(oldCartAdd, [productID, quantity]);
      };
    } else {
      console.log('cart variable is not available, AddToCart event is not able to be fired');
    }


    // adopting the same cart.add strategy for wishlist.add
    // added defensive checks to ensure the wishlist is loaded
    if (typeof wishlist !== 'undefined') {
      var oldWishlist = wishlist.add;
      wishlist.add = function(productID) {
        fireProductInfoEvent('AddToWishlist', productID);
        oldWishlist.apply(oldWishlist, [productID]);
      };
    } else {
      console.log('wishlist variable is not available, AddToWishlist event is not able to be fired');
    }
  });
}(window._facebookAdsExtension = window._facebookAdsExtension || {}, window, document));
