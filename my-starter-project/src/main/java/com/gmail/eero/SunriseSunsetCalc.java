package com.gmail.eero;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;


class SunriseSunsetCalc {
    public static int JGREG= 15 + 31*(10+12*1582);
    public static double HALFSECOND = 0.5;

    public static double toJulian(int[] ymd) {
        int year=ymd[0];
        int month=ymd[1]; // jan=1, feb=2,...
        int day=ymd[2];
        int julianYear = year;
        if (year < 0) julianYear++;
        int julianMonth = month;
        if (month > 2) {
            julianMonth++;
        }
        else {
            julianYear--;
            julianMonth += 13;
        }

        double julian = (Math.floor(365.25 * julianYear)
                + Math.floor(30.6001*julianMonth) + day + 1720995.0);
        if (day + 31 * (month + 12 * year) >= JGREG) {
            // change over to Gregorian calendar
            int ja = (int)(0.01 * julianYear);
            julian += 2 - ja + (0.25 * ja);
        }
        return Math.floor(julian);
    }

    public static double toJulianCentury(double julian){
        return (julian-2451545)/36525;
    }

    public static double geomMeanLongSunDeg(double julianCentury){
        return (280.46646+julianCentury*(36000.76983 + julianCentury*0.0003032)) % 360;
        //return (280.46646+julianCentury*(36000.76983 + (julianCentury*0.0003032)) % 360);
    }

    public static double geomMeanAnomSunDeg(double julianCentury){
        return 357.52911+julianCentury*(35999.05029 - 0.0001537*julianCentury);
    }

    public static double eccentEarthOrbit(double julianCentury){
        return 0.016708634-julianCentury*(0.000042037+0.0000001267*julianCentury);
    }

    public static double sunEqOfCtr(double GMAS, double julianCentury){
        return Math.sin(Math.toRadians(GMAS))*(1.914602-julianCentury*(0.004817+0.000014*julianCentury))+Math.sin(Math.toRadians(2*GMAS))*(0.019993-0.000101*julianCentury)+Math.sin(Math.toRadians(3*GMAS))*0.000289;
    }

    public static double sunTrueLongDeg(double GMLSdeg, double sunEqofCtr){
        return GMLSdeg + sunEqofCtr;
    }

    public static double sunTrueAnomDeg(double GMAS, double sunEqofCtr){
        return GMAS + sunEqofCtr;
    }

    public static double sunRadVectorAUs(double EEO, double STA){
        return (1.000001018*(1-EEO*EEO))/(1+EEO*Math.cos(Math.toRadians(STA)));
    }

    public static double sunAppLongDeg(double julianCentury, double STLdeg){
        return STLdeg-0.00569-0.00478*Math.sin(Math.toRadians(125.04-1934.136*julianCentury));
    }


    public static double meanObliqEclipticDeg(double julianCentury){
        return (23+(26+((21.448-julianCentury*(46.815+julianCentury*(0.00059-julianCentury*0.001813))))/60)/60);
    }

    public static double obliqCorrDeg(double julianCentury, double MOE){
        return MOE+0.00256*Math.cos(Math.toRadians(125.04-1934.136*julianCentury));
    }

    public static double sunRtAscenDeg(double SALdeg, double OCdeg){
        return Math.toDegrees(Math.atan2(Math.cos(Math.toRadians(SALdeg)),(Math.cos(Math.toRadians(OCdeg))*Math.sin(Math.toRadians(SALdeg)))));
    }

    public static double sunDeclinDeg(double OCDeg, double SALDeg){
        return Math.toDegrees(Math.asin(Math.sin(Math.toRadians(OCDeg))*Math.sin(Math.toRadians(SALDeg))));
    }

    public static double VarY(double OCDeg){
        return Math.tan(Math.toRadians(OCDeg/2))*Math.tan(Math.toRadians(OCDeg/2));
    }

    public static double EqOfTimeMin(double varY, double GMLSDeg, double EEO, double GMASDeg){
        return 4*Math.toDegrees(varY*Math.sin(2*Math.toRadians(GMLSDeg))-2*EEO*Math.sin(Math.toRadians(GMASDeg))+4*EEO*varY*Math.sin(Math.toRadians(GMASDeg))*Math.cos(2*Math.toRadians(GMLSDeg))-0.5*varY*varY*Math.sin(4*Math.toRadians(GMLSDeg))-1.25*EEO*EEO*Math.sin(2*Math.toRadians(GMASDeg)));
    }

    public static double HASunriseDeg(double LAT, double SDDeg){
        return Math.toDegrees(Math.acos(Math.cos(Math.toRadians(90.833))/(Math.cos(Math.toRadians(LAT))*Math.cos(Math.toRadians(SDDeg)))-Math.tan(Math.toRadians(LAT))*Math.tan(Math.toRadians(SDDeg))));
    }

    public static double solarNoon(double LONG, double EqofTime, double TZ){
        System.out.println((720-4*LONG-EqofTime+TZ*60)*60);
        double rounded = Math.round((720-4*LONG-EqofTime+TZ*60)*60 *1000)/1000;
        System.out.println(rounded);
        int seconds = (int)rounded ;
        System.out.println(seconds);
        System.out.println(((seconds % 86400) / 3600) + " hours");
        System.out.println(((seconds % 3600) / 60) + " minutes");
        System.out.println(((seconds % 3600) % 60) + " seconds");

        return (720-4*LONG-EqofTime+TZ*60)/1440;
    }

    public static String sunriseTime(double solarNoon, double HASunriseDeg, double TZ){
        double time = solarNoon-(HASunriseDeg*4/1440);
        long timeInMilliSeconds = (long) Math.floor((time *24*60*60*1000 - (TZ*60*60*1000)));
        Date date = new Date(timeInMilliSeconds);
        SimpleDateFormat sdf = new SimpleDateFormat("HH:mm:ss");
        System.out.println(sdf.format(date));
        return sdf.format(date).toString();
    }

    public static void sunsetTime(double solarNoon, double HASunriseDeg, double TZ){
        double time = solarNoon+(HASunriseDeg*4/1440);
        long timeInMilliSeconds = (long) Math.floor((time *24*60*60*1000 - (TZ*60*60*1000)));
        Date date = new Date(timeInMilliSeconds);
        SimpleDateFormat sdf = new SimpleDateFormat("HH:mm:ss");
        System.out.println(sdf.format(date));
    }


    public static String sunrise(double latitude, double longitude, double timeZone){

        Calendar today = Calendar.getInstance();
        double todayJulian = toJulian
                (new int[]{today.get(Calendar.YEAR), today.get(Calendar.MONTH)+1,
                        today.get(Calendar.DATE)});
        System.out.println("Julian date for today : " + todayJulian);

        double julianCentury = toJulianCentury(todayJulian);
        System.out.println("Julian Century: " + julianCentury);

        //JAH
        double GMLSdeg = geomMeanLongSunDeg(julianCentury);
        System.out.println("Geom Mean Long Sun (deg): " + GMLSdeg);

        //JAH
        double GMASdeg = geomMeanAnomSunDeg(julianCentury);
        System.out.println(GMASdeg);

        //JAH
        double eccentEarthOrbit = eccentEarthOrbit(julianCentury);
        System.out.println(eccentEarthOrbit);

        //JAH
        double sunEqOfCtr = sunEqOfCtr(GMASdeg, julianCentury);
        System.out.println(sunEqOfCtr);

        //JAH
        double sunTrueLongDeg = sunTrueLongDeg(GMLSdeg,sunEqOfCtr);
        System.out.println(sunTrueLongDeg);

        //double sunTrueAnomDeg = sunTrueAnomDeg(GMASdeg,sunEqOfCtr);
        //System.out.println(sunTrueAnomDeg);

        //double sunRadVectorAUs = sunRadVectorAUs(eccentEarthOrbit, sunTrueAnomDeg);
        //System.out.println(sunRadVectorAUs);

        //JAH
        double sunAppLongDeg = sunAppLongDeg(julianCentury, sunTrueLongDeg);
        System.out.println(sunAppLongDeg);

        //JAH
        double meanObliqEclipticDeg = meanObliqEclipticDeg(julianCentury);
        System.out.println(meanObliqEclipticDeg);

        //JAH
        double obliqCorrDeg = obliqCorrDeg(julianCentury, meanObliqEclipticDeg);
        System.out.println(obliqCorrDeg);

        //PROBLEMAATILINE
        //double sunRtAscenDeg = sunRtAscenDeg(sunAppLongDeg, obliqCorrDeg);
        //System.out.println(sunRtAscenDeg);

        //JAH
        double sunDeclinDeg = sunDeclinDeg(obliqCorrDeg, sunAppLongDeg);
        System.out.println(sunDeclinDeg);

        //JAH
        double VarY = VarY(obliqCorrDeg);
        System.out.println(VarY);

        //JAH
        double EqOfTimeMin = EqOfTimeMin(VarY, GMLSdeg, eccentEarthOrbit, GMASdeg);
        System.out.println("EqOfTimeMin " + EqOfTimeMin);

        //JAH
        double HASunriseDeg = HASunriseDeg(latitude, sunDeclinDeg);
        System.out.println("HASunriseDeg " + HASunriseDeg);

        //double solarNoon = solarNoon(longitude, EqOfTimeMin, timeZone);
        //System.out.println(solarNoon);

        //JAH
        double solarNoon = solarNoon(longitude, EqOfTimeMin, timeZone);


        sunsetTime(solarNoon,HASunriseDeg, timeZone);

        return sunriseTime(solarNoon,HASunriseDeg, timeZone);
    }

    public static void main(String args[]) {

        double latitude = 58.3750242;
        double longitude = 26.718989;
        double timeZone = 3;

        Calendar today = Calendar.getInstance();
        double todayJulian = toJulian
                (new int[]{today.get(Calendar.YEAR), today.get(Calendar.MONTH)+1,
                        today.get(Calendar.DATE)});
        System.out.println("Julian date for today : " + todayJulian);

        double julianCentury = toJulianCentury(todayJulian);
        System.out.println("Julian Century: " + julianCentury);

        //JAH
        double GMLSdeg = geomMeanLongSunDeg(julianCentury);
        System.out.println("Geom Mean Long Sun (deg): " + GMLSdeg);

        //JAH
        double GMASdeg = geomMeanAnomSunDeg(julianCentury);
        System.out.println(GMASdeg);

        //JAH
        double eccentEarthOrbit = eccentEarthOrbit(julianCentury);
        System.out.println(eccentEarthOrbit);

        //JAH
        double sunEqOfCtr = sunEqOfCtr(GMASdeg, julianCentury);
        System.out.println(sunEqOfCtr);

        //JAH
        double sunTrueLongDeg = sunTrueLongDeg(GMLSdeg,sunEqOfCtr);
        System.out.println(sunTrueLongDeg);

        //double sunTrueAnomDeg = sunTrueAnomDeg(GMASdeg,sunEqOfCtr);
        //System.out.println(sunTrueAnomDeg);

        //double sunRadVectorAUs = sunRadVectorAUs(eccentEarthOrbit, sunTrueAnomDeg);
        //System.out.println(sunRadVectorAUs);

        //JAH
        double sunAppLongDeg = sunAppLongDeg(julianCentury, sunTrueLongDeg);
        System.out.println(sunAppLongDeg);

        //JAH
        double meanObliqEclipticDeg = meanObliqEclipticDeg(julianCentury);
        System.out.println(meanObliqEclipticDeg);

        //JAH
        double obliqCorrDeg = obliqCorrDeg(julianCentury, meanObliqEclipticDeg);
        System.out.println(obliqCorrDeg);

        //PROBLEMAATILINE
        //double sunRtAscenDeg = sunRtAscenDeg(sunAppLongDeg, obliqCorrDeg);
        //System.out.println(sunRtAscenDeg);

        //JAH
        double sunDeclinDeg = sunDeclinDeg(obliqCorrDeg, sunAppLongDeg);
        System.out.println(sunDeclinDeg);

        //JAH
        double VarY = VarY(obliqCorrDeg);
        System.out.println(VarY);

        //JAH
        double EqOfTimeMin = EqOfTimeMin(VarY, GMLSdeg, eccentEarthOrbit, GMASdeg);
        System.out.println("EqOfTimeMin " + EqOfTimeMin);

        //JAH
        double HASunriseDeg = HASunriseDeg(latitude, sunDeclinDeg);
        System.out.println("HASunriseDeg " + HASunriseDeg);

        //double solarNoon = solarNoon(longitude, EqOfTimeMin, timeZone);
        //System.out.println(solarNoon);

        //JAH
        double solarNoon = solarNoon(longitude, EqOfTimeMin, timeZone);

        sunriseTime(solarNoon,HASunriseDeg, timeZone);
        sunsetTime(solarNoon,HASunriseDeg, timeZone);
    }

}
