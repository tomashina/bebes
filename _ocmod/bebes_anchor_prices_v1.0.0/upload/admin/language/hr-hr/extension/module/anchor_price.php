<?php
// Naslov
$_['heading_title'] = 'Sidrene cijene';

// Tekst
$_['text_extension'] = 'Proširenja';
$_['text_list'] = 'Registar sidrenih cijena';
$_['text_edit'] = 'Uredi sidrenu cijenu';
$_['text_filter'] = 'Filtri';
$_['text_publications'] = 'Dnevni cjenici';
$_['text_settings'] = 'Postavke i automatizacija';
$_['text_no_results'] = 'Nema pronađenih zapisa.';
$_['text_all_statuses'] = 'Svi statusi';
$_['text_status_confirmed'] = 'Potvrđeno';
$_['text_status_pending'] = 'Čeka provjeru';
$_['text_status_disabled'] = 'Isključeno';
$_['text_system'] = 'Sustav';
$_['text_missing_count'] = 'Aktivnih artikala bez sidrene cijene: %s';
$_['text_success_edit'] = 'Uspješno: Sidrena cijena i revizijski trag su ažurirani.';
$_['text_success_sync'] = 'Uspješno: Kreirano je %s nedostajućih sidrenih cijena.';
$_['text_success_publish'] = 'Uspješno: Objavljena su oba cjenika: %s';
$_['text_success_settings'] = 'Uspješno: Postavke sidrenih cijena su spremljene.';
$_['text_reference_rule'] = 'Artikli objavljeni do uključivo 10. rujna 2026. koriste 2026-09-10. Kasniji artikli koriste stvarni datum prve objave.';
$_['text_cron_help'] = 'BBM priprema PJ1 u 00:20. Objava za PJ1 i PJ3 pokreće se svaki dan u 00:30 (Europe/Zagreb). Ponovite objavu na dan promjene cijena do 11:00.';
$_['text_audit'] = 'Revizijski trag';

// Stupci
$_['column_product'] = 'Artikl';
$_['column_model'] = 'Model / SKU';
$_['column_net_price'] = 'Neto sidrena cijena';
$_['column_gross_price'] = 'Bruto sidrena cijena';
$_['column_reference_date'] = 'Referentni datum';
$_['column_status'] = 'Status';
$_['column_action'] = 'Radnja';
$_['column_location'] = 'Prodajno mjesto';
$_['column_sequence'] = 'Redni broj';
$_['column_filename'] = 'Datoteka';
$_['column_products'] = 'Artikala';
$_['column_published'] = 'Objavljeno';
$_['column_user'] = 'Korisnik';
$_['column_reason'] = 'Razlog';
$_['column_before'] = 'Prije';
$_['column_after'] = 'Poslije';
$_['column_date_added'] = 'Datum';

// Polja
$_['entry_filter_name'] = 'Naziv artikla';
$_['entry_filter_model'] = 'Model / SKU';
$_['entry_filter_status'] = 'Status provjere';
$_['entry_date_from'] = 'Referentni datum od';
$_['entry_date_to'] = 'Referentni datum do';
$_['entry_price'] = 'Neto iznos';
$_['entry_gross_price'] = 'Bruto iznos';
$_['entry_reference_date'] = 'Referentni datum';
$_['entry_verification_status'] = 'Status provjere';
$_['entry_reason'] = 'Razlog promjene';
$_['entry_default_unit'] = 'Zadana prodajna jedinica';
$_['entry_cron_url'] = 'URL dnevnog cron zadatka';
$_['entry_cron_key'] = 'Cron ključ';

// Gumbi
$_['button_filter'] = 'Filtriraj';
$_['button_clear'] = 'Očisti';
$_['button_sync'] = 'Kreiraj nedostajuće sidrene cijene';
$_['button_publish'] = 'Objavi PJ1 + PJ3 CSV';
$_['button_settings'] = 'Spremi postavke';
$_['button_download'] = 'Preuzmi';

// Pomoć
$_['help_reason'] = 'Obvezno. Razlog se trajno čuva u revizijskom tragu.';
$_['help_gross_price'] = 'Bruto snapshot s porezom. Mijenjajte samo kada je potrebno ispraviti sam spremljeni snapshot.';
$_['help_default_unit'] = 'Prodajna jedinica za komadnu robu. Za mjerenu robu ispunite atribute Neto količina i Jedinica mjere.';

// Upozorenja i greške
$_['warning_publication_due'] = 'Dnevni cjenici nisu oba objavljena do 11:00. Nedostaje: %s.';
$_['warning_product_data'] = '%s artikala web trgovine zahtijeva provjeru. Bazna cijena se objavljuje nakon potvrde.';
$_['text_product_warnings'] = 'Prikaži artikle i razloge upozorenja';
$_['warning_anchor_missing'] = 'Nedostaje sidrena cijena';
$_['warning_anchor_unconfirmed'] = 'Sidrena cijena nije potvrđena';
$_['warning_name_missing'] = 'Nedostaje naziv';
$_['warning_model_missing'] = 'Nedostaje model / šifra';
$_['warning_manufacturer_missing'] = 'Nedostaje proizvođač / marka';
$_['warning_ean_invalid'] = 'EAN nije valjani GTIN';
$_['warning_jan_invalid'] = 'JAN nije valjani GTIN';
$_['warning_isbn_invalid'] = 'ISBN nije valjani GTIN';
$_['error_permission'] = 'Upozorenje: Nemate ovlasti za izmjenu modula Sidrene cijene.';
$_['error_not_installed'] = 'Tablice modula ne postoje. Najprije instalirajte modul kroz Proširenja.';
$_['error_not_found'] = 'Tražena sidrena cijena nije pronađena.';
$_['error_price'] = 'Unesite ispravan nenegativan neto iznos.';
$_['error_gross_price'] = 'Unesite ispravan nenegativan bruto iznos.';
$_['error_reference_date'] = 'Unesite ispravan datum u obliku GGGG-MM-DD.';
$_['error_status'] = 'Odaberite ispravan status provjere.';
$_['error_reason'] = 'Razlog mora sadržavati između 3 i 255 znakova.';
$_['error_default_unit'] = 'Zadana jedinica mora sadržavati između 1 i 16 znakova.';
$_['error_file_missing'] = 'Datoteka objave nije dostupna ili joj je istekao rok čuvanja od 30 dana.';
$_['entry_history_group'] = 'Skupina robe';
$_['text_history_may'] = 'Hrana, piće, kozmetika, sredstva za čišćenje, toaletne potrepštine i proizvodi za kućanstvo';
$_['text_history_other'] = 'Ostala roba';
$_['entry_first_offer'] = 'Datum prve ponude';
$_['entry_reference_sale'] = 'Akcija ili popust na referentni dan';
$_['help_history_amount'] = 'Uz akciju za prvu skupinu upišite najnižu cijenu u 30 dana prije početka akcije. Za ostalu robu upišite prethodno primijenjenu cijenu. U razlogu navedite izvor provjere.';
$_['warning_net_quantity'] = 'Provjerite neto količinu i jedinicu za usporedbu cijena.';
$_['entry_bbm_password'] = 'BBM lozinka za PJ1';
$_['help_bbm_password'] = 'Upišite lozinku samo pri postavljanju ili promjeni. Račun: iis_altitudo.';

$_['warning_legacy_history_review'] = 'Provjerite povijesnu podlogu prethodno potvrđene cijene.';
$_['warning_unit_missing'] = 'Potvrdite jedinicu mjere.';
$_['warning_zero_retail'] = 'Potvrdite maloprodajnu cijenu od 0,00 EUR.';
