<?php

defined('_JEXEC') or die('Restricted Access');

jimport( 'joomla.plugin.plugin' );

class plgSystemHotelGallery extends JPlugin
{
		public function __construct( &$subject, $config )
		{
		parent::__construct( $subject, $config );
		}
	
    function onAfterRender()
    {
    // return if in administrator
    $app = JFactory::getApplication();
		if($app->isAdmin()) return;	

    $input = $output = JResponse::getBody();
    $bparts = explode('<body',$input);
    
    if (count($bparts)>1)
    	{
    	$before = $bparts[0];
    	$input = '<body';
    	for($c=1; $c < count($bparts); $c++) $input .= $bparts[$c];
    	$output = $input;
    	}	
    	
    if (preg_match_all("#{(.*?)}#s", $input, $matches) > 0)      // any plugins?
    	{
		foreach ($matches[0] as $match)                             // loop through all plugins
			{	
			$parts = explode('|',trim($match,'{}'));
  			if ($parts[0]=='hotelgallery')  // found a match!
  				{
	 				$pluginRoot = JURI::root()."/plugins/system/hotelgallery";
  					$id = $parts[1];
  					
  					$db = JFactory::getDBO();
  					$db->setQuery("SELECT * FROM `#__hotelreservation_hotel_pictures` WHERE hotel_id=$id");
  					$pictures= $db->loadObjectList();
  					
  					foreach( $pictures as $index=>$picture ){
  						$picture->hotel_picture_path = JURI::root() .PATH_PICTURES.$picture->hotel_picture_path;
					} 
$replace = <<<ASDF

		<div class="gamma-container gamma-loading" id="gamma-container">
		 
		    <ul class="gamma-gallery">
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[0]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[0]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[0]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[1]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[1]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[1]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[2]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[2]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[2]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[3]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[3]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[3]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[4]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[4]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[4]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>	
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[5]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[5]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[5]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>				  
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[6]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[6]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[6]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>				  
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[7]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[7]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[7]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>				  
		        <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[8]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[8]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[8]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>	
		         <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[9]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[9]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[9]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>			
		         <li>
		            <div data-alt="img01" data-description="<h3>{$pictures[10]->hotel_picture_info}</h3>" data-max-width="1800" data-max-height="2400">
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="1300"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="1000"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="700"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="300"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="200"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}" data-min-width="140"></div>
		                <div data-src="{$pictures[10]->hotel_picture_path}"></div>
		                <noscript>
		                    <img src="{$pictures[8]->hotel_picture_path}" alt="img01"/>
		                </noscript>
		            </div>
		        </li>						  			        		        		      
		    </ul>
		 
		    <div class="gamma-overlay"></div>
		 
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="{$pluginRoot}/gallery/js/jquery.masonry.min.js"></script>
		<script src="{$pluginRoot}/gallery/js/jquery.history.js"></script>
		<script src="{$pluginRoot}/gallery/js/js-url.min.js"></script>
		<script src="{$pluginRoot}/gallery/js/jquerypp.custom.js"></script>
		<script src="{$pluginRoot}/gallery/js/gamma.js"></script>
		<link rel="stylesheet" type="text/css" href="{$pluginRoot}/gallery/css/style.css"/>
		<script type="text/javascript">
				$ = jQuery.noConflict( true );
				$(function() {

				var GammaSettings = {
						// order is important!
						viewport : [ {
							width : 1200,
							columns : 5
						}, {
							width : 900,
							columns : 4
						}, {
							width : 500,
							columns : 3
						}, { 
							width : 320,
							columns : 2
						}, { 
							width : 0,
							columns : 2
						} ]
				};

				Gamma.init( GammaSettings, fncallback );
				function fncallback() {
				}

			});
				
		</script>	
ASDF;

  				// replace
  				$output	= str_replace($match,$replace,$output);
   				}
    		}
    	}
		if ($input != $output) JResponse::setBody($before . $output);
		return true;
		}
}

?>