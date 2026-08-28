# Sigurno vraćanje produkcije

Produkcijski webroot je `/home/amds/bebes.agmedia.rocks`. Dok traje oporavak,
njegov aktivni `.htaccess` mora ostati deny-all i javno vraćati HTTP 403.

## 1. Prije koda

1. U cPanelu napravite i preuzmite novi backup baze. Sačuvajte postojeći code
   snapshot, incidentni arhiv i njihove SHA-256 vrijednosti.
2. U phpMyAdminu uključite OpenCart maintenance prije skidanja 403 zaštite:

   ```sql
   UPDATE oc_setting SET value = '1' WHERE `key` = 'config_maintenance';
   SELECT `key`, value FROM oc_setting WHERE `key` = 'config_maintenance';
   ```

3. S čistog uređaja rotirajte cPanel/SSH/SFTP/e-mail/database i payment/API
   tajne. Ne šaljite nove vrijednosti u chat, Git ili screenshot.

## 2. Stage pregledanog releasea

Uploadani release raspakirajte u novu mapu ispod `/home/amds`, provjerite
`MANIFEST.sha256`, pa pokrenite deployment helper s apsolutnom putanjom te mape:

```bash
cd /home/amds/PUTANJA-DO-RELEASEA
sha256sum -c MANIFEST.sha256
chmod 700 tools/deploy_on_cpanel.sh
tools/deploy_on_cpanel.sh /home/amds/PUTANJA-DO-RELEASEA
```

Helper gradi potpuno novi webroot samo iz pregledanog releasea. U njega brzo
premješta postojeće produkcijske confige, medije iz `image/catalog`, poslovne
upload/download datoteke i AutoSSL challenge datoteke. Iz tih podataka izdvaja
izvršne/sumnjive tipove i symlinkove. Zatim renameom zamjenjuje webroot, a cijeli
stari kod/runtime i izdvojene datoteke ostavlja u timestampiranom quarantineu
ispod `/home/amds/incident-bebes-20260828/`; ništa se nepovratno ne briše.
Aktivni deny-all ostaje, a pregledani production config priprema kao
`.htaccess.next`.

## 3. Baza i provider postavke

U phpMyAdminu prvo izvršite read-only
`sql/2026_08_28_read_only_security_audit.sql` i spremite rezultat uz incident.
Potvrdite svaki administratorski račun i svaki OCMOD zapis. Zatim izvršite
`sql/2026_08_28_security_payment_cleanup.sql`. Ta skripta zaključava sve admin
račune, gasi poznate sumnjive OCMOD zapise, prekida API sesije i ostavlja samo:

- `cod`
- `kekspay`
- `wspay`

Ponovno uključite samo svaki pojedinačno potvrđeni admin račun novom, jedinstvenom
lozinkom od najmanje 20 znakova. Iz release direktorija za svaki račun pokrenite
sljedeće; lozinka se ne prikazuje niti sprema u shell history:

```bash
read -rsp 'Nova admin lozinka: ' BEBES_NEW_ADMIN_PASSWORD
printf '\n'
printf '%s' "$BEBES_NEW_ADMIN_PASSWORD" | /opt/cpanel/ea-php74/root/usr/bin/php tools/reset_admin_password.php 'ADMIN_USERNAME'
unset BEBES_NEW_ADMIN_PASSWORD
```

Nepoznate račune ostavite onemogućene i uklonite tek nakon spremanja njihovih
metapodataka uz incident. Izvršite i
`sql/2026_08_28_force_customer_password_reset.sql`; kompromitirani login
kontroleri mogli su od 24. lipnja slati plaintext lozinke, pa svi kupci moraju
proći uobičajeni **Zaboravljena lozinka** postupak.

U KEKS portalu/provideru rotirajte callback credentials i GLS API credential te
ih uskladite s novim vrijednostima spremljenima u postavkama. WSPay produkcijski
ShopID/secret također rotirajte i provjerite WSPay v2 postavke. Nove vrijednosti
ne šaljite u chat, Git, e-mail ili screenshot.

## 4. Maintenance test

Tek nakon koraka 1-3 aktivirajte pregledana Apache pravila:

```bash
cd /home/amds/bebes.agmedia.rocks
mv .htaccess .htaccess.deny-all-20260828
mv .htaccess.next .htaccess
```

Javnost sada mora vidjeti OpenCart maintenance, ne checkout. Prijavite se u
admin i pregledajte svaki OCMOD/VQMod zapis. Za GLS je live baza već potvrdila
glavni `GLS Croatia Shipping for Bebes Basel QuickCheckout` v1.0.2. Njega
ostavite uključenog. Obrišite stari `Bebes GLS Locker Disable COD` v1.0.0 pa u
**Extensions → Installer** učitajte pregledani
`_packages/gls_locker_disable_cod_bebes_v1.0.1.ocmod.zip` iz releasea. U
**Extensions → Modifications** novi naziv mora biti
`ZZZ - GLS Locker Disable COD for Bebes (after core)`, uključen i ispod glavnog
GLS zapisa. Zasebni UPC patch ne instalirajte: pravilo `UPC=1` već je u glavnom
v1.0.2 paketu.

Tek nakon pregleda svih zapisa kliknite **Refresh**. U `system/storage/logs/ocmod.log`
za novi ZZZ zapis ne smije biti `NOT FOUND`. Ponovno pokrenite IOC skener nad
cijelim `/home/amds`; rezultat mora završiti s exit statusom 0 prije otvaranja
trgovine.

U istoj administratorskoj sesiji provjerite:

- naslovnicu, kategoriju, proizvod, košaricu, registraciju i login;
- normalni i quick checkout nude točno WSPay, pouzeće i KEKS;
- GLS dostava/paketomat pojavljuju se za košaricu u kojoj svi dostavljivi
  artikli imaju UPC `1`, a ne pojavljuju se ako barem jedan nema tu oznaku;
- GLS ParcelShop dopušta pouzeće, dok odabrani paketomat skriva i serverski
  odbija pouzeće;
- WSPay sandbox/test potvrdu, KEKS test callback s rotiranim credentialsima i
  pouzeće; ne testirati stvarnom karticom;
- `pp_pro`, drugi PayPal, Corvus i `free_checkout` direktne rute vraćaju 404;
- direktan pristup `admin/table_edit_ajax.php`, `vqmod/install/`, `system/`,
  `vendor/`, `catalog/controller/` i PHP datoteci u `image/` vraća 403;
- nema PHP warning/fatal poruka u novim logovima.

## 5. Monitor i skidanje maintenancea

Incident tools raspakirajte u `/home/amds/incident-tools-20260828`, izvan
webroota. Zatim pokrenite:

```bash
chmod 700 /home/amds/incident-tools-20260828/incident_ioc_scan.sh
chmod 700 /home/amds/incident-tools-20260828/file_integrity_monitor.sh
set -o pipefail
/home/amds/incident-tools-20260828/incident_ioc_scan.sh /home/amds | tee /home/amds/incident-bebes-20260828/ioc-scan-before-live.txt
test "${PIPESTATUS[0]}" -eq 0
/home/amds/incident-tools-20260828/file_integrity_monitor.sh --test-email
/home/amds/incident-tools-20260828/file_integrity_monitor.sh --init
```

Testna poruka mora stići na `tomislav@agmedia.hr`. Tek tada u cPanel **Cron
Jobs** dodajte svakih pet minuta:

```text
/home/amds/incident-tools-20260828/file_integrity_monitor.sh --check >/dev/null 2>&1
```

Kad svi testovi prođu, u adminu isključite Maintenance Mode. Nakon toga iz
neprijavljenog/incognito preglednika ponovite naslovnicu, registraciju, košaricu
i sva tri testna payment toka. Ako bilo koji IOC, nepoznati admin, callback
problem ili neočekivani payment ostane, odmah vratite deny-all `.htaccess`.

```bash
cd /home/amds/bebes.agmedia.rocks
mv .htaccess .htaccess.failed-20260828
mv .htaccess.deny-all-20260828 .htaccess
```
