import time
import sys
import math
import datetime

#print ('Number of arguments:', len(sys.argv), 'arguments.')
#print ('Argument List:', sys.argv[2], "   ",  sys.argv[3])

timezone = 3

def toJulianCentury(julian):
    return (julian-2451545)/36525

def geomMeanLongSunDeg(julianCentury):
    return (280.46646+julianCentury*(36000.76983 + julianCentury*0.0003032)) % 360

def geomMeanAnomSunDeg(julianCentury):
    return 357.52911+julianCentury*(35999.05029 - 0.0001537*julianCentury)

def eccentEarthOrbit(julianCentury):
    return 0.016708634-julianCentury*(0.000042037+0.0000001267*julianCentury)

def sunEqOfCtr(GMAS, julianCentury):
    return math.sin(math.radians(GMAS))*(1.914602-julianCentury*(0.004817+0.000014*julianCentury))+math.sin(math.radians(2*GMAS))*(0.019993-0.000101*julianCentury)+math.sin(math.radians(3*GMAS))*0.000289

def sunTrueLongDeg(GMLSdeg, sunEqofCtr):
    return GMLSdeg + sunEqofCtr

def sunTrueAnomDeg(GMAS, sunEqofCtr):
    return GMAS + sunEqofCtr

def sunAppLongDeg(julianCentury, STLdeg):
    return STLdeg-0.00569-0.00478*math.sin(math.radians(125.04-1934.136*julianCentury))

def meanObliqEclipticDeg(julianCentury):
    return (23+(26+((21.448-julianCentury*(46.815+julianCentury*(0.00059-julianCentury*0.001813))))/60)/60)

def obliqCorrDeg(julianCentury, MOE):
    return MOE+0.00256*math.cos(math.radians(125.04-1934.136*julianCentury))

def sunDeclinDeg(OCDeg, SALDeg):
    return math.degrees(math.asin(math.sin(math.radians(OCDeg))*math.sin(math.radians(SALDeg))))

def VarY(OCDeg):
    return math.tan(math.radians(OCDeg/2))*math.tan(math.radians(OCDeg/2))

def EqOfTimeMin(varY, GMLSDeg, EEO,  GMASDeg):
    return 4*math.degrees(varY*math.sin(2*math.radians(GMLSDeg))-2*EEO*math.sin(math.radians(GMASDeg))+4*EEO*varY*math.sin(math.radians(GMASDeg))*math.cos(2*math.radians(GMLSDeg))-0.5*varY*varY*math.sin(4*math.radians(GMLSDeg))-1.25*EEO*EEO*math.sin(2*math.radians(GMASDeg)))
    
def HASunriseDeg(LAT, SDDeg):
    return math.degrees(math.acos(math.cos(math.radians(90.833))/(math.cos(math.radians(LAT))*math.cos(math.radians(SDDeg)))-math.tan(math.radians(LAT))*math.tan(math.radians(SDDeg))))

def solarNoon(LONG, EqofTime, TZ):
    return (720-4*LONG-EqofTime+TZ*60)/1440

def sunriseTime(solarNoon, HASunriseDeg, TZ):
    time = solarNoon - (HASunriseDeg * 4 / 1440)
    timeInMilliSeconds = math.floor((time *24*60*60*1000 ))
    #+ (TZ*60*60*1000)
    ts = datetime.datetime.fromtimestamp(abs(timeInMilliSeconds)/1000.0)
    print("millisec", timeInMilliSeconds)


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


def sunrise(latitude, longitude, timeZone, julian):
    julianCentury = toJulianCentury(julian)
    print("julianCentury", julianCentury)
    GMLSdeg = geomMeanLongSunDeg(julianCentury)
    print("GMLSdeg", GMLSdeg)
    GMASdeg = geomMeanAnomSunDeg(julianCentury)
    print("GMASdeg", GMASdeg)
    EEO = eccentEarthOrbit(julianCentury)
    print("EEO", EEO)
    SunEOC = sunEqOfCtr(GMASdeg, julianCentury)
    print("SunEOC", SunEOC)
    SunTLDeg = sunTrueLongDeg(GMLSdeg,SunEOC)
    print("SunTLDeg", SunTLDeg)
    SunALDeg = sunAppLongDeg(julianCentury, SunTLDeg)
    print("SunALDeg", SunALDeg)
    MOEDeg = meanObliqEclipticDeg(julianCentury)
    print("MOEDeg", MOEDeg)
    OCDeg= obliqCorrDeg(julianCentury, MOEDeg)
    print("OCDeg", OCDeg)
    SunDecDeg = sunDeclinDeg(OCDeg, SunALDeg)
    print("SunDecDeg", SunDecDeg)
    Y = VarY(OCDeg)
    print("Y", Y)
    EqOTMin = EqOfTimeMin(Y, GMLSdeg, EEO,  GMASdeg)
    print("EqOTMin", EqOTMin)
    HASunDeg = HASunriseDeg(latitude, SunDecDeg)
    print("HASunDeg", HASunDeg)
    SNoon = solarNoon(longitude, EqOTMin, timeZone)
    print("SNoon", SNoon)

    return sunriseTime(SNoon, HASunDeg, timeZone)


print(sunrise(float(sys.argv[1]), float(sys.argv[2]), timezone, float(sys.argv[3])))