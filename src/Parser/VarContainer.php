<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;

use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Interfaces\LoaderInterface;
use Apex\Debugger\Interfaces\DebuggerInterface;
use Apex\Syrus\Exceptions\SyrusInvalidArgumentException;


/**
 * Handles assignment of all variables, blocks, and callouts.
 */
class VarContainer
{

    // Properties
    private array $vars = [];
    private array $blocks = [];
    private array $callouts = [];
    private string $callout_type = 'success';
    protected string $template_file = '';
    protected bool $file_locked = false;
    protected string $theme = '';
    protected bool $has_errors = false;
    protected $rpc_enabled = true;

    // Interfaces
    public ApexContainerInterface $cntr;
    public ?DebuggerInterface $debugger = null;

    /**
     * Assign a variable which will be merged when template is rendered.
     */
    public function assign(string $name, mixed $value):void
    { 

        // Format value, as necessary
        if (is_array($value)) { 
            $value = array_map(
                function($v) { return (string) $v; }, 
                array_filter($value, function($v) { return is_scalar($v); }
            ));

        } elseif (is_scalar($value)) { 
            $value = (string) $value;
        } else { 
            return;
        }

        // Add to vars
        if ($name == '' && is_array($value)) { 
            foreach ($value as $k => $v) { 
                $this->vars[$k] = $v;
                $this->debugger?->addItem('syrus.vars', [$k, $v]);
            }

        } else { 
            $this->vars[$name] = $value;
            $this->debugger?->addItem('syrus.vars', [$k, $v]);
        }
    }

    /**
     * Add block that can be used within foreach template tags.
     */
    public function addBlock(string $name, array $block):void
    {

        // Start block, if needed
        if (!isset($this->blocks[$name])) { 
            $this->blocks[$name] = [];
        }

        // Add block
        $this->blocks[$name][] = array_filter($block, function ($val) { 
            return is_scalar($val) ? true : false;
        });

    }

    /**
     * Add callout message displayed at the top of the browser.
     */
    public function addCallout(string $message, string $type = 'success'):void
    {

        // Ensure valid type
        if (!in_array($type, ['success', 'error', 'warning', 'info'])) { 
            throw new SyrusInvalidArgumentException("Invalid callout message type, $type.  Supported values are:  success, error, warning, info");
        }

        // Purge any success messages if we have an error
        if ($type != 'success' && $this->callout_type == 'success') { 
            $this->callouts = [];
        }

        // Add
        $this->callouts[] = $message;
        $this->callout_type = $type;
        if ($type == 'error') { 
            $this->has_errors = true;
        }
    }

    /**
     * Get all variables, used by parser.
     */
    public function getVars():array
    {
        return $this->vars;
    }

    /**
     * Get all blocks for use within foreach tags.
     */
    public function getBlocks():array
    {
        return $this->blocks;
    }

    /**
     * Get a single block used within foreach tags.
     */
    public function getBlock(string $name):array
    {
        return $this->blocks[$name] ?? [];
    }

    /**
     * Get all call messages.
     */
    public function getCallouts():array
    {
        return $this->callouts;
    }

    /**
     * Get the type of callout message (success, error, warning)
     */
    public function getCalloutType():string
    {
        return $this->callout_type;
    }

    /**
     * Purge all previously added variables and blocks.
     */
    public function purge():void
    {
        $this->vars = [];
        $this->blocks = [];
        $this->callouts = [];
        $this->callout_type = 'success';
    }

    /**
     * Gather all variables and blocks, used by parser.
     */
    public function gatherVars():array
    {

        // Load base variables
        $this->vars['syrus'] = $this->loadBaseVariables();

        // Set vars
        $vars = [
            'vars' => $this->vars, 
            'blocks' => $this->blocks, 
            'callouts' => $this->callouts, 
            'callout_type' => $this->callout_type
        ];

        // Return
        return $vars;
    }

    /**
     * Load base variables that are supported by all templates.
     */
    private function loadBaseVariables():array
    {

        // Set vars
        $vars = [
            'theme' => $this->theme, 
            'theme_uri' => rtrim($this->cntr->get('syrus.theme_uri'), '/') . '/' . $this->theme, 
            'current_year' => date('Y')
        ];

        // Return
        return $vars;
    }

    /**
     * Set the template file to display upon rendering a template.
     */
    public function setTemplateFile(string $file, bool $is_locked = false):bool
    {

        // Check if locked
        if ($this->file_locked === true) { 
            return false;
        }

        // Set variables
        $this->template_file = ltrim($file, '/');
        $this->file_locked = $is_locked;

        // Return
        return true;
    }

    /**
     * Set theme to utilize upon rendering a template.
     */
    public function setTheme(string $theme):void
    {
        $this->theme = $theme;
    }

    /**
     * Get the template file assigned.
     */
    public function getTemplateFile():string
    {
        return $this->template_file;
    }

    /**
     * Get or determine the theme to utilize based on URI being viewed.
     */
    public function getTheme():string
    {

        // Get theme from loader, if needed
        if ($this->theme == '') { 
            $loader = $this->cntr->make(LoaderInterface::class);
            $this->theme = $loader->getTheme();
        }

        // Return
        return $this->theme;
    }

    /**
     * Clear all entries within an existing block.
     */
    public function clearBlock(string $name):void
    {
        $this->blocks[$name] = [];
    }

    /**
     * Check if any 'error' callout messages have been added.
     */
    public function hasErrors():bool
    {
        return $this->has_errors;
    }

    /**
     * Set rpc enabled
     */
    public function setRpcEnabled(bool $enabled):void
    {
        $this->rpc_enabled = $enabled;
    }

    /**
     * Get rpc enabled
     */
    public function getRpcEnabled():bool
    {
        return $this->rpc_enabled;
    }

}


