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

        $this->app['ggdx.toggl'] = $this->app->share(function($app){
            $config = $app->config->get('toggl', []);

            return new Toggl($config['api_key']);
        });


        $this->app->bind('GGDX\PhpToggl\Toggl', 'ggdx.toggl');
    }
}
