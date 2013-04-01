<?php

/**
 * Affichage de l'aide d'un plugin
 *
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/
include(dirname(__FILE__).'/prepend.php');

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

$plugin = isset($_GET['p'])?urldecode($_GET['p']):'';
$plugin = plxUtils::nullbyteRemove($plugin);

$output='';

# chargement du fichier d'aide du plugin
$filename = realpath(PLX_PLUGINS.$plugin.'/lang/'.$plxAdmin->aConf['default_lang'].'-help.php');
if(is_file($filename)) {
	ob_start();
	?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-wrench icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_ADVANCED_PLUGINS_PLUGIN_HELP ?></h5>
      <a href="parametres_plugins.php" class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_BACK_TO_PLUGINS ?>"><i class="icon icon-undo"></i></a> </div>
    <div class="widget-content">
      <?php
	
	include($filename);
	
	?>
    </div>
  </div>
</div>
<?php
	
	$output=ob_get_clean();
}
else {
	plxMsg::Error(L_NO_ENTRY);
	header('Location: parametres_plugin.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>
<?php
# Affichage des données
echo $output;
?>
<?php
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
