# 15min straipsnių scraper'is

### Informacija:

Scraper'iui nurodžius 15min. portalo straipsnio URL adresą, programa surenka
visą reikiamą informaciją iš nurodyto straipsnio. Tą patį atlieka ir su kitais,
nurodytame straipsnyje rastais, straipsniais. Apdorojus informaciją, straipsniai 
patalpinami duomenų bazėje. 

### Specifikacija:

* Naudojant CURL gaunamas nurodytas straipsnis;
* `simple_html_dom` biblioteka padeda išrinkti reikiamus straipsnio elementus.
* Naudojant MVC principus, URL įrašomas `articleSearch` - _View_. Tuomet pateikta 
informacija perduodama `Articles` - controller'iui, o vėliau perduodama `Article`
  modeliui, kuris gauna reikiamą informaciją iš `Crawler` ir perduoda ją į duomenų
  bazę per `Database` klasės metodus. 
  
Programa gali būti paprastai paleidžiama naudojant **Docker**. `docker-compose.yml`
failas nurodo Docker'iui sukurti 3 konteinerius:

* PHP
* Nginx
* MySQL

**PHP** konteinerio kūrimo metu įrašomas **Composer**, kuris kartu bus naudojamas kaip 
programos klasių _autoloader'is_.

**MySQL** konteinerio kūrimo metu sukofigūruojama duomenų bazė, sukuriama `articles` lentelė.

### Paleidimas

Turint **Docker** ir **docker-compose** programą paleisti galima programos _root_
aplanke paleidus komandą `docker-compose up -d`.

Norint sustabdyti programą, root aplanke reikia paleisti komandą `docker-compose down -v`.

#### Paleidžiant be Docker

Paleidžiant programą kitais metodais, greičiausiai reikės kitu būdu importuoti
`docker/mysql/docker-entrypoint-initdb.d/migration.sql` migracijos failą, kuriame nurodyta
`articles` lentelės struktūra.

Taip pat greičiausiai reikės paleisti `composer dump-autoload -o` komandą.
