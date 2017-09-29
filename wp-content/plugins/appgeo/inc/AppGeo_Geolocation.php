<?php

class AppGeo_Geolocation {

	public $latitude;
	public $longitude;
	public $place;
	public $place_id;
	public $address;
	public $id;

	public function __construct( $id ) {
		$this->add_id( $id );
	}

	public function add_id( $value ) {
		$this->id = (int)$value;
	}

	public function add_latitude( $value ) {
		$this->latitude = $value;
	}

	public function add_longitude( $value ) {
		$this->longitude = $value;
	}

	public function add_place( $value ) {
		$this->place = $value;
	}

	public function add_place_id( $value ) {
		$this->place_id = $value;
	}

	public function add_address( $value ) {
		$this->address = $value;
	}
}