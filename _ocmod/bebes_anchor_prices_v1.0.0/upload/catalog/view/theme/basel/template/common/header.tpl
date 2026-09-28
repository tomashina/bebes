<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<!-- CookieConsent (HR) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3/dist/cookieconsent.css">
<script defer src="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3/dist/cookieconsent.umd.js"></script>

<style>
:root{
  --cc-bg:#f7f3eb;
  --cc-bg-soft:#f3ecdf;
  --cc-bg-soft-2:#efe6d8;
  --cc-text:#2f2924;
  --cc-title:#3b2b24;
  --cc-border:#d8ccba;
  --cc-border-soft:#e2d6c4;
  --cc-brown:#3b2b24;
  --cc-brown-h:#2f221d;
  --cc-pink:#d59aa5;
  --cc-pink-h:#c88492;
  --cc-overlay:rgba(20,14,11,.58);
}

/* Consent modal */
#cc-main .cm{
  max-width:600px;
  width:calc(100% - 32px);
  background:var(--cc-bg)!important;
  color:var(--cc-text)!important;
  border:1px solid var(--cc-border)!important;
  border-radius:14px!important;
  box-shadow:0 18px 44px rgba(0,0,0,.28)!important;
  padding:14px!important;
}
#cc-main .cm__title,
#cc-main .pm__title{
  color:var(--cc-title)!important;
}
#cc-main .cm__desc,
#cc-main .pm__desc,
#cc-main .pm__section-desc{
  color:var(--cc-text)!important;
}

/* Buttons base */
#cc-main .cm__btn,
#cc-main .pm__btn{
  min-height:44px!important;
  border-radius:8px!important;
  font-weight:700!important;
  transition:all .2s ease!important;
}

/* Prihvati sve + Samo nužni */
#cc-main button[data-cc="accept-all"],
#cc-main button[data-cc="accept-necessary"],
#cc-main button[data-role="all"],
#cc-main button[data-role="necessary"]{
  background:var(--cc-brown)!important;
  color:#fff!important;
  border:1px solid var(--cc-brown)!important;
}
#cc-main button[data-cc="accept-all"]:hover,
#cc-main button[data-cc="accept-necessary"]:hover,
#cc-main button[data-role="all"]:hover,
#cc-main button[data-role="necessary"]:hover{
  background:var(--cc-brown-h)!important;
  border-color:var(--cc-brown-h)!important;
}

/* Postavke (donji gumb) */
#cc-main button[data-cc="show-preferencesModal"],
#cc-main button[data-role="settings"],
#cc-main .cm__btn--secondary{
  background:transparent!important;
  color:#b66f7e!important;
  border:1px solid var(--cc-pink)!important;
}
#cc-main button[data-cc="show-preferencesModal"]:hover,
#cc-main button[data-role="settings"]:hover,
#cc-main .cm__btn--secondary:hover{
  background:#f9eef1!important;
  color:#a85f6f!important;
  border-color:var(--cc-pink-h)!important;
}

/* Preferences modal - all beige (no gray) */
#cc-main .pm,
#cc-main .pm__header,
#cc-main .pm__body,
#cc-main .pm__footer{
  background:var(--cc-bg)!important;
}
#cc-main .pm{
  border:1px solid var(--cc-border)!important;
  border-radius:14px!important;
}
#cc-main .pm__section{
  background:var(--cc-bg)!important;
  border:1px solid var(--cc-border-soft)!important;
  border-radius:10px!important;
  padding: 10px !important;
}
#cc-main .pm__section--expandable .pm__section-title-wrapper,
#cc-main .pm__section--expandable .pm__section-title,
#cc-main .pm__section--expandable .pm__section-title *{
  background:var(--cc-bg-soft-2)!important;
  color:var(--cc-title)!important;
}
#cc-main .pm__section--read-only{
  background:var(--cc-bg-soft)!important;
  border:1px solid #decfb9!important;
  border-radius:10px!important;
  padding:14px!important;
  margin-bottom:12px!important;
}
#cc-main .pm__section--read-only .pm__section-title{
  margin:0 0 8px 0!important;
}
#cc-main .pm__section--read-only .pm__section-desc{
  margin:0!important;
  line-height:1.45!important;
}

/* Close button */
#cc-main .pm__close-btn{
  background:var(--cc-bg-soft-2)!important;
  color:var(--cc-title)!important;
  border:1px solid var(--cc-border)!important;
}
#cc-main .pm__close-btn:hover{
  background:#e5d8c4!important;
}

/* Save in preferences */
#cc-main button[data-cc="save-preferences"],
#cc-main .pm__btn--save,
#cc-main button[data-role="save"]{
  background:var(--cc-brown)!important;
  color:#fff!important;
  border:1px solid var(--cc-brown)!important;
}
#cc-main button[data-cc="save-preferences"]:hover,
#cc-main .pm__btn--save:hover,
#cc-main button[data-role="save"]:hover{
  background:var(--cc-brown-h)!important;
  border-color:var(--cc-brown-h)!important;
}

/* Toggles */
#cc-main .pm__toggle-slider{
  background:#c8b9a6!important;
}
#cc-main .pm__toggle:checked + .pm__toggle-slider{
  background:var(--cc-brown)!important;
}

/* Overlay + lock */
html.show--consent::before{
  content:'';
  position:fixed;
  inset:0;
  background:var(--cc-overlay);
  z-index:2147483646;
}
html.show--consent body{ overflow:hidden; }
html.show--consent body *{ pointer-events:none!important; }
html.show--consent #cc-main,
html.show--consent #cc-main *{ pointer-events:auto!important; }
#cc-main{ z-index:2147483647; }

/* Cookie floater */
#cookie-fab{
  position:fixed;
  left:18px;
  bottom:18px;
  width:46px;
  height:46px;
  border-radius:999px;
  border:1px solid var(--cc-border);
  background:var(--cc-bg);
  color:var(--cc-title);
  font-size:22px;
  line-height:1;
  display:none;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  box-shadow:0 8px 22px rgba(0,0,0,.18);
  z-index:2147483647;
}
#cookie-fab:hover{
  background:var(--cc-bg-soft-2);
}
</style>

<script>
(function () {
  var GTM_ID = 'GTM-MTSFB9ZP';
  var FB_PIXEL_ID = '2372050239696518';
  var MIDAS_PIXEL_URL = 'https://cdn.midas-network.com/MidasPixel/IndexAsync/c630ce68-331f-460d-90ca-40e2270feabc';

  var gtmLoaded = false;
  var fbLoaded = false;
  var midasLoaded = false;

  function loadGTM() {
    if (gtmLoaded) return;
    gtmLoaded = true;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });

    var f = document.getElementsByTagName('script')[0];
    var j = document.createElement('script');
    j.async = true;
    j.src = 'https://www.googletagmanager.com/gtm.js?id=' + GTM_ID;
    f.parentNode.insertBefore(j, f);
  }

  function loadFacebookPixel() {
    if (fbLoaded) return;
    fbLoaded = true;

    !function(f,b,e,v,n,t,s){
      if(f.fbq)return;
      n=f.fbq=function(){n.callMethod ? n.callMethod.apply(n,arguments) : n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;
      n.push=n; n.loaded=!0; n.version='2.0'; n.queue=[];
      t=b.createElement(e); t.async=!0; t.src=v;
      s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);
    }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', FB_PIXEL_ID);
    fbq('track', 'PageView');
  }

  function loadMidasPixel() {
    if (midasLoaded) return;
    midasLoaded = true;

    var img = document.createElement('img');
    img.height = 1;
    img.width = 1;
    img.style.display = 'none';
    img.src = MIDAS_PIXEL_URL;
    document.body.appendChild(img);
  }

  function hasConsentCookie() {
    return document.cookie.indexOf('cc_cookie=') !== -1;
  }

  function toggleCookieFab() {
    var fab = document.getElementById('cookie-fab');
    if (!fab) return;
    fab.style.display = hasConsentCookie() ? 'flex' : 'none';
  }

  function applyConsent() {
    if (CookieConsent.acceptedCategory('analytics')) {
      loadGTM();
    }
    if (CookieConsent.acceptedCategory('marketing')) {
      loadFacebookPixel();
      loadMidasPixel();
    }
  }

  window.addEventListener('load', function () {
    CookieConsent.run({
      disablePageInteraction: true,
      guiOptions: {
        consentModal: {
          layout: 'box',
          position: 'middle center'
        },
        preferencesModal: {
          layout: 'box'
        }
      },
      categories: {
        necessary: { enabled: true, readOnly: true },
        analytics: {},
        marketing: {}
      },
      language: {
        default: 'hr',
        translations: {
          hr: {
            consentModal: {
              title: 'Koristimo kolačiće',
              description: 'Koristimo nužne, analitičke i marketinške kolačiće za ispravan rad i mjerenje posjećenosti.',
              acceptAllBtn: 'Prihvati sve',
              acceptNecessaryBtn: 'Samo nužni',
              showPreferencesBtn: 'Postavke'
            },
            preferencesModal: {
              title: 'Postavke kolačića',
              acceptAllBtn: 'Prihvati sve',
              acceptNecessaryBtn: 'Samo nužni',
              savePreferencesBtn: 'Spremi odabir',
              closeIconLabel: 'Zatvori',
              sections: [
                {
                  title: 'Nužni kolačići',
                  description: 'Ovi kolačići su potrebni za rad stranice i ne mogu se isključiti.'
                },
                {
                  title: 'Analitički kolačići',
                  description: 'Pomažu nam razumjeti kako se stranica koristi.',
                  linkedCategory: 'analytics'
                },
                {
                  title: 'Marketinški kolačići',
                  description: 'Koriste se za prikaz relevantnih oglasa i remarketing.',
                  linkedCategory: 'marketing'
                }
              ]
            }
          }
        }
      },
      onFirstConsent: function () { applyConsent(); toggleCookieFab(); },
      onConsent: function () { applyConsent(); toggleCookieFab(); },
      onChange: function () { applyConsent(); toggleCookieFab(); }
    });

    toggleCookieFab();

    var fab = document.getElementById('cookie-fab');
    if (fab) {
      fab.addEventListener('click', function () {
        CookieConsent.showPreferences();
      });
    }
  });
})();
</script>
<!-- End CookieConsent -->

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta name="google-site-verification" content="AsBhI8fYkznfmZY9fxapKquVZwfwva-Ydyhb-Z7Mk4o" />
<?php if ($description) { ?><meta name="description" content="<?php echo $description; ?>" /><?php } ?>
<?php if ($keywords) { ?><meta name="keywords" content= "<?php echo $keywords; ?>" /><?php } ?>

<!-- Load essential resources -->
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
<script src="catalog/view/theme/basel/js/slick.min.js"></script>
<script src="catalog/view/theme/basel/js/basel_common.js"></script>

<!-- Main stylesheet -->
<link href="catalog/view/theme/basel/stylesheet/stylesheet.css?v=1.5" rel="stylesheet">
<!-- Mandatory Theme Settings CSS -->
<style id="basel-mandatory-css"><?php echo $basel_mandatory_css; ?></style>

<!-- Plugin Stylesheet(s) -->
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<!-- Pluing scripts(s) -->
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>"></script>
<?php } ?>

<!-- Page specific meta information -->
<?php foreach ($links as $link) { ?>
<?php if ($link['rel'] == 'image') { ?>
<meta property="og:image" content="<?php echo $link['href']; ?>" />
<?php } else { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php } ?>

<!-- Analytic tools -->
<?php
$cc_allow_tracking = false;

if (!empty($_COOKIE['cc_cookie'])) {
  $raw = html_entity_decode($_COOKIE['cc_cookie'], ENT_QUOTES, 'UTF-8');
  $raw = urldecode($raw);
  $cc = json_decode($raw, true);

  if (!empty($cc['categories']['analytics']) || !empty($cc['categories']['marketing'])) {
    $cc_allow_tracking = true;
  }
}
?>

<?php if ($cc_allow_tracking) { ?>
  <?php foreach ($analytics as $analytic) { ?>
    <?php echo $analytic; ?>
  <?php } ?>
<?php } ?>

<?php if (isset($basel_styles_status)) { ?>
<!-- Custom Color Scheme -->
<style id="basel-color-scheme"><?php echo $basel_styles_cache; ?></style>
<?php } ?>

<?php if (isset($basel_typo_status)) { ?>
<!-- Custom Fonts -->
<style id="basel-fonts"><?php echo $basel_fonts_cache; ?></style>
<?php } ?>

<?php if ($direction == 'rtl') { ?>
<link href="catalog/view/theme/basel/stylesheet/rtl.css" rel="stylesheet">
<?php } ?>

<?php if ($basel_custom_css_status) { ?>
<!-- Custom CSS -->
<style id="basel-custom-css">
<?php echo $basel_custom_css; ?>
</style>
<?php } ?>

<?php if ($basel_custom_js_status) { ?>
<!-- Custom Javascript -->
<script>
<?php echo $basel_custom_js; ?>
</script>
<?php } ?>
</head>
<body class="<?php echo $class; ?><?php echo $basel_body_class; ?>">
<button id="cookie-fab" type="button" aria-label="Postavke kolačića" title="Postavke kolačića">🍪</button>

<?php require_once('catalog/view/theme/basel/template/common/mobile-nav.tpl'); ?>
<div class="outer-container main-wrapper">
<?php if ($notification_status) { ?>
<div class="top_notificaiton">
  <div class="container<?php if ($top_promo_close) echo ' has-close'; ?> <?php echo $top_promo_width; ?> <?php echo $top_promo_align; ?>">
    <div class="table">
      <div class="table-cell w100"><div class="ellipsis-wrap"><?php echo $top_promo_text; ?></div></div>
      <?php if ($top_promo_close) { ?>
      <div class="table-cell text-right">
        <a onClick="addCookie('basel_top_promo', 1, 30);$(this).closest('.top_notificaiton').slideUp();" class="top_promo_close">&times;</a>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
<?php require_once('catalog/view/theme/basel/template/common/headers/' . $basel_header . '.tpl'); ?>

<!-- breadcrumb -->
<div class="breadcrumb-holder">
  <div class="container">
    <span id="title-holder">&nbsp;</span>
    <div class="links-holder">
      <a class="basel-back-btn" onClick="history.go(-1); return false;"><i></i></a><span>&nbsp;</span>
    </div>
  </div>
</div>

<div class="container">
<?php echo $position_top; ?>
</div>
