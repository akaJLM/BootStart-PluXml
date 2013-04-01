<?php

/**
 * Edition des pages statiques
 *
 * @package PLX
 * @author	Stephane F et Florent MONTHEL
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminStaticsPrepend'));

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN, PROFIL_MANAGER);

# On édite les pages statiques
if(!empty($_POST)) {
	if(isset($_POST['homeStatic']))
		$plxAdmin->editConfiguration($plxAdmin->aConf, array('homestatic'=>$_POST['homeStatic'][0]));
	else
		$plxAdmin->editConfiguration($plxAdmin->aConf, array('homestatic'=>''));
	$plxAdmin->editStatiques($_POST);
	header('Location: statiques.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>
<script type="text/javaScript">
function checkBox(cb) {
	cbs=document.getElementsByName('homeStatic[]');
	for (var i = 0; i < cbs.length; i++) {
		if(cbs[i].checked==true) {
			cbs[i].checked = ((i+1) == cb) ? true: false;
		}
	}
}
</script>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-list-alt icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_STATICS_PAGE_TITLE ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminStaticsTop')) # Hook Plugins ?>
      <form class="form" action="statiques.php" method="post" id="form_statics">
        <div class="control-group">
          <div class="controls" style="margin-left:0;">
            <div class="input-append">
              <?php plxUtils::printSelect('selection', array( '' =>L_FOR_SELECTION, 'delete' =>L_DELETE), '') ?>
              <input class="btn submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
            </div>
          </div>
        </div>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="checkAll(this.form, 'idStatic[]')" /></th>
              <th class="hidden-phone"><?php echo L_STATICS_ID ?></th>
              <th><span class="hidden-phone"><?php echo L_STATICS_HOME_PAGE ?></span></th>
              <th class="hidden-phone"><?php echo L_STATICS_GROUP ?></th>
              <th><?php echo L_STATICS_TITLE ?></th>
              <th class="hidden-phone"><?php echo L_STATICS_URL ?></th>
              <th><?php echo L_STATICS_ACTIVE ?></th>
              <th class="hidden-phone"><?php echo L_STATICS_ORDER ?></th>
              <th class="hidden-phone"><?php echo L_STATICS_MENU ?></th>
              <th><?php echo L_STATICS_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
	# Initialisation de l'ordre
	$num = 0;
	# Si on a des pages statiques
	if($plxAdmin->aStats) {
		foreach($plxAdmin->aStats as $k=>$v) { # Pour chaque page statique
			$ordre = ++$num;
			echo '<tr class="line-'.($num%2).'">';
			echo '<td><input type="checkbox" name="idStatic[]" value="'.$k.'" /><input type="hidden" name="staticNum[]" value="'.$k.'" /></td>';
			echo '<td class="hidden-phone">'.L_PAGE.' '.$k.'</td><td>';
			$selected = $plxAdmin->aConf['homestatic']==$k ? ' checked="checked"' : '';
			echo '<input title="'.L_STATICS_PAGE_HOME.'" type="checkbox" name="homeStatic[]" value="'.$k.'"'.$selected.' onclick="checkBox(\''.$num.'\')" />';
			echo '</td><td class="hidden-phone">';
			plxUtils::printInput($k.'_group', plxUtils::strCheck($v['group']), 'text', '13-100', false, 'span12');
			echo '</td><td>';
			plxUtils::printInput($k.'_name', plxUtils::strCheck($v['name']), 'text', '13-255', false, 'span12');
			echo '</td><td class="hidden-phone">';
			plxUtils::printInput($k.'_url', $v['url'], 'text', '12-255', false, 'span12');
			echo '</td><td>';
			plxUtils::printSelect($k.'_active', array('1'=>L_YES,'0'=>L_NO), $v['active'], false, 'span12');
			echo '</td><td class="hidden-phone">';
			plxUtils::printInput($k.'_ordre', $ordre, 'text', '2-3', false, 'span12');
			echo '</td><td class="hidden-phone">';
			plxUtils::printSelect($k.'_menu', array('oui'=>L_DISPLAY,'non'=>L_HIDE), $v['menu'], false, 'span12');

			if(!plxUtils::checkSite($v['url'])) {
				echo '</td><td><div class="btn-group">';
				echo '<a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.L_STATICS_SRC_TITLE.'" href="statique.php?p='.$k.'">'.L_STATICS_SRC.'</a>';
				if($v['active']) {
					echo '<a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="'.L_STATIC_VIEW_PAGE.' '.plxUtils::strCheck($v['name']).' '.L_STATIC_ON_SITE.'" href="'.PLX_ROOT.'?static'.intval($k).'/'.$v['url'].'">'.L_VIEW.'</a>';
				}
				echo '</div></td></tr>';
			}
			else
				echo '</td><td><a href="'.$v['url'].'" title="'.plxUtils::strCheck($v['name']).'">'.L_VIEW.'</a></td></tr>';
		}
		# On récupère le dernier identifiant
		$a = array_keys($plxAdmin->aStats);
		rsort($a);
	} else {
		$a['0'] = 0;
	}
	$new_staticid = str_pad($a['0']+1, 3, "0", STR_PAD_LEFT);
	?>
            <tr class="new info">
              <td>&nbsp;</td>
              <td class="hidden-phone"><?php echo L_STATICS_NEW_PAGE ?></td>
              <td class="hidden-phone"><?php
				echo '<input type="hidden" name="staticNum[]" value="'.$new_staticid.'" />';
				plxUtils::printInput($new_staticid.'_group', '', 'hidden', '13-100', false, 'span12');
				echo '</td><td>';
				echo '</td><td>';
				plxUtils::printInput($new_staticid.'_name', '', 'text', '13-255', false, 'span12');
				plxUtils::printInput($new_staticid.'_template', 'static.php', 'hidden', false, 'span12');
				echo '</td><td class="hidden-phone">';
				plxUtils::printInput($new_staticid.'_url', '', 'text', '12-255', false, 'span12');
				echo '</td><td>';
				plxUtils::printSelect($new_staticid.'_active', array('1'=>L_YES,'0'=>L_NO), '0', false, 'span12');
				echo '</td><td class="hidden-phone">';
				plxUtils::printInput($new_staticid.'_ordre', ++$num, 'text', '2-3', false, 'span12');
				echo '</td><td class="hidden-phone">';
				plxUtils::printSelect($new_staticid.'_menu', array('oui'=>L_DISPLAY,'non'=>L_HIDE), '1', false, 'span12');
			?></td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group pull-right">
          <div class="controls" style="margin-left:0;">
            <input class="btn btn-responsive update" type="submit" name="update" value="<?php echo L_STATICS_UPDATE ?>" />
          </div>
        </div>
        <div class="control-group">
          <div class="controls" style="margin-left:0;">
            <div class="input-append">
              <?php plxUtils::printSelect('selection', array( '' =>L_FOR_SELECTION, 'delete' =>L_DELETE), '') ?>
              <input class="btn submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminStaticsFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
