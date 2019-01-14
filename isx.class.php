<?php
/**
 * @class  isx
 * @author 카르마 (soonj@nate.com)
 * @brief 통합검색 확장모듈 class
 **/

class isx extends ModuleObject {

	/**
	 * @brief 설치시 추가 작업이 필요할시 구현
	 **/
	function moduleInstall() {
		$oModuleController = &getController('module');;
		$oModuleModel = &getModel('module');
		$module_info = $oModuleModel->getModuleInfoXml('isx');

		$isxConfig->isx_usee = 'N';
		$isxConfig->use_document = 'Y';
		$isxConfig->use_comment ='Y';
		$isxConfig->keep_keyword = 60;
		$isxCOnfig->ac_source ='key';
		$isxConfig->version = $module_info->version;
		$oModuleController->insertModuleConfig('isx', $isxConfig);
		return new Object();
	}

	/**
	 * @brief 설치가 이상이 없는지 체크하는 method
	 **/
	function checkUpdate() {
		$oDB = &DB::getInstance();
		$oModuleModel = &getModel('module');

		//설정파일
		$isxConfig = $oModuleModel->getModuleConfig('isx');
		$module_info = $oModuleModel->getModuleInfoXml('isx');
		if(!$isxConfig->version || ($isxConfig->version != $module_info->version)) return true;
		if(!$oDB->isColumnExists("search", "jamo")) return true;
		if(!$oDB->isIndexExists("search","idx_jamo")) return true;
//		if(!$oDB->isIndexExists("search","idx_ip_key")) return true;
//		if(!$oDB->isIndexExists("search","idx_jamo_key")) return true;

		return false;
	}

	/**
	 * @brief 업데이트 실행
	 **/
	function moduleUpdate() {
		$oDB = &DB::getInstance();
		$oModuleController = &getController('module');;
		$oModuleModel = &getModel('module');

		$isxConfig = $oModuleModel->getModuleConfig('isx');
		$module_info = $oModuleModel->getModuleInfoXml('isx');	
		$isxConfig->version = $module_info->version;
		$oModuleController->insertModuleConfig('isx', $isxConfig);

		if(!$oDB->isColumnExists("search", "jamo")) $oDB->addColumn('search',"jamo","char",250);
		if(!$oDB->isIndexExists("search","idx_jamo")) 
			$oDB->addIndex('search', 'idx_jamo', array('jamo'));
		if(!$oDB->isIndexExists("search","idx_jamo_key"))
			$oDB->addIndex('search', 'idx_jamo_key', array('jamo','keyword'));
		if(!$oDB->isIndexExists("search","idx_ip_key"))
			$oDB->addIndex('search', 'idx_ip_key', array('ipaddress','keyword'));

		return new Object(0, 'success_updated');
	}

	/**
	 * @brief 캐시 파일 재생성
	 **/
	function recompileCache() {
	}
}
/* End of file isx.class.php */
/* Location: ./modules/isx/isx.class.php */
