<?php
class Admin_RemoteUserController extends Zend_Controller_Action 
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
			->head('账户管理:<em>'.$orgDoc->orgName.'</em>');
	}
	
	public function createAction()
	{
		$this->_forward('edit');
	}
	
	public function editAction()
	{
		$userId = $this->getRequest()->getParam('id');
		$orgCode = $this->getRequest()->getParam('orgCode');
		
		$ru = App_Factory::_m('RemoteUser');
		if(!is_null($userId)) {
			$ruDoc = $ru->find($userId);
			$orgCode = $ruDoc->orgCode;
		} else {
			$ruDoc = $ru->create();
		}
		if(is_null($ruDoc)) {
			throw new Exception('user not found!');
		}
		
		$orgCo = App_Factory::_m('RemoteOrganization');
		$orgDoc = $orgCo->find($orgCode);
		
		$ro = App_Factory::_m('RemoteOrganization');
		$roDoc = $ro->find($orgCode);
		if(is_null($roDoc)) {
			throw new Exception('org not found!');
		}
		
		require APP_PATH.'/admin/forms/User/Edit.php';
		$form = new Form_User_Edit();
		$form->populate($ruDoc->toArray());
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			if($ruDoc->isNewDocument()) {
				$ruDoc->loginName = $form->getValue('loginName');
				$ruDoc->orgCode = $orgCode;
				$ruDoc->password = (string)rand(111111, 999999);
				$ruDoc->save();
			} else {
				$ruDoc->loginName = $form->getValue('loginName');
				$ruDoc->save();
			}
			$this->_helper->flashMessenger->addMessage('New User Created!');
			$this->_helper->redirector->gotoSimple('index', 'remote-user', null, array('orgCode' => $orgCode));
		}
		$this->view->form = $form;
		
		if($ruDoc->isNewDocument()) {
			$this->_helper->template->actionMenu(array('save'));
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
		}
		$this->_helper->template->head('账户管理:<em>'.$orgDoc->orgName.'</em>');
	}
	
	public function deleteAction()
	{
		$userId = $this->getRequest()->getParam('id');
		$ru = App_Factory::_m('RemoteUser');
		$ruDoc = $ru->find($userId);
		$orgCode = $ruDoc->orgCode;
		
		$ruDoc->delete();
		$this->_helper->redirector->gotoSimple('index', 'remote-user', null, array('orgCode' => $orgCode));
	}
}