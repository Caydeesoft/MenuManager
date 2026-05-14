<?php

namespace Caydeesoft\MenuManager\Tests;

use Caydeesoft\MenuManager\Providers\MenuManagerServiceProvider;
use PHPUnit\Framework\TestCase;

class MenuManagerServiceProviderTest extends TestCase
{
    public function test_package_config_file_exists_at_service_provider_path(): void
    {
        $providerDirectory = dirname((new \ReflectionClass(MenuManagerServiceProvider::class))->getFileName());

        $this->assertFileExists($providerDirectory.'/../config/menu-manager.php');
    }

    public function test_package_views_directory_exists_at_service_provider_path(): void
    {
        $providerDirectory = dirname((new \ReflectionClass(MenuManagerServiceProvider::class))->getFileName());

        $this->assertDirectoryExists($providerDirectory.'/../resources/views');
    }
}
