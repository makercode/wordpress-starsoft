<?php 

	class LoggedGuard {

		public function __construct() {
		}

		public function isLogged() {
			$settingsDatabase = new SettingsDatabase;
			$isLogged = $settingsDatabase->isLogged();
			return $isLogged;
		}

	}