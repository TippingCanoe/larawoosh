<?php namespace TippingCanoe\Larawoosh {

	use TippingCanoe\Pushwoosh\Service as Pushwoosh;
	use TippingCanoe\Larawoosh\Repository\Device as DeviceRepository;
	//
	use TippingCanoe\LaravelMobileDevices\Model\Device as LmdDevice;
	use TippingCanoe\Pushwoosh\Device as PwDevice;
	use TippingCanoe\Pushwoosh\Message;
	use Illuminate\Database\Eloquent\Collection;


	class Service {

		/** @var \TippingCanoe\Pushwoosh\Service */
		protected $pushwoosh;

		/** @var \TippingCanoe\Larawoosh\Repository\Device */
		protected $deviceRepository;

		/**
		 * @param \TippingCanoe\Pushwoosh\Service $pushwoosh
		 * @param DeviceRepository $deviceRepository
		 */
		public function __construct(
			Pushwoosh $pushwoosh,
			DeviceRepository $deviceRepository
		) {
			$this->pushwoosh = $pushwoosh;
			$this->deviceRepository = $deviceRepository;
		}

		/**
		 * Returns the underlying Pushwoosh service instance.
		 *
		 * @return \TippingCanoe\Pushwoosh\Service
		 */
		public function getPushwoosh() {
			return $this->pushwoosh;
		}

		/**
		 * Sends a Pushwoosh message to multiple Laravel Mobile Devices
		 *
		 * @param \TippingCanoe\Pushwoosh\Message $message
		 * @param \Illuminate\Database\Eloquent\Collection $lmdDevices
		 * @return mixed
		 */
		public function pushToDevices(Message $message, Collection $lmdDevices) {
			return $this->pushwoosh->pushToDevices(
				$message,
				$this->getPwsForLmds($lmdDevices)->toArray()
			);
		}

		/**
		 * Hydrates a collection of Pushwoosh devices from a collection of Laravel Mobile Devices
		 * ...In one query!
		 *
		 * @param Collection $lmdDevices
		 * @return mixed
		 */
		public function getPwsForLmds(Collection $lmdDevices) {
			return $this->deviceRepository->getPwsForLmds($lmdDevices);
		}

		/**
		 * Hydrates a Pushwoosh device instance for a Laravel Mobile Devices instance.
		 *
		 * @param \TippingCanoe\LaravelMobileDevices\Model\Device $lmdDevice
		 * @return \TippingCanoe\Pushwoosh\Device
		 */
		public function getPwForLmd(LmdDevice $lmdDevice) {
			return $this->deviceRepository->getPwForLmd($lmdDevice);
		}

		/**
		 * Obtains the Laravel Mobile Devices instance for a Pushwoosh device instance.
		 *
		 * @param \TippingCanoe\Pushwoosh\Device $pwDevice
		 * @return \TippingCanoe\LaravelMobileDevices\Model\Device
		 */
		public function getLmdForPw(PwDevice $pwDevice) {
			return $this->deviceRepository->getLmdForPw($pwDevice);
		}

		public function storeOrUpdatePw(LmdDevice $lmdDevice, $pushwooshId) {
			return $this->deviceRepository->storeOrUpdatePw($lmdDevice, $pushwooshId);
		}

	}

}