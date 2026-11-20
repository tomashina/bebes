# Bazne cijene i dnevni cjenici PJ1 i PJ3

Ove upute odnose se na modul `Bebes sidrene cijene i dnevni cjenici PJ1/PJ3` za OpenCart. Produkcijsko puštanje prvo treba uvježbati na kopiji produkcijske baze i datoteka. SQL skripte koriste prefiks tablica `oc_` i očekuju MySQL 8 funkcije korištene u migracijama.

## 1. Obvezna sigurnosna kopija

Prije bilo kakve izmjene napraviti i provjeriti potpunu sigurnosnu kopiju:

- cijele produkcijske baze podataka;
- svih OpenCart datoteka, posebno mapa `admin/`, `catalog/`, `system/storage/modification/` i `system/storage/download/`;
- postojećih OCMOD izmjena i postavki zakazanih poslova.

Zabilježiti vrijeme kopije i unaprijed potvrditi postupak povrata. Ne nastavljati ako se sigurnosna kopija ne može probno otvoriti ili vratiti.

## 2. Nadogradnja postojeće instalacije

U novu verziju prenesite sadržaj mape `_ocmod/bebes_anchor_prices_v1.0.0/upload/` ili iste datoteke iz paketa `_ocmod/bebes_anchor_prices_v1.1.0.ocmod.zip`. Postojeća OCMOD izmjena `bebes_anchor_prices` već ima kôd za prikaz cijena. Osvježite **Extensions → Modifications** i predmemoriju teme. Prenesite i ažuriranu skriptu crona prema odjeljku 4. Skripta za provjeru izvora nalazi se u `scripts/bebes_bbm_provjera.php`.

Ova nadogradnja koristi postojeće tablice i datoteke arhive. Kod prve instalacije učitajte OCMOD paket i instalirajte modul. Povijesne SQL skripte iz rujna namijenjene su inicijalnoj instalaciji. Vrijednosti iz tog početnog punjenja su kandidati za provjeru.

## 3. BBM izvor za fizičku trgovinu

BBM generira datoteku za PJ1, skladište 00001, u 00:20 po zagrebačkom vremenu. Modul u 00:30 preuzima ZIP s adrese `https://t02.bbm.hr/altitudo/sidrene_cijene.zip` preko HTTPS-a, s korisničkim imenom `iis_altitudo`. U administraciji otvorite **Sidrene cijene → Postavke → BBM lozinka za PJ1**. Upišite lozinku i spremite postavke. Prazno polje zadržava već spremljenu lozinku.

PHP proces sprema lozinku u `/home/amds/.config/bebes-bbm-password` s ovlastima 0600. Za drugačiju instalaciju postavite konstantu `BEBES_BBM_PASSWORD_FILE` na istu apsolutnu privatnu putanju u kataloškom i administratorskom configu. Lozinka se koristi isključivo za preuzimanje s navedene BBM adrese.

Prije prijelaza provjerite prvi autentični ZIP na poslužitelju iz mape projekta. Provjera čita datoteku, izrađuje probni CSV te ispisuje broj artikala, kontrolni zbroj i artikle s praznom baznom cijenom.

```sh
php scripts/bebes_bbm_provjera.php
```

BBM-ov izvoz sadrži jedan UTF-8 CSV s osam stupaca: naziv, sifra, mjera, mpcijena, mpcijena akcija, barkod, mpcijena bazna i mpcijena bazna datum. Modul provjerava OIB, adresu, PJ1 i datum u imenu datoteke. Neprazna akcijska cijena postaje važeći MPC. Izvorne šifre i barkodovi zadržavaju početne nule. Marka i eksplicitni atributi neto količine mogu se dopuniti iz kataloga trgovca kod jednoznačnog podudaranja. Iznosi za PJ1 dolaze iz BBM-a. Za provjeru spremljenog ZIP-a dodajte njegovu putanju naredbi iznad.

BBM je 20. 11. 2026. dostavio varijantu sa sedam stupaca. Trgovac je potvrdio da izostanak stupca `mpcijena akcija` znači da nema akcijskih cijena. Modul automatski čita obje varijante po zaglavlju. Ako stupac nedostaje ili je prazan, objavljuje `mpcijena` i oznaku NE. Ako je upisana akcijska cijena, objavljuje tu cijenu i oznaku DA. Oba formata moraju imati ostalih sedam stupaca. Cron zapisuje razlog eventualnog odbijanja u privatni dnevnik.

Stvarni raspored i sadržaj zaštićenog BBM izvoza treba potvrditi prvim preuzimanjem. Kod drukčije sheme usporedite zaglavlja i značenje polja s dobavljačem. Ako izvoz sadrži samo šifru i baznu cijenu, za ostale stupce potreban je vjerodostojan izvor PJ1 s odgovarajućim artiklima. Neuspjela prijava, stara datoteka, prazna datoteka ili nepoznata shema vraća pogrešku. Zadnji objavljeni par ostaje dostupan.

## 4. Zakazani posao

Produkcijska objava pokreće se svaki dan, uključujući subotu i nedjelju, u **00:30 po vremenu Europe/Zagreb**. Poslužitelj koristi UTC, pa se zbog automatskog praćenja ljetnog i zimskog vremena zakazuje provjera oba moguća UTC termina:

```cron
30 22,23 * * * /home/amds/.local/bin/bebes-anchor-cron.sh
45 8,9,22,23 * * * /home/amds/.local/bin/bebes-anchor-cron.sh --check-changes
```

Dodatne lokalne provjere u 00:45 i 10:45 preuzimaju najnoviji izvoz. Ako su oba sadržaja ista, zadržavaju postojeću objavu.

Skripta `scripts/bebes_anchor_cron.sh` provjerava lokalni zagrebački sat. Nastavlja samo ako je u Zagrebu točno 00:30, pa se poziv izvršava jednom svakoga dana i nakon promjene sata. Raspored mora imati `*` u polju dana u tjednu jer 00:30 po zagrebačkom vremenu tijekom dijela godine pada na prethodni UTC dan.

Skriptu iz repozitorija postaviti izvan web-korijena s ograničenim ovlastima:

```sh
install -d -m 700 /home/amds/.local/bin
install -m 700 /home/amds/bebes-source/scripts/bebes_anchor_cron.sh \
  /home/amds/.local/bin/bebes-anchor-cron.sh
```

Poziv mora koristiti HTTP zaglavlje `X-Anchor-Price-Key`; ključ se ne smije slati u URL-u niti zapisivati u javne upute ili logove. Postojeća datoteka `/home/amds/.config/bebes-anchor-curl.conf`, s ovlastima `600`, sadrži URL, opcije Curla i tajno zaglavlje. Skripta samo učitava tu datoteku i nikada ne ispisuje njezin sadržaj.

URL poziva:

```text
https://atelierbebes.com/index.php?route=extension/module/anchor_price/cron
```

Primjer sadržaja privatne Curl konfiguracije, s vrijednošću preuzetom iz administracije:

```text
fail
silent
show-error
url = "https://atelierbebes.com/index.php?route=extension/module/anchor_price/cron"
header = "X-Anchor-Price-Key: <KLJUC_IZ_ADMINISTRACIJE>"
```

Ključ je prikazan u administraciji modula. Svaki stvarni pokušaj dodaje u `/home/amds/logs/bebes-anchor-cron.log` samo zagrebačku vremensku oznaku, izlazni status Curla, HTTP status i rezultat provjere JSON odgovora. Log ima ovlasti `600`; ključ i tijelo odgovora ne zapisuju se. Uspješno izvršavanje ne šalje nikakav izlaz, dok neuspjeh vraća jednu opću poruku na standardni izlaz za pogreške kako bi je cPanel mogao poslati e-poštom.

Za idempotentnu ručnu provjeru, neovisno o trenutnom satu, koristiti:

```sh
/home/amds/.local/bin/bebes-anchor-cron.sh --run-now
tail -1 /home/amds/logs/bebes-anchor-cron.log
```

Uspješan redak završava s `curl_exit=0 http_status=200 json_success=1`. Pri svakom pozivu modul ponovno dohvaća BBM izvoz i čita web katalog. Dva jednaka kontrolna zbroja zadržavaju postojeću dnevnu objavu. Promjena u bilo kojem cjeniku stvara novi par. Izvještaj posljednjeg uspješnog poziva sa šiframa za provjeru nalazi se u privatnoj datoteci `/home/amds/logs/bebes-anchor-provjera.json` nakon nadogradnje omotača. Nakon promjene rasporeda treba provjeriti da je stari izravni Curl cron uklonjen te da oba gornja retka postoje po jednom.

## 5. Stranica i arhiva

Na stranici `https://atelierbebes.com/index.php?route=information/price_list` važeće datoteke imaju stalne adrese:

- `https://atelierbebes.com/index.php?route=information/price_list/download&location=PJ1`
- `https://atelierbebes.com/index.php?route=information/price_list/download&location=PJ3`

Arhivske poveznice koriste `publication_id`. Zadnji valjani redovni cjenik ostaje dostupan dok ga trgovac ne zamijeni. Trgovina čuva prethodne verzije 30 dana od zamjene, čime pokriva propisanih 15 dana. Naknadni povijesni primjerak ostaje označen i ne postaje važeći cjenik. Postojeće preuzete datoteke ostaju u izvornom formatu.

Datoteke nose OIB 59774997564 društva ALTITUDO CENTAR d.o.o., vrstu i adresu prodajnog mjesta, oznaku PJ1 ili PJ3, oznaku ROBA, redni broj pohrane i zagrebačku vremensku oznaku GGGGMMDD_SSMM. CSV koristi UTF-8 i točku sa zarezom. Nakon promjene ponovno pokrenite objavu do 11:00 na dan izmjene. Ako se promjena odnosi na PJ1, najprije zatražite da BBM osvježi izvoz.

## 6. Web trgovina i provjera povijesti

Za PJ3 modul čita web asortiman i aktualne maloprodajne cijene s PDV-om. Model artikla je šifra u cjeniku. Neto količina i jedinica za robu za koju je obvezna jedinična cijena unose se u hrvatske atribute proizvoda **Neto količina** i **Jedinica mjere**. Unesite količinu pakiranja, npr. 250 i ml. Web izvoz iskazuje 0,25 l i cijenu po litri. Provjerite primjenjivost za svaki artikl kojemu ti atributi nedostaju. Zaliha i transportna masa služe drugim namjenama. UPC u ovoj trgovini označava GLS. Provjereni EAN ima zaseban stupac.

U registru baznih cijena za svaku potvrdu unesite skupinu, stvarni datum prve ponude, referentni datum, podatak o akciji te izvor dokaza u razlogu izmjene. Prva skupina obuhvaća hranu, piće, kozmetiku, sredstva za čišćenje, toaletne potrepštine i proizvode za kućanstvo. Njezin je referentni datum 2. svibnja 2025. Za ostalu robu vrijedi 10. rujna 2026. Kod kasnije prve ponude vrijedi datum prve ponude. Za akciju na relevantni dan prve skupine provjerava se najniža cijena kroz 30 dana prije početka akcije. Za ostalu robu na akciji provjerava se prethodno primijenjena maloprodajna cijena.

Potvrde iz prethodne verzije prikazuju se za ponovnu provjeru. Neto i bruto iznosi, datumi i trag izmjena ostaju u registru. Na webu i u PJ3 CSV-u ostaju prethodno objavljene cijene koje je trgovac evidentirao kao potvrđene. Izvještaj ih označava za provjeru povijesne podloge dok se novi pregled ne dovrši. Zapisi bez potvrde ostaju prazni. U PJ1 se koristi dobavljačeva bazna cijena za to prodajno mjesto. Potvrdite s BBM-om koja je povijesna evidencija korištena za izvoz. Prazna bazna cijena ostaje u izvještaju za ispravak.

[Pravilnik o isticanju bazne cijene proizvoda (NN 132/2026, 1583)](https://narodne-novine.nn.hr/clanci/sluzbeni/2026_11_132_1583.html) · [Pravilnik o načinu objave cjenika proizvoda (NN 132/2026, 1584)](https://narodne-novine.nn.hr/clanci/sluzbeni/2026_11_132_1584.html)
