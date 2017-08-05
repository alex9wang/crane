function getElementsByClassName(classname, node)  {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
        if(re.test(els[i].className))a.push(els[i]);
    return a;
}

function FileProgress(file, targetID) {
	this.fileProgressID = file.id;
	this.opacity = 100;
	this.height = 0;
	this.filebuttonWrapper = getElementsByClassName("button", false)[0];
	this.fileProgressWrapper = getElementsByClassName(targetID, false)[0];
	this.fileProgressWrapper.style.display= 'block';
	if(typeof this.filebuttonWrapper != "undefined") this.filebuttonWrapper.style.display= 'none';
	this.fileProgressElement = this.fileProgressWrapper.childNodes[0].childNodes[0];
}

FileProgress.prototype.setTimer = function (timer) {};
FileProgress.prototype.getTimer = function (timer) {};

FileProgress.prototype.reset = function () {
	this.fileProgressElement.style.width = "0%";
	this.fileProgressElement.childNodes[0].innerText = "0%";
};

FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.style.width  = percentage + "%";
	if(percentage >= 100)
		this.fileProgressElement.childNodes[0].innerText = "보존중...";
	else
		this.fileProgressElement.childNodes[0].innerText = percentage + "%";
};

FileProgress.prototype.setComplete = function () {
	this.fileProgressWrapper.style.display= 'none';
	if(typeof this.filebuttonWrapper != "undefined") this.filebuttonWrapper.style.display= 'block';
	reloadData();
};
FileProgress.prototype.setError = function () {};
FileProgress.prototype.setCancelled = function () {};
FileProgress.prototype.setStatus = function (status) {};
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {};
FileProgress.prototype.appear = function () {};
FileProgress.prototype.disappear = function () {};