<?php

namespace Kargo\WompiSV\Providers;

use Illuminate\Support\ServiceProvider;

class WompiSVServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/wompisv.php', 'wompisv');

        $this->app->bind('payment.wompisv', fn () => new \Kargo\WompiSV\PaymentMethod\WompiSV);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes([
            __DIR__ . '/../../config/wompisv.php' => config_path('wompisv.php'),
        ], 'wompisv-config');

        $this->app['config']->set('payment_methods.wompisv', [
            'code'        => 'wompisv',
            'title'       => 'Wompi (El Salvador) 3DS',
            'description' => 'Tarjetas con 3-D Secure via Wompi SV',
            'class'       => \Kargo\WompiSV\PaymentMethod\WompiSV::class,
            'active'      => (bool) config('wompisv.enabled'),
            'sandbox'     => app()->environment('local') || config('wompisv.debug'),
            'sort'        => 5,
        ]);
    }
}