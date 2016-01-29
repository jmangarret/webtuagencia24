<div id="availability_main">

<div id="mainLoad"> 
    <?php echo $this->loadTemplate('load'); ?> 
</div>

<div class="row">
    <div class="col-3">
        <?php
            if($this->module != null)
            {
                echo '<div id="aaws-qs-top" style="display:none">';
                    echo JModuleHelper::renderModule($this->module);
                echo '</div>';
            }
        ?>

        <div id="left-container"></div>
    </div>
    <div class="col-9">
        <div id="title-header-aws">
            Selecciona tu vuelo desde <span id="from"></span> hasta <span id="to"></span>
        </div>
        <div id="availability_header"></div>
        <div id="right-container"></div>
    </div>
</div>
</div>
