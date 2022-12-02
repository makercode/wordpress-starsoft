<?php 

require_once dirname(__file__).'/_business/database/customers.database.php';
require_once dirname(__file__).'/_business/database/products.database.php';
require_once dirname(__file__).'/_business/database/settings.database.php';
require_once dirname(__file__).'/_business/database/invoices.database.php';
require_once dirname(__file__).'/_business/api/invoices.api.php';
require_once dirname(__file__).'/_business/api/login.api.php';
require_once dirname(__file__).'/_business/guards/logged.guard.php';
require_once dirname(__file__).'/_business/guards/validated.guard.php';
