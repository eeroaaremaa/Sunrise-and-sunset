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

Projekt on loodud, et kandideerida AS CGI Eesti praktikale. 