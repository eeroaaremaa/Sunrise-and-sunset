# Veebirakendus päiksetõusu ja loojangu arvutamiseks
### Autor: Eero Ääremaa
### Kuupäev: 22. aprill 2020

Projekt valmis vahemikul 16. - 22. aprill 2020.
Projekti loomiseks kasutasin Pythonit, PHP-d, JavaScripti ning HTMLi koos Bootstrapiga.
Veebirakendus on loodud kasutamiseks veebiserveris. Käivitamiseks järgi järgmist juhist:
1. Lae alla sunriseSunsetEeroAaremaa kaust ning käivita arvutis lokaalne server näiteks rakenduse [XAMPP](https://www.apachefriends.org/index.html) abil. Serveri käivitamisel piisab ainult Apache käivitamisest.
2. Paiguta kaust sunriseSunsetEeroAaremaa XAMPP-i htdocs kausta, mida võib leida näiteks "C:\xampp\htdocs"
3. Kontrolli, kas sul on paigaldatud [Python 3.8](https://www.python.org/downloads/) ja [pip](https://pip.pypa.io/en/stable/installing/)
4. Paigalda Pythoni teegid:
    1.  datetime - paigaldamiseks käsrurea käsk: `<pip install datetime>` dokumentatsioon: https://pypi.org/project/DateTime/
    2. timezonefinder - `<pip install timezonefinder>` dokumentatsioon: https://pypi.org/project/timezonefinder/
    3. pytz - `<pip install pytz>` dokumentatsioon: http://pytz.sourceforge.net/
5. Käivita veebibrauser ja ava `<localhost/sunriseSunsetEeroAaremaa>`


Projekti loomisele kulus oodatust rohkem aega, ligikaudu 30 tundi, kuna alguses proovisin kirjutada kogu projekti enda jaoks uue raamistikuga - [Vaadin-iga](https://vaadin.com/), sest tunnen ennast Javas kõige mugavamalt. Peale tervet päeva tööd, et saaksin Vaadiniga loodud rakenduse enda arvutisse loodud serveriga tööle mõistsin, et kuigi tegemist on väga jõulise raamistikuga pole see siiski minu situatsiooni jaoks optimaalne, kuna sellel pole eraldi Google Maps API toetust ning interaktiivse kaardi integreerimine oleks olnud liiga suur töö ühe koolinädala jaoks. Siiski olin jõudnud Vaadin-i raamistikuga üpris kaugele - kasutaja sai sisestada kuupäeva ja koordinaadid ning rakendus tagastas päiksetõusu ja loojangu ajad.

Peale seda tagasilööki otsustasin kirjutada veebirakenduse PHP, JavaScripti, HTML-i ja Bootstrapiga ning arvutuste jaoks otsustasin kasutada Pythonit. Olen nende vahenditega varasemalt ühe veebirakenduse loonud ning alustasin projektiga mitte päris uuesti aga pidin päiksetõusu ja loojangu arvutamise Javast Pythonisse ümber kirjutama.

Kõige suurem väljakutse oli minu jaoks Google Maps-i ja kasutaja poolt sisestatud koordinaatide sünkroniseerimise. Selle lahendasin küpsistega. Lisaks sellele oli Google Maps-i integreerimine kohati tülikas aga täna laialdasele dokumentatsioonile sain sellega üpris kiirelt hakkama. Päiksetõusu ja loojangu arvutamiseks olid valemid internetist kiiresti leitavad, seega see osutus oodatust kergemaks ülesandeks.

Kui projekti edasi arendada siis tasuks lisada ka pikema ajaperioodi vaatamise võimalus ja graafikud päiksetõusu ja aegade kohta.

Üldiselt jäin enda projektiga rahule ning arvan, et valmis igati viisakas veebirakendus.

Projekt on loodud, et kandideerida AS CGI Eesti praktikale. 