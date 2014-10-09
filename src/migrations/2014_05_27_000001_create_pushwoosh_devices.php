<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePushwooshDevices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('pushwoosh_device', function (Blueprint $table) {

			$table
				->increments('id')
				->unsigned()
			;

			$table
				->integer('device_id')
				->unsigned()
			;

			$table
				->string('token')
				->unsigned()
			;

			$table->timestamps();

			//
			//
			//

			$table->unique('token', 'U_token');
			$table->unique('device_id', 'U_device_id');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('pushwoosh_device');
	}

}