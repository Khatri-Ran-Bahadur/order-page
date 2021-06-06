function checkform(f){
	if (f.action.substr(0, 5) == 'https')
	{
		var url = f.action.substr(5);
		f.action = 'http' + url;
	}
	f.submit();
}