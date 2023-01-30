<?php
declare(strict_types = 1);

namespace Apex\Syrus;

use Apex\Syrus\Syrus;
use Apex\Container\{Container, Di};
use Apex\Container\Interfaces\ApexContainerInterface;
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
    public static function init(Syrus $syrus, ?string $container_file = '', ?ApexContainerInterface $container = null):ApexContainerInterface
    {

        // Instantiate container, if null
        if ($container === null) {
            $container = new Container();
        }

        // Use default, if none specified
        if ($container_file !== null && $container_file == '') { 
            $container_file = __DIR__ . '/../config/container.php';
        }

        // Build container
        if ($container_file !== null) { 
            $container->buildContainer($container_file);
            Di::buildContainer($container_file);
        }

        // Set UriInterface
        if (!$container->has(UriInterface::class)) {
            $container->set(UriInterface::class, [self::class, 'getUriInterface']);
            $container->markItemAsService(UriInterface::class);
        }

        // Mark items as services
        $container->markItemAsService(CacheItemPoolInterface::class);
        $container->markItemAsService(LoaderInterface::class);
        Di::set(ApexContainerInterface::class, $container);

        // Set debugger
        if (class_exists(DebuggerInterface::class)) { 
            $container->markItemAsService(DebuggerInterface::class);
            $syrus->debugger = $container->get(DebuggerInterface::class);
        } else { 
            $container->set(DebuggerInterface::class, null);
        }

        // Set Syrus instance in container, and return
        $container->set(Syrus::class, $syrus);
        return $container;
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
        $cntr = Di::get(ApexContainerInterface::class);
        Di::set(UriInterface::class, $uri);
        $cntr->set(UriInterface::class, $uri);
        return $uri;
    }

}


