<?php 

	class ValidatedGuard {

		public function __construct() {
		}

		public function isValidated() {
			$settingsDatabase = new SettingsDatabase;
			$isValidated = $settingsDatabase->isValidated();
			return $isValidated;
		}

	}