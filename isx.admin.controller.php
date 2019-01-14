<?php
/**
 * @class  isxAdminController
 * @author NHN (developers@xpressengine.com)
 * @author 카르마 (soonj@nate.com)
 * @brief  isx 모듈의 admin controller class
 **/

class isxAdminController extends isx {

	/**
	 * @brief 초기화
	 **/
	function init()
	{
	}

	/**
	 * @brief 설정
	 **/
	function procIsxAdminInsertConfig()
	{
		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');

		$getmodules = $oModuleModel->getModuleList();
        $moduleslist = array();
        foreach($getmodules as $key=>$val)
        {
            $moduleslist[] = $val->module;
        }

        if(in_array('store_search',$moduleslist)) return new Object(-1, 'msg_unabel_to_setup');
		$extended = $oModuleModel->getModuleExtend('integration_search','view','');
        if($extended && $extended !='isx') return new Object(-1, 'msg_unabel_to_install');
		
		// 기본 정보를 받음
		$args = Context::getRequestVars('isx_use','use_document','use_comment','keyword_use','ac_use','ac_source','use_trackback,','use_multimedia','use_file','version','get_use');
		
		//확장모듈 사용시 확장, 사용하지 않으면 제거
		if($args->isx_use !='N') 
		{
			if(!$oModuleModel->getModuleExtend('integration_search','view','')) $oModuleController->insertModuleExtend('integration_search','isx','view','');
			if(!$oModuleModel->getModuleExtend('integration_search','mobile','')) $oModuleController->insertModuleExtend('integration_search','isx','mobile','');
			if(!$oModuleModel->getTrigger('display', 'isx', 'view', 'triggerDisplay','before')) $oModuleController->insertTrigger('display', 'isx', 'view', 'triggerDisplay','before');
			
		}
		else 
		{
			if($oModuleModel->getModuleExtend('integration_search','view','')) $oModuleController->deleteModuleExtend('integration_search', 'isx', 'view','');
			if($oModuleModel->getModuleExtend('integration_search','mobile','')) $oModuleController->deleteModuleExtend('integration_search', 'isx', 'mobile','');
			if($oModuleModel->getTrigger('display', 'isx', 'view', 'triggerDisplay','before')) $oModuleController->deleteTrigger('display', 'isx', 'view', 'triggerDisplay','before');
			
		}
		
		// module Controller 객체 생성하여 입력
		$oModuleController = &getController('module');
		$output = $oModuleController->insertModuleConfig('isx',$args);
		return $output;
	}

	function procIsxAdminDeleteSearch()
	{
		$search_srl = Context::get('search_srl');
		if(!$search_srl)	return new Object(-1, 'msg_invalid_request');
		$args->search_srl = $search_srl;
        $output = executeQuery('isx.deleteSingleKeyword', $args);

		$this->setMessage('success_deleted');
	}
}
/* End of file isx.admin.controller.php */
/* Location: ./modules/isx/isx.admin.controller.php */
