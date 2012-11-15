<?php
class Form_Site_Create extends Zend_Form
{
	public function init()
	{
		$this->addElement('radio', 'server', array(
			'label' => '服务器',
			'multioptions' => array(
				'ant' => 'Ant'
			)
		));
		
		$this->addElement('select', 'language', array(
			'label' => '网站语言',
			'multioptions' => array('ch' => '中文', 'en' => '英语', 'fr' => '法语')
		));
	}
}