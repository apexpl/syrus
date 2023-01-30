<?php
declare(strict_types = 1);

namespace Apex\Syrus\Loaders;

use Apex\Syrus\Syrus;
use Apex\Container\Di;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\UriInterface;
use Apex\Container\Interfaces\ApexContainerInterface;
use Apex\Syrus\Exceptions\SyrusYamlException;
use Apex\Syrus\Interfaces\AbstractLoaderInterface;


/**
 * Abstract loader class, generally should be extended for dynamic page variables including layouts support.
 */
class AbstractLoader implements AbstractLoaderInterface
{

    // Properties
    public array $yaml = [];

    #[Inject(ApexContainerInterface::class)]
    protected ApexContainerInterface $cntr;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->cntr = Di::get(ApexContainerInterface::class);
    $this->cache = Di::get(CachePoolItemInterface::class);

        $this->loadYamlConfig();
    }

    /**
     * Load configuration
     */
    protected function loadYamlConfig():void
    {

        // Check cache
        $item = $this->cache?->getItem('syrus:site_yaml');
        if ($item?->isHit() === true) { 
            $this->yaml = $item->get();
            return;
        }

        // Get YAML file
        if (!$yaml_file = $this->cntr->get('syrus.site_yml')) { 
            throw new SyrusYamlException("Unable to load site configuration, as no 'syrus.site_yml' item exists within DI container.");
        } elseif (!file_exists($yaml_file)) { 
            throw new SyrusYamlException("Unable to load site configuration, as YAML file does not exist at $yaml_file");
        } 

        // Load YAML file
        try {
            $this->yaml = Yaml::parseFile($yaml_file);
        } catch (ParseException $e) { 
            throw new SyrusYamlException("Unable to load YAML configuration file at $yaml_file.  Error: " . $e->getMessage());
        }

        // Return, if no cache
        if ($this->cache === null) { 
            return;
    }

        // Set cache item
        $item->set($this->yaml);
        if (($ttl = $this->cntr->get('syrus.cache_ttl')) !== null) { 
            $item->expiresAfter((int) $ttl);
        }
        $this->cache->save($item);
    }

    /**
     * Get theme
     */
    public function getTheme():string
    {

        // Check themes are defined
        if (!isset($this->yaml['themes'])) { 
            return 'default';
        }

        // Get uri
        $syrus = $this->cntr->get(Syrus::class);
        $file = $syrus->getTemplateFile();

        // Go through themes
        $theme = $this->yaml['themes']['default'] ?? 'default';
        foreach ($this->yaml['themes'] as $chk => $alias) { 

        if (!str_starts_with($file, $chk)) { 
            continue;
            }
            $theme = $alias;
            break;
        }

        // Return
        return $theme;
    }

    /**
     * Get page var
     */
    public function getPageVar(string $var_name):?string
    {

        // Check page var is defined
        if (!isset($this->yaml['page_vars'])) { 
            return '';
        } elseif (!isset($this->yaml['page_vars'][$var_name])) { 
            return '';
        }
        $yaml_vars = $this->yaml['page_vars'][$var_name];

        // Get template file
        $syrus = $this->cntr->get(Syrus::class);
        $file = $syrus->getTemplateFile();

        // Go through page vars
        $value = $yaml_vars['default'] ?? '';
        if (isset($yaml_vars[$file])) { 
            $value = $yaml_vars[$file];
        }

        // Return
        return $value;
    }

    /**
     * Check nocache pages
     */
    public function checkNoCachePage(string $file):bool
    {

        // Check nocache_tags exist
        if (!isset($this->yaml['nocache_pages'])) { 
            return false;
        }

        // Return
        return in_array($file, $this->yaml['nocache_pages']);
    }

    /**
     * Check nocache tags
     */
    public function checkNoCacheTag(string $tag_name):bool
    {

        // Check nocache_tags exist
        if (!isset($this->yaml['nocache_tags'])) { 
            return false;
        }

        // Return
        return in_array($tag_name, $this->yaml['nocache_tags']);
    }

}



