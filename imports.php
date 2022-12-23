<?php 

require_once dirname(__file__).'/_business/database/customers.database.php';
require_once dirname(__file__).'/_business/database/products.database.php';
require_once dirname(__file__).'/_business/database/settings.database.php';
require_once dirname(__file__).'/_business/api/login.api.php';
require_once dirname(__file__).'/_business/guards/logged.guard.php';
require_once dirname(__file__).'/_business/guards/validated.guard.php';


require_once dirname(__file__).'/_business/api/documents/interfaces/documents.api.interface.php';
// Orders
require_once dirname(__file__).'/_business/api/documents/classes/orders.api.php';
require_once dirname(__file__).'/_business/api/documents/adapters/orders.api.adapter.php';
// Receipts
require_once dirname(__file__).'/_business/api/documents/classes/receipts.api.php';
require_once dirname(__file__).'/_business/api/documents/adapters/receipts.api.adapter.php';
// Documents
require_once dirname(__file__).'/_business/api/documents/classes/documents.api.php';
