<?php
class Rest_RemoteSiteController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$orgCode = $this->getRequest()->getHeader('Org_Code');
		
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
		$co->setFields(array('domainName', 'label', 'subdomainName'));
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
        
        $this->_helper->json($result);
	}
	
	public function getAction()
	{
		$siteId = $this->getRequest()->getParam('id');
		$co = App_Factory::_m('RemoteSite');
		$doc = $co->find($siteId);
		
		$result = array();
		if(is_null($doc)) {
			$orgCo = App_Factory::_m('RemoteOrganization');
			$orgDoc = $orgCo->find($siteId);
			if(is_null($orgDoc)) {
				$result['errMsg'] = 'site not found with id'. $siteId;
				$result['result'] = 'fail';
			} else {
				$data = array(
					'siteId' => $orgDoc->getId(),
					'orgCode' => $orgDoc->getId(),
					'label' => $orgDoc->orgName.' {'.'shared org folder'.'}',
					'remoteId' => 'not-set',
					'subdomainName' => 'server.apple.fucms.com'
				);
				$result['data'] = $data;
				$result['result'] = 'success';
				$doc = $co->create();
				$doc->setFromArray($data);
				$doc->save();
			}
		} else {
			$result['data'] = $doc->toArray();
			$result['result'] = 'success';
		}
        $this->_helper->json($result);
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