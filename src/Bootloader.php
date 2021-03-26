<?php
declare(strict_types = 1);

namespace Apex\Syrus;

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Apex\Debugger\Interfaces\DebuggerInterface;
use Apex\Syrus\Interfaces\LoaderInterface;
use League\Uri\Http;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\UriInterface;


/**
 * Blootloader
 */
class Bootloader
{

    /**
     * Setup container
     */
    public static function init(Syrus $syrus, ?string $container_file = ''):void
    {

        // Use default, if none specified
        if ($container_file !== null && $container_file == '') { 
            $container_file = __DIR__ . '/../config/container.php';
        }

        // Build container
        if ($container_file !== null) { 
            Di::buildContainer($container_file);
        }

        // Set UriInterface
        if (!Di::has(UriInterface::class)) {
            Di::set(UriInterface::class, [self::class, 'getUriInterface']);
            Di::markItemAsService(UriInterface::class);
        }

        // Mark items as services
        Di::markItemAsService(CacheItemPoolInterface::class);
        Di::markItemAsService(LoaderInterface::class);

        // Set debugger
        if (class_exists(DebuggerInterface::class)) { 
            Di::markItemAsService(DebuggerInterface::class);
            $syrus->debugger = Di::get(DebuggerInterface::class);
        } else { 
        Di::set(DebuggerInterface::class, null);
    }

        // Set Syrus instance in container
        Di::set(Syrus::class, $syrus);
    }

    /**
     * Get UriInterface
     */
    public static function getUriInterface():UriInterface
    {

        // Check if from cli

        // Generate Uri
        if (php_sapi_name() == "cli") {
            $uri = Http::createFromString('http://127.0.0.1/');
        } else { 
            $uri = Http::createFromServer($_SERVER);
        }

        // Set and return
        Di::set(UriInterface::class, $uri);
        return $uri;
    }

}


