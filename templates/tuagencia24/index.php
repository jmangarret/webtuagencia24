<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
include_once (dirname(__FILE__).DS.'/scripts/layout.php');

?>
<!doctype html>
<html>
<head>
    <jdoc:include type="head" />  

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-33354572-1', 'auto');
  ga('send', 'pageview');

</script>
  
</head>
<?php
//MICOD:
//Sistema de seguridad para bloquear el clic derecho del mouse y la tecla F12 del teclado
$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$buscar = "soto2";
$confirmar_soto = strpos($url, $buscar);

if ($confirmar_soto != false) {
  ?>
  <body oncontextmenu="return false" onkeydown="return false">
  <?php
}else{
  ?>
  <body>
  <?php
}
?>
  
    
  <!--[if lt IE 9]>
        <div id="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div>
    <![endif]-->

  <?php if ($this->countModules('menu-tools')) : ?>
    <section id="box-menu-tools">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div id="menu-tools">
              <jdoc:include type="modules" name="menu-tools" style="none" />
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <div id="box-page">
    <div class="container">
      
      <header id="box-header">
        
        <?php if ($this->countModules('logo') || $this->countModules('info')) : ?>
          <div class="row border-dashed">
            
            <?php if ($this->countModules('logo')) : ?>
              <div class="<?php echo $col_logo; ?>">
                <div id="logo">
                  <jdoc:include type="modules" name="logo" style="none" />
                </div>
              </div>
            <?php endif; ?>

            <?php if ($this->countModules('info')) : ?>
              <div class="<?php echo $col_info; ?>">
                <div id="info">
                  <jdoc:include type="modules" name="info" style="none" />
                </div>
              </div>
            <?php endif; ?>

          </div>
        <?php endif; ?>

        <?php if ($this->countModules('menu')) : ?>
          <div class="row">
            <div class="col-12 full">
              <div id="menu">
                <jdoc:include type="modules" name="menu" style="none" />
              </div>
            </div>
          </div>
        <?php endif; ?>
      </header>

      <jdoc:include type="message" />

      <?php if ($this->countModules('top-a')) : ?>
        <section id="box-top-a">
          <div class="row">
            <div class="col-12">
              <div id="top-a">
                <jdoc:include type="modules" name="top-a" style="xhtml" />
              </div>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($this->countModules('top-b') || $this->countModules('top-c')) : ?>
        <section id="box-top-bc">
          <div class="row">
            
            <?php if ($this->countModules('top-b')) : ?>
              <div class="<?php echo $col_top_b; ?>">
                <div id="top-b">
                  <jdoc:include type="modules" name="top-b" style="xhtml" />
                </div>
              </div>
            <?php endif; ?>

            <?php if ($this->countModules('top-c')) : ?>
              <div class="<?php echo $col_top_c; ?>">
                <div id="top-c">
                  <jdoc:include type="modules" name="top-c" style="xhtml" />
                </div>
              </div>
            <?php endif; ?>

          </div>
        </section>
      <?php endif; ?>

      <section id="box-main">
        <div class="row">
          
          <?php if ($this->countModules('side-a')) : ?>
            <div class="<?php echo $col_side_a; ?>">
              <div id="side-a">
                <jdoc:include type="modules" name="side-a" style="xhtml" />
              </div>
            </div>
          <?php endif; ?>

          <div class="<?php echo $col_main; ?>">
            <div id="main">
              
              <?php if ($this->countModules('main-top')) : ?>
                <div class="row">
                  <div class="col-12 full">
                    <div id="main-top">
                      <jdoc:include type="modules" name="main-top" style="xhtml" />
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="row">
                <div class="col-12 full">
                  <div id="main-content">
                    <jdoc:include type="component" />
                  </div>
                </div>
              </div>
              
              <?php if ($this->countModules('main-bottom')) : ?>
                <div class="row">
                  <div class="col-12 full">
                    <div id="main-bottom">
                      <jdoc:include type="modules" name="main-bottom" style="xhtml" />
                    </div>
                  </div>
                </div>
              <?php endif; ?>

            </div>
          </div>

          <?php if ($this->countModules('side-b')) : ?>
            <div class="<?php echo $col_side_b; ?>">
              <div id="side-b">
                <jdoc:include type="modules" name="side-b" style="xhtml" />
              </div>
            </div>
          <?php endif; ?>

        </div>
      </section>

      <?php if ($this->countModules('bottom-b') || $this->countModules('bottom-c')) : ?>
        <section id="box-bottom-bc">
          <div class="row">
            
            <?php if ($this->countModules('bottom-b')) : ?>
              <div class="<?php echo $col_bottom_b; ?>">
                <div id="bottom-b">
                  <jdoc:include type="modules" name="bottom-b" style="xhtml" />
                </div>
              </div>
            <?php endif; ?>

            <?php if ($this->countModules('bottom-c')) : ?>
              <div class="<?php echo $col_bottom_c; ?>">
                <div id="bottom-c">
                  <jdoc:include type="modules" name="bottom-c" style="xhtml" />
                </div>
              </div>
            <?php endif; ?>

          </div>
        </section>
      <?php endif; ?>

      <?php if ($this->countModules('bottom-a')) : ?>
        <section id="box-bottom-a">
          <div class="row">
            <div class="col-12">
              <div id="bottom-a">
                <jdoc:include type="modules" name="bottom-a" style="xhtml" />
              </div>
            </div>
          </div>
        </section>
      <?php endif; ?>

    </div>
  </div>

  <?php if ($this->countModules('menu-footer') || $this->countModules('info-footer') || $this->countModules('footer')) : ?>
    <footer id="box-footer">
      <div class="container">

        <?php if ($this->countModules('menu-footer') || $this->countModules('info-footer')) : ?>
          <div class="row">
            
            <?php if ($this->countModules('menu-footer')) : ?>
              <div class="<?php echo $col_menu_footer; ?>">
                <div id="menu-footer">
                  <jdoc:include type="modules" name="menu-footer" style="none" />
                </div>
              </div>
            <?php endif; ?>
            
            <?php if ($this->countModules('info-footer')) : ?>
              <div class="<?php echo $col_info_footer; ?>">
                <div id="info-footer">
                  <jdoc:include type="modules" name="info-footer" style="none" />
                </div>
              </div>
            <?php endif; ?>

          </div>
        <?php endif; ?>

        <?php if ($this->countModules('footer')) : ?>
          <div class="row">
            <div class="col-12">
              <div id="footer">
                <jdoc:include type="modules" name="footer" style="none" />
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </footer>
  <?php endif; ?>

  <pre style="display:none;">
  <?php var_dump($user); ?>
  </pre>

    
 <!-- Google Code para etiquetas de remarketing -->
<!--------------------------------------------------
Es posible que las etiquetas de remarketing todavía no estén asociadas a la información de identificación personal o que estén en páginas relacionadas con las categorías delicadas. Para obtener más información e instrucciones sobre cómo configurar la etiqueta, consulte http://google.com/ads/remarketingsetup.
---------------------------------------------------->
<script type="text/javascript">
<![CDATA[
var google_conversion_id = 999552507;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
]]>
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/999552507/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
   
    
</body>
</html>