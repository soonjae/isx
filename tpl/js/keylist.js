function doDeleteSearch(search_srl) {
	if(confirm("삭제하시겠습니까?")) {
		var params = {'search_srl' : search_srl};
		exec_xml('isx','procIsxAdminDeleteSearch', params, completeDeleteSearch);
	}
}

function completeDeleteSearch(ret_obj) {
	    alert(ret_obj['message']);
    location.reload();
}
