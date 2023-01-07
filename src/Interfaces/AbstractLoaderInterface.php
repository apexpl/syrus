<?php

namespace Apex\Syrus\Interfaces;

/**
 * Page variables loader interface
 */
interface AbstractLoaderInterface
{

    /**
     * Get theme
     */
    public function getTheme():string;


    /**
     * Get page var
     */
    public function getPageVar(string $var_name):?string;


    /**
     * Check nocache pages
     */
    public function checkNoCachePage(string $file):bool;


    /**
     * Check nocache tags
     */
    public function checkNoCacheTag(string $tag_name):bool;






}


