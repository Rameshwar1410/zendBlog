<?php

namespace Blog\Plugins;
 
interface PluginInterface
{
    public function convert($content);
}