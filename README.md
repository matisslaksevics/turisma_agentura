# Ceļojumu izveides un iegādes sistēma

## Projekta apraksts
Projekta mērķis ir izstrādāt web aplikāciju. Aplikācija tiks saistīta ar ceļojumiem. Aplikācijai dos klientiem iespēju pārlūkot dažādus ceļojumus uz dažādām valstīm un pilsētām un iegādāties tos, kā arī administratoriem iespēju izveidot jaunus ceļojumus, izdzēst jau reģistrētus ceļojumus un pat rediģēt tos. Daudzi cilvēki, kuri nav ceļojuši nekad vai jau ādu laiku nav ceļojuši, varētu redzēt tūrisma mājaslapas kā grūti lietojamas vai pat dažreiz nesaprotamas, tāpēc mana web aplikācija padarīs to visu vieglāku un daudz pārredzamāku ar ļoti vienkāršu dizainu un izskatu.

## Izmantotās tehnoloģijas
Projektā tiek izmantots:
- HTML
- CSS
- PHP
- JS
- MySQL
- Stripe API

## Izmantotie avoti
[W3School](https://www.w3schools.com/html/default.asp) - example html kods.
[W3School](https://www.w3schools.com/sql/sql_datatypes.asp) - MySQL Servera datu tipi.
[W3School](https://www.w3schools.com/tags/tag_tbody.asp) - HTML tbody un thead tagi.
[W3School](https://www.w3schools.com/howto/howto_css_modals.asp) - JS un CSS Modal kastes piemeri.
[Getbootstrap](https://getbootstrap.com/docs/4.3/getting-started/introduction/) - bootstrap integrācijas skripti.
[Getbootstrap](https://getbootstrap.com/docs/4.0/components/forms/) - example bootstrap form kods.
[Getbootstrap](https://getbootstrap.com/docs/4.0/components/navbar/?) - example navbar kods.
[Getbootstrap](https://getbootstrap.com/docs/4.0/layout/grid/) - grid sistēma.
[PHPDelusions](https://phpdelusions.net/pdo) - PHP PDO dokumentācija.
[W3School](https://www.w3schools.com/php/php_sessions.asp) - PHP sesijas.
[C-SharpCorner](https://www.c-sharpcorner.com/UploadFile/051e29/dropdown-list-in-php/) - PHP Dropdown izvelnes piemēri.
[Stripe](https://stripe.com/docs) - Stripe API dokumentācija.
[GitHub](https://github.com/stripe/stripe-php) - Stripe API GitHub repozitorija.

## Uzstādīšanas instrukcijas
1. Lai lietotu git lejupielādējam [Git for windows](https://git-scm.com/download/win)
2. Instalējam git.
3. [Lejupielādējam WAMP](http://www.wampserver.com/en/), lai varētu izveidot webserveri.
4. Instalējam WAMP.
5. Pārliecinamies par WAMP darbību atverot adresi http://localhost/
6. Dodamies uz WAMP atrašanās vietu parasti c:\wamp\www un izdzēšam tā saturu.
7. Veicam labo klikšķi un izvēlamies opciju "git bash here" un izpildam zemāk raksīto komandu.
```
git clone https://github.com/rvtprog-kvalifikacija-20/d41-MatissLaksevics-TurismaAgentura.git
```
8. Atveram adresi http://localhost/phpmyadmin/index.php un pieslēdzamies ar lietotājvārdu "root" bez paroles.
9. Izveidojam jaunu datu bāzi ar nosaukumu "turisma_agentura"
10. Dodamies uz "Import" sekciju datu bāzē un importējam pievienoto .sql failu no klonētās repozitorijas.
11. Atveram adresi http://localhost/d41-MatissLaksevics-TurismaAgentura/ un pārbaudam vai programma strādā.
