<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use fkooman\Config\Config;
use fkooman\Json\Json;

use fkooman\OAuth\Server\PdoOAuthStorage;
use fkooman\OAuth\Server\ClientRegistration;

try {
    $config = Config::fromIniFile(dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "oauth.ini");
    $storage = new PdoOAuthStorage($config);

    if ($argc !== 2) {
            throw new Exception("ERROR: please specify file with client registration information");
    }

    $registrationFile = $argv[1];
    if (!file_exists($registrationFile) || !is_file($registrationFile) || !is_readable($registrationFile)) {
            throw new Exception("ERROR: unable to read client registration file");
    }

    $registration = Json::decode(file_get_contents($registrationFile));

    foreach ($registration as $r) {
        // first load it in ClientRegistration object to check it...
        $cr = ClientRegistration::fromArray($r);
        if (false === $storage->getClient($cr->getId())) {
            // does not exist yet, install
            echo "Adding '" . $cr->getName() . "'..." . PHP_EOL;
            $storage->addClient($cr->getClientAsArray());
        }
    }
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}
