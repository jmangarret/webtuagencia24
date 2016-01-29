<?php
jimport('joomla.installer.helper');
jimport('joomla.installer.installer');
jimport( 'joomla.filesystem.folder' );

installHotelPackages();

function installHotelPackages(){
		jimport('joomla.installer.installer');
		// Install Package Manager
		$basedir = dirname(__FILE__);
		$packageDir = JPATH_ADMINISTRATOR .'/components/com_jhotelreservation/extensions';
		if(!is_dir($packageDir))
			$packageDir = $basedir.'/admin/extensions';
		$extensionsDirs = JFolder::folders($packageDir);
		foreach( $extensionsDirs as $extensionDir)
		{
			$tmpInstaller = new JInstaller();
			if(!$tmpInstaller->update($packageDir.'/'.$extensionDir))
			{
				JError::raiseWarning(100,"Extension :". $extensionDir);
			}
		}
		$db = JFactory::getDBO();
		$db->setQuery( " UPDATE #__extensions SET enabled=1 WHERE name='Hotel Url Translator' " );
		$db->query();
		
		$db = JFactory::getDBO();
		$db->setQuery( " UPDATE #__extensions SET enabled=1 WHERE name='Hotel Gallery' " );
		$db->query();
		
		
		$path = JPATH_ADMINISTRATOR . '/components/com_jhotelreservation/help/readme.html';
		?>
		
		<div style="text-align: left; float: left;">
		<?php
			include( $path);
		?>
		</div>
<?php
}
?>