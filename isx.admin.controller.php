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

                // 기본 정보를 받음
                $args = new StdClass();
                $args = Context::getRequestVars('isx_use','use_document','use_comment','keyword_use','ac_use','ac_source','use_trackback,','use_multimedia','use_file','version','get_use','skin','mskin');
                //확장모듈 사용시 확장, 사용하지 않으면 제거
                if($args->isx_use =='Y')
                {
			//isx 외의 확장모듈이 있는지 체크하고 제거
                        $extendedview = $oModuleModel->getModuleExtend('integration_search','view','');
                        if($extendedview && $extendedview !='isx')
                        {
                                $oModuleController->deleteModuleExtend('integration_search', $extendedview, 'view', '');
                                $oModuleController->insertModuleExtend('integration_search','isx','view','');
                        }
                        $extendedmobile = $oModuleModel->getModuleExtend('integration_search','mobile','');
                        if($extendedmobile && $extendedmobile !='isx')
                        {
                                $oModuleController->deleteModuleExtend('integration_search', $extendedmobile, 'mobile','');
                                $oModuleController->insertModuleExtend('integration_search','isx','mobile','');
                        }
			//확장 및 트리거 등록
                        if(!$oModuleModel->getModuleExtend('integration_search','view','')) $oModuleController->insertModuleExtend('integration_search','isx','view','');
                        if(!$oModuleModel->getModuleExtend('integration_search','mobile','')) $oModuleController->insertModuleExtend('integration_search','isx','mobile','');
                        if(!$oModuleModel->getTrigger('display', 'isx', 'view', 'triggerDisplay','before')) $oModuleController->insertTrigger('display', 'isx', 'view', 'triggerDisplay','before');

                }
                else
                {
		// 확장 및 트리거 제거
                        if($oModuleModel->getModuleExtend('integration_search','view','')) $oModuleController->deleteModuleExtend('integration_search', 'isx', 'view','');
                        if($oModuleModel->getModuleExtend('integration_search','mobile','')) $oModuleController->deleteModuleExtend('integration_search', 'isx', 'mobile','');
                        if($oModuleModel->getTrigger('display', 'isx', 'view', 'triggerDisplay','before')) $oModuleController->deleteTrigger('display', 'isx', 'view', 'triggerDisplay','before');

                }

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

	/**
	 * Save the skin information
	 *
	 * @return mixed
	 */
	function procIsxAdminInsertSkin()
	{
		// Get configurations (using module model object)
		$oModuleModel = getModel('module');
		$config = $oModuleModel->getModuleConfig('isx');
		$config = (object)get_object_vars($config);
		// Get skin information (to check extra_vars)
		$skin_info = $oModuleModel->loadSkinInfo($this->module_path, $config->skin);
		
		// Check received variables (delete the basic variables such as mo, act, module_srl, page)
		$obj = Context::getRequestVars();
		unset($obj->act);
		unset($obj->module_srl);
		unset($obj->page);
		
		// Separately handle if the extra_vars is an image type in the original skin_info
		if($skin_info->extra_vars)
		{
			foreach($skin_info->extra_vars as $vars)
			{
				if($vars->type!='image') continue;
				$image_obj = $obj->{$vars->name};
				// Get a variable on a request to delete
				$del_var = $obj->{"del_".$vars->name};
				unset($obj->{"del_".$vars->name});
				if($del_var == 'Y')
				{
					FileHandler::removeFile($module_info->{$vars->name});
					continue;
				}
				// Use the previous data if not uploaded
				if(!$image_obj['tmp_name'])
				{
					$obj->{$vars->name} = $module_info->{$vars->name};
					continue;
				}
				// Ignore if the file is not successfully uploaded, and check uploaded file
				if(!is_uploaded_file($image_obj['tmp_name']))
				{
					unset($obj->{$vars->name});
					continue;
				}
				// Ignore if the file is not an image
				if(!preg_match("/\.(jpg|jpeg|gif|png)$/i", $image_obj['name']))
				{
					unset($obj->{$vars->name});
					continue;
				}
				// Upload the file to a path
				$path = sprintf("./files/attach/images/%s/", $module_srl);
				// Create a directory
				if(!FileHandler::makeDir($path)) return false;
				$filename = $path.$image_obj['name'];
				// Move the file
				if(!move_uploaded_file($image_obj['tmp_name'], $filename))
				{
					unset($obj->{$vars->name});
					continue;
				}
				// Change a variable
				unset($obj->{$vars->name});
				$obj->{$vars->name} = $filename;
			}
		}
		
		// Serialize and save 
		$config->skin_vars = serialize($obj);
		$oModuleController = getController('module');
		$output = $oModuleController->insertModuleConfig('isx', $config);
		$this->setMessage('success_updated', 'info');
		$returnUrl = Context::get('success_return_url') ? Context::get('success_return_url') : getNotEncodedUrl('', 'module', 'admin', 'act', 'dispIsxAdminSkinInfo');
		return $this->setRedirectUrl($returnUrl, $output);
	}
}
/* End of file isx.admin.controller.php */
/* Location: ./modules/isx/isx.admin.controller.php */
