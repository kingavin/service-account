<?php
class Form_Site_Create extends Zend_Form
{
	public function init()
	{
		$this->addElement('text', 'loginName', array(
			'label' => 'Site Name'
		));
	}
}