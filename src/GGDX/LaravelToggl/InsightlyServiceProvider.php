<?php namespace GGDX\LaravelToggl;

use Illuminate\Support\ServiceProvider;

class TogglServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/toggl.php' => config_path('toggl.php'),
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ggdx.toggl', function ($app) {
            $config = $app->config->get('toggl', []);

            return new Toggl($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
