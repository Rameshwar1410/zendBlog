<?php

namespace Blog;
 
use Zend\ServiceManager\AbstractPluginManager;
 
class ConverterContentPluginManager extends AbstractPluginManager
{
    protected $invokableClasses = array(
        //represent invokables key
        'xls' => 'Blog\Plugins\Xls',
        'pdf' => 'Blog\Plugins\Pdf'
    );
 
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof \Blog\Plugins\PluginInterface) {
            // we're okay
            return;
        }
 
        throw new \InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Plugins\PluginInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}