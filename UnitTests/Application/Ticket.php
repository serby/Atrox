<?php

class TicketMapper {

	public function __construct() {

	}

	public function setup() {
		$dataSource = new Atrox_Core_Data_Source();
		$dataSource->addProperty(new Atrox_Core_Data_Property("Id", Atrox_Core_Data_Property::TYPE_INTEGER));
		$dataSource->addProperty(new Atrox_Core_Data_Property("Start", Atrox_Core_Data_Property::TYPE_STRING));
		$dataSource->addProperty(new Atrox_Core_Data_Property("Destination", Atrox_Core_Data_Property::TYPE_STRING));
	}

}