<?php 

	class ChoosedGuard {

		public function __construct() {
		}

		public function isChoosed() {
			$settingsDatabase = new SettingsDatabase;
			$isChoosed = $settingsDatabase->isChoosed();
			return $isChoosed;
		}

	}