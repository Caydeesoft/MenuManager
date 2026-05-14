<?php
	return [
		
		'menus' => [
			
			'sidebar' => [
				'view'  => [
					'wrapper' => [
						'class'      => 'sidebar-nav',
						'attributes' => [
							'role' => 'list',
						],
					],
					'header'  => [
						'class'      => 'sidebar-header',
						'attributes' => [
							'role' => 'presentation',
						],
					],
					'item'    => [
						'class'        => 'sidebar-item',
						'active_class' => 'active',
					],
					'link'    => [
						'class' => 'sidebar-link',
					],
					'icon'    => [
						'class' => 'align-middle',
					],
					'label'   => [
						'class' => 'align-middle',
					],
					'dropdown' => [
						'class'  => 'sidebar-item',
						'toggle' => [
							'class'      => 'sidebar-link collapsed',
							'attributes' => [
								'data-bs-toggle' => 'collapse',
								'aria-expanded'  => 'false',
							],
						],
						'children' => [
							'class'      => 'sidebar-dropdown list-unstyled collapse',
							'attributes' => [
								'role' => 'list',
							],
						],
					],
				],
				'items' => [
					[
						'type'     => 'header',
						'title'    => 'Main',
						'position' => 1,
					],
					[
						'title'    => 'Dashboard',
						'route'    => 'backend.admin_dashboard',
						'icon'     => 'fas fa-home',
						'position' => 2,
					],
					[
						'title'       => 'Events',
						'route'       => 'backend.event.index',
						'icon'        => 'fas fa-clock',
						'permissions' => [
							'view_event',
							'view_specific_event',
							],
							'position'    => 3,
						],
						[
							'type'       => 'dropdown',
							'title'      => 'Settings',
							'icon'       => 'fas fa-tools',
							'permission' => 'view_settings',
							'position'   => 4,
							'children'   => [
								[
									'title'      => 'General',
									'route'      => 'backend.settings.general',
									'permission' => 'view_settings',
									'position'   => 1,
								],
								[
									'title'      => 'Mail',
									'route'      => 'backend.settings.mail',
									'permission' => 'view_settings',
									'position'   => 2,
								],
							],
						],
					],
				],
		
		],
	
	];
