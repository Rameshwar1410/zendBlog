<?php

namespace Blog\Plugins;
 
class Pdf implements PluginInterface
{   
    public function convert($content)
    {
        echo 'pdf convert here';
    }
}