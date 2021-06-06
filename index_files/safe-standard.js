var psX7n3sid = "iDkTFvMZ8RVH";
// safe-standard@gecko.js

var psX7n3iso;
try {
	psX7n3iso = (opener != null) && (typeof(opener.name) != "unknown") && (opener.psX7n3wid != null);
} catch(e) {
	psX7n3iso = false;
}
if (psX7n3iso) {
	window.psX7n3wid = opener.psX7n3wid + 1;
	psX7n3sid = psX7n3sid + "_" + window.psX7n3wid;
} else {
	window.psX7n3wid = 1;
}
function psX7n3n() {
	return (new Date()).getTime();
}
var psX7n3s = psX7n3n();
function psX7n3st(f, t) {
	if ((psX7n3n() - psX7n3s) < 7200000) {
		return setTimeout(f, t * 1000);
	} else {
		return null;
	}
}
var psX7n3ol = true;
function psX7n3ow() {
	if (psX7n3ol || (1 == 1)) {
		var url = "http://messenger.providesupport.com/messenger/2pssup.html?ps_s=" + psX7n3sid + "";
		window.open(url, "_blank", "menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=500,height=460"); 
	} else if (1 == 2) {
		document.location = "http\u003a\u002f\u002f";
	}
}
var psX7n3il;
var psX7n3it;
function psX7n3pi() {
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
	if ((il != psX7n3il) || (it != psX7n3it)) {
		psX7n3il = il;
		psX7n3it = it;
		var d = document.getElementById('ciX7n3');
		if (d != null) {
			d.style.left  = Math.round(psX7n3il) + "px";
			d.style.top  = Math.round(psX7n3it) + "px";
		}
	}
	setTimeout("psX7n3pi()", 100);
}
var psX7n3lc = 0;
function psX7n3si() {
	window.onscroll = psX7n3pi;
	window.onresize = psX7n3pi;
	psX7n3pi();
	psX7n3lc = 0;
	var url = "http://messenger.providesupport.com/invitation/2pssup.html?ps_s=" + psX7n3sid + "";
	var d = document.getElementById('ciX7n3');
	if (d != null) {
		d.innerHTML = '<iframe allowtransparency="true" style="background:transparent;width=271;height=191" src="' + url + '" onload="psX7n3ld()" frameborder="no" width="271" height="191" scrolling="no"></iframe>';
	}
}
function psX7n3ld() {
	if (psX7n3lc == 1) {
		var d = document.getElementById('ciX7n3');
		if (d != null) {
			d.innerHTML = "";
		}
	}
	psX7n3lc++;
}
if (false) {
	psX7n3si();
}
var psX7n3d = document.getElementById('scX7n3');
if (psX7n3d != null) {
	if (psX7n3ol || (1 == 1) || (1 == 2)) {
		if (false) {
			psX7n3d.innerHTML = '<table style="display:inline" cellspacing="0" cellpadding="0" border="0"><tr><td align="center"><a href="#" onclick="psX7n3ow(); return false;"><img name="psX7n3image" src="http://img1.bestessays.com/images/phone_pict.png" width="236" height="113" border="0"></a></td></tr><tr><td align="center"><a href="http://www.providesupport.com/pb/2pssup" target="_blank"><img src="http://image.providesupport.com/lcbps.gif" width="140" height="17" border="0"></a></td></tr></table>';
		} else {
			psX7n3d.innerHTML = '<a href="#" onclick="psX7n3ow(); return false;"><img name="psX7n3image" src="http://img1.bestessays.com/images/phone_pict.png" width="236" height="113" border="0"></a>';
		}
	} else {
		psX7n3d.innerHTML = '';
	}
}
var psX7n3op = false;
function psX7n3co() {
	var w1 = psX7n3ci.width - 1;
	psX7n3ol = (w1 & 1) != 0;
	psX7n3sb(psX7n3ol ? "http://img1.bestessays.com/images/phone_pict.png" : "http://img1.bestessays.com/images/phone_pict.png");
	psX7n3scf((w1 & 2) != 0);
	var h = psX7n3ci.height;
	if (h != 2) {
		psX7n3op = false;
	} else if ((h == 2) && (!psX7n3op)) {
		psX7n3op = true;
		psX7n3si();
	}
}
var psX7n3ci = new Image();
psX7n3ci.onload = psX7n3co;
var psX7n3pm = true;
var psX7n3cp = psX7n3pm ? 30 : 60;
var psX7n3ct = null;
function psX7n3scf(p) {
	if (psX7n3pm != p) {
		psX7n3pm = p;
		psX7n3cp = psX7n3pm ? 30 : 60;
		if (psX7n3ct != null) {
			clearTimeout(psX7n3ct);
			psX7n3ct = null;
		}
		psX7n3ct = psX7n3st("psX7n3rc()", psX7n3cp);
	}
}
function psX7n3rc() {
	psX7n3ct = psX7n3st("psX7n3rc()", psX7n3cp);
	try {
		psX7n3ci.src = "http://image.providesupport.com/cmd/2pssup?" + "ps_t=" + psX7n3n() + "&ps_l=" + escape(document.location) + "&ps_r=" + escape(document.referrer) + "&ps_s=" + psX7n3sid + "" + "&site=bestessays.com";
	} catch(e) {
	}
}
psX7n3rc();
var psX7n3cb = "http://img1.bestessays.com/images/phone_pict.png";
function psX7n3sb(b) {
	if (psX7n3cb != b) {
		var i = document.images['psX7n3image'];
		if (i != null) {
			i.src = b;
		}
		psX7n3cb = b;
	}
}

