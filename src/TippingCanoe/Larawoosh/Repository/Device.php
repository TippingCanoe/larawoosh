<?php namespace TippingCanoe\Larawoosh\Repository {

	use TippingCanoe\LaravelMobileDevices\Model\Device as LmdDevice;
	use TippingCanoe\Pushwoosh\Device as PwDevice;
	use Illuminate\Database\Eloquent\Collection;


	interface Device {

		/**
		 * Hydrates a collection of Pushwoosh devices from a collection of Laravel Mobile Devices
		 * ...In one query!
		 *
		 * @param Collection $lmdDevices
		 * @return mixed
		 */
		public function getPwsForLmds(Collection $lmdDevices);

		/**
		 * Hydrates an instance of the Pushwoosh PHP library's device from MySQL
		 *
		 * @param \TippingCanoe\LaravelMobileDevices\Model\Device $lmdDevice
		 * @return \TippingCanoe\Pushwoosh\Device
		 */
		public function getPwForLmd(LmdDevice $lmdDevice);

		/**
		 * Obtains the corresponding Laravel Mobile Devices device instance for a Pushwoosh device.
		 *
		 * @param \TippingCanoe\Pushwoosh\Device $pwDevice
		 * @return \TippingCanoe\LaravelMobileDevices\Model\Device
		 */
		public function getLmdForPw(PwDevice $pwDevice);

		/**
		 * @param \TippingCanoe\LaravelMobileDevices\Model\Device $lmdDevice
		 * @param int $pushwooshId
		 * @return \TippingCanoe\Pushwoosh\Device
		 */
		public function storeOrUpdatePw(LmdDevice $lmdDevice, $pushwooshId);

	}

}