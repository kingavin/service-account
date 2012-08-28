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
				'siteFoder' => $orgCode,
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
				try {
					$returnArr = Zend_Json::decode($returned);
				} catch(Exception $e) {
					Zend_Debug::dump($returned);
					die();
				}
				$remotesiteCo = App_Factory::_m('RemoteSite');
				$remotesiteDoc = $remotesiteCo->create();
				$remotesiteDoc->orgCode = $orgCode;
				$remotesiteDoc->siteFolder = $orgCode;
				$remotesiteDoc->label = $roDoc->orgName.'-'.$form->getValue('language');
				$remotesiteDoc->serverFullName = $serverFullName;
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
		$id = $this->getRequest()->getParam('id');
		$co = App_Factory::_m('RemoteSite');
		$doc = $co->find($id);
		
		require APP_PATH.'/admin/forms/Site/Edit.php';
		$form = new Form_Site_Edit();
		$form->populate($doc->toArray());
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$serverFullName = $doc->serverFullName;
			$putArr = array(
				'domainName' => $form->getValue('domainName')
			);
			
			$putString = Zend_Json::encode($putArr);
			
			$ch = curl_init("http://".$serverFullName."/rest/site/".$doc->remoteId);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $putString);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$returned = curl_exec($ch);
			
			if (curl_error($ch)) {
    			print curl_error($ch);
			} else {
				try {
					$returnArr = Zend_Json::decode($returned);
				} catch(Exception $e) {
					Zend_Debug::dump($returned);
					die();
				}
				$doc->domainName = $form->getValue('domainName');
				$doc->save();
			}
			curl_close($ch);
			
			$this->_helper->flashMessenger->addMessage('网站信息已更新');
    		$this->_helper->redirector->gotoSimple('index', null, null, array('orgCode' => $doc->orgCode));
		}
		
		$this->view->doc = $doc;
		$this->view->form = $form;
		
		$this->_helper->template->actionMenu(array('save'))
			->head('绑定域名:<em>'.$doc->label.'</em>');
	}
	
	public function deleteAction()
	{
		
	}
}