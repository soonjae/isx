<?php
/**
 * @class  integration_searchView
 * @author 카르마 (soonj@nate.com)
 * @brief  ISX module의 view class 
 *	
 **/

class isxView extends isx
{

	/**
	 * @brief 초기화
	 **/
	var $target_mid = array();

    /**
     * Initialization
     *
     * @return void
     */
	function init()
	{
		$oModuleModel = &getModel('module');
		$this->isxconfig = $oModuleModel->getModuleConfig('isx');
		Context::set('module_config',$this->isxconfig);

	}

	function IS()
	{
		return $this->ISX();
	}

	function triggerDisplay()
	{
		$oModuleModel = &getModel('module');
		$isxconfig = $oModuleModel->getModuleConfig('isx');
		if($isxconfig->ac_use == 'N') return;
		if(Context::get('module') == 'admin') return;
		if($isxconfig->ac_use == 'Y')
		{
			if($isxconfig->ac_source == 'key')$target = getSiteUrl()."modules/isx/isx.key_query.php";
            		else  $target = getSiteUrl()."modules/isx/isx.document_query.php";

			Context::addCSSFile("./modules/isx/tpl/css/jquery.autocomplete.css", false);
			Context::addJsFile('./modules/isx/tpl/js/jquery.autocomplete.js',false,'',null,'');
			$scr = array();
            		$scr[] = "<script type=\"text/javascript\">(function($){";
            		if($isxconfig->get_use == 'Y')
            		{
                		$scr[] = "$('input[name=\"is_keyword\"]').parent().attr({\"method\":\"get\",\"no-error-return-url\":\"true\"});";
            		}
            		$scr[] = "$('input[name=\"is_keyword\"]').autocomplete( \"".$target."\", {  });";
            		$scr[] = "})(jQuery);</script>";
            		$script = implode(' ',$scr);
			Context::addHtmlFooter($script);
		}
	}
	
	/**
	 * @brief 통합 검색 출력
	 **/
	function ISX()
	{
		$oFile = &getClass('file');
		$oModuleModel = &getModel('module');

		// 권한 체크
		if(!$this->grant->access) return new Object(-1,'msg_not_permitted');
		$isxconfig = $this->isxconfig ;
		$config = $oModuleModel->getModuleConfig('integration_search');
		Context::set('config',$config);
		if(!$isxconfig->skin) $isxconfig->skin  = 'isxdefault';
        	$template_path = sprintf('%sskins/%s', $this->module_path, $isxconfig->skin);

		// Template path
        	$this->setTemplatePath($template_path);
        	$skin_vars = ($isxconfig->skin_vars) ? unserialize($isxconfig->skin_vars) : new stdClass;
        	Context::set('module_info', $skin_vars);

		$target = $config->target;
		if(!$target) $target = 'include';
		
		if(empty($config->target_module_srl))
            		$module_srl_list = array();
        	else
            		$module_srl_list = explode(',',$config->target_module_srl);

		// 검색어 변수 설정
		$is_keyword = Context::get('is_keyword');

		// 페이지 변수 설정
		$page = (int)Context::get('page');
		if(!$page) $page = 1;

		// 검색탭에 따른 검색
		$where = Context::get('where');

		//누리고 모듈 목록 
                $oNproductModel = getAdminModel('nproduct');
                if($oNproductModel)
                {
                        $product_module_srl_list = array();
                        $modinstlist_output = executeQueryArray('nproduct.getModInstList');
                        $tmp_arr = $modinstlist_output->data;
                        if(!is_array($tmp_arr))
                        {
                                $tmp_arr = array();
                        }
                        foreach($tmp_arr as $key => $val)

                        {
                                $product_module_srl_list[] = $val->module_srl;
                        }
                }
		
		// integration search model객체 생성
		if($is_keyword)
		{
			$oISx = &getModel('isx');
			$oIS = &getModel('integration_search');
			$oTrackbackModel = getAdminModel('trackback');
			Context::set('trackback_module_exist', true);
			if(!$oTrackbackModel)
			{
				Context::set('trackback_module_exist', false);
			}
			switch($where)
			{
				case 'document' :
					$search_target = Context::get('search_target');
					if(!in_array($search_target, array('title','content','title_content','tag'))) $search_target = 'title_content';
					Context::set('search_target', $search_target);
					$output = $oIS->getDocuments($target, $module_srl_list, $search_target, $is_keyword, $page, 10);
					Context::set('output', $output);
					$this->setTemplateFile("document", $page);
					break;
				case 'comment' :
					$output = $oIS->getComments($target, $module_srl_list, $is_keyword, $page, 10);
					Context::set('output', $output);
					$this->setTemplateFile("comment", $page);
					break;
				case 'trackback' :
					$search_target = Context::get('search_target');
					if(!in_array($search_target, array('title','url','blog_name','excerpt'))) $search_target = 'title';
					Context::set('search_target', $search_target);
					$output = $oIS->getTrackbacks($target, $module_srl_list, $search_target, $is_keyword, $page, 10);
					Context::set('output', $output);
					$this->setTemplateFile("trackback", $page);
					break;
				case 'multimedia' :
					$output = $oIS->getImages($target, $module_srl_list, $is_keyword, $page,20);
					Context::set('output', $output);
					$this->setTemplateFile("multimedia", $page);
					break;
				case 'file' :
					$output = $oIS->getFiles($target, $module_srl_list, $is_keyword, $page, 20);
					Context::set('output', $output);
					$this->setTemplateFile("file", $page);
					break;
				case 'livexe' :
                    			$output = $oISx->getLivexeSearch($target, $module_srl_list, $is_keyword, $page, 20);
                    			Context::set('output', $output);
                    			$this->setTemplateFile("livexe", $page);
                    			break;
				case 'nproduct' :
                                        $output = $oISx->getProducts('include', $product_module_srl_list, $search_target, $is_keyword, $page, 10);
                                        Context::set('output', $output);
                                        $this->setTemplateFile("nproduct", $page);
                                        break;
                                default :
                                        if($isxconfig->use_document == 'Y') $output['document'] = $oIS->getDocuments($target, $module_srl_list, 'title_content', $is_keyword, $page, 5);
                                        if($isxconfig->use_comment == 'Y') $output['comment'] = $oIS->getComments($target, $module_srl_list, $is_keyword, $page, 5);
                                        if($isxconfig->use_trackback == 'Y') $output['trackback'] = $oIS->getTrackbacks($target, $module_srl_list, 'title', $is_keyword, $page, 5);
                                        if($isxconfig->use_multimedia == 'Y') $output['multimedia'] = $oIS->getImages($target, $module_srl_list, $is_keyword, $page, 5);
                                        if($isxconfig->use_file == 'Y') $output['file'] = $oIS->getFiles($target, $module_srl_list, $is_keyword, $page, 5);
                                        if($isxconfig->use_livexe == 'Y') $output['livexe'] = $oISx->getLivexeSearch($target, $module_srl_list, $is_keyword, $page, 5);
                                        if($isxconfig->use_nproduct == 'Y') $output['nproduct'] = $oISx->getProducts('include', $product_module_srl_list, 'title_content', $is_keyword, $page, 5);
					Context::set('search_result', $output);
					Context::set('search_target', 'title_content');
					$this->setTemplateFile("index", $page);
					break;
			}
			if($this->isxconfig->keyword_use == 'Y')
			{
				$oISx = &getModel('isx');
				$oISx->insertKeyword($is_keyword, $this->isxconfig);
			}
		}
		else
		{
			$this->setTemplateFile("no_keywords");
		}
	}

}
/* End of file isx.view.php */
/* Location: ./modules/isx/isx.view.php */
