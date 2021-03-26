<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use PHPUnit\Framework\TestCase;


/**
 * YAML loader tests
 */
class render_test extends TestCase
{

    /**
     * Test render
     */
    public function test_render()
    {

        // Start syrus
        $syrus = new Syrus(null);
        $html = $syrus->render('index.html');

        // Check
        $this->assertTrue(str_contains($html, 'Welcome to Syrus!'));
        $this->assertTrue(str_contains($html, 'Current time'));
        $this->assertFalse(str_contains($html, '~current_time~'));

        // ENsure header / footer were added
        $this->assertTrue(str_contains($html, '<head>'));
        $this->assertTrue(str_contains($html, '</body>'));
    }

    /**
     * Render block
     */
    public function test_render_block()
    {

        // Start syrus
        $syrus = new Syrus(null);
        $syrus->assign('email', 'matt@apexpl.io');

        $html = $syrus->renderBlock('<h3>~email~</h3>');
        $this->assertEquals('<h3>matt@apexpl.io</h3>', $html);

        // Check array
        $vars = [
            'city' => 'Toronto', 
        'province' => 'Ontario', 
        'country' => 'Canada'
        ];
        $syrus->assign('loc', $vars);
        $html = $syrus->renderBlock('Welcome to ~loc.city~, ~loc.province~, ~loc.country~');
        $this->assertEquals('Welcome to Toronto, Ontario, Canada', $html);
    }



}


