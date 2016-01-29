var uiqs = {};

(function($, container){

    /**
     * Object to get the auto-complete HTML
     */
    var HTMLBuilding = function (type, iata, cityName, airportName, stateCode, stateName, countryCode, countryName) {
        this._type        = type;
        this._type2string = type;
        this._iata        = iata;
        this._cityName    = cityName;
        this._airportName = airportName;
        this._stateCode   = stateCode;
        this._stateName   = stateName;
        this._countryCode = countryCode;
        this._countryName = countryName;

        this.toHTML = function (strInputValue) {
            css = {
                country:       'country',
                allAirport:    'allAirport',
                subAllAirport: 'subAllAirport',
                city:          'city',
                airport:       'airport'
            };

            var stateCode = (this._stateCode != null && this._stateCode != "") ? (this._stateCode + ", ") : "";
            var stateName = (this._stateName != null && this._stateName != "") ? (this._stateName + ", ") : "";
            var country   = "<div class='" + css.country + "' title='" + stateName + this._countryName + "'>" + stateCode + this._countryName + "</div>";
            var classname = "";

            switch(this._type){
                case 1:
                    classname         = css.allAirport;
                    this._type2string = "city_several_airport";
                    break;
                case 2:
                    classname         = css.subAllAirport;
                    this._type2string = "airport_city_several_airport";
                    break;
                case 3:
                    classname         = css.city;
                    this._type2string = "city";
                    break;
                case 4:
                    classname         = css.airport;
                    this._type2string = "airport";
                    break;
                case 5:
                    classname         = css.airport;
                    this._type2string = "city_airport";
                    break;
                case 6:
                    classname         = css.city;
                    this._type2string = "noniata_city";
                    break;
            }

            var result = this.toString();
            var index  = result.toLowerCase().indexOf(strInputValue.toLowerCase());

            if (index != -1)
                result = result.substr(0,index) + result.substr(index,strInputValue.length).bold() + result.substr(index+strInputValue.length,result.length);

            return "<div class='" + classname + "'>" + country + result + "</div>";
        }

        this.toString = function(){
            var iata        = this._iata;
            var cityName    = this._cityName;
            var airportName = (this._cityName != this._airportName) ? ", " + this._airportName : "";

            switch(this._type) {
                case 1:
                    return cityName + " (" + iata + ")";
                case 2:
                    return cityName + airportName + " (" + iata + ")";
                case 3:
                    return cityName + " (" + iata + ")";
                case 4:
                    return cityName + airportName + " (" + iata + ")";
                case 5:
                    return cityName + airportName + " (" + iata + ")";
                case 6:
                    return this._airportName + " (" + cityName + ")";
            }
        }

    /*
    this.isMatch=function(match){
        match=decodeURIComponent(match).toUpperCase();
    
        if(this._airportName!=null&&this._airportName.toUpperCase().indexOf(match)==0)
            return true;
    
        if(this._cityName!=null&&this._type!=6&&this._cityName.toUpperCase().indexOf(match)==0)
            return true;

        if(this._iata!=null&&this._iata.indexOf(match)==0)
            return true;

        return false;
    }
    */
    }


    /**
     * Object that processes the string with all cities. It returns the recommendations
     */
    var ACProcessor = function () {
        this.getSuggestions = function (str, res) {
            if(res == null)
                return[];

            str = decodeURIComponent(str);

            var tRes = new Array();

            if (str.length < 4) {
                var iSrh = '#' + str.toUpperCase();
                searchSuggestion(res, iSrh, tRes);
            }

            var nSrh            = ",";
            var lastIsExtraChar = true;

            for(var i = 0; i < str.length; i++) {
                if (lastIsExtraChar) {
                    lastIsExtraChar  = false;
                    nSrh            += str.charAt(i).toUpperCase();
                } else
                    nSrh += str.charAt(i).toLowerCase();

                if(str.charAt(i) == ' ')
                    lastIsExtraChar = true;
            }

            searchSuggestion(res,nSrh,tRes);
            return tRes;
        }


        function searchSuggestion(res, srh, tRes) {

            var stripVowelAccent = function (str) {
                var rExps=[
                    {re:/[\xC0-\xC6]/g, ch:'A'},
                    {re:/[\xE0-\xE6]/g, ch:'a'},
                    {re:/[\xC8-\xCB]/g, ch:'E'},
                    {re:/[\xE8-\xEB]/g, ch:'e'},
                    {re:/[\xCC-\xCF]/g, ch:'I'},
                    {re:/[\xEC-\xEF]/g, ch:'i'},
                    {re:/[\xD2-\xD6]/g, ch:'O'},
                    {re:/[\xF2-\xF6]/g, ch:'o'},
                    {re:/[\xD9-\xDC]/g, ch:'U'},
                    {re:/[\xF9-\xFC]/g, ch:'u'},
                    {re:/[\xD1]/g, ch:'N'},
                    {re:/[\xF1]/g, ch:'n'}
                ];

                for(var i=0, len=rExps.length; i<len; i++)
                    str=str.replace(rExps[i].re, rExps[i].ch);

                return str;
            }

            var searchRes = stripVowelAccent(res);
            srh = stripVowelAccent(srh);

            var pN = searchRes.indexOf(srh);

            while (pN != -1) {
                var pSCC = res.lastIndexOf('[', pN) + 1;
                var cc   = res.substr(pSCC, 2);
                var ccn  = getCountryName(res, pSCC + 2);
                var pSSC = res.lastIndexOf(']', pN) + 1;
                var sc   = "";
                var scn  = "";

                if(pSSC != -1 && pSSC > pSCC) {
                    sc = res.substr(pSSC, 2);
                    var pESCN = res.indexOf('#', pSSC);
                    scn   = res.substring(pSSC + 2, pESCN);
                }

                var pSIC = res.lastIndexOf('#', pN) + 1;
                var ic   = res.substr(pSIC, 3), pSCN = res.indexOf(",", pSIC) + 1;
                var city = getCityName(res, pSCN);
                var cn   = city.name;

                if(city.stateCode != "") sc = city.stateCode;
                if(city.stateName != "") scn = city.stateName;
                if(city.countryCode != "") cc = city.countryCode;
                if(city.countryName != "") ccn = city.countryName;

                pSIC+=3;

                var type = res.charAt(pSIC);
                switch(type){
                    case'{':
                        addSuggestion(tRes, 1, ic, cn, null, sc, scn, cc, ccn);
                        var noa = res.charAt(pSIC+1);

                        for(var i = 0; i < noa; i++){
                            var pCAC    = res.indexOf('#', pSIC) + 1;
                            var aac     = res.substr(pCAC, 3);
                            pCAC       += 5;
                            var city    = getCityName(res, pCAC);
                            var aan     = city.name;
                            var citySc  = (city.stateCode != "") ? city.stateCode : sc;
                            var cityScn = (city.stateName != "") ? city.stateName : scn;
                            var cityCc  = (city.countryCode != "") ? city.countryCode : cc;
                            var cityCcn = (city.countryName != "") ? city.countryName : ccn;

                            addSuggestion(tRes,2,aac,cn,aan,citySc,cityScn,cityCc,cityCcn);
                            pSIC=res.indexOf('#',pCAC);
                        }
                        break;
                    case'(':
                        break;
                    case')':
                        var pS1 = res.lastIndexOf('{', pSIC);
                        var pS2 = res.lastIndexOf('+', pSIC);
                        var pS3 = res.lastIndexOf('-', pSIC);

                        if(pS3>pS2) pS2 = pS3;
                        if(pS2>pS1) pS1 = pS2;

                        var pSRCN   = res.indexOf(',', pS1) + 1;
                        var city    = getCityName(res, pSRCN);
                        var rcn     = city.name;
                        var citySc  = (city.stateCode != "") ? city.stateCode : sc;
                        var cityScn = (city.stateName != "") ? city.stateName : scn;
                        var cityCc  = (city.countryCode != "") ? city.countryCode : cc;
                        var cityCcn = (city.countryName != "") ? city.countryName : ccn;

                        addSuggestion(tRes, 4, ic, rcn, cn, citySc, cityScn, cityCc, cityCcn);
                        break;
                    case'+':
                    case'-':
                        var an      = cn;
                        var citySc  = sc;
                        var cityScn = scn;
                        if(type == '+') {
                            var pSAN = res.indexOf(',', pSIC + 5) + 1;
                            var city = getCityName(res, pSAN);

                            an      = city.name;
                            citySc  = (city.stateCode!="") ? city.stateCode : sc;
                            cityScn = (city.stateName!="") ? city.stateName : scn;

                            /**
                             * Se agrega el codigo necesario para obtener todos los aeropuertos de la misma
                             * ciudad, asi su IATA sea diferente (Principalmente Medellin)
                             */
                            if(res.charAt(pSIC + 1).match(/\d/)) {
                                var cities = parseInt(res.charAt(pSIC + 1));

                                for(var i = 0; i < cities; i++) {
                                    addSuggestion(tRes, 5, ic, cn, an, citySc, cityScn, cc, ccn);

                                    pN++;
                                    var pSIC = res.indexOf('#', pN) + 1;
                                    var ic   = res.substr(pSIC, 3), pSAN = res.indexOf(",", pSIC) + 1;
                                    var city = getCityName(res, pSAN);
                                    var an   = city.name;

                                    if(city.stateCode != "") sc = city.stateCode;
                                    if(city.stateName != "") scn = city.stateName;
                                }
                            }
                            /**
                             * Fin de mostrar varios aeropuertos
                             */
                        }

                        addSuggestion(tRes, 5, ic, cn, an, citySc, cityScn, cc, ccn);
                        break;
                }

                pN++;
                pN = searchRes.indexOf(srh, pN);
            }
        }

        function getCityName(s, p) {
            var name = "", stateCode = "", stateName = "", countryCode = "", countryName = "";
            var p1 = s.indexOf(',', p);
            var p2 = s.indexOf('#', p);
            var p3 = s.indexOf('[', p);
            var p4 = s.indexOf(']', p);
            var p5 = s.indexOf('%', p);
            var p6 = s.indexOf('~', p);

            if(p1 == -1) p1 = s.length;
            if(p2 == -1) p2 = s.length;
            if(p3 == -1) p3 = s.length;
            if(p4 == -1) p4 = s.length;
            if(p5 == -1) p5 = s.length;
            if(p6 == -1) p6 = s.length;

            if(p2 < p1) p1 = p2;
            if(p3 < p1) p1 = p3;
            if(p4 < p1) p1 = p4;

            if(p5 < p1) {
                name      = s.substring(p, p5);
                stateCode = s.substring(p5 + 1, p5 + 3);
                stateName = s.substring(p5 + 3, p1);
            }else if(p6 < p1) {
                name        = s.substring(p, p6);
                countryCode = s.substring(p6 + 1, p6 + 3);
                countryName = s.substring(p6 + 3, p1);
            }else{
                name = s.substring(p, p1);
            }

            return {
                name        : name,
                stateCode   : stateCode,
                stateName   : stateName,
                countryCode : countryCode,
                countryName : countryName
            };
        }


        function getCountryName(s,p){
            var p1 = s.indexOf('#', p);
            var p2 = s.indexOf(']', p);

            if(p1 == -1) p1 = s.length;
            if(p2 == -1) p2 = s.length;
            if(p2 < p1) p1 = p2;

            return s.substring(p, p1);
        }

        function addSuggestion(tRes, type, iata, cityName, airportName, stateCode, stateName, countryCode, countryName) {
            for(var bcl = 0; bcl < tRes.length; bcl++){
                if(tRes[bcl]['builder']._iata == iata)
                    return;
            }

            var s = new HTMLBuilding(type, iata, cityName, airportName, stateCode, stateName, countryCode, countryName);

            tRes[tRes.length] = {label: s.toString(), builder: s};
        }
    }


    /**
     * Code added by Manuel Mora to get the airlines suggestions
     */
    var ACALProcessor = function(){

        this.getSuggestions = function(str, res){
            if(res == null)
                return[];

            str = decodeURIComponent(str);

            var tRes = new Array();

            if (str.length < 3) {
                var iSrh = str.toUpperCase() + ',';
                searchSuggestion(res, iSrh, tRes);
            }

            var nSrh            = ",";
            var lastIsExtraChar = true;

            for(var i = 0; i < str.length; i++) {
                if (lastIsExtraChar) {
                    lastIsExtraChar  = false;
                    nSrh            += str.charAt(i).toUpperCase();
                } else
                    nSrh += str.charAt(i).toLowerCase();

                if(str.charAt(i) == ' ')
                    lastIsExtraChar = true;
            }

            searchSuggestion(res,nSrh,tRes);
            return tRes;
        }

        function searchSuggestion(res, srh, tRes){

            srh = srh.replace(/[\u00E0\u00E2\u00E4]/gi, "a");
            srh = srh.replace(/[\u00E9\u00E8\u00EA\u00EB]/gi, "e");
            srh = srh.replace(/[\u00ED\u00EE\u00EF]/gi, "i");
            srh = srh.replace(/[\u00F4\u00F6]/gi, "o");
            srh = srh.replace(/[\u00F9\u00FB\u00FC]/gi, "u");

            var pN = res.indexOf(srh);

            while (pN != -1) {
                var _code, _name,
                    pANC = res.indexOf(';', pN) - 1;

                if(srh[0] != ',') {
                    _code = res.substr(pN, 2);
                    _name = res.substr(pN + 3, pANC - pN - 2);
                } else {
                    _code = res.substr(pN - 2, 2);
                    _name = res.substr(pN + 1, pANC - pN);
                }

                addSuggestion(tRes, _code, _name);

                pN++;
                pN = res.indexOf(srh, pN);
            }
        }

        function addSuggestion(tRes, code, name){
            for(var ai = 0; ai < tRes.length; ai++){
                if(tRes[ai].code == code)
                    return;
            }

            tRes[tRes.length] = {code: code, label: name};
        }

    }

    $.extend(container, {
        getSuggestions: (function(){
                            var acp = new ACProcessor();
                            return acp.getSuggestions;
                        })(),
        getSuggestionsAirLine: (function(){
                                    var acp = new ACALProcessor();
                                    return acp.getSuggestions;
                               })()
    });

})(jQuery, uiqs);
