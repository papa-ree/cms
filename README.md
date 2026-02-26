# CMS package for manage content

[![Latest Version on Packagist](https://img.shields.io/packagist/v/papa-ree/cms.svg?style=flat-square)](https://packagist.org/packages/papa-ree/cms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/papa-ree/cms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/papa-ree/cms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/papa-ree/cms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/papa-ree/cms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/papa-ree/cms.svg?style=flat-square)](https://packagist.org/packages/papa-ree/cms)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/cms.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/cms)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require papa-ree/cms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="cms-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="cms-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="cms-views"
```

## Artisan Commands

Bale CMS provides several artisan commands to manage organizations, bales (tenants), and user assignments.

### Create Organization

Generate a new parent organization.

```bash
php artisan cms:make-organisasi --name="Nama Organisasi"
```

### Create Bale (Tenant)

Generate a new bale instance for a specific organization. This will store the tenant database credentials.

```bash
php artisan cms:make-bale \
    --organization_slug="nama-organisasi" \
    --name="Nama Bale" \
    --database="tenant_db_name" \
    --host="127.0.0.1" \
    --username="root" \
    --password="password" \
    --port=3306
```

> [!NOTE]
> Database credentials (username and password) are automatically encrypted before being stored in the database.

### Assign User to Bale

Link an existing user from the main database to a specific Bale instance using their NIP (username).

```bash
php artisan cms:make-user --bale_slug="nama-bale" --nip="12345678"
```

## Shared Components

### Option Component

The `x-core::option` component provides a standard UI for item options (edit/delete).

```blade
<x-core::option
    :item="$item->slug"       {{-- Identifier for route parameters --}}
    :itemId="$item->id"       {{-- Identifier for delete action --}}
    route="posts.edit"        {{-- Route name for edit action --}}
    :deleteButton="true"      {{-- Enable delete button --}}
/>
```

When using inside a loop (like a table), ensure the dropdown initializes safely.

### Item Actions (Livewire)

For more complex actions that need to trigger Livewire events directly, use the `ItemActions` component.

```blade
<livewire:core.shared-components.item-actions
    :editUrl="route('posts.edit', $post->slug)"
    :deleteId="$post->id"
    wire:key="item-actions-{{ $post->id }}"
    confirmMessage="Are you sure?"
/>
```

> [!IMPORTANT] > **Use `wire:key` in Loops**
> When iterating over a list of items (e.g., in a table) and using `livewire:core.shared-components.item-actions`, you **MUST** provide a unique `wire:key`.
> Failure to do so will result in Livewire losing track of components after updates (like deleting an item), causing errors like `Method [delete] not found`.

## Usage

The package is primarily used via its Livewire components for content management. Ensure you have properly configured your tenant database connections in the `bale_lists` table (or via the commands above).

## Analytics (Umami Integration)

Bale CMS integrates with Umami Analytics (self-hosted) to display website traffic statistics on the dashboard overview.

### How it works

1. `AnalyticsService` fetches data via `UmamiService` (from `bale-core`).
2. Configuration is fetched from the `tenant_analytics` table in the main database.
3. Data is cached in the active tenant's database to prevent excessive API calls.

### Display

- **Stats Cards**: Displays Total Visitors, Bounce Rate, and Avg Session Duration.
- **Traffic Overview**: A line chart showing Visitors and Page Views over the last 7 days using the `x-core::chart` component.

If the analytics service is unavailable or unconfigured, the dashboard will gracefully display an "Unavailable" state without crashing.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Papa Ree](https://github.com/papa-ree)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
