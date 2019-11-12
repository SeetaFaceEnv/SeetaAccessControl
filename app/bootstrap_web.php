<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');


try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include general services
     */
    require APP_PATH . '/config/services.php';

    /**
     * Include web environment specific services
     */
    require APP_PATH . '/config/services_web.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new Application($di);

    /**
     * Register application modules
     */
    $application->registerModules([
        'backend' => ['className' => 'SeetaAiBuildingCommunity\Modules\Backend\Module'],
    ]);

    /**
     * Set time zone
     */
    date_default_timezone_set('Asia/Shanghai');

    /**
     * Include routes
     */
    require APP_PATH . '/config/routes.php';

    /**
     * Read defines
     */
    require_once APP_PATH . '/common/defines.php';
    require_once APP_PATH . '/common/errorCodes.php';

    /**
     * Read language file
     */
    require_once APP_PATH . '/common/languages/cn/errors.php';

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
