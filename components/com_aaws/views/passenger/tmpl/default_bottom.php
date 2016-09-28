<?php
if(count($this->modules))
{
    echo '<div id="aaws-module">';
    $attributes = array('style' => 'xhtml');
    foreach ($this->modules as $module)
    {
        echo JModuleHelper::renderModule($module, $attributes);
    }
    echo '</div>';
}
