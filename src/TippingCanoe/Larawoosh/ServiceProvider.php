<?php namespace TippingCanoe\Larawoosh {

	use Illuminate\Support\ServiceProvider as Base;
	//
	use Illuminate\Foundation\Application;
	use TippingCanoe\Pushwoosh\Service as PushwooshService;


	class ServiceProvider extends Base {

		/**
		 * Indicates if loading of the provider is deferred.
		 *
		 * @var bool
		 */
		protected $defer = false;

		/**
		 * Bootstrap the application events.
		 *
		 * @return void
		 */
		public function boot() {
			$this->package('tippingcanoe/larawoosh');
		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register() {

			$this->app['events']->listen('device.stored', 'TippingCanoe\Larawoosh\Handler\DeviceStore');
			$this->app['events']->listen('device.updated', 'TippingCanoe\Larawoosh\Handler\DeviceUpdate');

			$this->app->bind('TippingCanoe\Larawoosh\Repository\Device', 'TippingCanoe\Larawoosh\Repository\DbDevice');

			// Configure the PHP Pushwoosh library from Laravel 4!
			$this->app->singleton('TippingCanoe\Pushwoosh\Service', function (Application $app) {

				$pushwoosh = new PushwooshService();

				$pushwoosh->setAccessToken($app['config']->get('larawoosh::access_token'));
				$pushwoosh->setApplicationCode($app['config']->get('larawoosh::application_code'));

				return $pushwoosh;

			});

		}

		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides() {
			return [];
		}

	}

}