<?php
/**
 * @class  integration_searchView
 * @author 카르마 (soonj@nate.com)
 * @brief  ISX module의 view class 
 *	
 **/

class isxMobile extends isx
{

	/**
	 * @brief 초기화
	 **/
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

		$config = $oModuleModel->getModuleConfig('integration_search');
		if(!$config->mskin) $config->mskin = 'default';
		Context::set('module_info', unserialize($config->skin_vars));
		$this->setTemplatePath($this->module_path."/m.skins/".$config->mskin."/");

		$target = $config->target;
		if(!$target) $target = 'include';
		$module_srl_list = explode(',',$config->target_module_srl);

		// 검색어 변수 설정
		$is_keyword = Context::get('is_keyword');

		// 페이지 변수 설정
		$page = (int)Context::get('page');
		if(!$page) $page = 1;

		// 검색탭에 따른 검색
		$where = Context::get('where');

		// integration search model객체 생성
		if($is_keyword)
		{
			$oIsx = &getModel('isx');
			$oIS = &getModel('integration_search');
			switch($where)
			{
				case 'document' :
					$search_target = Context::get('search_target');
					if(!in_array($search_target, array('title','content','title_content','tag'))) $search_target = 'title';
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
				default :
					$output['document'] = $oIS->getDocuments($target, $module_srl_list, 'title', $is_keyword, $page, 5);
					$output['comment'] = $oIS->getComments($target, $module_srl_list, $is_keyword, $page, 5);
					$output['trackback'] = $oIS->getTrackbacks($target, $module_srl_list, 'title', $is_keyword, $page, 5);
					$output['multimedia'] = $oIS->getImages($target, $module_srl_list, $is_keyword, $page, 5);
					$output['file'] = $oIS->getFiles($target, $module_srl_list, $is_keyword, $page, 5);
					Context::set('search_result', $output);
					Context::set('serach_target',$search_target);
					$this->setTemplateFile("index", $page);
					break;
			}
		$oIsx = &getModel('isx');
		$oIsx->insertKeyword($is_keyword);
		}
		else
		{
			$this->setTemplateFile("no_keywords");
		}
	}

}
/* End of file isx.mobile.php */
/* Location: ./modules/isx/isx.mobile.php */