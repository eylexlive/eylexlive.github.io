function deleteConfirm()
{
	var restart = confirm("Bu Kayıtı Silmek İstediğinizden Emin misiniz?");
	if(restart)
		return true;
	else
		return false;
}
function deleteConfirmRelated(related)
{
	var restart = confirm("Kaydın " + related + " ile  bağlantıları varsa onlar da silinecektir. Bu Kaydı Silmek İstediğinizden Emin misiniz?");
	if(restart)
		return true;
	else
		return false;
}
function deleteAllConfirm()
{
	var restart = confirm("Tüm Kayıtları Silmek İstediğinizden Emin misiniz?");
	if(restart)
		return true;
	else
		return false;
}
function valButton(btn)
{
	var cnt = -1;
	for (var i=0; i < btn.length; i++)
	{
		if (btn[i].checked)
		{
			cnt = i;
			i = btn.length;
		}
	}
	if (cnt > -1)
		return btn[cnt].value;
	else
		return null;
}

function validateMultipleSelect(SS)
{
	
	for (var iCount=0; SS.options[iCount]; iCount++)
	{
		if (SS.options[iCount].selected)
		{
			return false;
		}
	}
	return true;
}

function clearMultipleSelect(SS)
{
	sOptions = document.getElementById(SS);
	for (var iCount=0; sOptions.options[iCount]; iCount++)
	{
		sOptions.options[iCount].selected = false;
	}
}
function OpenWindow(windowURL, windowName, windowFeatures)
{
	//alert("asd");
	window.open(windowURL, windowName, windowFeatures);
}
function InvalidPassword(elm)
{
	if (elm.value.length < 6)
		return true;
	else
		return false;
}
function InvalidEmail(elm)
{
	if (elm.value.indexOf("@") <= 0 || elm.value.indexOf(".") <= 0 || elm.value.indexOf("@.") > 0 || elm.value.indexOf(".@") > 0)
		return true;
	else
		return false;
}
function openPopUp(S, T)
{
	window.open(S, T, 'toolbar=0, location=0, directories=0, status=1, menuBar=0, scrollBars=1, resizable=1, width=500, height=400, top=0');
	return false;
}
function SysError(msg, url, linenumber)
{
	var errWin = window.open('', '', 'width=300, height=225');
	errWin.document.write('<style>body{margin: 5; background: #999999;} body, h3, a{font-family: tahoma;}body, a{font-size: 9pt;}</style>');
	errWin.document.write('<body><div style="padding: 5; width: 100%; height: 100%; background: #FFFFFF;">');
	errWin.document.write('<h3>JavaScript hatası meydana geldi.</h3><br>');
	errWin.document.write('<b>Hata mesajı</b> = ' + msg + '<br><b>URL</b> = ' + url + '<br><b>Line Number</b> = ' + linenumber + '<br><br>');
	errWin.document.write('Lütfen sistem yöneticisine mail ile yukarıdaki bilgileri iletiniz.<br><br>');
	errWin.document.write('<a href="javascript:window.close();">Kapat</a>');
	errWin.document.write('</div></body>');
	errWin.document.close();
	return true;
}