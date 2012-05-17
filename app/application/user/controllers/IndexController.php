<?php
class User_IndexController extends Zend_Controller_Action 
{
	public function indexAction()
	{
		
	}
	
	public function passwordAction()
	{
		require APP_PATH.'/user/forms/Index/Password.php';
		$form = new Form_Index_Password();
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$csu = Class_Session_User::getInstance();
			$userCo = App_Factory::_m('RemoteUser');
			$userDoc = $userCo->find($csu->getUserId());
			if($userDoc->validatePassword($form->getValue('password_old'))) {
				$userDoc->password = $form->getValue('password');
				$userDoc->save();
				$this->_helper->flashMessenger->addMessage('密码修改成功');
				$this->_helper->redirector->gotoRoute(array(
					'action' => 'index'
				), 'user');
			} else {
				$this->_helper->flashMessenger->addMessage('原始密码输入错误');
				$this->_helper->redirector->gotoRoute(array(
					'action' => 'password'
				), 'user');
			}
		}
		
		$this->view->form = $form;
	}
}