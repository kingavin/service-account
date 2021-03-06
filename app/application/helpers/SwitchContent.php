<?php
class Helper_SwitchContent extends Zend_Controller_Action_Helper_Abstract
{
	public function gotoSimple($action, $controller = null, $module = null, array $params = array(), $returnString = false)
	{
		if($this->_actionController->getRequest()->isXmlHttpRequest() && $returnString) {
			if($returnString === true) {
				$returnString = 'success';
			}
			echo $returnString;
			exit(0);
		}
		$redirector = new Zend_Controller_Action_Helper_Redirector();
		$redirector->gotoSimple($action, $controller, $module, $params);
	}
}