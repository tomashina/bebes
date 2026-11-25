<!-- Google Tag Manager -->
<script>
window.dataLayer = window.dataLayer || [];
window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
(function (w, d) {
  w.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
  var first = d.getElementsByTagName('script')[0];
  var script = d.createElement('script');
  script.async = true;
  script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-MTSFB9ZP';
  first.parentNode.insertBefore(script, first);
})(window, document);
</script>
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
  var FB_PIXEL_ID = '2372050239696518';
  var MIDAS_PIXEL_URL = 'https://cdn.midas-network.com/MidasPixel/IndexAsync/c630ce68-331f-460d-90ca-40e2270feabc';

  var fbLoaded = false;
  var midasLoaded = false;

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
    fbq('trackSingle', FB_PIXEL_ID, 'PageView');
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
    var marketing = CookieConsent.acceptedCategory('marketing');
    if (window.fbq) {
      window.fbq('consent', marketing ? 'grant' : 'revoke');
    }
    if (marketing) {
      loadFacebookPixel();
      loadMidasPixel();
    }
    toggleCookieFab();
  }

  function publishConsent(choice) {
    var saved = CookieConsent.getCookie();
    if (saved && saved.consentId === choice.cookie.consentId &&
        JSON.stringify(saved.categories) === JSON.stringify(choice.cookie.categories)) {
      window.dataLayer.push({ event: 'cookie_consent_update' });
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
      onFirstConsent: publishConsent,
      onConsent: applyConsent,
      onChange: function (choice) {
        applyConsent();
        publishConsent(choice);
      }
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
