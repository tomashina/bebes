# Sidrene cijene i dnevni cjenici — puštanje na produkciju

Ove upute odnose se na modul `Bebes sidrene cijene i dnevni cjenici PJ1/PJ3` za OpenCart. Produkcijsko puštanje prvo treba uvježbati na kopiji produkcijske baze i datoteka. SQL skripte koriste prefiks tablica `oc_` i očekuju MySQL 8 funkcije korištene u migracijama.

## 1. Obvezna sigurnosna kopija

Prije bilo kakve izmjene napraviti i provjeriti potpunu sigurnosnu kopiju:

- cijele produkcijske baze podataka;
- svih OpenCart datoteka, posebno mapa `admin/`, `catalog/`, `system/storage/modification/` i `system/storage/download/`;
- postojećih OCMOD izmjena i postavki zakazanih poslova.

Zabilježiti vrijeme kopije i unaprijed potvrditi postupak povrata. Ne nastavljati ako se sigurnosna kopija ne može probno otvoriti ili vratiti.

## 2. Učitavanje OCMOD paketa

1. U administraciji otvoriti **Extensions > Extension Installer** i učitati paket `_ocmod/bebes_anchor_prices_v1.0.0.ocmod.zip`.
2. Otvoriti **Extensions > Modifications** i pritisnuti **Refresh**.
3. Očistiti OpenCart/theme predmemoriju ako je uključena.
4. Provjeriti da instalacija nije prijavila pogrešku i da su nove datoteke na poslužitelju.

Paket ne sadrži SQL migracije. One se izvršavaju zasebno kako bi svaki korak bio kontroliran i provjerljiv.

Paket izravno isporučuje i zajedničke Basel predloške kartica proizvoda, mega-menu, stilove i skriptu žive pretrage. Ti se resursi u temi učitavaju izravno i ne prolaze kroz OCMOD predmemoriju, pa nije dovoljno instalirati samo XML izmjenu. Pri ručnom puštanju treba prenijeti cijeli sadržaj mape `upload/`; verzije CSS-a i JavaScripta u zaglavlju/podnožju služe za trenutno osvježavanje predmemorije preglednika.

## 3. SQL migracije

Migracije izvršiti na produkcijskoj bazi točno ovim redoslijedom, jednu po jednu:

1. `sql/2026_09_28_anchor_price_01_schema.sql`
2. `sql/2026_09_28_anchor_price_02_activation.sql`
3. `sql/2026_09_28_anchor_price_03_backfill.sql`
4. `sql/2026_09_28_anchor_price_04_verify_read_only.sql`

Prve tri skripte stvaraju strukturu, aktiviraju modul i popunjavaju postojeće artikle. Četvrta je samo za čitanje i služi kao kontrola prije prve objave. Ne preskakati rezultate nijednog kontrolnog upita. Popisi nepravilnosti moraju biti prazni, a broj artikala i pokrivenost sidrenim cijenama moraju odgovarati aktivnom katalogu.

Backfill uzima postojeće cijene iz baze. Za artikle objavljene do 10. 9. 2026. koristi se referentni datum 10. 9. 2026. Postojećim aktivnim artiklima dodanima poslije tog prijelaznog datuma `date_added` se postavlja samo kao kandidat za datum prve objave, uz status `pending`. Administrator mora provjeriti stvarni datum prve objave, po potrebi ga ispraviti uz obrazloženje i zatim potvrditi zapis. `date_added` se ne smije smatrati automatskim konačnim dokazom objave. Nacrti dodani poslije prijelaznog datuma ne popunjavaju se unaprijed; modul hvata cijenu i datum kada se artikl prvi put aktivira. Neaktivni povijesni zapisi ostaju `pending` i ne ulaze u cjenik; pri aktivaciji ih treba pregledati i potvrditi, ili to administrator može učiniti ranije ručnom provjerom.

## 4. Blokada prije prve objave

Ne pokretati ručnu objavu i ne uključivati zakazani posao dok sve provjere nisu završene.

Barkod nije obvezan: artikl kod kojeg su `EAN`, `JAN` i `ISBN` svi prazni smije ući u objavu. Ako je bilo koje od tih polja popunjeno, svaka upisana vrijednost mora biti valjani GTIN-8, GTIN-12, GTIN-13 ili GTIN-14: isključivo numerička, dopuštene duljine i s ispravnom kontrolnom znamenkom. Polje `UPC` se u ovoj trgovini koristi za GLS i ne smije se preuzimati kao barkod proizvoda. Kontrolni popis prikazuje samo upisane nevaljane vrijednosti i mora biti prazan prije produkcijske objave. Ako artikl nema stvarni barkod, pogrešno unesenu vrijednost treba ukloniti umjesto izmišljati GTIN.

Prije prve objave također potvrditi sljedeće:

- svaki aktivni artikl ima potvrđenu sidrenu cijenu, naziv, model i proizvođača;
- porezni razredi i izračun bruto cijene odgovaraju produkcijskim podacima;
- datum sidrene cijene za artikle dodane nakon 10. 9. 2026. odgovara stvarnom datumu prve objave;
- izlazna mapa `system/storage/download/anchor_price/` postoji ili je aplikacija može stvoriti i u nju pisati.

## 5. PJ1, PJ3 i dostupnost

Svaki dan nastaju dvije odvojene CSV datoteke:

- `PJ1` — fizička trgovina; naziv datoteke sadrži prodajno mjesto/adresu i oznaku `PJ1`;
- `PJ3` — web trgovina; naziv datoteke sadrži web prodajno mjesto/domenu i oznaku `PJ3`.

Cijene su iste za oba prodajna mjesta, prema potvrdi korisnika. Nije potvrđeno da je jednak i asortiman. Zbog jednog globalnog OpenCart store/mapiranja trenutna implementacija u `PJ1` i `PJ3` uključuje isti aktivni skup artikala. Ako se stvarni asortiman razlikuje, prije produkcijske objave mora se dodati vjerodostojan izvor ili mapa artikala po lokaciji.

Trenutna baza također ima samo jednu globalnu količinu artikla. Zbog toga modul sada objavljuje jednaku dostupnost za `PJ1` i `PJ3`. Ako stvarna zaliha fizičke i web trgovine nije jednaka, modul se ne smije pustiti u produkcijsku objavu dok se ne osigura vjerodostojan izvor količine po lokaciji i mapiranje tog izvora na `PJ1` i `PJ3`.

## 6. Administracija sidrenih cijena

U administracijskom modulu može se pregledati i, uz odgovarajuću ovlast, izmijeniti neto cijena, bruto cijena i referentni datum sidrene cijene. Svaka ručna izmjena zahtijeva obvezan razlog i ostavlja revizijski zapis s prethodnim i novim vrijednostima te korisnikom i vremenom izmjene.

Zapise sa statusom `pending` treba pregledati i potvrditi prije objave. Prije produkcijskog crona provjeriti da nijedan aktivni artikl koji ulazi u cjenik nije ostao nepotvrđen.

## 7. Zakazani posao

Zakazani posao postaviti za svaki radni dan, dovoljno prije 08:00, u vremenskoj zoni `Europe/Zagreb` (primjerice u 07:30). Poziv mora koristiti HTTP zaglavlje `X-Anchor-Price-Key`; ključ se ne smije slati u URL-u niti zapisivati u javne upute ili logove.

URL poziva:

```text
https://atelierbebes.com/index.php?route=extension/module/anchor_price/cron
```

Primjer poziva, s vrijednošću preuzetom iz administracije:

```sh
curl --fail --silent --show-error \
  --header 'X-Anchor-Price-Key: <KLJUC_IZ_ADMINISTRACIJE>' \
  'https://atelierbebes.com/index.php?route=extension/module/anchor_price/cron'
```

Ključ je prikazan u administraciji modula. Produkcijski scheduler treba zabilježiti samo uspjeh ili pogrešku poziva, bez sadržaja zaglavlja. Na neuspjeh zakazanog posla postaviti upozorenje odgovornoj osobi prije 08:00.

## 8. Javna objava i arhiva

Javna stranica cjenika nalazi se na:

```text
https://atelierbebes.com/index.php?route=information/price_list
```

Objavljene CSV datoteke čuvaju se 30 dana. Starije datoteke modul označava isteklima i uklanja iz javno dostupne arhive. Ne postavljati dodatni vanjski posao koji bi brisao datoteke mlađe od 30 dana.

## 9. Završna provjera

Nakon prvog kontroliranog pokretanja, a prije 08:00, provjeriti:

- odgovor zakazanog poziva označava uspjeh;
- za isti datum postoje točno dvije objavljene datoteke, jedna za `PJ1` i jedna za `PJ3`;
- obje datoteke imaju jednak broj artikala i, osim polja prodajnog mjesta/dostupnosti po lokaciji, iste dogovorene podatke i cijene;
- naziv svake datoteke sadrži vrstu prodajnog mjesta, adresu/domenu, `PJ1` odnosno `PJ3`, redni broj i vremensku oznaku;
- datoteke se mogu preuzeti s javne stranice i otvoriti kao CSV;
- kontrolni zbroj SHA-256 spremljene datoteke odgovara zapisu publikacije;
- nema aktivnog artikla bez potvrđene sidrene cijene ili obveznih podataka, niti artikla s upisanim nevaljanim GTIN-om;
- ponovno pokretanje istog dana ne stvara nepotrebne duplikate;
- administracija i javna stranica prikazuju obje publikacije;
- sidrena cijena prikazuje se na detalju proizvoda, kategoriji, pretrazi, akcijama, proizvođaču, povezanim proizvodima, početnim Basel modulima, standardnim modulima, mega-menu proizvodu i živoj pretrazi;
- publikacije starije od 30 dana više nisu javno dostupne, dok zadnjih 30 dana ostaje dostupno.

Ako bilo koja od navedenih provjera ne uspije, zaustaviti daljnju objavu, sačuvati log i rezultat četvrte SQL provjere te vratiti sustav prema prethodno pripremljenom postupku oporavka.
