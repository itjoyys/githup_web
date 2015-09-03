var psUls8sid = "h6ZLEXkeHTn6";
// safe-standard@gecko.js

var psUls8iso;
try {
	psUls8iso = (opener != null) && (typeof(opener.name) != "unknown") && (opener.psUls8wid != null);
} catch(e) {
	psUls8iso = false;
}
if (psUls8iso) {
	window.psUls8wid = opener.psUls8wid + 1;
	psUls8sid = psUls8sid + "_" + window.psUls8wid;
} else {
	window.psUls8wid = 1;
}
function psUls8n() {
	return (new Date()).getTime();
}
var psUls8s = psUls8n();
function psUls8st(f, t) {
	if ((psUls8n() - psUls8s) < 7200000) {
		return setTimeout(f, t * 1000);
	} else {
		return null;
	}
}
var psUls8ol = true;
function psUls8ow() {
	if (psUls8ol || (1 == 1)) {
		var pswo = "menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=650,height=680";
		var pswn = "pscw_" + psUls8n();
		var url = "http://messenger.providesupport.com/messenger/1g3qum7f1upr3000lgogtq4isy.html?ps_l=" + escape(document.location) + "";
		window.open(url, pswn, pswo);
	} else if (1 == 2) {
		document.location = "http://";
	}
}
var psUls8il;
var psUls8it;
function psUls8pi() {
	var il;
	if (3 == 2) {
		il = window.pageXOffset + 50;
	} else if (3 == 3) {
		il = (window.innerWidth * 50 / 100) + window.pageXOffset;
	} else {
		il = 50;
	}
	il -= (271 / 2);
	var it;
	if (3 == 2) {
		it = window.pageYOffset + 50;
	} else if (3 == 3) {
		it = (window.innerHeight * 50 / 100) + window.pageYOffset;
	} else {
		it = 50;
	}
	it -= (191 / 2);
	if ((il != psUls8il) || (it != psUls8it)) {
		psUls8il = il;
		psUls8it = it;
		var d = document.getElementById('ciUls8');
		if (d != null) {
			d.style.left  = Math.round(psUls8il) + "px";
			d.style.top  = Math.round(psUls8it) + "px";
		}
	}
	setTimeout("psUls8pi()", 100);
}
var psUls8lc = 0;
function psUls8si(t) {
	window.onscroll = psUls8pi;
	window.onresize = psUls8pi;
	psUls8pi();
	psUls8lc = 0;
	var url = "http://messenger.providesupport.com/" + ((t == 2) ? "auto" : "chat") + "-invitation/1g3qum7f1upr3000lgogtq4isy.html?ps_t=" + psUls8n() + "";
	var d = document.getElementById('ciUls8');
	if (d != null) {
		d.innerHTML = '<iframe allowtransparency="true" style="background:transparent;width:271;height:191" src="' + url + 
			'" onload="psUls8ld()" frameborder="no" width="271" height="191" scrolling="no"></iframe>';
	}
}
function psUls8ld() {
	if (psUls8lc == 1) {
		var d = document.getElementById('ciUls8');
		if (d != null) {
			d.innerHTML = "";
		}
	}
	psUls8lc++;
}
if (false) {
	psUls8si(1);
}
var psUls8d = document.getElementById('scUls8');
if (psUls8d != null) {
	if (psUls8ol || (1 == 1) || (1 == 2)) {
		var ctt = "";
		if (ctt != "") {
			tt = 'alt="' + ctt + '" title="' + ctt + '"';
		} else {
			tt = '';
		}
		if (false) {
			var p1 = '<table style="display:inline;border:0px;border-collapse:collapse;border-spacing:0;"><tr><td style="padding:0px;text-align:center;border:0px;vertical-align:middle"><a href="#" onclick="psUls8ow(); return false;"><img name="psUls8image" src="http://image.providesupport.com/image/1g3qum7f1upr3000lgogtq4isy/online-940251935.gif" width="110" height="150" style="border:0;display:block;margin:auto"';
			var p2 = '<td style="padding:0px;text-align:center;border:0px;vertical-align:middle"><a href="http://www.providesupport.com/pb/1g3qum7f1upr3000lgogtq4isy" target="_blank"><img src="http://image.providesupport.com/';
			var p3 = 'style="border:0;display:block;margin:auto"></a></td></tr></table>';
			if ((110 >= 140) || (110 >= 150)) {
				psUls8d.innerHTML = p1+tt+'></a></td></tr><tr>'+p2+'lcbpsh.gif" width="140" height="17"'+p3;
			} else {
				psUls8d.innerHTML = p1+tt+'></a></td>'+p2+'lcbpsv.gif" width="17" height="140"'+p3;
			}
		} else {
			psUls8d.innerHTML = '<a href="#" onclick="psUls8ow(); return false;"><img name="psUls8image" src="http://image.providesupport.com/image/1g3qum7f1upr3000lgogtq4isy/online-940251935.gif" width="110" height="150" border="0"'+tt+'></a>';
		}
	} else {
		psUls8d.innerHTML = '';
	}
}
var psUls8op = false;
function psUls8co() {
	var w1 = psUls8ci.width - 1;
	psUls8ol = (w1 & 1) != 0;
	psUls8sb(psUls8ol ? "http://image.providesupport.com/image/1g3qum7f1upr3000lgogtq4isy/online-940251935.gif" : "http://image.providesupport.com/image/1g3qum7f1upr3000lgogtq4isy/offline-223720017.gif");
	psUls8scf((w1 & 2) != 0);
	var h = psUls8ci.height;

	if (h == 1) {
		psUls8op = false;

	// manual invitation
	} else if ((h == 2) && (!psUls8op)) {
		psUls8op = true;
		psUls8si(1);
		//alert("Chat invitation in standard code");
		
	// auto-invitation
	} else if ((h == 3) && (!psUls8op)) {
		psUls8op = true;
		psUls8si(2);
		//alert("Auto invitation in standard code");
	}
}
var psUls8ci = new Image();
psUls8ci.onload = psUls8co;
var psUls8pm = false;
var psUls8cp = psUls8pm ? 30 : 60;
var psUls8ct = null;
function psUls8scf(p) {
	if (psUls8pm != p) {
		psUls8pm = p;
		psUls8cp = psUls8pm ? 30 : 60;
		if (psUls8ct != null) {
			clearTimeout(psUls8ct);
			psUls8ct = null;
		}
		psUls8ct = psUls8st("psUls8rc()", psUls8cp);
	}
}
function psUls8rc() {
	psUls8ct = psUls8st("psUls8rc()", psUls8cp);
	try {
		psUls8ci.src = "http://image.providesupport.com/cmd/1g3qum7f1upr3000lgogtq4isy?" + "ps_t=" + psUls8n() + "&ps_l=" + escape(document.location) + "&ps_r=" + escape(document.referrer) + "&ps_s=" + psUls8sid + "" + "";
	} catch(e) {
	}
}
psUls8rc();
var psUls8cb = "http://image.providesupport.com/image/1g3qum7f1upr3000lgogtq4isy/online-940251935.gif";
function psUls8sb(b) {
	if (psUls8cb != b) {
		var i = document.images['psUls8image'];
		if (i != null) {
			i.src = b;
		}
		psUls8cb = b;
	}
}

