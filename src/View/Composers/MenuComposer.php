<?php
	
	namespace Caydeesoft\MenuManager\View\Composers;
	
	use Caydeesoft\MenuManager\Services\MenuBuilder;
	use Illuminate\View\View;
	
	class MenuComposer
		{
			public function __construct(protected MenuBuilder $menuBuilder)
				{
				}
			
			public function compose(View $view)
			: void
				{
					$menuName = $view->getData()['menu'] ?? match ($view->getName())
						{
						'menu-manager::top' => 'top',
						default             => 'sidebar',
						};
					
					$view->with([
						            'menus'    => $this->menuBuilder->build($menuName),
						            'menuView' => $this->menuBuilder->view($menuName),
						            'menuName' => $menuName,
					            ]);
				}
		}
