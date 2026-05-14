<?php
	
	namespace Caydeesoft\MenuManager\Providers;
	
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\ServiceProvider;
	use Caydeesoft\MenuManager\Services\MenuBuilder;
	use Caydeesoft\MenuManager\View\Composers\MenuComposer;
	
	class MenuManagerServiceProvider extends ServiceProvider
		{
			public function register()
			: void
				{
					$this->mergeConfigFrom(
						__DIR__ . '/../../config/menu-manager.php',
						'menu-manager'
					);
					
					$this->app->singleton(MenuBuilder::class, function ()
						{
							return new MenuBuilder();
						});
				}
			
			public function boot()
			: void
				{
					$this->publishes([
						                 __DIR__ . '/../../config/menu-manager.php' => config_path('menu-manager.php'),
					                 ], 'menu-manager-config');
					
					$this->publishes([
						                 __DIR__ . '/../../resources/views' => resource_path('views/vendor/menu-manager'),
					                 ], 'menu-manager-views');
					
					$this->loadViewsFrom(
						__DIR__ . '/../../resources/views',
						'menu-manager'
					);
					
					view()->composer(
						[
							'menu-manager::menu',
							'menu-manager::sidebar',
							'menu-manager::top',
						],
						MenuComposer::class
					);
					
					Blade::componentNamespace(
						'Caydeesoft\\MenuManager\\View\\Components',
						'menu-manager'
					);
				}
		}
