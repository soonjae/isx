<?php
/**
 * @class  isxAdminView
 * @author NHN (developers@xpressengine.com)
 * @author 카르마 (soonj@nate.com)
 * @brief  isx 모듈의 admin view class
 **/

class isxAdminView extends isx {

	/**
	 * @brief 초기화
	 **/
	function init() {
		// 설정 정보를 받아옴 (module model 객체를 이용)
		$oModuleModel = &getModel('module');
		$config = $oModuleModel->getModuleConfig('isx');
		Context::set('config',$config);
		Context::set('isx_config',$oModuleModel->getModuleInfoXml('isx'));
        	Context::set('int_config',$oModuleModel->getModuleInfoXml('integration_search'));
	}

	/**
	 * @brief 설정
	 **/
	function dispIsxAdminConfig() {
                // Get a list of skins
                $oModuleModel = getModel('module');
                $skin_list = $oModuleModel->getSkins($this->module_path);
                Context::set('skin_list',$skin_list);
                $mskin_list = $oModuleModel->getSkins($this->module_path, 'm.skins');
                Context::set('mskin_list', $mskin_list);

                // 템플릿 파일 지정
                $oModuleModel = getModel('module');
                $modules = $oModuleModel->getModuleList();
                $moduleslist = array();
                foreach($modules as $key=>$val)
                {
                        $moduleslist[] = $val->module;
                }
                $oTrackbackModel = getAdminModel('trackback');
                if($oTrackbackModel && in_array('trackback',$moduleslist))
                {
                        Context::set('TrackbackInstalled', true);
                } else {
                        Context::set('TrackbackInstalled', false);
                }
                $oLivexeModel = getAdminModel('livexe');
                if($oLivexeModel && in_array('livexe',$moduleslist))
                {
                        Context::set('LivexeInstalled', true);
                } else {
                        Context::set('LivexeInstalled', false);
                }
                $oNproductModel = getAdminModel('nproduct');
                if($oNproductModel && in_array('nproduct',$moduleslist))
                {
                        Context::set('NproductInstalled', true);
                } else {
                        Context::set('NproductInstalled', false);
                }

                $this->setTemplatePath($this->module_path.'tpl');
                $this->setTemplateFile('index');
        }

	function dispIsxAdminKeywordlist() {
        // 템플릿 파일 지정
        $this->setTemplatePath($this->module_path.'tpl');
        $this->setTemplateFile('keylist');
		$args->search_srls = array();
		$args->sort_index = Context::get('sort_index');
		$args->order_type = Context::get('order_type');
		if(!$args->order_type) $args->order_type = "desc";
		$r_order = ($args->order_type == 'desc')?'asc':'desc';
		Context::set('r_order',$r_order);
		if(!$args->sort_index) $args->sort_index = "regdate";
		$args->page = Context::get('page');
		if(!$args->page) $args->page=1;
		$oIsx = &getModel('isx');
		$output = $oIsx->getKeywordAll($args);
		if(count($output->data))
		{
			$oMemberModel = &getModel('member');
            $data = array();
            foreach($output->data as $key => $val)
            {
                $temp = array();
                $temp['regdate'] = $val->regdate;
                $temp['member_srl'] = $val->member_srl;
                $temp['ipaddress'] = $val->ipaddress;
                $temp['keyword'] = $val->keyword;
                $temp['search_srl'] = $val->search_srl;
                if($val->member_srl)
                {
                    $member_info = $oMemberModel->getMemberInfoByMemberSrl($val->member_srl);
                    $temp['nickname'] = $member_info->nick_name;
                }
                else
                    $temp['nickname'] = '';
                $data[] = $temp;
            }
			Context::set('key_list', $data);
		}
		else Context::set('key_list', $output->data);
        Context::set('total_count', $output->total_count);
        Context::set('total_page', $output->total_page);
        Context::set('page', $output->page);
        Context::set('page_navigation', $output->page_navigation);
    }

	function dispIsxAdminSkinInfo()
	{
		$oModuleModel = &getModel('module');
                $config = $oModuleModel->getModuleConfig('isx');
		$this->setTemplatePath($this->module_path.'tpl');
		$skin_info = $oModuleModel->loadSkinInfo($this->module_path, $config->skin);
		$skin_vars = unserialize($config->skin_vars);
		// value for skin_info extra_vars
		if(count($skin_info->extra_vars))
		{
			foreach($skin_info->extra_vars as $key => $val)
			{
				$name = $val->name;
				$type = $val->type;
				$value = $skin_vars->{$name};
				if($type=="checkbox"&&!$value) $value = array();
				$skin_info->extra_vars[$key]->value= $value;
			}
		}
		Context::set('skin_info', $skin_info);
		Context::set('skin_vars', $skin_vars);
		Context::set('module_info', unserialize($config->skin_vars));
		$security = new Security();
		$security->encodeHTML('skin_info...');
		$this->setTemplateFile("skin_info");
	}


}
/* End of file isx.admin.view.php */
/* Location: ./modules/isx/isx.admin.view.php */
