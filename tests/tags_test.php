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

    /**
     * box
     */
    public function test_box()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:box><s:box_header title="Test Header">Header Contents</s:box_header>Box Body Contents<s:box_footer>Box Footer</s:box_footer></s:box>');
        $this->assertTrue(str_contains($html, '<h3>Test Header</h3>'));
        $this->assertTrue(str_contains($html, 'Box Body Contents'));
        $this->assertTrue(str_contains($html, '<div class="panel-footer">'));
    }

    /**
     * Breadcrumbs
     */
    public function test_breadcrumbs()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:breadcrumbs>');
        $this->assertTrue(str_contains($html, '<ul class="breadcrumbs">'));
    }

    /**
     * button_group
     */
    public function test_button_group()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:button_group>Test Group</s:button_group>');
        $this->assertTrue(str_contains($html, 'Test Group'));
    }

    /**
     * Callouts
     */
    public function test_callouts()
    {
        $syrus = new Syrus(null);
        $syrus->addCallout('Test error message', 'error');
        $html = $syrus->renderBlock('<s:callouts>');
        $this->assertTrue(str_contains($html, '<span><i class="fa fa-ban"></i> Test error message</span><br />'));
    }

    /**
     * checkbox
     */
    public function test_checkbox()
    {
        $syrus = new syrus(null);
        $html = $syrus->renderBlock('<s:checkbox name="is_active" value="1" is_checked="1">');
        $this->assertEquals('<input type="checkbox" name="is_active" value="1" class="form-control" checked="checked" > <span>Is Active</span><br />', $html);
    }

    /**
     * data_table
     */
    public function test_data_table()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:data_table has_search="1" total_rows="251" current_page="3"><thead><tr><th>ID</th><th has_sort="1">Username</th></tr></thead><tbody><tr><td>51</td><td>jsmith</td></tr><tr><td>31</td><td>mike</td></tr></tbody></s:data_table>');
        $this->assertTrue(str_contains($html, '<div class="formsearch">'));
        $this->assertTrue(str_contains($html, '</a> <a href="#?sort_col=username&sort_dir=asc" border="0" title="Sort Ascending username" class="asc">'));
        $this->assertTrue(str_contains($html, '<li style="display: none;"><a href="/?page=1">&laquo;</a></li><li style="display: visible;"><a href="/?page=2">&lt;</a></li><li style="display: visible;"><a href="/?page=1">1</a></li><li style="display: visible;"><a href="/?page=2">2</a></li><li class="active"><a>3</a></li><li style="display: visible;"><a href="/?page=4">4</a></li><li style="display: visible;"><a href="/?page=5">5</a></li><li style="display: visible;"><a href="/?page=6">6</a></li><li style="display: visible;"><a href="/?page=7">7</a></li><li style="display: visible;"><a href="/?page=8">8</a></li><li style="display: visible;"><a href="/?page=9">9</a></li><li style="display: visible;"><a href="/?page=10">10</a></li><li style="display: visible;"><a href="/?page=4">&gt;</a></li><li style="display: visible;"><a href="/?page=10">&raquo;</a></li>'));
    }

    /**
     * date_interval_selector
     */
    public function test_date_interval_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:date_interval_selector name="frequency" value="W2">');
        $this->assertTrue(str_contains($html, '<input type="text" name="frequency_length" class="form-control" value="2" > '));
        $this->assertTrue(str_contains($html, '<select name="frequency_period" class="form-control" style="width: 100%" >'));
    }

    /**
     * date_selector
     */
    public function test_date_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:date_selector name="start" value="2021-03-14">');
        $this->assertTrue(str_contains($html, '<select name="start_month" class="form-control" style="width: 120px; float: left;">'));
        $this->assertTrue(str_contains($html, '<option value="00" >----------</option><option value="01" >January</option><option value="02" >February</option><option value="03" selected="selected">March</option><option value="04" >April</option><option value="05" >May</option><option value="06" >June</option><option value="07" >July</option><option value="08" >August</option><option value="09" >September</option><option value="10" >October</option><option value="11" >November</option><option value="12" >December</option>'));
    }

    /**
     * form
     */
    public function test_form()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:form action="/login">');
        $this->assertEquals('<form action="/login" method="POST" enctype="application/x-www-form-urlencoded" class="form-inline" id="frmMain" data-parsley-validate="">', $html);
    }

    /**
     * ft_amount
     */
    public function test_ft_amount()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_amount name="price" value="19.95">');
        $this->assertTrue(str_contains($html, '<td><label for="price">Price:</label></td>'));
        $this->assertTrue(str_contains($html, '<input type="text" name="price" value="19.95" class="form-control" style="width: 60px; float: left;"  data-parsley-type="decimal">'));
    }

    /**
     * ft_boolean
     */
    public function test_ft_boolean()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_boolean name="is_active" value="1">');
        $this->assertTrue(str_contains($html, '<td><label for="is_active">Is Active:</label></td>'));
        $this->assertTrue(str_contains($html, '<input type="radio" name="is_active" class="form-control" value="1" checked="checked" /> <span>Yes</span> '));
    }

    /**
     * ft_date_interval_selector
     */
    public function test_ft_date_interval_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_date_interval_selector name="frequency" value="M3">');
        $this->assertTrue(str_contains($html, '<td><label for="frequency">Frequency:</label></td>'));
        $this->assertTrue(str_contains($html, '<input type="text" name="frequency_length" class="form-control" value="3" > '));
        $this->assertTrue(str_contains($html, '<option value="" >-----</option><option value="D" >Days</option><option value="W" >Weeks</option><option value="M" selected="selected">Months</option><option value="Y" >Years</option>'));
    }

    /**
     * ft_date_selector
     */
    public function test_ft_date_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_date_selector name="start" value="2021-03-14">');
        $this->assertTrue(str_contains($html, '<td><label for="start">Start:</label></td>'));
        $this->assertTrue(str_contains($html, '<select name="start_day~" class="form-control" style="width: 30px; float: left;">'));
        $this->assertTrue(str_contains($html, '<option value="00" >----------</option><option value="01" >January</option><option value="02" >February</option><option value="03" selected="selected">March</option><option value="04" >April</option><option value="05" >May</option><option value="06" >June</option><option value="07" >July</option><option value="08" >August</option><option value="09" >September</option><option value="10" >October</option><option value="11" >November</option><option value="12" >December</option>'));
    }

    /**
     * ft_phone
     */
    public function test_ft_phone()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_phone name="phone" value="+36 4165551234">');
        $this->assertTrue(str_contains($html, '<td><label for="phone">Phone:</label></td>'));
        $this->assertTrue(str_contains($html, '<option value="36" selected="selected">+ 36</option>'));
        $this->assertTrue(str_contains($html, '<input type="text" name="phone" value="4165551234" class="form-control col-lg-10"  >'));
    }

    /**
     * ft_select
     */
    public function test_ft_select()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_select name="color"><option>Red</option><option>Blue</option><option>Green</option></s:ft_select>');
        $this->assertTrue(str_contains($html, '<td><label for="color">Color:</label></td>'));
        $this->assertTrue(str_contains($html, '<select name="color" class="form-control"  >'));
        $this->assertTrue(str_contains($html, '<option>Red</option><option>Blue</option><option>Green</option>'));
    }

    /**
     * ft_seperator
     */
    public function test_ft_seperator()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_seperator label="Test Sep">');
        $this->assertTrue(str_contains($html, '<td colspan="2" align="left">Test Sep</td>'));
    }

    /**
     * ft_submit
     */
    public function test_ft_submit()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_submit value="create" label="Create User">');
        $this->assertTrue(str_contains($html, '<td colspan="2" align="center"><div class="text-left">'));
        $this->assertTrue(str_contains($html, '<button type="submit" name="submit" value="create" class="btn btn-primary btn-lg">Create User</button>'));
    }

    /**
     * ft_textarea
     */
    public function test_ft_textarea()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_textarea name="message">Hi there</s:ft_textarea>');
        $this->assertTrue(str_contains($html, '<td><label for="message">Message:</label></td>'));
        $this->assertTrue(str_contains($html, '<textarea name="message" class="form-control" id="inputmessage" style="width: 100%" >Hi there</textarea>'));
    }

    /**
     * ft_textbox
     */
    public function test_ft_textbox()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_textbox name="full_name" value="Matt Dizak">');
        $this->assertTrue(str_contains($html, '<td><label for="full_name">Full Name:</label></td>'));
        $this->assertTrue(str_contains($html, '<input type="text" name="full_name" value="Matt Dizak" class="form-control" id="inputfull_name"   ~validation~ />'));
    }

    /**
     * ft_time_selector
     */
    public function test_ft_time_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:ft_time_selector name="remind_me" value="14:45">');
        $this->assertTrue(str_contains($html, '<td><label for="remind_me">Remind Me:</label></td>'));
        $this->assertTrue(str_contains($html, '<select name="remind_me_hour" class="form-control" style="width: 60px; float: left;">'));
        $this->assertTrue(str_contains($html, '<option value="00" >-----</option><option value="00" >00</option><option value="01" >01</option><option value="02" >02</option><option value="03" >03</option><option value="04" >04</option><option value="05" >05</option><option value="06" >06</option><option value="07" >07</option><option value="08" >08</option><option value="09" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" selected="selected">14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option>'));
        $this->assertTrue(str_contains($html, '<option value="00" >-----</option><option value="00" >00</option><option value="15" >15</option><option value="30" >30</option><option value="45" selected="selected">45</option>'));
    }

    /**
     * phone
     */
    public function test_phone()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:phone name="phone" value="+36 4165551234">');
        $this->assertTrue(str_contains($html, '<option value="36" selected="selected">+ 36</option>'));
        $this->assertTrue(str_contains($html, '<input type="text" name="phone" value="4165551234" class="form-control col-lg-10"  >'));
    }

    /**
     * select
     */
    public function test_select()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:select name="color"><option>Red</option><option>Blue</option><option>Green</option></s:ft_select>');
        $this->assertTrue(str_contains($html, '<select name="color" class="form-control"  >'));
        $this->assertTrue(str_contains($html, '<option>Red</option><option>Blue</option><option>Green</option>'));
    }

    /**
     * submit
     */
    public function test_submit()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:submit value="create" label="Create User">');
        $this->assertTrue(str_contains($html, '<button type="submit" name="submit" value="create" class="btn btn-primary btn-lg">Create User</button>'));
    }

    /**
     * textarea
     */
    public function test_textarea()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:textarea name="message">Hi there</s:textarea>');
        $this->assertTrue(str_contains($html, '<textarea name="message" class="form-control" id="inputmessage" style="width: 100%" >Hi there</textarea>'));
    }

    /**
     * textbox
     */
    public function test_textbox()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:textbox name="full_name" value="Matt Dizak">');
        $this->assertTrue(str_contains($html, '<input type="text" name="full_name" value="Matt Dizak" class="form-control" id="inputfull_name"   ~validation~ />'));
    }

    /**
     * time_selector
     */
    public function test_time_selector()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:time_selector name="remind_me" value="14:45">');
        $this->assertTrue(str_contains($html, '<select name="remind_me_hour" class="form-control" style="width: 60px; float: left;">'));
        $this->assertTrue(str_contains($html, '<option value="00" >-----</option><option value="00" >00</option><option value="01" >01</option><option value="02" >02</option><option value="03" >03</option><option value="04" >04</option><option value="05" >05</option><option value="06" >06</option><option value="07" >07</option><option value="08" >08</option><option value="09" >09</option><option value="10" >10</option><option value="11" >11</option><option value="12" >12</option><option value="13" >13</option><option value="14" selected="selected">14</option><option value="15" >15</option><option value="16" >16</option><option value="17" >17</option><option value="18" >18</option><option value="19" >19</option><option value="20" >20</option><option value="21" >21</option><option value="22" >22</option><option value="23" >23</option>'));
        $this->assertTrue(str_contains($html, '<option value="00" >-----</option><option value="00" >00</option><option value="15" >15</option><option value="30" >30</option><option value="45" selected="selected">45</option>'));
    }

    /**
     * radio
     */
    public function test_radio()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:radio name="type" value="new" is_checked="1" label="New User">');
        $this->assertTrue(str_contains($html, '<input type="radio" name="type" value="new" class="form-control" ~~ checked="checked"> <span>New User</span>'));
    }

    /**
     * Tab control
     */
    public function test_tab_control()
    {
        $syrus = new Syrus(null);
        $html = $syrus->renderBlock('<s:tab_control><s:tab_page name="General"><h3>General Tab</h3></s:tab_page><s:tab_page name="Log"><h3>Log History</h3></s:tab_page></s:tab_control>');
        $this->assertTrue(str_contains($html, '<li class="active"><a href="#tab1" data-toggle="tab">General</a></li><li class=""><a href="#tab2" data-toggle="tab">Log</a></li>'));
        $this->assertTrue(str_contains($html, '<div class="tab-pane active" id="tab1">'));
    }

}


