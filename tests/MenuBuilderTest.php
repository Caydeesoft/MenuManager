<?php
	
	namespace Caydeesoft\MenuManager\Tests;
	
	use Caydeesoft\MenuManager\Services\MenuBuilder;
	use Illuminate\Container\Container;
	use Illuminate\Support\Facades\Facade;
	use PHPUnit\Framework\TestCase;
	
	class MenuBuilderTest extends TestCase
		{
			protected function tearDown()
			: void
				{
					Facade::clearResolvedInstances();
					Facade::setFacadeApplication(null);
					Container::setInstance(null);
					
					parent::tearDown();
				}
			
			public function test_it_hides_all_menu_items_when_user_is_not_authenticated()
			: void
				{
					$this->bootContainer(
						authenticated: false,
						permissions  : [],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'items' => [
									               [
										               'type'     => 'item',
										               'title'    => 'Dashboard',
										               'route'    => 'dashboard',
										               'position' => 1,
									               ],
								               ],
							               ],
						               ],
					);
					
					$this->assertCount(0, (new MenuBuilder())->build('sidebar'));
				}
			
			public function test_it_shows_items_matching_a_single_permission()
			: void
				{
					$this->bootContainer(
						authenticated: true,
						permissions  : ['view_dashboard'],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'items' => [
									               [
										               'type'       => 'item',
										               'title'      => 'Dashboard',
										               'route'      => 'dashboard',
										               'permission' => 'view_dashboard',
										               'position'   => 1,
									               ],
									               [
										               'type'       => 'item',
										               'title'      => 'Users',
										               'route'      => 'users.index',
										               'permission' => 'view_user',
										               'position'   => 2,
									               ],
								               ],
							               ],
						               ],
					);
					
					$items = (new MenuBuilder())->build('sidebar');
					
					$this->assertSame(['Dashboard'], $items->pluck('title')->all());
				}
			
			public function test_it_shows_items_matching_any_permission()
			: void
				{
					$this->bootContainer(
						authenticated: true,
						permissions  : ['view_specific_event'],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'items' => [
									               [
										               'type'        => 'item',
										               'title'       => 'Events',
										               'route'       => 'events.index',
										               'permissions' => [
											               'view_event',
											               'view_specific_event',
										               ],
										               'position'    => 1,
									               ],
								               ],
							               ],
						               ],
					);
					
					$items = (new MenuBuilder())->build('sidebar');
					
					$this->assertSame(['Events'], $items->pluck('title')->all());
				}
			
			public function test_it_shows_public_items_to_authenticated_users()
			: void
				{
					$this->bootContainer(
						authenticated: true,
						permissions  : [],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'items' => [
									               [
										               'type'     => 'item',
										               'title'    => 'Dashboard',
										               'route'    => 'dashboard',
										               'position' => 1,
									               ],
								               ],
							               ],
						               ],
					);
					
					$items = (new MenuBuilder())->build('sidebar');
					
					$this->assertSame(['Dashboard'], $items->pluck('title')->all());
				}
			
			public function test_it_filters_dropdown_children_by_permission()
			: void
				{
					$this->bootContainer(
						authenticated: true,
						permissions  : ['view_general_settings'],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'items' => [
									               [
										               'type'     => 'dropdown',
										               'title'    => 'Settings',
										               'route'    => 'settings.index',
										               'position' => 1,
										               'children' => [
											               [
												               'type'       => 'item',
												               'title'      => 'General',
												               'route'      => 'settings.general',
												               'permission' => 'view_general_settings',
												               'position'   => 1,
											               ],
											               [
												               'type'       => 'item',
												               'title'      => 'Mail',
												               'route'      => 'settings.mail',
												               'permission' => 'view_mail_settings',
												               'position'   => 2,
											               ],
										               ],
									               ],
								               ],
							               ],
						               ],
					);
					
					$items = (new MenuBuilder())->build('sidebar');
					
					$this->assertSame(['General'], collect($items->first()['children'])->pluck('title')->all());
				}
			
			public function test_it_returns_menu_view_configuration()
			: void
				{
					$this->bootContainer(
						authenticated: true,
						permissions  : [],
						config       : [
							               'menu-manager.menus.sidebar' => [
								               'view'  => [
									               'wrapper' => [
										               'tag'   => 'ul',
										               'class' => 'sidebar-nav',
									               ],
								               ],
								               'items' => [],
							               ],
						               ],
					);
					
					$this->assertSame(
						[
							'wrapper' => [
								'tag'   => 'ul',
								'class' => 'sidebar-nav',
							],
						],
						(new MenuBuilder())->view('sidebar'),
					);
				}
			
			protected function bootContainer(bool $authenticated, array $permissions, array $config)
			: void
				{
					$container = new Container();
					
					$container->instance('config', new FakeConfig($config));
					$container->instance('auth', new FakeAuth($authenticated, new FakeUser($permissions)));
					
					Container::setInstance($container);
					Facade::setFacadeApplication($container);
				}
		}
	
	class FakeConfig
		{
			public function __construct(protected array $items)
				{
				}
			
			public function get(string $key, mixed $default = null)
			: mixed
				{
					if (array_key_exists($key, $this->items))
						{
							return $this->items[$key];
						}
					
					$segments = explode('.', $key);
					
					for ($length = count($segments) - 1; $length > 0; $length--)
						{
							$prefix = implode('.', array_slice($segments, 0, $length));
							
							if (array_key_exists($prefix, $this->items))
								{
									return $this->getFromArray(
										$this->items[$prefix],
										array_slice($segments, $length),
										$default,
									);
								}
						}
					
					return $this->getFromArray($this->items, $segments, $default);
				}
			
			protected function getFromArray(array $items, array $segments, mixed $default)
			: mixed
				{
					$value = $this->items;
					
					if ($items !== $this->items)
						{
							$value = $items;
						}
					
					foreach ($segments as $segment)
						{
							if (!is_array($value) || !array_key_exists($segment, $value))
								{
									return $default;
								}
							
							$value = $value[$segment];
						}
					
					return $value;
				}
		}
	
	class FakeAuth
		{
			public function __construct(
				protected bool     $authenticated,
				protected FakeUser $user,
			)
				{
				}
			
			public function check()
			: bool
				{
					return $this->authenticated;
				}
			
			public function user()
			: FakeUser
				{
					return $this->user;
				}
		}
	
	class FakeUser
		{
			public function __construct(protected array $permissions)
				{
				}
			
			public function can(string $permission)
			: bool
				{
					return in_array($permission, $this->permissions, true);
				}
		}
