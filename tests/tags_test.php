<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use PHPUnit\Framework\TestCase;


/**
 * YAML loader tests
 */
class tags_test extends TestCase
{

    /**
     * amount
     */
    public function test_amount()
    {

        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:amount name="price" value="19.95">');
        $this->assertTrue(str_contains($html, '<input type="text" name="price" value="19.95" class="form-control" style="width: 60px; float: left;"  data-parsley-type="decimal">'));
    }

    /**
     * boolean
     */
    public function test_boolean()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:boolean name="is_active" value="1">');
        $this->assertTrue(str_contains($html, '<input type="radio" name="is_active" class="form-control" value="1" checked="checked" /> <span>Yes</span> '));
    }

}


