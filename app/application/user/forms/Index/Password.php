<?php
class Form_Index_Password extends Zend_Form
{
public function init()
	{
		$this->addElement('password', 'password_old', array(
			'filters' => array('StringTrim'),
			'label' => '原密码：',   
			'required'=>true,
		));

		$this->addElement('password', 'password', array(
			'filters' => array('StringTrim'),
			'validators' => array(array('StringLength',true,array(6,12))),
			'label' => '新密码：',
			'required'=>true,
		));

		$this->addElement('password', 'password_2', array(
			'filters' => array('StringTrim'),
			'label' => '确认新密码：',   
			'required'=>true,
		));
		$this->password_2->addValidator('identical', true, array( isset($_POST['password'])? $_POST['password']:'' ));

		$this->addElement('submit', 'changePassword', array(
			'ignore' => true,
			'label' => '保存',
		));
		
		$this->setAttrib('class', 'simple-form index-password-form');
		$this->setDecorators(array(
			'FormElements',
			array('HtmlTag', array('tag' => 'dl')),
			'Form'
        ));
	}
}