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
		$sIndex = $this->getRequest()->getParam('sIndex');
		$sOrder = intval($this->getRequest()->getParam('sOrder'));
		$queryStr = $this->getRequest()->getParam('query');
		
		$pageSize = 20;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$co = App_Factory::_m('RemoteOrganization');
		$co->setFields(array('orgName'));
        $co->setPage($currentPage)->setPageSize($pageSize)
			->sort($sIndex, $sOrder);
			
		if($queryStr != 'none') {
			$queryArr = explode('-', $queryStr);
			foreach($queryArr as $qItem) {
				list($key, $val) = explode(':', $qItem);
				switch($key) {
					case '_id':
						$co->addFilter('_id', new MongoID($val));
						break;
					case 'orgName':
						$co->addFilter($key, new MongoRegex("/".$val."/"));
						break;
				}
			}
		}
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