<?php namespace TippingCanoe\Larawoosh\Handler {

	use TippingCanoe\Larawoosh\Service;


	class DeviceUpdate {

		/** @var \TippingCanoe\Larawoosh\Service */
		protected $larawoosh;

		public function __construct(
			Service $larawoosh
		) {
			$this->larawoosh = $larawoosh;
		}

		public function handle($data) {

			if(
				empty($data['attributes']['device'])
				|| empty($data['attributes']['pushwoosh_id'])
			)
				return;

			$this->larawoosh->storeOrUpdatePw($data['attributes']['device'], $data['attributes']['pushwoosh_id']);

		}

	}

}