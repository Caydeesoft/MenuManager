# Laravel Menu Manager

Config-driven Laravel menu manager for Blade sidebar navigation, top menus,
dropdown menus, and permission-aware admin panel links.

Menu Manager lets you define Laravel navigation menus in one config file and
render them with one reusable Blade view. Configure menu items, named routes,
route parameters, HTML tags, CSS classes, Font Awesome icons, dropdowns, and
Spatie permission checks from `config/menu-manager.php`.

## Features

- Config-driven Laravel sidebar and top navigation menus.
- One reusable Blade renderer for multiple menu locations.
- Permission-aware links using Laravel authorization and Spatie permissions.
- Dropdown menu support with configurable wrapper, toggle, and child classes.
- Route parameters, icons, custom attributes, active classes, and raw HTML items.
- Publishable config and views for Laravel admin panels and dashboards.

## Install

```bash
composer require caydeesoft/menu-manager
```

Publish the config:

```bash
php artisan vendor:publish --tag=menu-manager-config
```

## Menu Location

After publishing, configure all menus here:

```text
config/menu-manager.php
```

Package source config:

```text
vendor/caydeesoft/menu-manager/src/config/menu-manager.php
```

Direct package development config:

```text
src/config/menu-manager.php
```

## Config Structure

Each menu has a `view` section and an `items` section:

```php
return [
    'menus' => [
        'sidebar' => [
            'view' => [
                'wrapper' => [
                    'tag' => 'ul',
                    'class' => 'sidebar-nav',
                    'attributes' => [
                        'role' => 'list',
                    ],
                ],
                'header' => [
                    'tag' => 'li',
                    'class' => 'sidebar-header',
                    'attributes' => [
                        'role' => 'presentation',
                    ],
                ],
                'item' => [
                    'tag' => 'li',
                    'class' => 'sidebar-item',
                    'active_class' => 'active',
                ],
                'link' => [
                    'class' => 'sidebar-link',
                ],
                'icon' => [
                    'tag' => 'i',
                    'class' => 'align-middle',
                ],
                'label' => [
                    'tag' => 'span',
                    'class' => 'align-middle',
                ],
            ],
            'items' => [
                [
                    'type' => 'header',
                    'title' => 'Main',
                    'position' => 1,
                ],
                [
                    'type' => 'item',
                    'title' => 'Dashboard',
                    'route' => 'backend.admin_dashboard',
                    'icon' => 'fas fa-home',
                    'position' => 2,
                ],
            ],
        ],
    ],
];
```

That renders:

```blade
<ul class="sidebar-nav" role="list">
    <li class="sidebar-header" role="presentation">Main</li>
    <li class="sidebar-item">
        <a href="{{ route('backend.admin_dashboard') }}" class="sidebar-link">
            <i class="fas fa-home align-middle"></i>
            <span class="align-middle">Dashboard</span>
        </a>
    </li>
</ul>
```

## Rendering

Use the generic renderer for any configured menu:

```blade
@include('menu-manager::menu', ['menu' => 'sidebar'])
```

Convenience aliases are also available:

```blade
@include('menu-manager::sidebar')
@include('menu-manager::top')
```

Both aliases use the same generic Blade renderer internally.

## Item Types

### Header

```php
[
    'type' => 'header',
    'title' => 'Revenue',
    'position' => 20,
],
```

### Item

```php
[
    'type' => 'item',
    'title' => 'Transactions',
    'route' => 'backend.transaction.index',
    'icon' => 'fas fa-dollar-sign',
    'permission' => 'view_transaction',
    'position' => 22,
],
```

### Item With Route Params

```php
[
    'type' => 'item',
    'title' => 'Activity Log',
    'route' => 'backend.logs.index',
    'params' => [
        'user' => 1,
    ],
    'icon' => 'fas fa-pen',
    'position' => 41,
],
```

### Dropdown

```php
[
    'type' => 'dropdown',
    'title' => 'Settings',
    'icon' => 'fas fa-tools',
    'permission' => 'view_settings',
    'position' => 50,
    'children' => [
        [
            'type' => 'item',
            'title' => 'General',
            'route' => 'backend.settings.general',
            'permission' => 'view_settings',
            'position' => 1,
        ],
    ],
],
```

Configure dropdown HTML in the menu `view` section:

```php
'dropdown' => [
    'tag' => 'li',
    'class' => 'sidebar-item',
    'toggle' => [
        'class' => 'sidebar-link collapsed',
        'attributes' => [
            'data-bs-toggle' => 'collapse',
            'aria-expanded' => 'false',
        ],
    ],
    'children' => [
        'tag' => 'ul',
        'class' => 'sidebar-dropdown list-unstyled collapse',
        'attributes' => [
            'role' => 'list',
        ],
    ],
],
```

### Raw HTML

Use `html` for special menu entries that do not fit the standard item shape:

```php
[
    'type' => 'html',
    'html' => '<li class="nav-item"><button type="button" class="btn btn-theme-toggle">Light</button></li>',
    'position' => 1,
],
```

## Per-Item Classes And Attributes

You can override classes on any item:

```php
[
    'type' => 'item',
    'title' => 'Dashboard',
    'route' => 'backend.admin_dashboard',
    'icon' => 'fas fa-home',
    'item_class' => 'sidebar-item custom-item',
    'link_class' => 'sidebar-link custom-link',
    'icon_class' => 'align-middle text-primary',
    'label_class' => 'align-middle fw-bold',
    'link_attributes' => [
        'data-turbo' => 'false',
    ],
    'position' => 2,
],
```

## Permissions

The menu is built only for authenticated users.

- `permission` shows the entry when the user has that permission.
- `permissions` shows the entry when the user has at least one listed permission.
- Entries without `permission` or `permissions` are visible to any authenticated user.

Dropdown children are filtered by permission too.

## Sorting

Items are sorted by `position` in ascending order.

## Views

Publish views only if you need to change the renderer itself:

```bash
php artisan vendor:publish --tag=menu-manager-views
```

Published views:

```text
resources/views/vendor/menu-manager/menu.blade.php
resources/views/vendor/menu-manager/sidebar.blade.php
resources/views/vendor/menu-manager/top.blade.php
resources/views/vendor/menu-manager/partials/items.blade.php
resources/views/vendor/menu-manager/partials/attributes.blade.php
```

## Releases

Releases are documented automatically by GitHub Actions when a semantic version
tag is pushed:

```bash
git tag v1.0.0
git push origin v1.0.0
```

The release workflow validates Composer, installs dependencies, runs PHPUnit,
and creates a GitHub release with generated release notes.

Update `CHANGELOG.md` before tagging.

## MCP Compatibility

This package is a Laravel Composer package, not a Model Context Protocol server.
It includes `MCP.md` and `llms.txt` for AI/agent-friendly project metadata, but
it does not publish an MCP `server.json` because it does not expose MCP tools,
resources, prompts, or transport.
