<?php
declare(strict_types = 1);

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use PHPUnit\Framework\TestCase;


/**
 * YAML loader tests
 */
class control_test extends TestCase
{

    /**
     * Test render
     */
    public function test_if()
    {

        // Start Syrus
        $syrus = new Syrus();
        $syrus->assign('userid', 5);

        // Check true
        $html = $syrus->renderBlock('<s:if ~userid~ == 0>no login<s:else>yes login</s:if>');
        $this->assertEquals('yes login', $html);

        // Check false
        $syrus->assign('userid', 0);
        $html = $syrus->renderBlock('<s:if ~userid~ == 0>no login<s:else>yes login</s:if>');
        $this->assertEquals('no login', $html);

        // Check greater than
        $syrus->assign('userid', 14);
        $html = $syrus->renderBlock('<s:if ~userid~ &gt; 0>yes login<s:else>no login</s:if>');
        $this->assertEquals('yes login', $html);

        // Check true without <s:else>
        $syrus->assign('userid', 14);
        $html = $syrus->renderBlock('<s:if ~userid~ &gt; 0>yes login</s:if>');
        $this->assertEquals('yes login', $html);

        // Check false without <s:else>
        $syrus->assign('userid', 0);
        $html = $syrus->renderBlock('<s:if ~userid~ &gt; 0>yes login</s:if>');
        $this->assertEquals('', $html);
    }

    /**
     * foreach
     */
    public function test_foreach()
    {

        // Start
        $syrus = new Syrus(null);
        $syrus->addBlock('users', ['username' => 'jsmith', 'email' => 'jsmith@domain.com']);
        $syrus->addBlock('users', ['username' => 'mike', 'email' => 'mike@domain.com']);

        // Check
        $html = $syrus->renderBlock('<table><s:foreach name="users" item="u"><tr><td>~u.username~</td><td>~u.email~</td></tr></s:foreach></table>');
        $this->assertEquals('<table><tr><td>jsmith</td><td>jsmith@domain.com</td></tr><tr><td>mike</td><td>mike@domain.com</td></tr></table>', $html);

    }

}



