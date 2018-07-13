/*	Unobtrusive Flash Objects (UFO) v3.20 <http://www.bobbyvandersluis.com/ufo/>
	Copyright 2005, 2006 Bobby van der Sluis
	This software is licensed under the CC-GNU LGPL <http://creativecommons.org/licenses/LGPL/2.1/>
*/

var UFO = {
    req: ["movie", "width", "height", "majorversion", "build"],
    opt: ["play", "loop", "menu", "quality", "scale", "salign", "wmode", "bgcolor", "base", "flashvars", "devicefont", "allowscriptaccess", "seamlesstabbing"],
    optAtt: ["id", "name", "align"],
    optExc: ["swliveconnect"],
    ximovie: "ufo.swf",
    xiwidth: "215",
    xiheight: "138",
    ua: navigator.userAgent.toLowerCase(),
    pluginType: "",
    fv: [0, 0],
    foList: [],

    create: function (FO, id) {
        if (!UFO.uaHas("w3cdom") || UFO.uaHas("ieMac")) return;
        UFO.getFlashVersion();
        UFO.foList[id] = UFO.updateFO(FO);
        UFO.createCSS("#" + id, "visibility:hidden;");
        UFO.domLoad(id);
    },

    updateFO: function (FO) {
        if (typeof FO.xi != "undefined" && FO.xi == "true") {
            if (typeof FO.ximovie == "undefined") FO.ximovie = UFO.ximovie;
            if (typeof FO.xiwidth == "undefined") FO.xiwidth = UFO.xiwidth;
            if (typeof FO.xiheight == "undefined") FO.xiheight = UFO.xiheight;
        }
        FO.mainCalled = false;
        return FO;
    },

    domLoad: function (id) {
        var _t = setInterval(function () {
            if ((document.getElementsByTagName("body")[0] != null || document.body != null) && document.getElementById(id) != null) {
                UFO.main(id);
                clearInterval(_t);
            }
        }, 250);
        if (typeof document.addEventListener != "undefined") {
            document.addEventListener("DOMContentLoaded", function () { UFO.main(id); clearInterval(_t); }, null); // Gecko, Opera 9+
        }
    },

    main: function (id) {
        var _fo = UFO.foList[id];
        if (_fo.mainCalled) return;
        UFO.foList[id].mainCalled = true;
        document.getElementById(id).style.visibility = "hidden";
        if (UFO.hasRequired(id)) {
            if (UFO.hasFlashVersion(parseInt(_fo.majorversion, 10), parseInt(_fo.build, 10))) {
                if (typeof _fo.setcontainercss != "undefined" && _fo.setcontainercss == "true") UFO.setContainerCSS(id);
                UFO.writeSWF(id);
            }
            else if (_fo.xi == "true" && UFO.hasFlashVersion(6, 65)) {
                UFO.createDialog(id);
            }
        }
        document.getElementById(id).style.visibility = "visible";
    },

    createCSS: function (selector, declaration) {
        var _h = document.getElementsByTagName("head")[0];
        var _s = UFO.createElement("style");
        if (!UFO.uaHas("ieWin")) _s.appendChild(document.createTextNode(selector + " {" + declaration + "}")); // bugs in IE/Win
        _s.setAttribute("type", "text/css");
        _s.setAttribute("media", "screen");
        _h.appendChild(_s);
        if (UFO.uaHas("ieWin") && document.styleSheets && document.styleSheets.length > 0) {
            var _ls = document.styleSheets[document.styleSheets.length - 1];
            if (typeof _ls.addRule == "object") _ls.addRule(selector, declaration);
        }
    },

    setContainerCSS: function (id) {
        var _fo = UFO.foList[id];
        var _w = /%/.test(_fo.width) ? "" : "px";
        var _h = /%/.test(_fo.height) ? "" : "px";
        UFO.createCSS("#" + id, "width:" + _fo.width + _w + "; height:" + _fo.height + _h + ";");
        if (_fo.width == "100%") {
            UFO.createCSS("body", "margin-left:0; margin-right:0; padding-left:0; padding-right:0;");
        }
        if (_fo.height == "100%") {
            UFO.createCSS("html", "height:100%; overflow:hidden;");
            UFO.createCSS("body", "margin-top:0; margin-bottom:0; padding-top:0; padding-bottom:0; height:100%;");
        }
    },

    createElement: function (el) {
        return (UFO.uaHas("xml") && typeof document.createElementNS != "undefined") ? document.createElementNS("http://www.w3.org/1999/xhtml", el) : document.createElement(el);
    },

    createObjParam: function (el, aName, aValue) {
        var _p = UFO.createElement("param");
        _p.setAttribute("name", aName);
        _p.setAttribute("value", aValue);
        el.appendChild(_p);
    },

    uaHas: function (ft) {
        var _u = UFO.ua;
        switch (ft) {
            case "w3cdom":
                return (typeof document.getElementById != "undefined" && typeof document.getElementsByTagName != "undefined" && (typeof document.createElement != "undefined" || typeof document.createElementNS != "undefined"));
            case "xml":
                var _m = document.getElementsByTagName("meta");
                var _l = _m.length;
                for (var i = 0; i < _l; i++) {
                    if (/content-type/i.test(_m[i].getAttribute("http-equiv")) && /xml/i.test(_m[i].getAttribute("content"))) return true;
                }
                return false;
            case "ieMac":
                return /msie/.test(_u) && !/opera/.test(_u) && /mac/.test(_u);
            case "ieWin":
                return /msie/.test(_u) && !/opera/.test(_u) && /win/.test(_u);
            case "gecko":
                return /gecko/.test(_u) && !/applewebkit/.test(_u);
            case "opera":
                return /opera/.test(_u);
            case "safari":
                return /applewebkit/.test(_u);
            default:
                return false;
        }
    },

    getFlashVersion: function () {
        if (UFO.fv[0] != 0) return;
        if (navigator.plugins && typeof navigator.plugins["Shockwave Flash"] == "object") {
            UFO.pluginType = "npapi";
            var _d = navigator.plugins["Shockwave Flash"].description;
            if (typeof _d != "undefined") {
                _d = _d.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                var _m = parseInt(_d.replace(/^(.*)\..*$/, "$1"), 10);
                var _r = /r/.test(_d) ? parseInt(_d.replace(/^.*r(.*)$/, "$1"), 10) : 0;
                UFO.fv = [_m, _r];
            }
        }
        else if (window.ActiveXObject) {
            UFO.pluginType = "ax";
            try { // avoid fp 6 crashes
                var _a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
            }
            catch (e) {
                try {
                    var _a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
                    UFO.fv = [6, 0];
                    _a.AllowScriptAccess = "always"; // throws if fp < 6.47 
                }
                catch (e) {
                    if (UFO.fv[0] == 6) return;
                }
                try {
                    var _a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
                }
                catch (e) { }
            }
            if (typeof _a == "object") {
                var _d = _a.GetVariable("$version"); // bugs in fp 6.21/6.23
                if (typeof _d != "undefined") {
                    _d = _d.replace(/^\S+\s+(.*)$/, "$1").split(",");
                    UFO.fv = [parseInt(_d[0], 10), parseInt(_d[2], 10)];
                }
            }
        }
    },

    hasRequired: function (id) {
        var _l = UFO.req.length;
        for (var i = 0; i < _l; i++) {
            if (typeof UFO.foList[id][UFO.req[i]] == "undefined") return false;
        }
        return true;
    },

    hasFlashVersion: function (major, release) {
        return (UFO.fv[0] > major || (UFO.fv[0] == major && UFO.fv[1] >= release)) ? true : false;
    },

    writeSWF: function (id) {
        var _fo = UFO.foList[id];
        var _e = document.getElementById(id);
        if (UFO.pluginType == "npapi") {
            if (UFO.uaHas("gecko") || UFO.uaHas("xml")) {
                while (_e.hasChildNodes()) {
                    _e.removeChild(_e.firstChild);
                }
                var _obj = UFO.createElement("object");
                _obj.setAttribute("type", "application/x-shockwave-flash");
                _obj.setAttribute("data", _fo.movie);
                _obj.setAttribute("width", _fo.width);
                _obj.setAttribute("height", _fo.height);
                var _l = UFO.optAtt.length;
                for (var i = 0; i < _l; i++) {
                    if (typeof _fo[UFO.optAtt[i]] != "undefined") _obj.setAttribute(UFO.optAtt[i], _fo[UFO.optAtt[i]]);
                }
                var _o = UFO.opt.concat(UFO.optExc);
                var _l = _o.length;
                for (var i = 0; i < _l; i++) {
                    if (typeof _fo[_o[i]] != "undefined") UFO.createObjParam(_obj, _o[i], _fo[_o[i]]);
                }
                _e.appendChild(_obj);
            }
            else {
                var _emb = "";
                var _o = UFO.opt.concat(UFO.optAtt).concat(UFO.optExc);
                var _l = _o.length;
                for (var i = 0; i < _l; i++) {
                    if (typeof _fo[_o[i]] != "undefined") _emb += ' ' + _o[i] + '="' + _fo[_o[i]] + '"';
                }
                _e.innerHTML = '<embed type="application/x-shockwave-flash" src="' + _fo.movie + '" width="' + _fo.width + '" height="' + _fo.height + '" pluginspage="http://www.macromedia.com/go/getflashplayer"' + _emb + '></embed>';
            }
        }
        else if (UFO.pluginType == "ax") {
            var _objAtt = "";
            var _l = UFO.optAtt.length;
            for (var i = 0; i < _l; i++) {
                if (typeof _fo[UFO.optAtt[i]] != "undefined") _objAtt += ' ' + UFO.optAtt[i] + '="' + _fo[UFO.optAtt[i]] + '"';
            }
            var _objPar = "";
            var _l = UFO.opt.length;
            for (var i = 0; i < _l; i++) {
                if (typeof _fo[UFO.opt[i]] != "undefined") _objPar += '<param name="' + UFO.opt[i] + '" value="' + _fo[UFO.opt[i]] + '" />';
            }
            var _p = window.location.protocol == "https:" ? "https:" : "http:";
            _e.innerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + _objAtt + ' width="' + _fo.width + '" height="' + _fo.height + '" codebase="' + _p + '//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=' + _fo.majorversion + ',0,' + _fo.build + ',0"><param name="movie" value="' + _fo.movie + '" />' + _objPar + '</object>';
        }
    },

    createDialog: function (id) {
        var _fo = UFO.foList[id];
        UFO.createCSS("html", "height:100%; overflow:hidden;");
        UFO.createCSS("body", "height:100%; overflow:hidden;");
        UFO.createCSS("#xi-con", "position:absolute; left:0; top:0; z-index:1000; width:100%; height:100%; background-color:#fff; filter:alpha(opacity:75); opacity:0.75;");
        UFO.createCSS("#xi-dia", "position:absolute; left:50%; top:50%; margin-left: -" + Math.round(parseInt(_fo.xiwidth, 10) / 2) + "px; margin-top: -" + Math.round(parseInt(_fo.xiheight, 10) / 2) + "px; width:" + _fo.xiwidth + "px; height:" + _fo.xiheight + "px;");
        var _b = document.getElementsByTagName("body")[0];
        var _c = UFO.createElement("div");
        _c.setAttribute("id", "xi-con");
        var _d = UFO.createElement("div");
        _d.setAttribute("id", "xi-dia");
        _c.appendChild(_d);
        _b.appendChild(_c);
        var _mmu = window.location;
        if (UFO.uaHas("xml") && UFO.uaHas("safari")) {
            var _mmd = document.getElementsByTagName("title")[0].firstChild.nodeValue = document.getElementsByTagName("title")[0].firstChild.nodeValue.slice(0, 47) + " - Flash Player Installation";
        }
        else {
            var _mmd = document.title = document.title.slice(0, 47) + " - Flash Player Installation";
        }
        var _mmp = UFO.pluginType == "ax" ? "ActiveX" : "PlugIn";
        var _uc = typeof _fo.xiurlcancel != "undefined" ? "&xiUrlCancel=" + _fo.xiurlcancel : "";
        var _uf = typeof _fo.xiurlfailed != "undefined" ? "&xiUrlFailed=" + _fo.xiurlfailed : "";
        UFO.foList["xi-dia"] = { movie: _fo.ximovie, width: _fo.xiwidth, height: _fo.xiheight, majorversion: "6", build: "65", flashvars: "MMredirectURL=" + _mmu + "&MMplayerType=" + _mmp + "&MMdoctitle=" + _mmd + _uc + _uf };
        UFO.writeSWF("xi-dia");
    },

    expressInstallCallback: function () {
        var _b = document.getElementsByTagName("body")[0];
        var _c = document.getElementById("xi-con");
        _b.removeChild(_c);
        UFO.createCSS("body", "height:auto; overflow:auto;");
        UFO.createCSS("html", "height:auto; overflow:auto;");
    },

    cleanupIELeaks: function () {
        var _o = document.getElementsByTagName("object");
        var _l = _o.length
        for (var i = 0; i < _l; i++) {
            _o[i].style.display = "none";
            for (var x in _o[i]) {
                if (typeof _o[i][x] == "function") {
                    _o[i][x] = null;
                }
            }
        }
    }

};

if (typeof window.attachEvent != "undefined" && UFO.uaHas("ieWin")) {
    window.attachEvent("onunload", UFO.cleanupIELeaks);
}

function parseXml(xml) {
   var dom = null;
   if (window.DOMParser) {
      try { 
         dom = (new DOMParser()).parseFromString(xml, "text/xml"); 
      } 
      catch (e) { dom = null; }
   }
   else if (window.ActiveXObject) {
      try {
         dom = new ActiveXObject('Microsoft.XMLDOM');
         dom.async = false;
         if (!dom.loadXML(xml)) // parse error ..

            window.alert(dom.parseError.reason + dom.parseError.srcText);
      } 
      catch (e) { dom = null; }
   }
   else
      alert("cannot parse xml string!");
   return dom;
}

function xml2json(xml, tab) {
   var X = {
      toObj: function(xml) {
         var o = {};
         if (xml.nodeType==1) {   // element node ..
            if (xml.attributes.length)   // element with attributes  ..
               for (var i=0; i<xml.attributes.length; i++)
                  o["@"+xml.attributes[i].nodeName] = (xml.attributes[i].nodeValue||"").toString();
            if (xml.firstChild) { // element has child nodes ..
               var textChild=0, cdataChild=0, hasElementChild=false;
               for (var n=xml.firstChild; n; n=n.nextSibling) {
                  if (n.nodeType==1) hasElementChild = true;
                  else if (n.nodeType==3 && n.nodeValue.match(/[^ \f\n\r\t\v]/)) textChild++; // non-whitespace text
                  else if (n.nodeType==4) cdataChild++; // cdata section node
               }
               if (hasElementChild) {
                  if (textChild < 2 && cdataChild < 2) { // structured element with evtl. a single text or/and cdata node ..
                     X.removeWhite(xml);
                     for (var n=xml.firstChild; n; n=n.nextSibling) {
                        if (n.nodeType == 3)  // text node
                           o["#text"] = X.escape(n.nodeValue);
                        else if (n.nodeType == 4)  // cdata node
                           o["#cdata"] = X.escape(n.nodeValue);
                        else if (o[n.nodeName]) {  // multiple occurence of element ..
                           if (o[n.nodeName] instanceof Array)
                              o[n.nodeName][o[n.nodeName].length] = X.toObj(n);
                           else
                              o[n.nodeName] = [o[n.nodeName], X.toObj(n)];
                        }
                        else  // first occurence of element..
                           o[n.nodeName] = X.toObj(n);
                     }
                  }
                  else { // mixed content
                     if (!xml.attributes.length)
                        o = X.escape(X.innerXml(xml));
                     else
                        o["#text"] = X.escape(X.innerXml(xml));
                  }
               }
               else if (textChild) { // pure text
                  if (!xml.attributes.length)
                     o = X.escape(X.innerXml(xml));
                  else
                     o["#text"] = X.escape(X.innerXml(xml));
               }
               else if (cdataChild) { // cdata
                  if (cdataChild > 1)
                     o = X.escape(X.innerXml(xml));
                  else
                     for (var n=xml.firstChild; n; n=n.nextSibling)
                        o["#cdata"] = X.escape(n.nodeValue);
               }
            }
            if (!xml.attributes.length && !xml.firstChild) o = null;
         }
         else if (xml.nodeType==9) { // document.node
            o = X.toObj(xml.documentElement);
         }
         else
            alert("unhandled node type: " + xml.nodeType);
         return o;
      },
      toJson: function(o, name, ind) {
         var json = name ? ("\""+name+"\"") : "";
         if (o instanceof Array) {
            for (var i=0,n=o.length; i<n; i++)
               o[i] = X.toJson(o[i], "", ind+"\t");
            json += (name?":[":"[") + (o.length > 1 ? ("\n"+ind+"\t"+o.join(",\n"+ind+"\t")+"\n"+ind) : o.join("")) + "]";
         }
         else if (o == null)
            json += (name&&":") + "null";
         else if (typeof(o) == "object") {
            var arr = [];
            for (var m in o)
               arr[arr.length] = X.toJson(o[m], m, ind+"\t");
            json += (name?":{":"{") + (arr.length > 1 ? ("\n"+ind+"\t"+arr.join(",\n"+ind+"\t")+"\n"+ind) : arr.join("")) + "}";
         }
         else if (typeof(o) == "string")
            json += (name&&":") + "\"" + o.toString() + "\"";
         else
            json += (name&&":") + o.toString();
         return json;
      },
      innerXml: function(node) {
         var s = ""
         if ("innerHTML" in node)
            s = node.innerHTML;
         else {
            var asXml = function(n) {
               var s = "";
               if (n.nodeType == 1) {
                  s += "<" + n.nodeName;
                  for (var i=0; i<n.attributes.length;i++)
                     s += " " + n.attributes[i].nodeName + "=\"" + (n.attributes[i].nodeValue||"").toString() + "\"";
                  if (n.firstChild) {
                     s += ">";
                     for (var c=n.firstChild; c; c=c.nextSibling)
                        s += asXml(c);
                     s += "</"+n.nodeName+">";
                  }
                  else
                     s += "/>";
               }
               else if (n.nodeType == 3)
                  s += n.nodeValue;
               else if (n.nodeType == 4)
                  s += "<![CDATA[" + n.nodeValue + "]]>";
               return s;
            };
            for (var c=node.firstChild; c; c=c.nextSibling)
               s += asXml(c);
         }
         return s;
      },
      escape: function(txt) {
         return txt.replace(/[\\]/g, "\\\\")
                   .replace(/[\"]/g, '\\"')
                   .replace(/[\n]/g, '\\n')
                   .replace(/[\r]/g, '\\r');
      },
      removeWhite: function(e) {
         e.normalize();
         for (var n = e.firstChild; n; ) {
            if (n.nodeType == 3) {  // text node
               if (!n.nodeValue.match(/[^ \f\n\r\t\v]/)) { // pure whitespace text node
                  var nxt = n.nextSibling;
                  e.removeChild(n);
                  n = nxt;
               }
               else
                  n = n.nextSibling;
            }
            else if (n.nodeType == 1) {  // element node
               X.removeWhite(n);
               n = n.nextSibling;
            }
            else                      // any other node
               n = n.nextSibling;
         }
         return e;
      }
   };
   if (xml.nodeType == 9) // document node
      xml = xml.documentElement;
   var json = X.toJson(X.toObj(X.removeWhite(xml)), xml.nodeName, "\t");
   return "{\n" + tab + (tab ? json.replace(/\t/g, tab) : json.replace(/\t|\n/g, "")) + "\n}";
}