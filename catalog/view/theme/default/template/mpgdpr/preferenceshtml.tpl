<div class="mpgdpr-wrap">
  <div class="mpgdpr-cookie">
    <div class="mpgdpr-container">
      <div class="mpgdpr-text">
        <h3><?php echo $text_heading; ?></h3>

        <p style="margin-top:15px">Altitudo centar d.o.o. prikuplja i obrađuje Vaše osobne podatke kada pristupite stranici.
Za funkcioniranje stranice koristimo kolačiće. Više o pravilima kolačića saznajte na <a href="https://atelierbebes.com/privatnost-i-kolacici" rel="noopener noreferrer nofollow" target="_blank">Zaštita privatnosti i kolačići</a>.
Vaša prava su pravo na pristup, pravo na brisanje, pravo na ispravak, pravo na prigovor, pravo na prenosivost te pravo na ograničenje obrade.
Privolu koju nam dajete klikom na pojedinu kategoriju kolačića možete u bilo kojem trenutku povući.
Detaljnije o vašim pravima možete saznati na <a href="https://atelierbebes.com/pravila-privatnosti"> Pravila privatnosti</a> ili možete kontaktirati ALTITUDO CENTAR d.o.o. na e-mail adresu: <a href="">info@atelierbebes.com</a>
</p>
          <label><input type="checkbox" checked="checked" disabled="disabled" value="m:required" /> <?php echo $text_cookie_strickly; ?></label>
          <br/><p><?php echo $text_cookie_strickly_detail; ?></p>
          <label><input type="checkbox" name="mcookie_analytics" value="m:required" /> <?php echo $text_cookie_analytics; ?></label>
          <br/><p><?php echo $text_cookie_analytics_detail; ?></p>
          <label><input type="checkbox" name="mcookie_marketing" value="m:required" /> <?php echo $text_cookie_marketing; ?></label>
          <br/><p><?php echo $text_cookie_marketing_detail; ?></p>
      </div>
      <div class="set-btns">
        <a class="mcookie-btn mp-prefrences-close"><?php echo $button_close; ?></a>
        <a class="mcookie-btn mpprefrences-update"><?php echo $button_update; ?></a>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .mpgdpr-wrap { position: fixed; width: 100%; top: 30px; left: 0; z-index: 9999; display: none; }
  .mpgdpr-cookie { padding: 15px 25px; background: #000; color: #fff; width: 556px; margin: auto; height: 650px; overflow-y: auto; }
  .mpgdpr-cookie .mpgdpr-container { margin: auto; }
  .mpgdpr-cookie .mpgdpr-text{ display: block; vertical-align: top; }
  .mpgdpr-cookie .set-btns{ display: block; text-align: center; margin-top: 25px; }
  .mpgdpr-cookie .mcookie-btn{ border: none; font-size: 16px; padding: 10px 15px; cursor: pointer; border-radius: 5px; -webkit-border-radius: 5px; background: #cecece; color: #000; display: inline-block; font-weight: 550; margin-right: 10px}
  .mpgdpr-cookie h3{ font-size: 16px; }
  .mpgdpr-cookie h3{ margin-top: 0; margin-bottom: 0px; }
  <?php if ($cbcolor) { ?>

  .mpgdpr-cookie {
    background: <?php echo $cbcolor['box_bg']; ?>;
    color: <?php echo $cbcolor['box_text']; ?>
  }
  .mpgdpr-cookie h3 {
    color: <?php echo $cbcolor['box_text']; ?>
  }
  .mpgdpr-cookie .mcookie-btn {
    background: <?php echo $cbcolor['btn_bg']; ?>;
    color: <?php echo $cbcolor['btn_text']; ?>;
    padding: <?php echo $cbcolor['btn_padding']['top'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['right'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['bottom'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['left'] . $cbcolor['btn_padding']['unit']; ?>
  }
  <?php } ?>
  <?php echo $cbcss; ?>
</style>
<script type="text/javascript">
   var mpgdpr = {
    mcookies : {
      <?php /*
      var cookie = get('tracking');
      cookie['tracking']
      or
      var cookies = get(['tracking','currency','language']);
      cookies['tracking']
      cookies['currency']
      cookies['language']
      or
      var cookies = get();
      all cookies in array {cookie-name1 : cookie-value1,cookie-name2 : cookie-value2}
      */ ?>
      get : function(name) {
        function getCookie(name) {
          if (name) {
            var value = '; ' + document.cookie;
            var parts = value.split('; ' + name + '=');
            return parts.length < 2 ? undefined : parts.pop().split(';').shift();
          }
          var cookies = document.cookie.split('; ');
          var data = {};
          for (var i in cookies) {
            var cookie = cookies[i].split('=');
            if (cookie.length==2) {
              data[cookie[0]] = cookie[1];
            }
          }
          return data;
        }
        var data = {};
        if (typeof name == 'string') {
          data[name] = getCookie(name);
        }

        if (typeof name == 'object') {
          for (var i in name) {
            data[name[i]] =  getCookie(name[i]);
          }
        }
        if (typeof name == 'undefined' || '' == name) {
          data = getCookie();
        }
        return data;
      },
      <?php /*
        set([{
          "name" : "name of the cookie",
          "value" : "value",
          "expiryDays" : "specified in days (specify -1 for no expiry)",
          "domain" : "domain that the cookie 'name' belongs to. The cookie can only be read on this domain.",
          "path" : "the url path that the cookie 'name' belongs to. The cookie can only be read at this location",
          "secure" : "true the cookie will be created with the secure flag. Secure cookies will only be transmitted via HTTPS. true/false",
        }]);
        or
        set(
           "name of the cookie",
          "value",
          "specified in days (specify -1 for no expiry)",
          "domain that the cookie 'name' belongs to. The cookie can only be read on this domain.",
          "the url path that the cookie 'name' belongs to. The cookie can only be read at this location",
          "true the cookie will be created with the secure flag. Secure cookies will only be transmitted via HTTPS. true/false",
        );
      */ ?>
      set: function(cookies) {
        function setCookie(name, value, expiryDays, domain, path, secure) {

          if (expiryDays == -1) {
            var cookie = [
              name + '=' + value,
              'expires=' + 'Thu, 01 Jan 1970 00:00:01 GMT',
              'path=' + (path || '/')
            ];
          } else {
             var exdate = new Date();
            exdate.setDate(exdate.getDate() + (expiryDays || 365));

            var cookie = [
              name + '=' + value,
              'expires=' + exdate.toUTCString(),
              'path=' + (path || '/')
            ];
          }
          if (domain) {
            cookie.push('domain=' + domain);
          }
          if (secure) {
            cookie.push('secure');
          }
          <?php if ($logging) { ?>console.log("final setting cookie : " + cookie.join(';')+';'); <?php } ?>
          document.cookie = cookie.join(';')+';';
        }

        if (typeof cookies == 'undefined' || '' == cookies) {
          return;
        }
        if (typeof cookies == 'string') {
          var parts = cookies.split(";");
          <?php if ($logging) { ?>
            console.log("cookies string : " + cookies);
            console.log("parts : ");
            console.log(parts);
          <?php } ?>
          cookies = [{
            'name' : parts[0],
            'value' : parts[1],
            'expiryDays' : parts[3],
            'domain' : parts[4],
            'path' : parts[5],
            'secure' : parts[6],
          }];
        }
        <?php if ($logging) { ?>
          console.log("set cookies : ");
          console.log(cookies);
        <?php } ?>
        for (var i in cookies) {
          setCookie(
            cookies[i]['name'],
            cookies[i]['value'],
            cookies[i]['expiryDays'],
            cookies[i]['domain'],
            cookies[i]['path'],
            cookies[i]['secure']
          );
        }
      },
      <?php /*
        clear([{
          "name" : "name of the cookie",
          "domain" : "domain that the cookie 'name' belongs to. The cookie can only be read on this domain.",
          "path" : "the url path that the cookie 'name' belongs to. The cookie can only be read at this location",
        }]);
        or
        clear(
          "name of the cookie",
          "domain that the cookie 'name' belongs to. The cookie can only be read on this domain.",
          "the url path that the cookie 'name' belongs to. The cookie can only be read at this location",
        ]);
      */ ?>
      clear : function(cookies) {
        function clearCookie(name, domain, path) {
          mpgdpr.mcookies.set([{
            'name' : name,
            'value' : '',
            'expiryDays' : -1,
            'domain' : domain,
            'path' : path
          }]);
        }

        if (typeof cookies == 'undefined' || '' == cookies) {
          return;
        }
        if (typeof cookies == 'string') {
          var parts = cookies.split(";");
          <?php if ($logging) { ?>
          console.log("cookie string :" + cookies);
          console.log("parts")
          console.log(parts)
          <?php } ?>
          cookies = [{
            'name' : parts[0],
            'domain' : parts[1],
            'path' : parts[2]
          }];
        }
        for (var i in cookies) {
          clearCookie(
            cookies[i]['name'],
            cookies[i]['domain'],
            cookies[i]['path']
          );
        }
      },
    },
    instance : null ,
    err : null,
    deniedCookiess : <?php echo $deniedCookiess; ?>, <?php /* ['analytics', 'marketing'] // values comes as per user preferences */ ?>
    cookies : {
      analytics : <?php echo $cookies_analytics; ?>,
      marketing : <?php echo $cookies_marketing; ?>,
    },
    domains : <?php echo $cookie_domain; ?>, <?php /* ['.system','system'], */ ?>

    handle_cookie:function() {
      $('body').delegate('.mp-prefrences','click', function() {
        $('.mpgdpr-wrap').fadeIn('slow');
      });

      $('body').delegate('.mp-prefrences-close','click', function() {
        $('.mpgdpr-wrap').fadeOut('slow');
      });

      <?php /* deny all cookies */ ?>
      $('body').delegate('a.cc-mpdeny','click', function() {
        <?php /* here we disable all the cookies */ ?>
        <?php if ($logging) { ?>
        console.log("deny all cookies");
        <?php } ?>


       <?php /* "first we detect user prefrences. if user allow analytics or marketing cookies. If allowed then we will not disable them other wise disable according to the settings" */ ?>

        var disable = [];
        if (!(mpgdpr.deniedCookiess.indexOf('analytics') >= 0) && (<?php if ($cbaction_close == 'cookieanalytic_block' || $cbaction_close == 'cookieanalyticmarketing_block') { ?>true<?php } else { ?>false<?php } ?>) ) {
          disable.push('analytics');
          $('input[name="mcookie_analytics"]').prop('checked',false);
        }
        if (!(mpgdpr.deniedCookiess.indexOf('marketing') >= 0) && (<?php if ($cbaction_close == 'cookiemarketing_block' || $cbaction_close == 'cookieanalyticmarketing_block') { ?>true<?php } else { ?>false<?php } ?>) ) {
          disable.push('marketing');
          $('input[name="mcookie_marketing"]').prop('checked',false);
        }

        if (disable.length) {
          mpgdpr.mcookies.set('mpcookie_preferencesdisable;'+ disable.join(',') +';365');
          mpgdpr.deniedCookiess = disable;
        }

      });

      $('body').delegate('a.cc-mpallow','click', function() {
        <?php if ($cbpptrack && $cbpolicy && $cbpolicy_page_url) { ?>
        $.get('<?php echo $base; ?>index.php?route=mpgdpr/mpgdpr/acceptanceOfPp');
        <?php } ?>
      });

      $('body').delegate('a.mpprefrences-update','click', function() {
        <?php /* "here we disable all the cookies" */ ?>
        <?php if ($logging) { ?>
        console.log("update preferences as  per selections");
        <?php } ?>
        var disable = [];

        if (!$('input[name="mcookie_marketing"]').prop('checked')) disable.push('marketing');
        if (!$('input[name="mcookie_analytics"]').prop('checked')) disable.push('analytics');
        <?php if ($logging) { ?>
        console.log(disable);
        console.log('mpcookie_preferencesdisable;'+disable.join(',')+';365');
        <?php } ?>

        mpgdpr.mcookies.set('mpcookie_preferencesdisable;'+disable.join(',')+';365');

        mpgdpr.deniedCookiess = disable;
        $('input[name="mcookie_analytics"]').prop('checked', !(disable.indexOf('analytics') >= 0));
        $('input[name="mcookie_marketing"]').prop('checked', !(disable.indexOf('marketing') >= 0));

        <?php /* "01-05--2022: updation - analytics, marketing allow/deny code execution start" */ ?>
        if (!disable.indexOf('analytics') >= 0) {
          <?php if ($cookie_analytics_allow) { ?>(function () { <?php echo $cookie_analytics_allow; ?> })();<?php } ?>
        } else {
          <?php if ($cookie_analytics_deny) { ?>(function () { <?php echo $cookie_analytics_deny; ?> })();<?php } ?>
        }
        if (!disable.indexOf('marketing') >= 0) {
          <?php if ($cookie_marketing_allow) { ?>(function () { <?php echo $cookie_marketing_allow; ?> })();<?php } ?>
        } else {
          <?php if ($cookie_marketing_deny) { ?>(function () { <?php echo $cookie_marketing_deny; ?> })();<?php } ?>
        }
        <?php /* "01-05-2022: updation - analytics, marketing allow/deny code execution end" */ ?>

        $('.mpgdpr-wrap').fadeToggle('slow');
      });

    },

    maintainance_cookies:function() {

      var analytics = mpgdpr.deniedCookiess.indexOf('analytics') >= 0;
      var marketing = mpgdpr.deniedCookiess.indexOf('marketing') >= 0;
      <?php /* "first we detect user prefrences. if user disabled analytics or marketing cookies. If disabled then we will clear cookies accordingly other wise clear cookies according to the settings" */ ?>
      <?php if (($cbinitial == 'cookieanalytic_block' || $cbinitial == 'cookieanalyticmarketing_block') && (!in_array($cookieconsent_status, $cookieconsentstatuss)) ) { ?>if (!analytics) { analytics = true; }<?php } ?>
      <?php if (($cbinitial == 'cookiemarketing_block' || $cbinitial == 'cookieanalyticmarketing_block') && (!in_array($cookieconsent_status, $cookieconsentstatuss)) ) { ?>if (!marketing) { marketing = true; }<?php } ?>
      $('input[name="mcookie_analytics"]').prop('checked', (!analytics));
      $('input[name="mcookie_marketing"]').prop('checked', (!marketing));

      $.each(mpgdpr.mcookies.get(), function(key, value) {
        if (analytics && mpgdpr.cookies.analytics.indexOf(key) >= 0) {
          for (var i in mpgdpr.domains) {
            mpgdpr.mcookies.clear(key+';'+mpgdpr.domains[i]+';'+'/');
          }
        }
        if (marketing && mpgdpr.cookies.marketing.indexOf(key) >= 0) {
          for (var i in mpgdpr.domains) {
            mpgdpr.mcookies.clear(key+';'+mpgdpr.domains[i]+';'+'/');
          }
        }
      });

      <?php /* "01-05-2022: updation - analytics, marketing allow/deny code execution start" */ ?>
      if (!analytics) {
        <?php if ($cookie_analytics_allow) { ?>(function () { <?php echo $cookie_analytics_allow; ?> })();<?php } ?>
      } else {
        <?php if ($cookie_analytics_deny) { ?>(function () { <?php echo $cookie_analytics_deny; ?> })();<?php } ?>
      }
      if (!marketing) {
        <?php if ($cookie_marketing_allow) { ?>(function () { <?php echo $cookie_marketing_allow; ?> })();<?php } ?>
      } else {
        <?php if ($cookie_marketing_deny) { ?>(function () { <?php echo $cookie_marketing_deny; ?> })();<?php } ?>
      }
      <?php /* 01-05-2022: updation - analytics, marketing allow/deny code execution end */ ?>
    },
    cookieconsent:function() {
      window.cookieconsent.initialise({
        <?php /* "autoOpen" : true, */ ?>
        "type" : 'opt-in',
        "position" : '<?php echo $position; ?>',
        <?php if ($static) { ?>
        "static" : true,
        <?php } ?>
        "palette" : {
          "popup": {
            "background": "<?php echo $cbcolor['box_bg']; ?>",
            "text": "<?php echo $cbcolor['box_text']; ?>"
          },
          "button": {
            "background": "<?php echo $cbcolor['btn_bg']; ?>",
            "text": "<?php echo $cbcolor['btn_text']; ?>",
            "padding": "<?php echo $cbcolor['btn_padding']['top'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['right'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['bottom'] . $cbcolor['btn_padding']['unit']; ?> <?php echo $cbcolor['btn_padding']['left'] . $cbcolor['btn_padding']['unit']; ?>"
          }
        },
        "revokable" : !!<?php echo $cbshowagain; ?>,
        "showLink" : !!<?php echo $cbpolicy; ?>,
        "content": {
          "message": "<?php echo $text_cookielang_msg; ?>",
          "deny" : "<?php echo $text_cookielang_btn_deny; ?>",
          "allow" : "<?php echo $text_cookielang_btn_accept; ?>",
          "prefrences" : "<?php echo $text_cookielang_btn_prefrence; ?>",
          <?php if ($cbpolicy_page_url && $cbpolicy) { ?>
          "link" : '<?php echo $cbpolicy_page_text; ?>',
          "href" : '<?php echo $cbpolicy_page_url; ?>',
          <?php } ?>
          "policy" : '<?php echo $text_cookielang_btn_showagain; ?>'
        },

      },
      function(popup) {
        mpgdpr.instance = popup;
      },
      function(err, popup) {
        mpgdpr.instance = popup;
        mpgdpr.err = err;
      });
    },
  };
</script>