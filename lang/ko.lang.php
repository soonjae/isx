<?php
/**
 * @file   modules/isx/lang/ko.lang.php
 * @author NHN (developers@xpressengine.com)
 * @author 카르마 (soonj@nate.com)
 * @brief  한국어 언어팩 (기본적인 내용만 수록)
 **/

// 일반 단어들
$lang->msg_unabel_to_install ="통합검색을 확장하는 다른 모듈이 이미 설치되어있어 ISX 사용이 불가능합니다. DB에서 prefix_module_extend를 살펴보시기 바랍니다.";
$lang->msg_unabel_to_setup =" 통합검색을 확장하는 다른 모듈이 이미 설치되어있어 ISX 사용이 불가능합니다. 삭제하신후 사용하시기 바랍니다.";
$lang->cmd_search = "검색";
$lang->isx_hit_list ="인기검색";
$lang->isx_count = "조회수";
$lang->group_keyword ="키워드별 ";
$lang->keyword = "검색어";
$lang->get_use = "GET 방식으로 변경 사용";
$lang->about_get_use = "기본적으로 POST 방식을 사용하고 있으나 뒤로가기하는 경우 에러메시지와 함께 새로고침을 해야하는 불편이 있습니다. GET 방식으로 변경시 주소창의 주소가 길어지기는 하지만 뒤로가기의 에러를 없앨수 있는 방법입니다.";
$lang->keysource ="추천검색어 출처";
$lang->about_keysource = "문서를 선택하는 경우 게시판문서의 제목들에서 추출하고
검색어를 선택하는 경우 검색시 저장되는 검색어에서 추출합니다.";
$lang->docu = "게시물제목";
$lang->about_keep_keyword = "저장된 키워드는 일정기간이 지나면 삭제됩니다. 기본은 60일.";
$lang->keep_keyword = "키워드 저장기간(days)";
$lang->isx = "통합검색 확장모듈";
$lang->cmd_isx= "확장 검색 설정";
$lang->about_isx = "검색기능을 선택하고 검색 키워드를 저장해주는 확장모듈입니다. 추천검색어를 제시하는 기능이 있습니다.<br />검색어 하이라이트 구현 : StyleRoot<br />한글자소분리 검색 구현 : WebEngine ";
    $lang->sample_code = '샘플코드';
    $lang->about_target = '선택된 항목만 검색합니다. 선택하지 않은 항목은 출력되지 않습니다.';
    $lang->msg_no_keyword = '검색어를 입력해주세요.';
    $lang->msg_document_more_search  = '\'계속 검색\' 버튼을 선택하시면 아직 검색하지 않은 부분까지 계속 검색 하실 수 있습니다.';

    $lang->is_result_text = "<strong>'%s'</strong> 에 대한 검색결과 <strong>%d</strong>건";
    $lang->multimedia = '이미지/동영상';

	$lang->isx_search_option = array(
        'document' => array(
            'title_content' => '제목+내용',
            'title' => '제목',
            'content' => '내용',
            'tag' => '태그',
        ),
        'trackback' => array(
            'url' => '대상 URL',
            'blog_name' => '대상 사이트 이름',
            'title' => '제목',
            'excerpt' => '내용',
        ),
    );
	$lang->is_sort_option = array(
        'regdate' => '등록일',
        'comment_count' => '댓글 수',
        'readed_count' => '조회 수',
        'voted_count' => '추천 수',
    );
// 에러 메시지들
$lang->integration_search = "통합검색";
$lang->isx_use ="확장검색기능 사용";
$lang->about_isx_use = "미사용을 선택하시는 경우 기존 통합검색을 통하여 검색이 이루어지며 사용을 선택하는 경우에만 아래에 선택된 기능들이 동작합니다.";
$lang->keyword_use = "검색 키워드 저장";
$lang->about_keyword_use ="검색어를 DB에 저장합니다. 저장된 키워드는 추천검색어에 이용되며 위젯을 이용하여 출력할 수도 있습니다.";
$lang->multimedia = '이미지/동영상';
$lang->livexe = "LiveXe";
$lang->search_first = '우선검색대상';
$lang->autocomplete_use = "추천검색어기능";
$lang->about_autocomplete = "검색창에 추천검색어를 제시해주는 기능입니다. 저장된 검색어를 이용하기 때문에 검색 키워드 저장기능을 사용하지 않으면 동작하지 않습니다.";
/*
$lang->search_first_option = array(
		'first_titlecontent' => '제목+내용',
		'first_title' => '제목',
		'first_content' => '내용',
		'fitst_tag' => '태그',
		'first_extravars' => '확장변수',
	);
*/
	$lang->find = '찾기';
	$lang->select = '선택';
	$lang->confirm = '확인';
$lang->isx_config = "기본설정";
$lang->isx_keyword_list = "검색 목록";
$lang->nomember = "비회원";
