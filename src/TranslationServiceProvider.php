<?php

namespace Stevebauman\Translation;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Set up the blade directive.
     */
    public function boot()
    {
        Blade::directive('t', function($args) {
            return "<?php echo App::make('translation')->translate{$args}; ?>";
        });
    }

    /**
     * Register the service provider.
     *
     * @method void package(string $package, string $namespace, string $path)
     */
    public function register()
    {
        // Allow configuration to be publishable
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('translation.php'),
        ], 'config');

        // Allow migrations to be publishable
        $this->publishes([
            __DIR__.'/Migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        // Bind translation to the IoC
        $this->app->bind('translation', function($app) {
            return new Translation($app);
        });

        // Include the helpers file for global `_t()` function
        include __DIR__.'/helpers.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['translation'];
    }
}
