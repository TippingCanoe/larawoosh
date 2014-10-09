<?php namespace TippingCanoe\Larawoosh\Repository {

	use TippingCanoe\Larawoosh\Repository\Device;
	//
	use Illuminate\Database\DatabaseManager;
	//
	use TippingCanoe\LaravelMobileDevices\Model\Device as LmdDevice;
	use TippingCanoe\Pushwoosh\Device as PwDevice;
	use Illuminate\Database\Eloquent\Collection;
	use Exception;


	class DbDevice implements Device {

		/** @var \Illuminate\Database\DatabaseManager|\Illuminate\Database\Connection */
		protected $db;

		/**
		 * @param DatabaseManager $db
		 */
		public function __construct(
			DatabaseManager $db
		) {
			$this->db = $db;
		}

		/**
		 * Gets a collection of Pushwoosh devices for a collection of Laravel Mobile Devices
		 * ...In one query!
		 *
		 * Note: 	If there is no corresponding Pushwoosh configuration for the device
		 * 			this method will not throw any errors.
		 *
		 * @param Collection $lmdDevices
		 * @return mixed
		 */
		public function getPwsForLmds(Collection $lmdDevices) {

			$pushwooshDevices = new Collection($this->db->table('pushwoosh_device')
				->whereIn('device_id', $lmdDevices->modelKeys())
				->get()
			);

			return $pushwooshDevices->transform(function ($pushWooshDeviceData) {
				return $this->hydratePushwooshDevice($pushWooshDeviceData);
			});

		}

		/**
		 * Hydrates an instance of the Pushwoosh PHP library's device from MySQL
		 *
		 * @param \TippingCanoe\LaravelMobileDevices\Model\Device $lmdDevice
		 * @return \TippingCanoe\Pushwoosh\Device
		 */
		public function getPwForLmd(LmdDevice $lmdDevice) {

			$pushWooshDeviceData = $this->db->table('pushwoosh_device')
				->where('device_id', $lmdDevice->getKey())
				->first()
			;

			return $this->hydratePushwooshDevice($pushWooshDeviceData);

		}

		/**
		 * Obtains the corresponding Laravel Mobile Devices device instance for a Pushwoosh device.
		 *
		 * @param \TippingCanoe\Pushwoosh\Device $pwDevice
		 * @return \TippingCanoe\LaravelMobileDevices\Model\Device
		 */
		public function getLmdForPw(PwDevice $pwDevice) {
			return LmdDevice
				::join('pushwoosh_device', 'pushwoosh_device.device_id', '=', 'device.id')
				->where('pushwoosh_device', 'token', '=', $pwDevice->id)
				->first()
			;
		}

		/**
		 * @param \TippingCanoe\LaravelMobileDevices\Model\Device $lmdDevice
		 * @param int $pushwooshId
		 * @return \TippingCanoe\Pushwoosh\Device
		 */
		public function storeOrUpdatePw(LmdDevice $lmdDevice, $pushwooshId) {

			try {
				$pwDeviceData = $this->db->table('pushwoosh_device')
					->insert([
						'device_id' => $lmdDevice->getKey(),
						'token' => $pushwooshId
					])
				;
			}
			catch(Exception $ex) {
				$pwDeviceData = $this->db->table('pushwoosh_device')
					->where('device_id', $lmdDevice->getKey())
					->update([
				 		'token' => $pushwooshId
					])
				;
			}

			return $this->hydratePushwooshDevice($pwDeviceData);

		}

		//
		//
		//

		/**
		 * Hydrates a single instance of the Pushwoosh library's Device class from a row of database results.
		 *
		 * @param $data
		 * @return PwDevice
		 */
		protected function hydratePushwooshDevice($data) {

			$device = new PwDevice();
			$device->id = $data->token;

			return $device;

		}

	}

}