var flashinstalled = 0;
var flashversion = 0;
MSDetect = "false";
if (navigator.plugins && navigator.plugins.length)
{
	x = navigator.plugins["Shockwave Flash"];
	if (x)
	{
		flashinstalled = 2;
		if (x.description)
		{
			y = x.description;
			flashversion = y.charAt(y.indexOf('.')-1);
		}
	}
	else
		flashinstalled = 1;
	if (navigator.plugins["Shockwave Flash 2.0"])
	{
		flashinstalled = 2;
		flashversion = 2;
	}
}
else if (navigator.mimeTypes && navigator.mimeTypes.length)
{
	x = navigator.mimeTypes['application/x-shockwave-flash'];
	if (x && x.enabledPlugin)
		flashinstalled = 2;
	else
		flashinstalled = 1;
}
else
	MSDetect = "true";


function commitFlashObject(_obj, _container){
	_output=""
	_paramoutput=""
	_src=""
	_ver=""
	for(_cO in _obj){
		_output+=_cO+"=\""+_obj[_cO]+"\" "
		_paramoutput+="<param name="+_cO+" value=\""+_obj[_cO]+"\">";
		if(_cO=="movie")_src="src=\""+_obj[_cO]+"\"";
		if(_cO=="version")_ver=_obj[_cO];
	}
	if(_ver=="")_ver="8,0,0,0"
	ihtm="<object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version="+_ver+" "+_output+">\n"
	ihtm+=_paramoutput+"\n"
	ihtm+="<embed "+_src+" pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash "+_output+">\n";
	ihtm+="</embed>\n";
	ihtm+="</object>\n";
	document.getElementById(_container).innerHTML=ihtm;
}
function insertflash(el_id, swf, width, height) {
	if (t = navigator.appVersion.match(/MSIE ([\d.]+);/)) {
		obj=new Object();
		obj.movie=swf;
		obj.quality="high";
		obj.wmode="transparent";
		obj.width=width;
		obj.height=height;
		obj.version="5,0,0,0"
		commitFlashObject(obj, el_id);
	} else {
		document.getElementById(el_id).innerHTML = 
			'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase='+
			'"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#v'+
			'ersion=5,0,0,0" width="'+width+'" height="'+height+'"><param name=movie value="'+swf+
			'"><param name=quality value=high><embed src="'+swf+
			'" quality=high pluginspage="http://www.macromedia.com/shockwave/dow'+
			'nload/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-s'+
			'hockwave-flash" width="'+width+'" height="'+height+'"></embed></object>';
	}
}
