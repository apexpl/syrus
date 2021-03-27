<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Psr\Http\Message\UriInterface;
use PHPUnit\Framework\TestCase;

/**
 * YAML loader tests
 */
class auto_routing_test extends TestCase
{

    /**
     * Breadcrumbs
     */
    public function test_routing()
    {

        // Start Syrus
        $syrus = new Syrus();

        // Test
        $_SERVER['REQUEST_URI'] = '/tags/breadcrumbs';
        $html = $syrus->render();
        $this->assertTrue(str_contains($html, 'breadcrumbs'));

        // Again
        Di::set(UriInterface::class, null);
        $_SERVER['REQUEST_URI'] = '/tags/data_table';
        $html = $syrus->render();
        $this->assertTrue(str_contains($html, 'data_table'));
        unset($_SERVER['REQUEST_URI']);
    }

}


