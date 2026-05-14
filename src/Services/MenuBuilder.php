<?php
	
	namespace Caydeesoft\MenuManager\Services;
	
	use Illuminate\Support\Collection;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Config;
	
	class MenuBuilder
		{
			public function build(string $menuName = 'sidebar')
			: Collection
				{
					$menu = collect($this->items($menuName));
					
					return $menu
						->sortBy('position')
						->filter(fn($item) => $this->isVisible($item))
						->map(fn($item) => $this->filterChildren($item))
						->values();
				}
			
			public function view(string $menuName = 'sidebar')
			: array
				{
					return Config::get("menu-manager.menus.{$menuName}.view", []);
				}
			
			protected function items(string $menuName)
			: array
				{
					$menu = Config::get("menu-manager.menus.{$menuName}");
					
					if (isset($menu['items']) && is_array($menu['items']))
						{
							return $menu['items'];
						}
					
					if (is_array($menu))
						{
							return $menu;
						}
					
					return Config::get('menu-manager.menu', []);
				}
			
			protected function isVisible(array $item)
			: bool
				{
					if (!Auth::check())
						{
							return false;
						}
					
					if (isset($item['permission']))
						{
							return Auth::user()->can($item['permission']);
						}
					
					if (isset($item['permissions']))
						{
							foreach ($item['permissions'] as $permission)
								{
									if (Auth::user()->can($permission))
										{
											return true;
										}
								}
							
							return false;
						}
					
					return true;
				}
			
			protected function filterChildren(array $item)
			: array
				{
					if (!isset($item['children']) || !is_array($item['children']))
						{
							return $item;
						}
					
					$item['children'] = collect($item['children'])
						->sortBy('position')
						->filter(fn($child) => $this->isVisible($child))
						->values()
						->all();
					
					return $item;
				}
		}
