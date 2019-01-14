<?php
/**
 * @class  IsxController
 * @author karma (http://www.wildgreen.co.kr)
 * @brief  Isx 모듈의 controller 클래스
 **/

class IsxController extends Isx {

	/**
	 * @brief 초기화
	 **/
	function init() 
	{
	}

	function procIsxGetKeyList($obj)
	{
		if(!Context::get('is_logged')) return new Object(-1,'msg_not_permitted');
        $searchSrls = Context::get('search_srls');
        if($searchSrls) $searchSrlList = explode(',', $searchSrls);
		$args->sort_index = "regdate";
		$args->order_type = "desc";

        if(count($searchSrlList) > 0)
        {
			$args->search_srls = $searchSrlList;
            $oISX = &getModel('isx');
            //$columnList = array('search_srl', 'title', 'nick_name', 'status');
            $KeyList = $oISX->getKeywordAll($args);
        }
        else
        {
            global $lang;
            $KeyList = array();
            $this->setMessage($lang->no_documents);
        }
        $oSecurity = new Security($KeyList);
        $oSecurity->encodeHTML('..variables.');
        $this->add('document_list', $KeyList);

	}

} 
/* End of file Isx.controller.php */
/* Location: ./modules/Isx/Isx.controller.php */
