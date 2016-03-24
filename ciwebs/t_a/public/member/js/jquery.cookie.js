/*
 * jQuery Cookie Plugin
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function(A){A.cookie=function(J,F,I){if(arguments.length>1&&(!/Object/.test(Object.prototype.toString.call(F))||F===null||F===undefined)){I=A.extend({},I);if(F===null||F===undefined){I.expires=-1}if(typeof I.expires==="number"){var C=I.expires,E=I.expires=new Date();E.setDate(E.getDate()+C)}F=String(F);return(document.cookie=[encodeURIComponent(J),"=",I.raw?F:encodeURIComponent(F),I.expires?"; expires="+I.expires.toUTCString():"",I.path?"; path="+I.path:"",I.domain?"; domain="+I.domain:"",I.secure?"; secure":""].join(""))}I=F||{};var D=I.raw?function(K){return K}:decodeURIComponent;var H=document.cookie.split("; ");for(var G=0,B;B=H[G]&&H[G].split("=");G++){if(D(B[0])===J){return D(B[1]||"")}}return null}})(jQuery);