//===== relative URL handling code for js files ================
sWZBaseFolder = "kcr";                          
sWZ = window.location.href;                                     
iWZ = sWZ.indexOf(sWZBaseFolder) + sWZBaseFolder.length + 1;    
sWZBase = sWZ.substring(0,iWZ);                                 
//===== Copyright ?2001 Spidersoft. All rights reserved. ======


function msgposit(){ 
	divphoto.style.posLeft = event.x + 20 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y + 25 + document.body.scrollTop 
} 
function msgposit1(width){ 
	var clWidth;
	var clRight;

	divphoto.style.posLeft = event.x + 20 + document.body.scrollLeft; //100
	clWidth = document.body.clientWidth;
	clRight = divphoto.style.posLeft + width;
	if (clRight > clWidth)
	{
		divphoto.style.posLeft=divphoto.style.posLeft-(clRight - clWidth + 50);
	}
	divphoto.style.posTop = event.y + 10 + document.body.scrollTop ;
	//if(event.x>550) divphoto.style.posLeft=divphoto.style.posLeft-width-120
} 
function msgposit2(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -150 + document.body.scrollTop 
} 
function msgposit3(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -150 + document.body.scrollTop 
} 
function msgposit4(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -150 + document.body.scrollTop 
} 
function msgposit5(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollTop 
	divphoto.style.posTop = event.y -150 + document.body.scrollTop 
} 
function msgposit6(){ 
	divphoto.style.posLeft = event.x + 30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 

function msgposit7(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 
function msgposit8(){ 
	divphoto.style.posLeft = event.x + 30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 
function msgposit9(){ 
	divphoto.style.posLeft = event.x - 400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 
function msgposit10(){ 
	divphoto.style.posLeft = event.x - 400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 
	function msgposit11(){ 
	divphoto.style.posLeft = event.x + 30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -250 + document.body.scrollTop 
} 

function msgposit12(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 250 + document.body.scrollTop 
} 
function msgposit13(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 250 + document.body.scrollTop 
} 
function msgposit14(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 250 + document.body.scrollTop 
} 
function msgposit15(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 250 + document.body.scrollTop 
} 
	function msgposit16(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -300 + document.body.scrollTop 
} 

function msgposit17(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -300 + document.body.scrollTop 
} 
function msgposit18(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -300 + document.body.scrollTop 
} 
function msgposit19(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -300 + document.body.scrollTop 
} 
function msgposit20(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -300 + document.body.scrollTop 
} 
	function msgposit21(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -350 + document.body.scrollTop 
} 

function msgposit22(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -350 + document.body.scrollTop 
} 
function msgposit23(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -350 + document.body.scrollTop 
} 
function msgposit24(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -350 + document.body.scrollTop 
} 
function msgposit25(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -350 + document.body.scrollTop 
} 
	function msgposit26(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -400 + document.body.scrollTop 
} 

function msgposit27(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -400 + document.body.scrollTop 
} 
function msgposit28(){ 
	divphoto.style.posLeft = event.x +30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -400 + document.body.scrollTop 
} 
function msgposit29(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -400 + document.body.scrollTop 
} 
function msgposit30(){ 
	divphoto.style.posLeft = event.x -400 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y -400 + document.body.scrollTop 
} 
function msgposit100(){ 
	divphoto.style.posLeft = event.x + 30 + document.body.scrollLeft //100
	divphoto.style.posTop = event.y - 200 + document.body.scrollTop 
} 

function msgset(str,str2){ 
	var text 
	text ='<table border="0" cellpadding="6" cellspacing="0" bgcolor="#ffffff" style="font-size:9pt; border-width:1; border-color:#999999; border-style:solid;">' 
	text += '<tr><td><p>' + str2 + '</td></tr></table>' 
	divphoto.innerHTML=text 
} 
function msghide(){ 
	divphoto.innerHTML='' 
	//divphoto.style.visibility='hidden' 
} 

function new_window(ref,ref1) {
      window.open(ref,'',ref1);
      return; 
 }


var newWindow;
function na_open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable)
{
  toolbar_str = toolbar ? 'yes' : 'no';
  menubar_str = menubar ? 'yes' : 'no';
  statusbar_str = statusbar ? 'yes' : 'no';
  scrollbar_str = scrollbar ? 'yes' : 'no';
  resizable_str = resizable ? 'yes' : 'no';
  cookie_str = document.cookie;
  cookie_str.toString();
  pos_start  = cookie_str.indexOf(name);
  pos_start  = cookie_str.indexOf('=', pos_start);
  pos_end    = cookie_str.indexOf(';', pos_start);
  if (pos_end <= 0) pos_end = cookie_str.length;
  cookie_val = cookie_str.substring(pos_start + 1, pos_end);
  if (cookie_val  == "done")
    return;
  if(newWindow){newWindow.close();newWindow=null;}
  newWindow=window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}

function na_restore_img_src(name, nsdoc)
{
  var img = eval((navigator.appName.indexOf('Netscape', 0) != -1) ? nsdoc+'.'+name : 'document.all.'+name);
  if (name == '')
    return;
  if (img && img.altsrc) {
    img.src    = img.altsrc;
    img.altsrc = null;
  } 
}

function na_preload_img()
{ 
  var img_list = na_preload_img.arguments;
  if (document.preloadlist == null) 
    document.preloadlist = new Array();
  var top = document.preloadlist.length;
  for (var i=0; i < img_list.length-1; i++) {
    document.preloadlist[top+i] = new Image;
    document.preloadlist[top+i].src = img_list[i+1];
  } 
}

function na_change_img_src(name, nsdoc, rpath, preload)
{ 
  var img = eval((navigator.appName.indexOf('Netscape', 0) != -1) ? nsdoc+'.'+name : 'document.all.'+name);
  if (name == '')
    return;
  if (img) {
    img.altsrc = img.src;
    img.src    = rpath;
  } 
}
