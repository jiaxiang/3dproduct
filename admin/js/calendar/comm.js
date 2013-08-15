function doUpLoadPhoto()
{
	document.forms[0].action = document.forms[0].action + "?operate=doUpLoadPhoto";
	document.forms[0].submit();
}
function toUpLoadPhoto()
{
	document.forms[0].action = document.forms[0].action + "?operate=toUpLoadPhoto";
	document.forms[0].submit();
}
				
function toUpLoadAttach()
{
	document.forms[0].action = document.forms[0].action + "?operate=toUpLoadAttach";
	document.forms[0].submit();
}
function doUpLoadAttach()
{
	document.forms[0].action = document.forms[0].action + "?operate=doUpLoadAttach";
	document.forms[0].submit();
}
function doUpLoadPhoto2(method)
{
	document.forms[0].action = document.forms[0].action + "?operate=doUpLoadPhoto&method=" + method;
	document.forms[0].submit();
}
function toUpLoadPhoto2(method)
{
	document.forms[0].action = document.forms[0].action + "?operate=toUpLoadPhoto&method=" + method;
	document.forms[0].submit();
}
				
function toUpLoadAttach2(method)
{
	document.forms[0].action = document.forms[0].action + "?operate=toUpLoadAttach&method=" + method;
	document.forms[0].submit();
}
function doUpLoadAttach2(method)
{
	document.forms[0].action = document.forms[0].action + "?operate=doUpLoadAttach&method=" + method;
	document.forms[0].submit();
}
function finish()
{
	document.forms[0].action = document.forms[0].action + "?operate=doAdd";
	document.forms[0].submit();
}
function finish2()
{
	document.forms[0].action = document.forms[0].action + "?operate=doCheck";
	document.forms[0].submit();
}
function finish3()
{
	document.forms[0].action = document.forms[0].action + "?operate=doEdit";
	document.forms[0].submit();
}
function toAdd()
{
		document.forms[0].action = document.forms[0].action + "?operate=toAdd";
		document.forms[0].submit();
}

function doAdd()
{
		document.forms[0].action = document.forms[0].action + "?operate=doAdd";
		document.forms[0].submit();
}
		
function toEdit(id)
{
		document.forms[0].action = document.forms[0].action + "?operate=toEdit&id=" + id;
		document.forms[0].submit();
}

function doEdit(id)
{
		document.forms[0].action = document.forms[0].action + "?operate=doEdit&id=" + id;
		document.forms[0].submit();
}
	
function toDel(id)
{
		document.forms[0].action = document.forms[0].action + "?operate=toDelete&id=" + id;
		document.forms[0].submit();
}
function doDel(id)
{
 		document.forms[0].action = document.forms[0].action + "?operate=doDelete&id="+ id;
 		document.forms[0].submit();
}

function reBack()
{
		document.forms[0].action = document.forms[0].action + "?operate=list";
		document.forms[0].submit();
}