<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Syrus\Loaders\{ExampleLoader, TestLoader};
use PHPUnit\Framework\TestCase;

// Load files
require_once(__DIR__ . '/files/TestLoader.php');


/**
 * YAML loader tests
 */
class content_loader_test extends TestCase
{

    /**
     * Breadcrumbs
     */
    public function test_breadcrumbs()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set(LoaderInterface::class, Di::make(TestLoader::class));

        // Render html
        $html = $syrus->renderBlock('<s:breadcrumbs>');
        $this->assertTrue(str_contains($html, '<li><a href="/syrus">Syrus</a> &gt; </li><li><a href="/is">Is</a> &gt; </li><li>Great</li>'));
    }

    /**
     * Social links
     */
    public function test_social_links()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set(LoaderInterface::class, Di::make(TestLoader::class));

        // Render
        $html = $syrus->renderBlock('<s:social_links>');
        $this->assertEquals('<a href="https://twitter.com/syrus" target="_blank" title="Visit Twitter"><span class="fa fa-twitter"></span></a> <a href="https://facebook.com/syrus" target="_blank" title="Visit Facebook"><span class="fa fa-facebook"></span></a> <a href="https://youtube.com/syrus" target="_blank" title="Visit Youtube"><span class="fa fa-youtube"></span></a> ', $html);
    }

    /**
     * placeholder
     */
    public function test_placeholder()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set(LoaderInterface::class, Di::make(TestLoader::class));

        // Render
        $html = $syrus->renderBlock('<s:placeholder alias="this is fun">');
        $this->assertEquals('this is fun', $html);
    }

    /**
     * Page vars
     */
    public function test_page_vars()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set('syrus.site_yml', __DIR__ . '/files/site.yml');
        Di::set(LoaderInterface::class, Di::make(TestLoader::class));

        // Render
        $html = $syrus->render('tags/breadcrumbs.html');
        $loader = Di::get(LoaderInterface::class);
        $this->assertEquals('Hello', $loader->getPageVar('greetings'));

        // Render
        $html = $syrus->render('tags/data_table.html');
        $loader = Di::get(LoaderInterface::class);
        $this->assertEquals('Hi There', $loader->getPageVar('greetings'));

        // Render
        $html = $syrus->render('tags/callouts.html');
        $loader = Di::get(LoaderInterface::class);
        $this->assertEquals('Good Day', $loader->getPageVar('greetings'));

        // Reset
        Di::set(LoaderInterface::class, Di::make(ExampleLoader::class));
    }






}

