<?php
class Form_Site_Edit extends Zend_Form
{
	public function init()
	{
		$this->addElement('text', 'domainName', array(
			'label' => '域名'
		));
	}
}