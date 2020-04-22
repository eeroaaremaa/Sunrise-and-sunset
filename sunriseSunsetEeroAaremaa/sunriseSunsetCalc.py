'''
Programm päiksetõusu, loojangu ja päeva arvutamiseks.
Autor: Eero Ääremaa
Kuupäev: 22. aprill 2020

Programm nõuab käivitamiseks järgnevaid teeke, mida saab paigaldada järgnevate käskudega:
    1. time - standardteek, mida pole vaja eraldi paigaldada
    2. sys - standardteek
    3. math - standardteek
    4. datetime - paigaldamiseks käsrurea käsk: "pip install datetime" dokumentatsioon: https://pypi.org/project/DateTime/
    5. timezonefinder - "pip install timezonefinder" dokumentatsioon: https://pypi.org/project/timezonefinder/
    6. pytz - "pip install pytz" dokumentatsioon: http://pytz.sourceforge.net/

Programmi käivitamiseks tuleb käsurealt anda: 
    1. Laiuskraad
    2. Laiuskraadi minutid
    3. Laiuskraadi sekundid
    4. Pikkuskraad
    5. Pikkuskraadi minutid
    6. Pikkuskraadi sekundid
    7. Kuupäev formaadis: "aaaa-kk-pp"

Programm tagastab: 
    1. Päiksetõusu kellaaja tundides ja minutites
    2. Päikseloojangu kellaaja tundides ja minutites
    3. Päeva pikkuse tundides ja minutites


Allikad:
    1. Earth System Research Laboratories Global Monitoring Laboratory, Solar Calculation Details
        https://www.esrl.noaa.gov/gmd/grad/solcalc/calcdetails.html
    2. Astronomy StackExchange: https://astronomy.stackexchange.com/questions/24598/how-to-calculate-the-maximum-and-minimum-solar-azimuth-at-a-given-location


'''

# https://astronomy.stackexchange.com/questions/24598/how-to-calculate-the-maximum-and-minimum-solar-azimuth-at-a-given-location
# https://www.esrl.noaa.gov/gmd/grad/solcalc/calcdetails.html

import time
import sys
import math
import datetime
from timezonefinder import TimezoneFinder
import pytz

# Kasutaja sisestab kuupäeva tavalises formaadis aga päiksetõusu ja loojangu arvutamiseks
# on tarvis kuupäeva Juliuse kalendris ning järgmine funktsioon tõlgib kuupäeva Juliuse kalendrisse
def toJulian(date):
    JGREG = 15 + 31*(10+12*1582)

    # Võtame ette antud sõnest välja vajalikud arvud: aasta, kuu ja päev
    dateList = date.split("-")
    year = float(dateList[0])
    month = float(dateList[1])
    day = float(dateList[2])
    julianYear = year

    # Teeme vajalikud arvutused
    # William H. Jefferys "Julain Day Numbers" 1996 
    # https://quasar.as.utexas.edu/BillInfo/JulianDatesG.html
    if(year < 0): 
        julianYear += 1
    julianMonth = month
    if (month > 2):
        julianMonth += 1
    else:
        julianYear -= 1
        julianMonth += 13
    
    julian = (math.floor(365.25 * julianYear) + math.floor(30.6001*julianMonth) + day + 1720995.0)
    
    if (day + 31 * (month + 12 * year) >= JGREG):
        ja = int(0.01 * julianYear)
        julian += 2 - ja + (0.25 * ja)
        
    # Tagastame vastava päeva Juliuse kalendris
    return math.floor(julian)
    
# Järgnev meetod tagastab kui kaugel antud ajahetk on ühes Juliuse sajandis
def toJulianCentury(julian):
    return (julian-2451545)/36525

# Arvutame järgne kahe funktsioonig välja päikse geograafilise positsiooni kraadides
def geomMeanLongSunDeg(julianCentury):
    return (280.46646+julianCentury*(36000.76983 + julianCentury*0.0003032)) % 360

def geomMeanAnomSunDeg(julianCentury):
    return 357.52911+julianCentury*(35999.05029 - 0.0001537*julianCentury)

# Arvutame Maa orbiidi ekstsentrilisuse antud ajahetkel
# Lisalugemist: https://et.wikipedia.org/wiki/Orbiidi_ekstsentrilisus
def eccentEarthOrbit(julianCentury):
    return 0.016708634-julianCentury*(0.000042037+0.0000001267*julianCentury)

# Abifunktsioon keskmise anomaalia arvutamiseks
def sunEqOfCtr(GMAS, julianCentury):
    return math.sin(math.radians(GMAS))*(1.914602-julianCentury*(0.004817+0.000014*julianCentury))+math.sin(math.radians(2*GMAS))*(0.019993-0.000101*julianCentury)+math.sin(math.radians(3*GMAS))*0.000289

# Arvutame päikse nurga anomaalia
def sunTrueAnomDeg(GMAS, sunEqofCtr):
    return GMAS + sunEqofCtr
 
# Leiame päikse ekliptilise pikkuse kraadides
def sunTrueLongDeg(GMLSdeg, sunEqofCtr):
    return GMLSdeg + sunEqofCtr

# Leiame tegeliku päiks ekliptilise pikkuse, kui võtame arvesse aberratsiooni ja pöörde
def sunAppLongDeg(julianCentury, STLdeg):
    return STLdeg-0.00569-0.00478*math.sin(math.radians(125.04-1934.136*julianCentury))

# Võtame arvesse Maa ligikaudu 23.4 kraadise nurga päikse suhtes
# Lisalugemiseks: http://radixpro.com/a4a-start/obliquity/
def meanObliqEclipticDeg(julianCentury):
    return (23+(26+((21.448-julianCentury*(46.815+julianCentury*(0.00059-julianCentury*0.001813))))/60)/60)

def obliqCorrDeg(julianCentury, MOE):
    return MOE+0.00256*math.cos(math.radians(125.04-1934.136*julianCentury))

# Võtame arvesse Maa kääde ehk deklinatsiooni
def sunDeclinDeg(OCDeg, SALDeg):
    return math.degrees(math.asin(math.sin(math.radians(OCDeg))*math.sin(math.radians(SALDeg))))

# Kasutame abivalemit
def VarY(OCDeg):
    return math.tan(math.radians(OCDeg/2))*math.tan(math.radians(OCDeg/2))

# Võtame arvesse aja valemi minutites
# Lisalugemist: https://en.wikipedia.org/wiki/Equation_of_time
def EqOfTimeMin(varY, GMLSDeg, EEO,  GMASDeg):
    return 4*math.degrees(varY*math.sin(2*math.radians(GMLSDeg))-2*EEO*math.sin(math.radians(GMASDeg))+4*EEO*varY*math.sin(math.radians(GMASDeg))*math.cos(2*math.radians(GMLSDeg))-0.5*varY*varY*math.sin(4*math.radians(GMLSDeg))-1.25*EEO*EEO*math.sin(2*math.radians(GMASDeg)))

# Arvutame ekvatoriaalse koordinaadi tunninurk
def HASunriseDeg(LAT, SDDeg):
    return math.degrees(math.acos(math.cos(math.radians(90.833))/(math.cos(math.radians(LAT))*math.cos(math.radians(SDDeg)))-math.tan(math.radians(LAT))*math.tan(math.radians(SDDeg))))

# Arvutame millal on päike seniidile kõige lähemal ehk millal on solaarne keskpäev
def solarNoon(LONG, EqofTime, TZ):
    return (720-4*LONG-EqofTime+TZ*60)/1440

# Abifunktsioon, et kuvad millisekundied tundide ja minutitena
def millisToTime(time):
    timeInMilliSeconds = math.floor((time *24*60*60*1000 ))
    
    millis = int(timeInMilliSeconds)
    seconds=(millis/1000)%60
    seconds = int(seconds)
    minutes=(millis/(1000*60))%60
    minutes = int(minutes)
    hours=(millis/(1000*60*60))%24
    if(seconds >= 30):
        minutes += 1
    if(minutes < 10):
        minutes_str = "0" + str(minutes)
    else:
        minutes_str = minutes
    
    return ("%d:%s" % (hours, minutes_str))

# Arvutame päiksetõusu aja ning tagastame selle tundide ja minutitena
# Kordame sarnast protsessi päikseloojangu leidmiseks
def sunriseTime(solarNoon, HASunriseDeg, TZ):
    time = solarNoon - (HASunriseDeg * 4 / 1440)
    result = millisToTime(time)

    return result

def sunsetTime(solarNoon, HASunriseDeg, TZ):
    time = solarNoon + (HASunriseDeg * 4 / 1440)
    result = millisToTime(time)

    return result

# Leiame päeva pikkuse ja tagastame selle tundide ja minutitena
def dayLength(solarNoon, HASunriseDeg, TZ):
    sunriseTime = solarNoon - (HASunriseDeg * 4 / 1440)
    sunsetTime = solarNoon + (HASunriseDeg * 4 / 1440)
    dif = sunsetTime - sunriseTime

    result = millisToTime(dif)

    return result

# Järgnev funktsioon rakendab tervet valemit, kasutates eelnevalt loodud abivalemeid
def sunriseSunset(latitude, longitude, timeZone, date):

    julian = toJulian(date)
    julianCentury = toJulianCentury(julian)
    GMLSdeg = geomMeanLongSunDeg(julianCentury)
    GMASdeg = geomMeanAnomSunDeg(julianCentury)
    EEO = eccentEarthOrbit(julianCentury)
    SunEOC = sunEqOfCtr(GMASdeg, julianCentury)
    SunTLDeg = sunTrueLongDeg(GMLSdeg,SunEOC)
    SunALDeg = sunAppLongDeg(julianCentury, SunTLDeg)
    MOEDeg = meanObliqEclipticDeg(julianCentury)
    OCDeg= obliqCorrDeg(julianCentury, MOEDeg)
    SunDecDeg = sunDeclinDeg(OCDeg, SunALDeg)
    Y = VarY(OCDeg)
    EqOTMin = EqOfTimeMin(Y, GMLSdeg, EEO,  GMASdeg)
    HASunDeg = HASunriseDeg(latitude, SunDecDeg)
    SNoon = solarNoon(longitude, EqOTMin, timeZone)

    # Tagastame päiksetõusu, päikseloojangu ja päeva pikkus
    return sunriseTime(SNoon, HASunDeg, timeZone) + " " + sunsetTime(SNoon, HASunDeg, timeZone) + " " + dayLength(SNoon, HASunDeg, timeZone)


# Abifunktisoon, mis tõlgib kraadid minutit ja sekundid kümnendsüsteemi
def dms_to_deg (deg, min, sec):
	return float(deg) + (float(min)/60) + (float(sec)/3600)

# Järgnev meetod leiab automaatselt vastavate koordinaatide ajavööndi
# ning arvestab ka kella keeramisega.
# Selle jaoks olen kasutanud Pythoni teeki TimezoneFinder ja pytz
def getTimezone(latit, longit, date):

    # Esmalt leiame TimzoneFinderiga ajavööndi kujul näiteks "Europe/Tallinn"
    tzFinder = TimezoneFinder()
    tz_string = tzFinder.certain_timezone_at(lng = longit, lat = latit)
    timezone = pytz.timezone(tz_string)
    print(timezone, " timezone")

    # Kui ajavöönd on leitud siis leiame mis on selle konkreetse ajavööndi erinevus UTC ajast
    if(timezone is not None):
        currentDate = date
        date_time_str = currentDate + ' 12:00:00.000000'
        date_time_obj = datetime.datetime.strptime(date_time_str, '%Y-%m-%d %H:%M:%S.%f')
        print(date_time_obj)
        utc_offset = timezone.utcoffset(date_time_obj)
        print(utc_offset)
    else:
        return "NODATA"

    print(str(utc_offset))

    # Järgmiste kui-laustega teeme kindaks ajavööndi, et see kümnendsüsteemis edastada.
    # Kui ajavöönd on negatiivne siis võtame ühtedelt kohtadelt soovitud info aga 
    # kui positiivne siis peame soovitud info mujalt kätte saama.
    # Arvestame ka ajavööndeid, mis erinevad 15, 30 või 45 min võrra
    timezone = 0
    utcStr = str(utc_offset)
    first = utcStr[0]
    if(first == "-"):
        timezone = -1
        hour = utcStr[8:10]
        timezone = int(hour)-24
        if(utcStr[11:13] != "00"):
            if(utcStr[11:13] == "15"):
                timezone -= 0.25
            if(utcStr[11:13] == "30"):
                timezone -= 0.5
            if(utcStr[11:13] == "45"):
                timezone -= 0.75
    else:
        if(utcStr[1:2] == ":"):
            hour = utcStr[0:1]
            timezone = hour
            print(utcStr[2:4])
            if(utcStr[2:4] != "00"):
                if(utcStr[2:4] == "15"):
                    timezone += 0.25
                if(utcStr[2:4] == "30"):
                    timezone += 0.5
                if(utcStr[2:4] == "45"):
                    timezone += 0.75
        else:
            hour = utcStr[0:2]
            timezone = hour
            print(utcStr[3:5])
            if(utcStr[3:5] != "00"):
                if(utcStr[3:5] == "15"):
                    timezone += 0.25
                if(utcStr[3:5] == "30"):
                    timezone += 0.5
                if(utcStr[3:5] == "45"):
                    timezone += 0.75
    
    # Tagastame ajavööndi täisarvuna
    return timezone


# Võtame käsurealt laius- ja pikkuskraadid ning kuupäeva
latDeg = dms_to_deg(sys.argv[1], sys.argv[2], sys.argv[3])
lngDeg = dms_to_deg(sys.argv[4], sys.argv[5], sys.argv[6])
date = sys.argv[7]
# Leiame ajavööndi
timezone = float(getTimezone(latDeg, lngDeg, date))
# Trükime ekraanile päiksetõusu, loojangu ja päeva pikkus, mille hiljem php-ga kinni püüame
print(sunriseSunset(latDeg, lngDeg, timezone, date))