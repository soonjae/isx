<?php
	define('__XE__', true);

	@require_once('../../config/config.inc.php');
	$oContext = &Context::getInstance();
	$oContext->init();

	$var = Context::get('q');
	if(!$var) exit();

	$oIsxModel = &getModel("isx");
	$searchKey = $oIsxModel->getKeyfromKey($var);

	echo $searchKey;
	exit();
/* End of file isx.key_query.php */
/* Location: ./modules/isx/isx.key_query.php */
