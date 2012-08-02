<?php
class Rest_OrgController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$currentPage = $this->getRequest()->getParam('page');
		
		$pageSize = 40;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$co = App_Factory::_m('RemoteOrganization');
		$co->setFields(array('orgName'));
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'page':
            			if(intval($value) != 0) {
            				$currentPage = $value;
            			}
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
		$co->setPage($currentPage)->setPageSize($pageSize)
			->sort('_id', -1);
		$data = $co->fetchAll(true);
		$dataSize = $co->count();
		
		$result = array();
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
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