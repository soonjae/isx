<?php
define('__XE__', true);

@require_once('../../config/config.inc.php');
$oContext = &Context::getInstance();
$oContext->init();

$var=urldecode($_GET['q']);
if(!$var) exit();
$oIsxModel = &getModel("isx");
$searchKey = $oIsxModel->getKeyfromDocument($var);
echo $searchKey;
exit();
/* End of file isx.document_query.php */
/* Location: ./modules/isx/isx.document_query.php */
