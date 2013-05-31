<?php
/**
 * Edition des utilisateurs
 *
 * @package PLX
 * @author	Stephane F.
 * modification 30/03/2013
 * @author akaJLM
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# Edition des utilisateurs
if (!empty($_POST)) {
	$plxAdmin->editUsers($_POST);
	header('Location: parametres_users.php');
	exit;
}

# Tableau des profils
$aProfils = array(
	PROFIL_ADMIN => L_PROFIL_ADMIN,
	PROFIL_MANAGER => L_PROFIL_MANAGER,
	PROFIL_MODERATOR => L_PROFIL_MODERATOR,
	PROFIL_EDITOR => L_PROFIL_EDITOR,
	PROFIL_WRITER => L_PROFIL_WRITER
);

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-group icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_CONFIG_USERS_TITLE; ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminUsersTop')) # Hook Plugins ?>
      <form class="form" action="parametres_users.php" method="post" id="form_users">
        <div class="control-group">
          <div class="controls" style="margin-left:0;;">
            <div class="input-append">
              <?php plxUtils::printSelect('selection', array( '' => L_FOR_SELECTION, 'delete' => L_DELETE), '') ?>
              <input class="btn submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
            </div>
          </div>
        </div>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><input type="checkbox" onclick="checkAll(this.form, 'idUser[]')" /></th>
              <th class="hidden-phone hidden-tablet"><?php echo L_CONFIG_USERS_ID ?></th>
              <th class="hidden-phone"><?php echo L_PROFIL_USER ?></th>
              <th><span class="hidden-phone"><?php echo L_PROFIL_LOGIN ?></span></th>
              <th><span class="hidden-phone"><?php echo L_PROFIL_PASSWORD ?></span></th>
              <th><?php echo L_PROFIL ?></th>
              <th class="hidden-phone"><?php echo L_CONFIG_USERS_ACTIVE ?></th>
              <th><?php echo L_CONFIG_USERS_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
	# Initialisation de l'ordre
	$num = 0;
	if($plxAdmin->aUsers) {
		foreach($plxAdmin->aUsers as $_userid => $_user)	{
			if (!$_user['delete']) {
				echo '<tr class="line-'.($num%2).'">';
				echo '<td><input class="span12" type="checkbox" name="idUser[]" value="'.$_userid.'" /><input type="hidden" name="userNum[]" value="'.$_userid.'" /></td>';
				echo '<td class="hidden-phone hidden-tablet">Utilisateur '.$_userid.'</td><td class="hidden-phone">';
				plxUtils::printInput($_userid.'_name', plxUtils::strCheck($_user['name']), 'text', '20-255', false, 'span12');
				echo '</td><td>';
				plxUtils::printInput($_userid.'_login', plxUtils::strCheck($_user['login']), 'text', '11-255', false, 'span12');
				echo '</td><td>';
				plxUtils::printInput($_userid.'_password', '', 'password', '11-255', false, 'span12');
				echo '</td><td>';
				if($_userid=='001') {
					plxUtils::printSelect($_userid.'_profil', $aProfils, $_user['profil'], true, 'uneditable-input span2');
					echo '</td><td class="hidden-phone">';
					plxUtils::printSelect($_userid.'_active', array('1'=>L_YES,'0'=>L_NO), $_user['active'], true, 'uneditable-input span2');
				} else {
					plxUtils::printSelect($_userid.'_profil', $aProfils, $_user['profil'], false, 'span12');
					echo '</td><td class="hidden-phone">';
					plxUtils::printSelect($_userid.'_active', array('1'=>L_YES,'0'=>L_NO), $_user['active'], false, 'span12');
				}
				echo '</td>';
				echo '<td><a class="btn btn-mini" href="user.php?p='.$_userid.'">'.L_OPTIONS.'</a></td>';
				echo '</tr>';
			}
		}
		# On récupère le dernier identifiant
		$a = array_keys($plxAdmin->aUsers);
		rsort($a);
	} else {
		$a['0'] = 0;
	}
	$new_userid = str_pad($a['0']+1, 3, "0", STR_PAD_LEFT);
	?>
            <tr class="info">
              <td>&nbsp;</td>
              <td class="hidden-phone hidden-tablet"><?php echo L_CONFIG_USERS_NEW; ?></td>
              <td class="hidden-phone"><?php
				echo '<input type="hidden" name="userNum[]" value="'.$new_userid.'" />';
				plxUtils::printInput($new_userid.'_newuser', 'true', 'hidden');
				plxUtils::printInput($new_userid.'_name', '', 'text', '20-255', false, 'span12');
				plxUtils::printInput($new_userid.'_infos', '', 'hidden');
				echo '</td><td>';
				plxUtils::printInput($new_userid.'_login', '', 'text', '11-255', false, 'span12');
				echo '</td><td>';
				plxUtils::printInput($new_userid.'_password', '', 'password', '11-255', false, 'span12');
				echo '</td><td>';
				plxUtils::printSelect($new_userid.'_profil', $aProfils, PROFIL_WRITER, false, 'span12');
				echo '</td><td class="hidden-phone">';
				plxUtils::printSelect($new_userid.'_active', array('1'=>L_YES,'0'=>L_NO), '1', false, 'span12');
				echo '</td>';
			?>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group pull-right">
          <div class="controls" style="margin-left:0;">
            <input class="btn btn-responsive update" type="submit" name="update" value="<?php echo L_CONFIG_USERS_UPDATE ?>" />
          </div>
        </div>
        <div class="control-group">
          <div class="controls" style="margin-left:0;">
            <div class="input-append">
              <?php plxUtils::printSelect('selection', array( '' => L_FOR_SELECTION, 'delete' => L_DELETE), '') ?>
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
eval($plxAdmin->plxPlugins->callHook('AdminUsersFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
