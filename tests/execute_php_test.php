<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Psr\Http\Message\UriInterface;
use PHPUnit\Framework\TestCase;

// Load
require_once(__DIR__ . '/files/view_social_links.php');

/**
 * YAML loader tests
 */
class execute_php_test extends TestCase
{

    /**
     * test
     */
    public function test_execute_php()
    {

        // Setup
        $view_dir = __DIR__ . '/../views/php/tags';
        //if (!is_dir($view_dir)) { mkdir($view_dir); }
        //copy(__DIR__ . '/files/view_social_links.php', "$view_dir/social_links.php");



        // Start Syrus
        $syrus = new Syrus(null);
        $html = $syrus->render('tags/social_links.html');
file_put_contents('t.txt', $html);
        // Clean up
        @unlink("$view_dir/social_links.php");
        @rmdir($view_dir);

        // Assert
        $this->assertTrue(str_contains($html, 'data_table'));
        $this->assertFalse(str_contains($html, 'social_links'));
    }

}


