


var IEVersion;
if (navigator.userAgent.indexOf(' MSIE ') > -1) {
    IEVersion = parseFloat(navigator.userAgent.match(/MSIE (\d+\.\d+)/)[1]);
}


function initPng(img){
	if( ! IEVersion ){
		img.style.visibility='visible';
	}
}

