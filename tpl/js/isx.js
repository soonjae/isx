jQuery(function($) {
	var ischecked = $('input[name="isx_use"]:checked').val();
	if(ischecked =='Y') $('.ac').show();
	else $('.ac').hide();
	$('input[name="isx_use"]').change(function(){
		var ischecked = $('input[name="isx_use"]:checked').val();
		if(ischecked =='Y') $('.ac').show();
		else $('.ac').hide();
	});
	var ischeckedkey = $('input[name="keyword_use"]:checked').val();
    if(ischeckedkey =='Y') $('.keepkeyword').show();
    else $('.keepkeyword').hide();
    $('input[name="keyword_use"]').change(function(){
        var ischeckedkey = $('input[name="keyword_use"]:checked').val();
        if(ischeckedkey =='Y') $('.keepkeyword').show();
        else $('.keepkeyword').hide();
    });
	var ischeckedac = $('input[name="ac_use"]:checked').val();
	if(ischeckedac =='Y') $('.ackey').show();
	else $('.ackey').hide();
	$('input[name="ac_use"]').change(function(){
		var ischeckedac = $('input[name="ac_use"]:checked').val();
		if(ischeckedac =='Y') $('.ackey').show();
		else $('.ackey').hide();
	});
});
