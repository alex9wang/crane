//	管理
function ToggleTools(){ }

function createCookie(name,value,days,Tdom){
	var Tdom=(Tdom)?Tdom:"/";
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires="; expires="+date.toGMTString();}else{
		var expires="";
	}
	document.cookie=name+"="+value+expires+"; path="+Tdom;
}

function readCookie(name){
	var nameEQ=name+"=";
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while(c.charAt(0)==' '){c=c.substring(1,c.length);}
		if(c.indexOf(nameEQ)==0){return c.substring(nameEQ.length,c.length);}
	}
	return null;
}

function randomString(min, max) {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    if(!min) min = 7;
    if(!max) max = 10;     
    var string_length = Math.floor(Math.random() * (max - min + 1) + min);
    var randomstring = '';
    for (var i=0; i < string_length ; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum,rnum+1);
    }
    return randomstring;
}

function DisplayMessage(messageContent, messageStatus, addCommand){
	$(".jGrowl").html('<div class="jGrowl-notification"></div><div class="jGrowl-notification ' + messageStatus + ' ui-state-highlight ui-corner-all" style="display: block; "><div class="jGrowl-close">×</div><div class="jGrowl-header"></div><div class="jGrowl-message">' + messageContent + '</div></div>').show();
	if(addCommand != ""){
		$(".jGrowl-close").unbind("click").click(function(){ $(".jGrowl").hide(); eval(addCommand);});
		setTimeout('$(".jGrowl").hide();'+addCommand, 2000);
	} else {
		$(".jGrowl-close").unbind("click").click(function(){ $(".jGrowl").hide();});
		setTimeout('$(".jGrowl").hide();', 2000);
	}
}

function validateEmail(emailAddress)
{
	if(emailAddress.indexOf("@") == -1) return false;
	atPos = emailAddress.indexOf("@");
	if(atPos < 2) return false;
	afterEmail = emailAddress.substring(atPos);
	if(afterEmail.indexOf(".") == -1) return false;
	return true;
}

$(function(){
	$(".largeMenu .menuSel").unbind("mouseover").mouseover(function(){
		if($(this).hasClass("curr")) return false;
		$(this).addClass("mover");
	}).unbind("mouseout").mouseout(function(){
		if($(this).hasClass("curr")) return false;
		$(this).removeClass("mover");
	}).unbind("click").click(function(){
		if($(this).hasClass("curr")) return false;
		$(this).removeClass("mover");
		$(".largeMenu .menuSel").removeClass("curr");
		$(this).addClass("curr");
	});
});

