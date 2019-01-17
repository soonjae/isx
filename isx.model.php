<?php
/**
 * @class  integrationModel
 * @author NHN (developers@xpressengine.com)
 * @brief  integration 모듈의 Model class
 **/

class isxModel extends module {

	/**
	 * @brief 초기화
	 **/
	function init() {
	}
	
	// WebEngine Edit For JaMo Searching Start
	function utfCharToNumber($char) {
		$i = 0;
		$number = '';
		$convmap = array(0x80, 0xffff, 0, 0xffff);
		$number = mb_encode_numericentity($char, $convmap, 'UTF-8');
		return $number;
	}

	function strToArray($str){
		$result = array();
		$stop = mb_strlen($str, 'UTF-8');
		for($idx = 0; $idx < $stop; $idx++)
			$result[] = mb_substr($str, $idx, 1, 'UTF-8');
		return $result;
	}

	function parseInt($string){
		if(preg_match('/(\d+)/', $string, $array))
			return $array[1];
		else
			return 0;
	}

	function mb_chr($ord){
		if($ord < 128) return chr($ord);// 1-byte 
		for($i=1; $i < 6 && $ord >= (1 << 5 * $i + 6); $i++); // units
		$chr = chr(($ord >> $i * 6) + 256 - (1 << 6 - $i + 1)); // start byte
		for($i -= 1; $i >= 0; $i--) // multi-bytes
			$chr .= chr((63 & ($ord >> $i * 6)) + 128);
		return $chr;
	}

	function make_jamo($str){
		//초성(19자) ㄱ ㄲ ㄴ ㄷ ㄸ ㄹ ㅁ ㅂ ㅃ ㅅ ㅆ ㅇ ㅈ ㅉ ㅊ ㅋ ㅌ ㅍ ㅎ
		$ChoSeong = array(0x3131, 0x3132, 0x3134, 0x3137, 0x3138,
		0x3139, 0x3141, 0x3142, 0x3143, 0x3145, 0x3146, 0x3147, 0x3148,
		0x3149, 0x314a, 0x314b, 0x314c, 0x314d, 0x314e);
		
		//중성(21자) ㅏ ㅐ ㅑ ㅒ ㅓ ㅔ ㅕ ㅖ ㅗ ㅘ(9) ㅙ(10) ㅚ(11) ㅛ ㅜ ㅝ(14) ㅞ(15) ㅟ(16) ㅠ ㅡ ㅢ(19) ㅣ
		$JungSeong = array(0x314f, 0x3150, 0x3151, 0x3152, 0x3153,
		0x3154, 0x3155, 0x3156, 0x3157, 0x3158, 0x3159, 0x315a, 0x315b,
		0x315c, 0x315d, 0x315e, 0x315f, 0x3160, 0x3161, 0x3162, 0x3163);

		//종성(28자) <없음> ㄱ ㄲ ㄳ(3) ㄴ ㄵ(5) ㄶ(6) ㄷ ㄹ ㄺ(9) ㄻ(10) ㄼ(11) ㄽ(12) ㄾ(13) ㄿ(14) ㅀ(15) ㅁ ㅂ ㅄ(18) ㅅ ㅆ ㅇ ㅈ ㅊ ㅋ ㅌ ㅍ ㅎ
		$JongSeong = array(0x0000, 0x3131, 0x3132, 0x3133, 0x3134,
		0x3135, 0x3136, 0x3137, 0x3139, 0x313a, 0x313b, 0x313c, 0x313d,
		0x313e, 0x313f, 0x3140, 0x3141, 0x3142, 0x3144, 0x3145, 0x3146,
		0x3147, 0x3148, 0x314a, 0x314b, 0x314c, 0x314d, 0x314e);

		$chars = array();
		$result = array();
		$array_str = array();
		$array_str = $this->strToArray($str);

		for($i=0; $i < mb_strlen($str, 'UTF-8'); $i++){
			$one_char_num = $this->utfCharToNumber($array_str[$i]);
			$one_char_num = substr($one_char_num, 2, mb_strlen($one_char_num, 'UTF-8')-3);

			// "AC00:가" ~ "D7A3:힣"에 속한 글자만 분해.(한글 모두) 
			if($one_char_num >= 0xAC00 && $one_char_num <= 0xD7A3){
				$i1 = 0;
				$i2 = 0;
				$i3 = 0;

				$i3 = $one_char_num - 0xAC00;
				$i1 = $i3 / (21 * 28);
				$i3 = $i3 % (21 * 28);

				$i2 = $i3 / 28;
				$i3 = $i3 % 28;

				$result[] = $this->mb_chr($ChoSeong[$this->parseInt($i1)]);

				switch($this->parseInt($i2)){
					case 9:
						$result[] = 'ㅗㅏ';
						break;
					case 10:
						$result[] = 'ㅗㅐ';
						break;
					case 11:
						$result[] = 'ㅗㅣ';
						break;
					case 14:
						$result[] = 'ㅜㅓ';
						break;
					case 15:
						$result[] = 'ㅜㅔ';
						break;
					case 16:
						$result[] = 'ㅜㅣ';
						break;
					case 19:
						$result[] = 'ㅡㅣ';
						break;
					default:
						$result[] = $this->mb_chr($JungSeong[$this->parseInt($i2)]);
						break;
				}

				if($i3 != 0x0000){ // c가 0이 아니면, 즉 받침이 있으면 
					//복자음 분리 
					switch ($this->parseInt($i3)) {
						case 3:
							$result[] = 'ㄱㅅ';
							break;
						case 5:
							$result[] = 'ㄴㅈ';
							break;
						case 6:
							$result[] = 'ㄴㅎ';
							break;
						case 9:
							$result[] = 'ㄹㄱ';
							break;
						case 10:
							$result[] = 'ㄹㅁ';
							break;
						case 11:
							$result[] = 'ㄹㅂ';
							break;
						case 12:
							$result[] = 'ㄹㅅ';
							break;
						case 13:
							$result[] = 'ㄹㅌ';
							break;
						case 14:
							$result[] = 'ㄹㅍ';
							break;
						case 15:
							$result[] = 'ㄹㅎ';
							break;
						case 18:
							$result[] = 'ㅂㅅ';
							break;
						default:
							$result[] = $this->mb_chr($JongSeong[$this->parseInt($i3)]);
							break;
					}
				}
			}
			else $result[] = $array_str[$i];
		}
		return $result;
	}
	// WebEngine Edit For JaMo Searching End
	
	function getKeywordAll($obj)
	{
		$output = executeQuery('isx.getKeywordAll', $obj);
		return $output;
	}

	//동일한 검색어를 여러번 검색해도 저장은 하루에 한번만...
	function getKeywordCount($keyword,$config=NULL)
	{
		$args = new StdClass();
	   	$args->keyword = $keyword;
	   	if(!$args->keyword) return;
	   	$logged_info=Context::get('logged_info');
	   	$args->ipaddress = $_SERVER["REMOTE_ADDR"];
		$args->regdate = date("YmdHis",mktime(0,0,0,date('n'),date('j'),date('Y')));
	   	$output = executeQuery('isx.getKeywordCount', $args);
	   	return count($output->data);
   }

	function insertKeyword($keyword,$config=NULL)
	{
		$args = new StdClass();
		$args->keyword = trim($keyword);
		$args->keyword = removeHackTag($args->keyword);
		if(!$args->keyword) return;
		
		// WebEngine Edit For JaMo Searching Start
		$args->jamo = join("", $this->make_jamo($args->keyword));
		// WebEngine Edit For JaMo Searching End
		
		$logged_info=Context::get('logged_info');
		if($logged_info) $args->member_srl=$logged_info->member_srl;
		else $args->member_srl='0';
		
		$args->ipaddress = $_SERVER["REMOTE_ADDR"];
		$args->regdate = date("YmdHis");

		if($this->getKeywordCount($args->keyword)) return;	
		$this->deleteKeyword($config->keep_keyword);
		
		$output = executeQuery('isx.insertKeyword', $args);
		return $output;
	}

	//일정기간이 지난 검색어는 자동으로 삭제
	function deleteKeyword($sdays) 
	{
		$args = new StdClass();
		if(!$sdays) $sdays=60;	//기본이 60일
		$args->regdate = date("Ymd",mktime(0,0,0,date('n'),date('j')-$sdays,date('Y')));
		executeQuery('isx.deleteKeyword', $args);
	}

	function getKeywordList($args)
	{
		if($args->list_type=="day") $args->regdate = date("YmdHis",mktime(0,0,0,date('n'),date('j')-2,date('Y')));
		elseif($args->list_type=="week") $args->regdate = date("YmdHis",mktime(0,0,0,date('n'),date('j')-7,date('Y')));
		else $args->regdate = date("YmdHis",mktime(0,0,0,date('n')-1,date('j'),date('Y')));
		$output = executeQuery('isx.getKeywordList', $args);
		return $output;
	}

	function getLivexeSearch($search_target,$is_keyword,$page,$limit=20)
    	{
		$args = new StdClass();
	        $args->page = $page;
        	$args->list_count = $limit;
	        $args->page_count = $args->page_count;
        	$args->sort_index = 'documents.regdate';
	        $args->order_type = 'desc';

        	switch($search_target)
        	{
	            case 'tag' :
        	        $args->tag = $is_keyword;
                	break;
	            case 'title' :
        	        $args->title = $is_keyword;
                	break;
	            case 'content' :
        	        $args->content = $is_keyword;
                	break;
	            default :
        	        $args->title = $is_keyword;
                	$args->content = $is_keyword;
	                break;
        	}
        	$output = executeQueryArray('livexe.getLiveDocumentList', $args);
        	return $output;
    	}

	function getKeyfromKey($key)
	{
		$args = new StdClass();
		if(!$key) return;
		$list = array();
		$args->s_jamo = join("",$this->make_jamo($key));
		$output = executeQueryArray('isx.getKeyFromKey', $args);
		if(!count($output->data)) return ;
	
		foreach($output->data as $key=>$val){
			$list[] = $val->keyword;
		}
		if(count($list)){
			return implode("\n",$list);
		}
	}

	function getKeyfromDocument($key)
	{
		if(!$key) return;
		$oModuleModel = &getModel('module');
		$config = $oModuleModel->getModuleConfig('integration_search');

		$target = $config->target;
		if(!$target) $target = 'include';
		
		if(empty($config->target_module_srl)) $module_srl_list = array();
        	else $module_srl_list = explode(',',$config->target_module_srl);

		$oIS = &getModel('integration_search');
		$output= $oIS->getDocuments($target, $module_srl_list, 'title', $key, 1, 10);
        
		if(!count($output->data)) return ;
	        foreach($output->data as $key=>$val){
        	    $list[] = $val->get('title');
        	}
        	if(count($list)){
            		return implode("\n",$list);
        	}
	}
	
	function getProducts($target, $module_srls_list, $search_target, $search_keyword, $page = 1, $list_count = 20)
                        {
                if(is_array($module_srls_list))
                {
                        $module_srls_list = implode(',', $module_srls_list);
                }

                $args = new stdClass();
                if($target == 'exclude')
                {
                        $module_srls_list .= ',0'; // exclude 'trash'
                        if($module_srls_list{0} == ',')
                        {
                                $module_srls_list = substr($module_srls_list, 1);
                        }
                        $args->exclude_module_srl = $module_srls_list;
                }
                else
                {
                        $args->module_srl = $module_srls_list;
                        $args->exclude_module_srl = '0'; // exclude 'trash'
                }

                $args->page = $page;
                $args->list_count = $list_count;
                $args->page_count = 10;
                $args->search_target = $search_target;
                $args->search_keyword = $search_keyword;
                $args->sort_index = 'list_order';
                $args->order_type = 'asc';
                if(!$args->module_srl)
                {
                        unset($args->module_srl);
                }
                                // Get a list of documents
                $oDocumentModel = getModel('document');

                $documentlist_output = $oDocumentModel->getDocumentList($args);
                if(!$documentlist_output->toBool())
                {
                        return $documentlist_output;
                }
                $args = new stdClass();
                $document_srl_list = array();
                $documentlist_index = array();
                if($documentlist_output->data)
                {
                        foreach($documentlist_output->data as $key => $val)
                        {
                                $document_srl_list[] = $val->document_srl;
                                $documentlist_index[$val->document_srl] = $key;
                        }
                }
                $args->document_srl = implode(',', $document_srl_list);
                $output = executeQueryArray('nproduct.getItemListByDocumentSrl', $args);
                if(!$output->toBool())
                {
                        return $output;
                }
                if($output->data)
                {
                        foreach($output->data as $key => $val)
                        {
                                if($documentlist_output->data[$documentlist_index[$val->document_srl]])
                                {
                                        $documentlist_output->data[$documentlist_index[$val->document_srl]]->item = new nproductItem($val);
                                }
                        }
                                }
                return $documentlist_output;
        }

}
/* End of file isx.model.php */
/* Location: ./modules/isx/isx.model.php */
