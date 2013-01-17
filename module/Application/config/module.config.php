<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Application\Index' => 'Application\Controller\IndexController'
		),
	),
	'router' => array(
		'routes' => array(
			'application' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller'    => 'Application\Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
			),
		),
	),
	'controller_plugins' => array(
		'invokables' => array(
			'brickConfig'		=> 'Brick\Helper\Controller\Config',
			'documentManager'	=> 'Core\Controller\Plugin\DocumentManager',
			'switchContext'		=> 'Core\Controller\Plugin\SwitchContext'
		)
	),
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			'layout/error'				=> __DIR__ . '/../view/layout/error.phtml',
			'layout/layout'				=> __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index'	=> __DIR__ . '/../view/application/index/index.phtml',
			'error/404'					=> __DIR__ . '/../view/error/404.phtml',
			'error/index'				=> __DIR__ . '/../view/error/index.phtml',
		),
	),
	'view_helpers' => array(
		'invokables' => array(
			'singleForm'			=> 'Core\View\Helper\SingleForm',
			'brickConfigForm'		=> 'Core\View\Helper\BrickConfigForm',
			'tabForm'				=> 'Core\View\Helper\TabForm',
			'bootstrapRow'			=> 'Core\View\Helper\BootstrapRow',
			'bootstrapCollection'	=> 'Core\View\Helper\BootstrapCollection',
			'selectOptions'			=> 'Core\View\Helper\SelectOptions',
		),
	),
);