# LaravelToggl
## Getting started
### Composer
`composer require ggdx/laravel-toggl`

### Laravel
Add the provider:
```php
'providers' => [
    GGDX\LaravelToggl\TogglServiceProvider::class,
]
```
Add the facade:
```php
'aliases' => [
    'Toggl' => GGDX\LaravelToggl\TogglFacade::class,
]
```
Generate the config file:
```php
php artisan vendor:publish
```
If using version control, add `TOGGL_KEY=your_key` to the .env or add your key directly to config/toggl.php
***
## More info
Wiki will be built as and when time allows however GGDX\LaravelToggl\Toggl is fully annotated with the available methods.
