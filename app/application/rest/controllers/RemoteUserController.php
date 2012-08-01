<?php
class Rest_RemoteUserController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$orgCode = $this->getRequest()->getHeader('Org_Code');
		$co = App_Factory::_m('RemoteUser');
		$result = array();
        
		$co->addFilter('orgCode', $orgCode)->sort('_id', -1);
		$data = $co->fetchAll(true);
		
		$result['data'] = $data;
        
        $this->_helper->json($result);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		
	}
	
	public function putAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}