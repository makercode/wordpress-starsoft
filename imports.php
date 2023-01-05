<?php 


require_once dirname(__file__).'/_business/database/customers.database.php';
require_once dirname(__file__).'/_business/database/products.database.php';
require_once dirname(__file__).'/_business/database/settings.database.php';
require_once dirname(__file__).'/_business/api/login.api.php';
require_once dirname(__file__).'/_business/guards/logged.guard.php';
require_once dirname(__file__).'/_business/guards/validated.guard.php';
require_once dirname(__file__).'/_business/guards/choosed.guard.php';

require_once dirname(__file__).'/_business/api/documents/interfaces/documents.api.interface.php';

require_once dirname(__file__).'/_business/api/documents/classes/documents.api.php';
require_once dirname(__file__).'/_business/api/documents/classes/orders.api.php';
require_once dirname(__file__).'/_business/api/documents/classes/receipts.api.php';

require_once dirname(__file__).'/_business/api/documents/adapters/orders.api.adapter.php';
require_once dirname(__file__).'/_business/api/documents/adapters/receipts.api.adapter.php';



require_once dirname(__file__).'/_business/database/documents/interfaces/documents.database.interface.php';

require_once dirname(__file__).'/_business/database/documents/classes/documents.database.php';
require_once dirname(__file__).'/_business/database/documents/classes/orders.database.php';
require_once dirname(__file__).'/_business/database/documents/classes/receipts.database.php';

require_once dirname(__file__).'/_business/database/documents/adapters/orders.database.adapter.php';
require_once dirname(__file__).'/_business/database/documents/adapters/receipts.database.adapter.php';


require_once dirname(__file__).'/_business/globals/settings.global.php';
