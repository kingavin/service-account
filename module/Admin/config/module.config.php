<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'index'			=> 'Admin\Controller\IndexController'
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'admin' => __DIR__ . '/../view',
		),
		'template_map' => array(
			'layout/head'			=> __DIR__ . '/../view/layout/head.phtml',
			'layout/admin-toolbar'	=> __DIR__ . '/../view/layout/toolbar.phtml',
		),
	),
	'router' => array(
		'routes' => array(
			'admin' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/admin',
					'defaults' => array(
						'controller' => 'index',
						'action' => 'index'
					)
				),
				'may_terminate' => true,
				'child_routes' => array(
					'childroutes' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '[/:controller][/:action]',
							'constraints' => array(
								'controller' => '[a-z-.]*',
								'action' => '[a-z-]*'
							),
						),
						'child_routes'  => array(
							'wildcard' => array(
								'type' => 'wildcard',
							),
						),
					)
				)
			),
		),
	),
);