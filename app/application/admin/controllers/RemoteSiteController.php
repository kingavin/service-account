<?php
class Admin_RemoteSiteController extends Zend_Controller_Action 
{
	public function indexAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		$orgCo = App_Factory::_m('RemoteOrganization');
		$orgDoc = $orgCo->find($orgCode);
		
		if(is_null($orgDoc)) {
			throw new Exception('org not found with given code: '.$orgCode);
		}
		
		$this->view->orgCode = $orgCode;
		$this->view->orgDoc = $orgDoc;
		
		$this->_helper->template->actionMenu(array('create'))
			->head('站点管理:<em>'.$orgDoc->orgName.'</em>');
	}
	
	public function createAction()
	{
		$orgCode = $this->getRequest()->getParam('orgCode');
		$ro = App_Factory::_m('RemoteOrganization');
		$roDoc = $ro->find($orgCode);
		if(is_null($roDoc)) {
			throw new Exception('org not found!');
		}
		
		require APP_PATH.'/admin/forms/Site/Create.php';
		$form = new Form_Site_Create();
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$serverFullName = 'server.'.$form->getValue('server').'.fucms.com';
			
			$siteInfo = array(
				'organizationCode' => $orgCode,
				'label' => $roDoc->orgName.'-'.$form->getValue('language')
			);
			
			$siteInfoJsonString = Zend_Json::encode($siteInfo);
			
			$ch = curl_init("http://".$serverFullName."/rest/site");
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $siteInfoJsonString);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$returned = curl_exec($ch);
			
			if (curl_error($ch)) {
    			print curl_error($ch);
			} else {
				$returnArr = Zend_Json::decode($returned);
				
				$remotesiteCo = App_Factory::_m('RemoteSite');
				$remotesiteDoc = $remotesiteCo->create();
				$remotesiteDoc->orgCode = $orgCode;
				$remotesiteDoc->language = $orgCode;
				$remotesiteDoc->label = $roDoc->orgName.'-'.$form->getValue('language');
				$remotesiteDoc->setFromArray($returnArr);
				
				$remotesiteDoc->save();
			}
			curl_close($ch);
			
			$this->_helper->flashMessenger->addMessage('新网站以创建,使用域名 <a target="_blank" href="http://'.$returnArr['subdomainName'].'">'.$returnArr['subdomainName'].'</a> 访问！');
    		$this->_helper->redirector->gotoSimple('index', null, null, array('orgCode' => $orgCode));
		}
		$this->view->form = $form;
		
		$this->_helper->template->actionMenu(array('save'));
	}
	
	public function editAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}