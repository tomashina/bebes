-- Publish the complete Croatian Terms and Conditions from the 2026-09-02 source document.
-- This updates the existing OpenCart Information page used by the footer and checkout.
-- Run only after a verified database backup. The production oc_ prefix, information ID 7,
-- and hr-hr language ID 2 were verified before this migration was prepared.

SET NAMES utf8mb4;
START TRANSACTION;

SET @terms_information_id := (
    SELECT CAST(`value` AS UNSIGNED)
    FROM `oc_setting`
    WHERE `store_id` = 0
      AND `key` = 'config_checkout_id'
    ORDER BY `setting_id` DESC
    LIMIT 1
);

SET @terms_language_id := (
    SELECT `language_id`
    FROM `oc_language`
    WHERE `code` = 'hr-hr'
    ORDER BY `language_id`
    LIMIT 1
);

UPDATE `oc_information_description` AS `description`
JOIN `oc_information` AS `information`
  ON `information`.`information_id` = `description`.`information_id`
SET `description`.`description` = '<style>
.terms-content { font-family: "Times New Roman", serif; font-size: 16px; line-height: 1.55; }
.terms-content p { margin: 0 0 1em; text-align: justify; }
.terms-content h2 { color: #0070c0; font-family: "Times New Roman", serif; font-size: 1.2em; font-weight: 700; line-height: 1.3; margin: 1.6em 0 .65em; text-align: left; }
.terms-content ul { margin: 0 0 1em 1.5em; padding: 0; }
.terms-content li { margin: .2em 0; }
</style>
<div class="terms-content">
<p>Na Uvjete kupnje web stranice <a href="https://atelierbebes.com/">https://atelierbebes.com/</a> i sklapanja ugovora na daljinu putem sredstva daljinske komunikacije primjenjuju se prvenstveno ovdje navedeni Uvjeti kupnje, osim u slučaju da se ugovorene strane (Prodavatelj i Kupac) ne dogovore drugačije. Uvjeti kupnje podliježu Zakonu o zaštiti potrošača (NN 19/22, 59/23) te Zakonu o obveznim odnosima (NN 35/05, 41/08, 125/11, 78/15, 29/18, 126/21, 114/22, 156/22, 155/23). Kupci trebaju obratiti posebnu pozornost na razliku uvjeta koji se primjenjuju na kupnju u fizičkoj trgovini i kupnju na daljinu (Internet trgovina).</p>
<p>Kako bi kupac obavio kupnju na web shopu, registracija nije obavezna. Potrebno je samo u prvom koraku checkout procesa unijeti određene podatke (ime, prezime, e-mail adresu, broj telefona, adresu, grad, poštanski broj, državu, županiju) koji su nužni za dostavu robe i slanje potvrde o narudžbi.</p>
<p>Ako kupac ne želi svaki put tijekom kupnje unositi adresu dostave i druge potrebne podatke, preporuča se prijava. Potrebno je samo jednom, prilikom kreiranja korisničkog računa, unijeti ime, prezime, e-mail adresu, broj telefona, adresu, grad, poštanski broj, državu, županiju (po izboru je moguće unijeti podatke o tvrtki te broj faxa) te lozinku pomoću koje se pristupa korisničkom računu. Ona može biti proizvoljan niz znakova - što je duža, otpornija je na hakersko probijanje. Ako je kupac bez ideja, na web stranici Password generator može dobiti generiranu lozinku.</p>
<p>Nakon unosa traženih podataka, kupac može označiti da li želi primati newsletter te je registraciju potrebno potvrditi klikom na polje "Registracija". Kreirani korisnički račun ostaje registriran u sustavu, a pristupa mu se kombinacijom e-mail adrese i lozinke klikom na poveznicu "Korisnički račun".</p>
<p>U slučaju da je kupac zaboravio lozinku, na istoj stranici treba kliknuti na "Zaboravljena lozinka". Kupac zatim dolazi do stranice na kojoj može ostaviti svoju e-mail adresu s kojom se registrirao na web shopu, a na koju onda sustav automatski pošalje link pomoću kojeg može resetirati lozinku i dobiti novu. Navedenu lozinku kupac kasnije može sam promijeniti u postavkama računa budući da automatski kreirane lozinke nisu lako ''pamtljive'' (ili samo može kliknuti na DA kada ga preglednik pita da upamti unesenu lozinku da ju ne mora svaki put unositi kod prijave).</p>
<p>Ukratko, nakon kreiranja računa, za svaku daljnju kupnju potrebna je samo prijava s e-mailom i lozinkom.</p>
<h2>OSNOVNI PODACI O PRODAVATELJU</h2>
<p>Naziv: ALTITUDO CENTAR d.o.o. (dalje u tekstu: Društvo)</p>
<p>Sjedište: Nikole Jurišića 8, 10000 Zagreb</p>
<p>Upisano u reg. uložak Trgovačkog suda u Zagrebu pod MBS subjekta: 080604135</p>
<p>IBAN: HR9523600001102670022</p>
<p>OIB: 59774997564</p>
<p>MBS: 080604135</p>
<p>Jedini član društva i osoba ovlaštena za zastupanje: Anita Kandare</p>
<p>Broj telefona: 099 337 3730</p>
<p>Adresa elektroničke pošte: info@atelierbebes.com</p>
<p>Adresa za dostavu pošte: Nikole Jurišića 8, 10000 Zagreb</p>
<p>Potrošač, u svojstvu kupca, sklapa ugovor o kupoprodaji s ALTITUDO CENTAR d.o.o., Nikole Jurišića 8, 10000 Zagreb u svojstvu prodavatelja (dalje u tekstu: Društvo i/ili Prodavatelj)</p>
<p>Pravne osobe kao kupci podliježu primjeni Zakona o obveznim odnosima i Zakona o elektroničkoj trgovini, te se na njih ne primjenjuje Zakon o zaštiti potrošača.</p>
<p>Na pravne osobe kao kupce ne odnose se odjeljci ovih Uvjeta kupnje pod nazivom „Materijalni nedostatak“, „Pravo na jednostrani raskid ugovora“ i „Obavijest o načinu pisanog prigovora potrošača“. U navedenim slučajevima primjenjuju se relevantne odredbe Zakona o obveznim odnosima i Zakona o elektroničkoj trgovini. Prodavatelj može prema svome izboru pravnoj osobi omogućiti u svakom konkretnom slučaju prava koja ima kupac koji je potrošač.</p>
<p>Korisnik je osoba koja koristi web stranicu <a href="https://atelierbebes.com/">https://atelierbebes.com/</a> (dalje u tekstu: web stranica), isto kao i svaki kupac i posjetitelj web stranice.</p>
<p>Sklapanje ugovora o kupoprodaji putem web stranice regulirano je u skladu sa zakonskim odredbama uzimajući u obzir naročito interpretativni učinak direktiva i uredaba Europske unije. Sklapanje ugovora putem web stranice predstavlja sklapanje ugovora na daljinu.</p>
<p>Ovi Uvjeti kupnje predstavljaju i predugovornu obavijest kada ugovor o kupoprodaji sklapa potrošač, odnosno svaka fizička osoba koja sklapa pravni posao ili djeluje na tržištu izvan svoje trgovačke, poslovne, obrtničke ili profesionalne djelatnosti, te ukoliko se ugovor sklapa između trgovca i potrošača u okviru organiziranog sustava prodaje ili pružanja usluge bez istodobne fizičke prisutnosti trgovca i potrošača na jednome mjestu, pri čemu se do trenutka sklapanja ugovora te za sklapanje ugovora isključivo koristi jedno ili više sredstava daljinske komunikacije.</p>
<p>Sredstva daljinske komunikacije jesu sva sredstva koja se bez istodobne fizičke prisutnosti trgovca i potrošača mogu koristiti za sklapanje ugovora na daljinu, kao što su internet i elektronička pošta.</p>
<p>Prodavatelj nije dužan ispuniti svoje ugovorne obveze ako kupac ne uplati kupoprodajnu cijenu, te nije dužan izvršiti isporuku proizvoda do trenutka primitka kupoprodajne cijene, osim u slučaju kada je kupac odabrao način plaćanja pouzećem. Ukoliko kupac iz nekog razloga ne preuzme pošiljku te se ista vrati prodavatelju, prodavatelj neće ponoviti druge dostave, osim po dogovoru s kupcem. Prije ponavljanja dostave kupac treba dostaviti potvrdu o plaćanju troška ponovljene dostave, sukladno uputi koju primi.</p>
<p>Sadržaj web stranice je dostupan na hrvatskom jeziku.</p>
<p>Službeni jezik za sklapanje ugovora o kupoprodaji je hrvatski jezik. Na sklopljene ugovore o kupoprodaji primjenjuje se hrvatsko pravo.</p>
<p>Informacije o kupovini kupci mogu dobiti putem e-mail adrese: <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a>.</p>
<p>Ako je bilo koji dio ovih Uvjeta kupnje suprotan (ili postane suprotan) prisilnim propisima primjenjuju se odredbe tih prisilnih propisa.</p>
<p>Društvo ulaže svaki razumni napor da svoje obveze iz ovih Uvjeta kupnje ispuni uredno i na vrijeme. Pritom, Društvo ne odgovara za svako neispunjavanje obveza prouzrokovano izvanrednim okolnostima (višom silom).</p>
<p>Društvo ulaže u razvoj procesa davanja recenzija. Recenzije Društva podliježu procesu provjere identiteta korisnika, te isto ulaže napore da objavljene recenzije potječu od potrošača koji su proizvod doista koristili ili kupili. Društvo ne prakticira niti potiče podnošenje lažnih potrošačkih recenzija ili preporuka, naručivanje od druge pravne ili fizičke osobe da ih podnese te pogrešno predstavljanje potrošačkih recenzija ili društvenih preporuka radi promocije proizvoda. Na recenzije ostavljene putem Google računa Društvo nema utjecaj te ne može utjecati na njegove tehničke postavke u pogledu verifikacije identiteta korisnika koji daju recenzije.</p>
<h2>GLAVNA OBILJEŽJA PROIZVODA</h2>
<p>Kupac se upoznaje s glavnim obilježjima proizvoda na web stranici.</p>
<p>Informacije o proizvodima ponuđenim na web stranici su preuzete s deklaracija pojedinog proizvoda, unutarnjih uputa proizvoda ili su preuzete iz materijala (brošura, letaka, web stranica) koje je uredio proizvođač, uvoznik ili dobavljač pojedinog proizvoda uz dodatnu doradu stručnjaka Društva.</p>
<p>Uz sliku proizvoda nalazi se opis glavnih obilježja proizvoda i njegova cijena s PDV-om.</p>
<p>Cijene, uvjeti plaćanja i akcijske ponude vrijede u formi u kojoj su bile na snazi u vrijeme sklapanja ugovora.</p>
<p>Kupovina se obavlja naručivanjem proizvoda koje kupac bira na temelju fotografije i osnovnog opisa. Fotografije su ilustrativne prirode te ne moraju uvijek i u svim detaljima odgovarati proizvodima, primjerice zbog postavki aplikacije, zaslona uređaja, percepcije boja, načina fotografiranja i slično. Opisani eventualni nesklad između proizvoda na fotografiji i isporučenog proizvoda ne predstavlja materijalni nedostatak proizvoda.<br>Dostupnost odabranog proizvoda podložna je promjeni. Ukoliko Društvo iz bilo kojeg razloga nije u mogućnosti isporučiti neki od naručenih proizvoda zbog toga što proizvoda nema na zalihi ili ga više ne može naručiti, s kupcem će, pisanim ili telefonskim putem, u kontakt stupiti djelatnik Društva te ga obavijestiti o tome, a kupac ima pravo odustati od narudžbe/ugovora ili pristati na naknadni rok za prihvaćanje narudžbe i određivanje roka isporuke. Novac s kreditne kartice kupca neće biti povučen do trenutka isporuke.</p>
<p>Ukoliko Društvo nije u mogućnosti isporučiti sve naručene artikle, isporučit će one koje može, a o roku isporuke ostatka će pismenim putem obavijestiti Kupca. Kupac može prihvatiti ili otkazati isporuku preostalih artikala. Ukoliko kupac nema e-mail adresu, bit će obaviješten telefonskim putem.</p>
<p>Društvo nastoji pružiti što točnije i detaljnije informacije o pojedinom proizvodu. Društvo zadržava pravo izmjene informacija (uključujući cijene artikala i akcijske ponude) na stranicama bez prethodne najave.</p>
<p>Društvo ulaže u kontrolu prikaza cijena na web stranicama i da one budu točne. U slučaju očite pogreške u cijeni proizvoda Društvo zadržava pravo bez odgađanja o tome obavijestiti kupca, otkazati narudžbu i vratiti mu uplaćeni iznos novca. Kupac ima pravo, u navedenoj situaciji, potvrditi narudžbu po ispravnoj cijeni.</p>
<h2>SLIKE PROIZVODA</h2>
<p>Podaci i fotografije proizvoda na web stranici Društva pružaju informativni pregled i mogu se mijenjati. Društvo ih nastoji redovito ažurirati, no moguće su izmjene u formulaciji ili ambalaži proizvoda. Stoga, Društvo moli za provjeru točnih informacija navedenih na pakiranju proizvoda prije upotrebe.</p>
<h2>POSTUPAK SKLAPANJA UGOVORA</h2>
<p>Kupovina se obavlja na web stranicama Društva, ispunjavanjem za to predviđenog obrasca. Prilikom ispunjavanja obrasca kupac je dužan upisati sve podatke koji se od njega traže. Kupovinu je moguće ostvariti uz potvrdu kupca da je prethodno pročitao Uvjete kupnje te da iste prihvaća i da je upoznat da je to narudžba s obvezom plaćanja. Kupovina je moguća 24 sata na dan, 7 dana u tjednu. Roba se naručuje elektronskim obrascem. Ukoliko je roba raspoloživa na skladištu, za narudžbe zaprimljene radnim danom do 9 sati, pošiljke se u pravilu otpremaju istog dana, a na odredišta u RH stižu kroz 2-3 radna dana po otpremi.</p>
<p>Društvo ne odgovara za troškove korištenja računalne opreme i telekomunikacijskih usluga potrebnih za pristup usluzi. Kupac će elektroničkom poštom biti obaviješten o prihvatu narudžbe i slanju paketa. Ako Društvo nije u mogućnosti prihvatiti narudžbu koja predstavlja ponudu potrošača za sklapanje ugovora, potrošač će o istome biti obaviješten.</p>
<p>U iznimnim situacijama povećane potražnje/intenziteta narudžbi ili uslijed otežane isporuke od strane dobavljača koje se ponekad mogu dogoditi, dostava narudžbe može kasniti nekoliko dana. U tom slučaju Društvo će kupca o tome obavijestiti telefonskim putem.</p>
<p>Društvo može u svakom trenutku ukloniti bilo koji proizvod s web stranice kao i izmijeniti ili ukloniti opis istog. Društvo ne odgovara za takvo uklanjanje ili izmjenu. U slučaju izvanrednih okolnosti, Društvo zadržava pravo, i nakon prihvaćene narudžbe istu otkazati i kupcu izvršiti povrat plaćenog novca.</p>
<p>Kupovinu proizvoda u ime i za račun maloljetnika ili osobe lišene poslovne sposobnosti (potpuno ili djelomično), mogu zatražiti samo njihovi zakonski zastupnici.</p>
<p>Kupovina se obavlja u nekoliko jednostavnih koraka u udobnosti doma kupca, s bilo kojeg mjesta u svijetu.</p>
<p>1. Pretraživanje proizvoda je moguće po različitim kriterijima. Upisom određenog pojma u "Pretraga" pojavit će se proizvodi koji su povezani s navedenim pojmom. Kupac može izabrati određeni proizvod koji ga zanima i pročitati dostupan opis proizvoda kako bi mogao samostalno donijeti odluku odgovara li proizvod njegovim potrebama. Kupac proizvode bira iz kataloga proizvoda Društva koji je uređen prema vrstama proizvoda.</p>
<p>2. Narudžba proizvoda se obavlja elektronskim putem. Klikom miša na ikonu "Stavi u košaricu" odabrani proizvod dodaje se u košaricu. Stavljanjem proizvoda u košaricu proizvod nije rezerviran niti naručen niti kupljen. Kupac može nastaviti dodavati proizvode klikom na novu kategoriju proizvoda ili može izvršiti pregled košarice klikom na „Pogledajte košaricu“. Ukoliko kupac želi završiti proces odabira proizvoda, potrebno je kliknuti na ikonu „Naplata“ nakon čega će se kupac preusmjeriti na stranicu na kojoj se može prijaviti na svoj korisnički račun putem e-mail adrese i lozinke. Ukoliko se prijavi na svoj korisnički račun, kupac tada može koristiti postojeću adresu naplate i dostave koja je navedena prilikom registracije ili unijeti novu adresu za dostavu i/ili naplatu, u kojem slučaju je obvezan unijeti sljedeće podatke: ime, prezime, e-mail adresu, broj telefona, adresu, grad, poštanski broj, državu i županiju.</p>
<p>Ukoliko se kupac ne prijavi u svoj korisnički račun, obvezan je unijeti sljedeće podatke kako bi se izvršila narudžba: ime, prezime, e-mail adresa, broj telefona, adresa, grad, poštanski broj, država i županija. Kupac može potvrditi da je adresa za isporuku (dostavu) robe jednaka adresi za dostavu računa. U slučaju da se adrese razlikuju, kupac je obvezan ispuniti obrazac sa sljedećim obveznim podacima za dostavu: ime, prezime, e-mail adresa, broj telefona, adresa, grad, poštanski broj, država i županija.</p>
<p>Nakon popunjavanja svih navedenih obveznih podataka, kupac odabire način preuzimanja robe - dostava na kućnu adresu, dostava u paketomat ili preuzimanje u dućanu Društva. U slučaju odabira dostave na kućnu adresu, kupcu su dostupni načini plaćanja: „Plaćanje karticama“, „Plaćanje pouzećem“ ili „KEKS Pay“. U slučaju odabira preuzimanja robe u dućanu Društva, kupcu su dostupni načini plaćanja: „Plaćanje karticama“ i „KEKS Pay“. U slučaju odabira dostave na paketomat kupcu su dostupni načini plaćanja: „Plaćanje karticama“ i „KEKS Pay“.</p>
<p>Ako je kupac odabrao plaćanje „kreditnom/debitnom karticom“, dužan je označiti kućicu <em>„Da, pročitao/pročitala sam Uvjete kupnje te ih prihvaćam i upoznat/a sam s time da narudžba uključuje obvezu plaćanja.”</em> Klikom na ikonu „Dovršite narudžbu“ otvara se prozor s podacima o narudžbi (naziv, model, količina, cijena i ukupna cijena) u kojem kupac može kliknuti na ikonu „Natrag“ ili „Potvrdi narudžbu“. Klikom na ikonu „Potvrdi narudžbu“ otvara se prozor u kojem kupac može unijeti podatke o broju kartice, mjesec i godina isteka kartice, CVV/CVC kod te ima opciju izmjene osobnih podataka o vlasniku kartice (ime, prezime, adresa, grad, poštanski broj, država, telefon i e-mail). Nakon što je ispunio sve potrebne podatke, kupac može kliknuti na ikonu „Izvrši plaćanje“ ili na ikonu „Odustani“.</p>
<p>U slučaju da je kupac odabrao plaćanje pouzećem, dužan je označiti kućicu <em>„Da, pročitao/pročitala sam Uvjete kupnje te ih prihvaćam i upoznat/a sam s time da narudžba uključuje obvezu plaćanja.”</em> Klikom na ikonu „Dovršite narudžbu“ otvara se prozor s podacima o narudžbi (naziv, model, količina, cijena i ukupna cijena) u kojem kupac može kliknuti na ikonu „Natrag“ ili „Potvrdi narudžbu“. Klikom na ikonu „Potvrdi narudžbu“ kupac naručuje proizvod.</p>
<p>U slučaju da je kupac odabrao plaćanje putem „Keks Pay-a“, dužan je označiti kućicu <em>„Da, pročitao/pročitala sam Uvjete kupnje te ih prihvaćam i upoznat/a sam s time da narudžba uključuje obvezu plaćanja.”</em> Klikom na ikonu „Dovršite narudžbu“ otvara se prozor s podacima o narudžbi (naziv, model, količina, cijena i ukupna cijena) u kojem kupac može kliknuti na ikonu „Natrag“ ili „Otvori keks pay“. Klikom na ikonu „Otvori keks pay“ kupcu se otvara aplikacija KEKS PAY u kojoj može izvršiti isplatu.</p>
<p>3. Nakon što kupac izvrši narudžbu, prodavatelj će kupcu, ako prihvati narudžbu, poslati na adresu njegove e-pošte potvrdu o sklopljenom kupoprodajnom ugovoru (potvrdu narudžbe) zajedno s potvrdom da je narudžba u procesu obrade.</p>
<p>4. Ukoliko kupac ne primi naručene proizvode koje je platio u ugovorenom roku dostave dužan je obavijestiti Društvo o istom na adresu e-pošte <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a>.</p>
<p>5. Ukoliko kupac nije zaprimio potvrdu narudžbe putem e-pošte dužan je kontaktirati prodavatelja na adresu e-pošte <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a> ili na broj telefona 099 337 3730.</p>
<p>6. U slučaju problema ili nejasnoća tijekom narudžbe kupac može kontaktirati Društvo na adresu e-pošte <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a> ili na broj telefona 099 337 3730.</p>
<h2>CIJENA PROIZVODA I NAČIN PLAĆANJA</h2>
<p>Važeće cijene u trenutku narudžbe su one koje se nalaze pored proizvoda u web trgovini.</p>
<p>Cijene su izražene kao bruto cijene, cijene s PDV-om, ali ne sadržavaju cijenu dostave.</p>
<p>Cijena dostave je vidljiva kod „Naplate“ prije završetka kupovine i slanja narudžbe.</p>
<p>Ukoliko u web trgovini dođe do greške, tehničkih poteškoća ili nedostatka kod proizvoda ili cijene, Društvo zadržava pravo korekcije. U takvom slučaju odmah nakon prepoznavanja problema, greške, Društvo obavještava kupca o nastaloj situaciji. Nakon informiranja kupca o učinjenoj korekciji cijene, Društvo ima pravo odustati od ugovora / narudžbe, a kupac može i potvrditi navedenu narudžbu prema cijeni koja se nakon korekcije nalazi pored proizvoda u web trgovini.</p>
<p>Sve cijene su izražene u eurima (€). PDV je uključen u cijenu. U slučaju promjene PDV-a Društvo zadržava pravo na promjenu cijena. Ponuda vrijedi do isteka zaliha.</p>
<p>Kupac se obvezuje naručene proizvode platiti jednim od sljedećih načina plaćanja:</p>
<p>1. Gotovinom pri preuzimanju pošiljke od kurirske službe (Pouzećem)</p>
<p>2. Kreditnim i debitnim karticama pri osobnom preuzimanju pošiljke u fizičkoj trgovini</p>
<p>3. On-line kreditnim i debitnim karticama: MasterCard, Maestro, Visa, Diners (opcije obročnog on-line plaćanja provjerite ovdje)</p>
<p>4. Kekspay servisom</p>
<p>Plaćanje na rate:</p>
<p>ZAGREBAČKA BANKA-MASTERCARD 2-4 RATE</p>
<p>PBZ-VISA INSPIRE I MAESTRO PBZ 2-6 RATA</p>
<p>ERSTE-DINERS 2-6 RATA.</p>
<p>Minimalni iznos kupovine za plaćanje na rate je 13,27 €.</p>
<p>Naručene proizvode s troškovima dostave (ako ih ima), Kupac će Prodavatelju platiti pouzećem prilikom dostave robe ili kreditnom ili debitnom karticom na Internet stranici prodavatelja preko WS Paymet Gateway-a sa Diners, Visa ili Maestro i MasterCard karticama. U slučaju da Kupac naruči robu i odbije je primiti, Prodavatelj ima pravo tražiti od Kupca nadoknadu poštanskih i vlastitih manipulativnih troškova.</p>
<p>Izjava o konverziji (u slučaju kupovine iz inozemstva)</p>
<p>Sva plaćanja bit će izvršena u valuti Euro €. Prilikom naplate kreditnom karticom Kupca, iznos se pretvara u lokalnu valutu prema važećoj tečajnoj listi udruženja kartičara.</p>
<p>1. Odabere li kupac opciju plaćanja pouzećem, odnosno plaćanje prilikom preuzimanja narudžbe, pošiljku će platiti dostavljaču prilikom preuzimanja. Iznos računa kupac podmiruje u gotovini prilikom preuzimanja paketa.</p>
<p>2. Plaćanje kreditnim ili debitnim karticama kupac obavlja na siguran način preko WS Paymet Gateway-a. Plaćanje je sigurno i šifrirano!</p>
<p>WSpay™ sustav koristi najviše standarde zaštite i privatnosti podataka.</p>
<p>Svi trgovci koji koriste WSpay™ su uključeni u 3D secure zaštitu, čime se jamči korisnicima shopa da je kupnja sigurna.</p>
<p>Brojevi kreditnih kartica kupaca se ne čuvaju na sustavu a sami upis se štiti SSL enkripcijom podataka.</p>
<p>Certifikacija po PCI DSS standardima.</p>
<p>WSpay™ sustav radi kontinuirano na povećanju sigurnosti i potvrđivanju toga. Od ove godine će biti potvrđeno da posluje po najvišim standardima koji kartičar propisuje.</p>
<p>PCI Data Security Standard (PCI DSS) je norma koja definira sigurnosne mjere za obradu, spremanje i prenošenja (komunikaciju) kartičnih podataka.</p>
<p>Podaci za uplatu:</p>
<p>ALTITUDO CENTAR d.o.o.</p>
<p>Nikole Jurišića 8</p>
<p>10000 Zagreb</p>
<p>OIB 59774997564</p>
<p>IBAN: HR9523600001102670022</p>
<h2>UVJETI DOSTAVE I ISPORUKE</h2>
<p>Dostava robe vrši se ekspresnom dostavom unutar 3 -5 radnih dana. Ukoliko su proizvodi odmah na raspolaganju isporuka će biti izvršena u roku 2-5 radna dana po primitku narudžbe. Korisnik će o načinu dostave biti obaviješten u trenutku naručivanja robe (elektronskim ili telefonskim putem ovisno o načinu kupnje za kojega se kupac odluči).</p>
<p>Ukoliko Društvo iz bilo kojeg razloga nije u mogućnosti isporučiti neki od naručenih proizvoda zbog toga što proizvoda nema na zalihi ili ga više ne može naručiti, s kupcem će, pisanim ili telefonskim putem, u kontakt stupiti djelatnik Društva te ga obavijestiti o tome, a kupac ima pravo odustati od narudžbe/ugovora ili pristati na naknadni rok za prihvaćanje narudžbe i određivanje roka isporuke.</p>
<p>Ukoliko Društvo nije u mogućnosti isporučiti sve naručene artikle, isporučit će one koje može, a o roku isporuke ostatka će pismenim putem obavijestiti Kupca. Kupac može prihvatiti ili otkazati isporuku preostalih artikala. Ukoliko kupac nema e-mail adresu, bit će obaviješten telefonskim putem.</p>
<p>Odluči li se Kupac na otkazivanje narudžbe, Prodavatelj je dužan vratiti mu sva uplaćena sredstva bez odgađanja, a najkasnije u roku od 14 dana od dana kad je zaprimio obavijest o odluci Kupca da raskida ugovor, odnosno otkazuje narudžbu.</p>
<p>Proizvodi će biti zapakirani tako da se uobičajenom manipulacijom u transportu ne mogu oštetiti. Kupac je dužan odmah prilikom preuzimanja robe provjeriti stanje pošiljke i o eventualnim vidljivim oštećenjima ambalaže obavijestiti prodavatelja. Kupac nije dužan preuzeti robu s fizičkim oštećenjem pošiljke. Roba je osigurana od gubitka u dostavi.</p>
<p>Ukoliko kupac ne preuzme proizvod ili odbije preuzeti proizvod bez valjanog razloga, Društvo zadržava pravo zahtijevati nadoknadu troškova manipulacije, transporta i drugih mogućih troškova.</p>
<p>Reklamacije vezane za oštećenja ili nedostatke proizvoda te svi ostali prigovori mogu se predati isključivo u pisanom obliku putem pošte ili elektroničke pošte te u poslovnim prostorijama/trgovinama prodavatelja:</p>
<ul>
<li>poštom na adresu: Altitudo Centar d.o.o, Nikole Jurišića 8 , 10000 Zagreb;</li>
<li>na adresu elektroničke pošte: info@atelierbebes.com;</li>
<li>dolaskom u trgovinu Društva.</li>
</ul>
<p>Odgovor na prigovor Društvo zakonski mora dati u pisanom obliku najkasnije u roku 15 dana od dana primitka prigovora.</p>
<p>Ukoliko kupac prihvaća Uvjete kupnje, Društvo zadržava pravo izmjene istih bez prethodne najave. Uvjeti kupnje su sukladni zakonima Republike Hrvatske.</p>
<p>Prije isporuke, Društvo provjerava svaki artikl da nije oštećen.</p>
<p>Društvo pošiljke isporučuje putem ugovorenih dostavnih službi.</p>
<p>Trošak dostave iznosi 6,00 EUR (PDV uključen) za područje Republike Hrvatske.</p>
<p>Trošak dostave na GLS paketomat iznosi 3,00 EUR (PDV uključen).</p>
<p>Za sve narudžbe iznad 70,00 € za dostave na području Republike Hrvatske dostava je besplatna.</p>
<h2>OPĆE INFORMACIJE</h2>
<p>Korisnici odnosno kupci su dužni prije početka korištenja web stranice Društvo upoznati se s Uvjetima kupnje. Ukoliko korisnici ili kupci imaju dodatnih pitanja ili nejasnoća vezanih uz Uvjete kupnje, mogu se obratiti na adresu e-pošte <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a>.</p>
<p>Pristupanjem web stranici ili korištenjem bilo kojeg dijela njezinog sadržaja korisnik prihvaća Uvjete kupnje web stranice, kao i sva ostala pravila predmetne web stranice te usluga koje se putem nje pružaju.</p>
<p>Korisnici su suglasni da neće koristiti web stranicu i obavljati narudžbe proizvoda na istoj na način koji bi mogao prouzročiti štetu autorima ili trećim osobama, a osobito da neće:</p>
<p>1) koristiti web stranicu za bilo koju svrhu osim podnošenja upita i pravno valjanih narudžbi proizvoda,</p>
<p>2) davati lažne narudžbe,</p>
<p>3) pružati netočne ili nepotpune informacije koje se od njih traže,</p>
<p>4) namjerno unositi ili širiti zlonamjerne računalne programe ili drugi štetan sadržaj,</p>
<p>5) neovlašteno pristupati web stranici ili pokušavati izvršiti napade distribuiranog uskraćivanja usluge ili slične napade,</p>
<p>6) koristiti web stranicu na način koji bi mogao ometati njezin rad ili ugroziti sigurnost pruženih usluga.</p>
<p>Ukoliko se korisnik ne slaže s navedenim, dužan je prestati koristiti web stranicu i usluge koje se putem nje pružaju.</p>
<p>U slučaju da kupci koriste web stranice suprotno točki 3), 6) i 7) Društvo je ovlašteno takvu narudžbu otkazati te o tome obavijestiti nadležne institucije.</p>
<p>Sadržaj web stranice zaštićen je autorskim pravima. Sva autorska prava, zaštićeni žigovi i druga prava intelektualnog vlasništva nad sadržajima web stranice pripadaju Društvu ili osobama koje su dale dozvolu Društvu za korištenje tih prava. Mijenjanje, posuđivanje, prodavanje ili distribuiranje sadržaja moguće je samo uz prethodnu pisanu dozvolu Društva.</p>
<p>Društvo omogućuje korištenje web stranice na najbolji mogući način. Društvo ne preuzima odgovornost za eventualne probleme u radu stranica i usluga. Društvo ne može jamčiti da korištenje web stranice neće biti prekinuto ili bez pogrešaka. Korisnik je suglasan s time da pristup web stranici ponekad može biti u prekidu ili privremeno nedostupan.</p>
<p>Korisnici koriste web stranicu na vlastitu odgovornost. Društvo ni na koji način nije odgovoran za štetu koju korisnik može pretrpjeti korištenjem web stranice. Autori i druge fizičke ili pravne osobe uključene u stvaranje, proizvodnju i distribuciju web stranice nisu odgovorni ni za kakvu štetu nastalu kao posljedica korištenja ili nemogućnosti korištenja iste.</p>
<p>Društvo zadržava pravo da onemogući pristup web stranici korisnicima u slučaju procjene da se ista koristi na neprikladan način. Korisnik se obvezuje da će web stranicu koristiti na način da ne ugrožava resurse i usluge u cijelosti. Neprimjereno korištenje web stranice zabranjeno je i može rezultirati ukidanjem pristupa istoj.</p>
<p>Društvo zadržava pravo u bilo kojem trenutku izmijeniti ili dopuniti Uvjete kupnje. Promjene stupaju na snagu danom objave na web stranici. Uvjeti kupnje dostavljaju se putem e-pošte u PDF obliku. Za kupca su obvezujući Uvjeti kupnje koji su mu dostavljeni putem e-pošte u PDF obliku.</p>
<p>Društvo zadržava pravo u bilo kojem trenutku i bez prethodne najave izmijeniti, dopuniti ili ukinuti bilo koji dio svog poslovanja, što uključuje i web stranicu, odnosno bilo koji njezin dio, servise, podstranice ili usluge koje se putem njih pružaju. Predmetno pravo uključuje, ali se ne ograničava na, promjenu vremena dostupnosti sadržaja, dostupnosti novih podataka, načina prijenosa, kao i prava na pristup ili korištenje web stranicom.</p>
<p>Dužnost je i obveza korisnika koristiti web stranicu u skladu s pozitivnim propisima te općim moralnim i etičkim načelima. Društvo ima pravo u svakom trenutku vršiti kontrolu sadržaja web stranice kako bi osigurao poštivanje Uvjeta kupnje i pozitivnih propisa. Izmjene Uvjeta kupnje važeće su odmah po objavi na web stranicama.</p>
<h2>MATERIJALNI NEDOSTACI</h2>
<p>Društvo odgovara za materijalne nedostatke proizvoda, sukladno primjenjivim propisima. Kupac je obvezan obavijestiti Društvo o postojanju vidljivih nedostataka u roku od dva mjeseca od dana kad je otkrio nedostatak, a najkasnije u roku od dvije godine od prijelaza rizika na kupca. Kad se nakon primitka stvari od strane kupca pokaže da stvar ima neki nedostatak koji se nije mogao otkriti uobičajenim pregledom prilikom preuzimanja stvari, kupac je dužan, pod prijetnjom gubitka prava, o tom nedostatku obavijestiti Društvo u roku od dva mjeseca računajući od dana kad je nedostatak otkrio.</p>
<p>Materijalni nedostaci za koje Društvo (prodavatelj) odgovara:</p>
<p>(1) za materijalne nedostatke stvari koje je ona imala u trenutku prijelaza rizika na kupca, bez obzira je li joj to bilo poznato.</p>
<p>(2) za materijalne nedostatke koji se pojave nakon prijelaza rizika na kupca ako su posljedica uzroka koji je postojao prije toga.</p>
<p>Predmnijeva se da je svaki nedostatak stvari koji se pokazao u roku od jedne godine od trenutka prijelaza rizika postojao i u trenutku prijelaza rizika, osim ako prodavatelj dokaže suprotno ili suprotno proizlazi iz naravi stvari ili naravi nedostatka.<br><br>Nedostatak postoji:</p>
<p>1) ako stvar ne odgovara opisu, vrsti, količini i kvaliteti odnosno nema funkcionalnost, kompatibilnost, interoperabilnost i druge značajke kako je utvrđeno ugovorom o kupoprodaji,<br>2) ako stvar nije prikladna za bilo koju posebnu namjenu za koju je potrebna kupcu i s kojom je kupac upoznao prodavatelja najkasnije u trenutku sklapanja ugovora te u odnosu na koju je prodavatelj dao pristanak,</p>
<p>3) ako stvar nije isporučena sa svom dodatnom opremom i uputama, uključujući upute za instalaciju, kako je utvrđeno ugovorom o kupoprodaji ili<br>4) ako stvar nije isporučena s ažuriranjima kako je utvrđeno ugovorom o kupoprodaji,<br>5) ako stvar nije prikladna za upotrebu u svrhe za koje bi se stvar iste vrste uobičajeno koristila, uzimajući u obzir sve propise Europske unije i propise Republike Hrvatske, tehničke standarde ili, ako takvih tehničkih standarda nema, primjenjive kodekse ponašanja u određenom području ako oni postoje,</p>
<p>6) ako stvar ne odgovara kvaliteti i opisu uzorka ili modela koji je prodavatelj stavio na raspolaganje kupcu prije sklapanja ugovora</p>
<p>7) ako stvar nije isporučena s dodatnom opremom, uključujući ambalažu, upute za instalaciju ili druge upute, čiji primitak kupac može razumno očekivati,</p>
<p>8) ako stvar ne odgovara količini ili nema ona svojstva i druge značajke, uključujući one koje se odnose na trajnost, funkcionalnost, kompatibilnost i sigurnost, koji su uobičajeni za stvar iste vrste i koje kupac može razumno očekivati s obzirom na prirodu stvari te uzimajući u obzir sve javne izjave koje su dali prodavatelj ili druge osobe u prethodnim fazama lanca transakcija, uključujući proizvođača, ili koje su dane u njihovo ime, osobito u oglašavanju ili označivanju,<br>9) ako je stvar nepravilno instalirana odnosno montirana, a usluga instalacije odnosno montaže čini dio ugovora o kupoprodaji i obavio ju je prodavatelj ili osoba za koju on odgovara ili</p>
<p>10) ako je stvar za koju je bilo predviđeno da je instalira odnosno montira kupac nepravilno instalirana odnosno montirana od strane kupca, a nepravilna instalacija odnosno montaža posljedica je nedostatka u uputama koje je dostavio prodavatelj ili, u slučaju stvari s digitalnim elementima, koje je dostavio prodavatelj ili dobavljač digitalnog sadržaja ili digitalne usluge.</p>
<p>Ako je kupac na temelju izjava proizvođača ili njegova predstavnika očekivao postojanje određenih svojstava stvari, nedostatak se ne uzima u obzir ako prodavatelj nije znao niti morao znati za te izjave, ili su te izjave bile opovrgnute do trenutka sklapanja ugovora ili one nisu utjecale na odluku kupca da sklopi ugovor.</p>
<p>Nedostaci za koje Društvo ne odgovara:</p>
<p>Društvo ne odgovara za nedostatke ako su u trenutku sklapanja ugovora bili poznati kupcu ili mu nisu mogli ostati nepoznati te za nedostatke koje je kupac mogao lako opaziti ako je izjavio da stvar nema nikakve nedostatke ili da stvar ima određena svojstva ili odlike.</p>
<p>Pregled stvari i vidljivi nedostaci</p>
<p>Kupac nije obvezan pregledati stvar niti je dati na pregled, ali je obvezan obavijestiti prodavatelja o postojanju vidljivih nedostataka u roku od dva mjeseca od dana kad je otkrio nedostatak.<br><br>Ako se utvrdi postojanje materijalnog nedostatka Društvo može imati jednu od sljedećih obveza, sve u skladu s odredbama Zakona o obveznim odnosima:</p>
<ul>
<li>uklanjanje nedostatka,</li>
<li>predaja drugog proizvoda bez nedostatka,</li>
<li>sniženje cijene.</li>
</ul>
<p>Kupac može raskinuti ugovor samo ako je prethodno dao Društvu naknadni primjereni rok za ispunjenje ugovora.</p>
<p>U slučaju da kupac vrati oštećen proizvod u roku od 14 dana i zahtijeva povrat plaćenog iznosa, Društvo će kupca obavijestiti pisanim putem o iznosu umanjenja vrijednosti zbog oštećenja, na koje kao trgovac ima pravo tj. naknadu za umanjenje vrijednosti proizvoda. Ako kupac na obavijest o procjeni umanjenja iznosa ne reagira ili ne prihvaća umanjenje vrijednosti robe, trgovac pokreće tužbu radi nadoknade štete.</p>
<p>Društvo ima pravo odbiti uklanjanje nedostatka ako su popravak i zamjena nemogući ili bi mu time bili prouzročeni nerazmjerni troškovi uzimajući u obzir sve okolnosti, a osobito vrijednost stvari bez nedostatka, značaj nedostatka i pitanje može li se popravak odnosno zamjena obaviti bez znatnih neugodnosti za kupca.</p>
<p>Kupac može raskinuti ugovor i bez ostavljanja naknadnog roka ako mu je Društvo nakon obavijesti o nedostacima priopćilo da neće ispuniti ugovor ili ako iz okolnosti konkretnog slučaja očito proizlazi da Društvo neće moći ispuniti ugovor ni u naknadnom roku, kao i u slučaju kad kupac zbog zakašnjenja Društva ne može ostvariti svrhu radi koje je sklopio ugovor.</p>
<p>Ako Društvo u naknadnom roku ne ispuni ugovor, on se raskida po samom zakonu, ali ga kupac može održati ako bez odgađanja izjavi Društvu da ugovor održava na snazi.</p>
<p>Ako je nedostatak neznatan, kupac nema pravo na raskid ugovora, ali mu pripadaju druga prava iz odgovornosti za materijalne nedostatke uključujući i pravo na popravljanje štete. Teret dokaza da je nedostatak neznatan je na Društvu.</p>
<p>Troškove otklanjanja nedostatka i predaje druge stvari bez nedostatka snosi prodavatelj.<br>Kada je kupac pravna osoba na njega se odnose pravila o materijalnom nedostatku propisana Zakonom o obveznim odnosima te se na njega ne primjenjuju pravila iz ovog odjeljka „Materijalni nedostaci“.</p>
<h2>JAMSTVO I SERVISNI UVJETI</h2>
<p>Ako određeni proizvod ima jamstvo ili podliježe servisnim uvjetima, to će biti naglašeno u opisu tog proizvoda. Kupac je dužan čuvati jamstveni list i račun za vrijeme trajanja jamstvenog roka. Samo s predočenjem računa i jamstvenog lista kupac može ostvariti svoja prava. Kupac zadržava pravo ostvarivanja svih zakonskih prava vezanih uz materijalne nedostatke, neovisno o postojanju ili korištenju jamstvenih prava. Sva prava koja proizlaze iz jamstva opisana su u jamstvenom listu uz svaki proizvod.</p>
<h2>OSTALE INTERNET STRANICE</h2>
<p>Kada Društvo odgovarajućim linkovima pruža mogućnost posjeta drugih web stranica drugih osoba, iste nisu u vlasništvu prodavatelja i ovi Uvjeti kupnje u slučaju korištenja predmetnih web stranica ne primjenjuju se u odnosu na prodavatelja i kupca. Prodavatelj navedene web stranice ne kontrolira i ne preuzima nikakvu odgovornost za bilo koju od njih ili njihov sadržaj. Posjet tim stranicama u cijelosti je na vlastiti rizik kupca i prodavatelj ne snosi nikakvu odgovornost.</p>
<h2>PRAVO NA JEDNOSTRANI RASKID UGOVORA</h2>
<p>Potrošač može ugovor sklopljen na daljinu jednostrano raskinuti u roku od 14 dana bez navođenja razloga.</p>
<p>Rok od 14 dana započinje teći od dana kada je potrošaču ili trećoj osobi određenoj od strane potrošača, a koja nije prijevoznik, proizvod predan u posjed.</p>
<p>Ukoliko potrošač jednom narudžbom naruči više komada proizvoda koji trebaju biti isporučeni odvojeno, odnosno ako je riječ o robi koja se dostavlja u više komada ili više pošiljki, rok od 14 dana započinje teći od dana kada je potrošaču ili trećoj osobi određenoj od strane potrošača, a koja nije prijevoznik, predan u posjed zadnji komad ili zadnja pošiljka proizvoda.<br><br>Ako je ugovorena redovita isporuka robe kroz određeni period, rok od 14 dana započinje teći dana kada je potrošaču ili trećoj osobi određenoj od strane potrošača, a koja nije prijevoznik, predan u posjed prvi komad ili prva pošiljka proizvoda.</p>
<p>Ukoliko potrošač ne bude obaviješten o pravu na raskid ugovora, pravo potrošača na jednostrani raskid ugovora prestaje po isteku 12 mjeseci od isteka roka od 14 dana.<br>Ako je prodavatelj dostavio potrošaču obavijest o pravu na raskid ugovora u roku od 12 mjeseci pravo na jednostrani raskid ugovora prestaje po isteku roka od 14 dana od kada je potrošač primio tu obavijest.</p>
<p>Da bi potrošač mogao ostvariti pravo na jednostrani raskid ugovora, isti mora prodavatelja obavijestiti o svojoj odluci o jednostranom raskidu ugovora prije isteka roka od 14 dana i to nedvosmislenom izjavom poslanom poštom na adresu Društva (ALTITUDO CENTAR d.o.o., Nikole Jurišića 8, 10000 Zagreb), ili elektroničkom poštom na info@atelierbebes.com u kojoj će navesti svoje ime i prezime, adresu, broj telefona, telefaksa ili adresu elektroničke pošte, a potrošač može, po vlastitom izboru, koristiti i niže priloženi obrazac za jednostrani raskid ugovora.</p>
<p>Primjerak obrasca za jednostrani raskid ugovora potrošač može elektronički ispuniti tako da klikne na <a href="https://atelierbebes.com/obrazac-za-povrat">obrazac za Jednostrani raskid ugovora</a>.</p>
<p>Potvrdu primitka izjave o jednostranom raskidu ugovora prodavatelj će dostaviti potrošaču bez odgađanja, elektroničkom poštom. U slučaju raskida ugovora, svaka je strana dužna vratiti drugoj strani ono što je primila na temelju ugovora.</p>
<p>Osim kada je prodavatelj ponudio da robu koju potrošač vraća sam preuzme, prodavatelj mora izvršiti povrat plaćenog tek nakon što mu roba bude vraćena, odnosno, nakon što mu potrošač dostavi dokaz da je robu poslao natrag prodavatelju, ako bi o tome prodavatelj bio obaviješten prije primitka robe.</p>
<p>Prodavatelj nije u obvezi izvršiti povrat dodatnih troškova koji su rezultat potrošačeva izričitog izbora vrste prijevoza koji je različit od najjeftinije vrste standardnog prijevoza koji je ponudio prodavatelj. Prodavatelj mora izvršiti povrat plaćenoga služeći se istim sredstvima plaćanja kojim se koristio potrošač prilikom plaćanja, osim ako potrošač izričito ne pristane na neko drugo sredstvo plaćanja, te uz pretpostavku da potrošač ne bude obvezan platiti nikakve dodatne troškove za takav povrat.</p>
<p>Osim ako je prodavatelj ponudio da robu koju potrošač vraća sam preuzme, potrošač mora izvršiti povrat robe bez odgađanja a najkasnije u roku od 14 dana od kada je obavijestio prodavatelja o svojoj odluci da raskine ugovor.</p>
<p>Potrošač je dužan robu predati ili je poslati na adresu Altitudo Centar d.o.o., Nikole Jurišića 8, 10000 Zagreb, bez nepotrebnog odgađanja, a u svakom slučaju najkasnije u roku od 14 (četrnaest) dana od dana kada je Prodavatelju uputio svoju odluku o jednostranom raskidu Ugovora.</p>
<p>Smatra se da je potrošač izvršio svoju obvezu povrata robe na vrijeme ako prije isteka roka pošalje robu ili je preda prodavatelju, odnosno osobi koju je prodavatelj ovlastio da primi robu.<br><br>Potrošač je odgovoran za svako umanjenje vrijednosti robe koje je rezultat rukovanja robom, osim onog koje je bilo potrebno za utvrđivanje prirode, obilježja i funkcionalnosti robe. Kako bi potrošač utvrdio prirodu, obilježja i funkcionalnosti robe isti može postupati s robom i pregledati je isključivo na način na koji je to uobičajeno pri kupnji robe u prostorijama Prodavatelja. U periodu u kojem potrošač ostvaruje pravo povrata robu mora čuvati s dužnom pažnjom.</p>
<p>Kako bi potrošač utvrdio prirodu, obilježja i funkcionalnosti robe isti može postupati s robom i pregledati robu isključivo na način na koji je to uobičajeno pri kupnji robe u poslovnicama prodavatelja. Robu koju kupac namjerava vratiti u roku od 14 dana isti ne smije, primjerice, pokretati, koristiti niti smije poduzimati osobito bilo koje radnje koje se ne smiju poduzimati u fizičkoj poslovnici prodavatelja, kao i one kojima bi umanjio vrijednost robe.<br><br>U periodu u kojem potrošač ostvaruje pravo povrata robu mora čuvati s dužnom pažnjom. U slučaju umanjenja vrijednosti proizvoda koje je rezultat prekomjernog rukovanja robom Prodavatelj će procijeniti umanjenje vrijednosti robe uzimajući u obzir objektivne kriterije svakog pojedinog slučaja te će o tome izvijestiti Kupca.</p>
<p>Pravo na raskid ugovora o kupoprodaji nije dopušten u sljedećim slučajevima:</p>
<p>1. ako je ugovor Prodavatelj u potpunosti ispunio, a ispunjenje je započelo uz izričit prethodni pristanak Kupca te uz njegovu potvrdu da je upoznat s činjenicom da će izgubiti pravo na jednostrani raskid ugovora ako ugovor bude u potpunosti ispunjen,</p>
<p>2. ako je predmet ugovora zapečaćena roba koja zbog zdravstvenih ili higijenskih razloga nije pogodna za vraćanje, ako je bila otpečaćena nakon dostave,</p>
<p>3. ako je predmet Ugovora roba koja je zbog svoje prirode nakon dostave nerazdvojivo pomiješana s drugim stvarima,</p>
<p>4. ako je predmet Ugovora isporuka zapečaćenih audiosnimaka ili videosnimaka, odnosno računalnih programa, koji su otpečaćeni nakon isporuke te</p>
<p>5. ako je predmet Ugovora isporuka novina, periodičnog tiska ili magazina, s iznimkom pretplatničkih ugovora za takve publikacije.</p>
<h2>OBAVIJEST O NAČINU PISANOG PRIGOVORA POTROŠAČA</h2>
<p>Sve prigovore sukladno članku 10. Zakona o zaštiti potrošača, potrošač može poslati putem pošte na adresu Altitudo Centar d.o.o, Nikole Jurišića 8 , 10000 Zagreb, elektronskim putem na e-mail adresu <a href="mailto:info@atelierbebes.com">info@atelierbebes.com</a> te u prodavaonici Društva. Ako se prigovor podnosi u prodavaonici, Prodavatelj je dužan odmah pisanim putem ovjeriti njegov primitak.</p>
<p>Kako bi potrošaču Društvo odgovorilo na pisani prigovor koji nije upućen elektroničkom poštom, mole se potrošači da navedu točne podatke o svome imenu i prezimenu te adresi na koju će im biti dostavljen odgovor.</p>
<p>Društvo će bez odgađanja, po primitku prigovora, potrošaču potvrditi primitak istog.<br>Odgovor na prigovor potrošača Društvo zakonski mora dati u pisanom obliku najkasnije u roku 15 dana od dana primitka prigovora.</p>
<p>U slučaju eventualnog spora Društvo i potrošač će spor riješiti mirnim putem, a ukoliko nije moguće nadležan je stvarno i mjesno nadležan sud u Republici Hrvatskoj uz primjenu hrvatskog prava.</p>
<p>Rješavanje sporova je moguće pred centrima za mirenje.</p>
</div>'
WHERE `description`.`information_id` = @terms_information_id
  AND `description`.`language_id` = @terms_language_id
  AND `description`.`title` = 'Uvjeti kupnje'
  AND @terms_information_id = 7
  AND @terms_language_id = 2;

SET @terms_description_rows := ROW_COUNT();

UPDATE `oc_information`
SET `bottom` = 1,
    `status` = 1
WHERE `information_id` = @terms_information_id
  AND @terms_information_id = 7
  AND EXISTS (
      SELECT 1
      FROM `oc_information_description`
      WHERE `information_id` = @terms_information_id
        AND `language_id` = @terms_language_id
        AND `title` = 'Uvjeti kupnje'
  );

SET @terms_page_rows := ROW_COUNT();

COMMIT;

SELECT
    @terms_information_id AS `information_id`,
    @terms_language_id AS `language_id`,
    @terms_description_rows AS `description_rows_changed`,
    @terms_page_rows AS `page_rows_changed`;

SELECT
    `description`.`title`,
    CHAR_LENGTH(`description`.`description`) AS `description_characters`,
    (`description`.`description` LIKE '%Trošak dostave iznosi 6,00 EUR%') AS `has_home_delivery_price`,
    (`description`.`description` LIKE '%Trošak dostave na GLS paketomat iznosi 3,00 EUR%') AS `has_locker_delivery_price`,
    (`description`.`description` LIKE '%https://atelierbebes.com/obrazac-za-povrat%') AS `has_new_termination_form`,
    (`description`.`description` LIKE '%OBAVIJEST O NAČINU PISANOG PRIGOVORA POTROŠAČA%') AS `has_final_section`,
    `information`.`bottom`,
    `information`.`status`
FROM `oc_information_description` AS `description`
JOIN `oc_information` AS `information`
  ON `information`.`information_id` = `description`.`information_id`
WHERE `description`.`information_id` = @terms_information_id
  AND `description`.`language_id` = @terms_language_id;
