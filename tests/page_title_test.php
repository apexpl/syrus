<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Apex\Syrus\Loaders\ExampleLoader;
use Apex\Syrus\Interfaces\LoaderInterface;
use PHPUnit\Framework\TestCase;


/**
 * YAML loader tests
 */
class page_title_test extends TestCase
{

    /**
     * With auto-extract
     */
    public function test_with_auto_extract()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set('syrus.auto_extract_title', true);
        Di::set('syrus.page_title', null);

        // Render
        $html = $syrus->render('tags/breadcrumbs.html');
        $this->assertEquals('&lt;s:breadcrumbs&gt;', di::get('syrus.page_title'));
    }

    /**
     * Without auto-extract
     */
    public function test_without_auto_extract()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set('syrus.auto_extract_title', false);
        Di::set('syrus.site_yml', __DIR__ . '/../config/site.yml');
        Di::set(LoaderInterface::class, Di::make(ExampleLoader::class));
        Di::set('syrus.page_title', null);

        // Render
        $html = $syrus->render('tags/breadcrumbs.html');
        $this->assertEquals('Syrus', di::get('syrus.page_title'));
    }

    /**
     * YAML file
     */
    public function test_yaml_file()
    {

        // Start Syrus
        $syrus = new Syrus(null);
        Di::set('syrus.site_yml', __DIR__ . '/files/site.yml');
        Di::set(LoaderInterface::class, null);
        Di::set('syrus.auto_extract_title', false);
        Di::set('syrus.page_title', null);

        // Render
        $html = $syrus->render('tags/breadcrumbs.html');
        $this->assertEquals('Testing Page Title', di::get('syrus.page_title'));
    }


}


