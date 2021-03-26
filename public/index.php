<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

// Init syrus
$syrus = new Syrus();

// Load vars.php file, if exists
if (file_exists(__DIR__ . '/vars.php')) { 
    require_once(__DIR__ . '/vars.php');
    if (function_exists('loadSyrusVars')) { 
        loadSyrusVars($syrus);
    }
}

// Render page
echo $syrus->render();


