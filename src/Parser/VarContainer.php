<?php
declare(strict_types = 1);

namespace Apex\Syrus\Parser;

use Apex\Container\Di;
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
    public ?DebuggerInterface $debugger = null;

    /**
     * Assign merge variable
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
     * Add block
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
     * Add callout message
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
    }

    /**
     * Get variables
     */
    public function getVars():array
    {
        return $this->vars;
    }

    /**
     * Get blocks
     */
    public function getBlocks():array
    {
        return $this->blocks;
    }

    /**
     * Get single block
     */
    public function getBlock(string $name):array
    {
        return $this->blocks[$name] ?? [];
    }

    /**
     * Get callouts
     */
    public function getCallouts():array
    {
        return $this->callouts;
    }

    /**
     * Get callout type
     */
    public function getCalloutType():string
    {
        return $this->callout_type;
    }

    /**
     * Purge
     */
    public function purge():void
    {
        $this->vars = [];
        $this->blocks = [];
        $this->callouts = [];
        $this->callout_type = 'success';
    }

    /**
     * Gather all variables for the parser
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
     * Load base Syrus variables
     */
    private function loadBaseVariables():array
    {

        // Set vars
        $vars = [
            'theme' => $this->theme, 
            'theme_uri' => rtrim(Di::get('syrus.theme_uri'), '/') . '/' . $this->theme, 
            'current_year' => date('Y')
        ];

        // Return
        return $vars;
    }

    /**
     * Set template file
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
     * Set theme
     */
    public function setTheme(string $theme):void
    {
        $this->theme = $theme;
    }

    /**
     * Get template file
     */
    public function getTemplateFile():string
    {
        return $this->template_file;
    }

    /**
     * Get theme
     */
    public function getTheme():string
    {
        return $this->theme;
    }

}


