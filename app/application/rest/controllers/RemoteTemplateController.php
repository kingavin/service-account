<?php
class Rest_RemoteTemplateController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$orgCode = '4f7455866d5461b00b000000';
		
		$currentPage = $this->getRequest()->getParam('page');
		$sIndex = $this->getRequest()->getParam('sIndex');
		$sOrder = intval($this->getRequest()->getParam('sOrder'));
		$queryStr = $this->getRequest()->getParam('query');
		
		$pageSize = 20;
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		
		$co = App_Factory::_m('RemoteSite');
		$co->addFilter('orgCode', $orgCode);
		$co->setFields(array('domainName', 'label', 'subdomainName', 'siteFolder', 'filename'));
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
        
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
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
