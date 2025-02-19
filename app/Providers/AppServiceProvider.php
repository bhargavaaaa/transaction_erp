<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('adminorcan', function ($string) {
            return "<?php if (hasPermissionTo($string)) { ?>";
        });

        Blade::directive('endadminorcan', function () {
            return '<?php } ?>';
        });
    }
}
