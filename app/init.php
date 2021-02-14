<?php
// Simple stuff.
#error_reporting(0);
session_cache_limiter('nocache');
session_set_cookie_params(43200);
session_start();
$pageTitle = null;

// Directory constants.
$tplDir = __DIR__ . '/templates';
define('APP', __DIR__);
define('TPL', $tplDir);
unset($tplDir, $clsDir);

// Exception handler.
/**
 * @param Exception $e
 */
$logException = function (Exception $e) {
    echo "<p>An error occurred. Contact <b>extempdraw@fgccfl.net</b> for assistance if it recurs.</p>";
    $errorLog = fopen(APP . '/error.csv' ,'a');
    fwrite($errorLog, sprintf('"%s","%s","%s","%s' . "\n",
        date('Y-m-d H:i:s'), $_SERVER['REMOTE_ADDR'], $e->getFile(), $e->getMessage()));
    fclose($errorLog);
};
set_exception_handler($logException);

// Class autoloader.
/**
 * @param $klass
 * @throws Exception
 */
$loadClass = function ($klass) {
    $klassFile = APP . '/classes/' . strtolower($klass) . '.php';
    if (is_file($klassFile) || is_link($klassFile)) {
        include_once $klassFile;
    } else {
        throw new Exception("The class $klass you attempted to load was not found in $klassFile");
    }
};
spl_autoload_register($loadClass);

// Redirection shortcut.
function redirect(string $location)
{
    if (ob_get_length()) ob_end_clean();
    header("Location: $location");
    exit();
}