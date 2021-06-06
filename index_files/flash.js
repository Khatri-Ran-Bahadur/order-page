flashreplace = {
	'logo2' : {
		swf: '/images/logo_3.swf?url=http://www.bestessays.com',
		width: 527,
		height: 79
	}
}

function replaceFlash() {
	if (flashinstalled == 2) {
		for (a in flashreplace) {
			if (t = document.getElementById(a)) {
				_item = flashreplace[a];
				t.style.padding = '0';
				insertflash(a, _item.swf,_item.width, _item.height);
			}
		}
	}
}