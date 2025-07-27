<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * This file is loaded by your src/Application.php bootstrap method.
 * Feel free to extend/extract parts of the bootstrap process into your own files
 * to suit your needs/preferences.
 */

date_default_timezone_set('Asia/Ho_Chi_Minh'); // GMT+7

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/*
 * Bootstrap CakePHP
 * Currently all this does is initialize the router (without loading your routes)
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/*
 * Load global functions for collections, translations, debugging etc.
 */
require CAKE . 'functions.php';

try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file to provide overrides to your configuration.
 * Notice: For security reasons app_local.php **should not** be included in your git repo.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * When debug = true the metadata cache should only last for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_translations_.duration', '+2 minutes');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check https://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/*
 * CLI/Command specific configuration.
 */
if (PHP_SAPI === 'cli') {
    // Set the fullBaseUrl to allow URLs to be generated in commands.
    // This is useful when sending email from commands.
    // Configure::write('App.fullBaseUrl', php_uname('n'));

    // Set logs to different files so they don't have permission conflicts.
    if (Configure::check('Log.debug')) {
        Configure::write('Log.debug.file', 'cli-debug');
    }
    if (Configure::check('Log.error')) {
        Configure::write('Log.error.file', 'cli-error');
    }
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 * Can be very useful for CLI/Commandline applications.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if ($httpHost) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

/*
 * Apply the loaded configuration settings to their respective systems.
 * This will also remove the loaded config data from memory.
 */
Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Setup detectors for mobile and tablet.
 * If you don't use these checks you can safely remove this code
 * and the mobiledetect package from composer.json.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

// Định nghĩa trạng thái phê duyệt
define('STATUS_OPTIONS', [
    0 => 'Chưa phê duyệt',
    1 => 'Đã phê duyệt'
]);

Configure::write('ColumnTypeMapping', [
    'varchar' => 'Chuỗi ngắn',
    'text' => 'Chuỗi dài',
    'int' => 'Số nguyên',
    'float' => 'Số thực',
    'date' => 'Ngày',
    'datetime' => 'Ngày giờ',
    'file' => 'Tệp đính kèm',
    'tinyint' => 'Có/Không'
]);

Configure::write('TinyintBooleanMapping', [
    0 => 'Không',
    1 => 'Có',
]);

// Ánh xạ ngược (nếu cần)
//Configure::write('ReverseColumnTypeMapping', array_flip(Configure::read('ColumnTypeMapping')));