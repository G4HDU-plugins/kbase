function kbaseSaveCookie(LastTab){
	document.cookie="kbaseLastTab="+LastTab+"; path=/";
	kbaseSaveLastTime();
}
function kbaseSaveLastTime(){
	var tnow=Math.floor(Date.now() / 1000);
	//document.cookie="kbaseLastTabTime="+tnow+"; path=/";
}
$(document).ready(function(){

	$('#kbaseTab1').click(function(){
		kbaseSaveCookie('1');
	});

	$('#kbaseTab2').click(function(){
		kbaseSaveCookie('2');
	});

	$('#kbaseTab3').click(function(){
		kbaseSaveCookie('3');
	});

	$('#kbaseTab4').click(function(){
		kbaseSaveCookie('4');
	});
	$('#kbasePrefForm').submit(function(){
		kbaseSaveLastTime();
	});

});
