<?php if(!defined('PLX_ROOT')) exit; ?>

<!-- top.php -->
</div>
<!-- .span11 main pull-right -->
</div>
<!-- .span12 -->
</div>
<!-- .row-fluid -->
</div>
<!-- .container -->
</div>
<!-- .content -->

<!--Footer-->

<footer class="footer">
  <p class="text-center"><a href="http://www.littlerabbitlabs.net" target="_blank" title="Tiny Web Agency & Web Interactive Labs">© littleRabbitLabs</a> html5/css3 admin theme development - <a title="PluXml" href="http://www.pluxml.org">Pluxml <?php echo $plxAdmin->aConf['version'] ?></a> - license <a title="License publique générale GNU Version 3" target="_blank" href="http://org.rodage.com/gpl-3.0.fr.txt">GNU v3</a></p>
</footer>
<?php eval($plxAdmin->plxPlugins->callHook('AdminFootEndBody')) ?>

<!--Javascript--> 

<!--libraries--> 
<script src="<?php echo PLX_CORE ?>admin/theme/js/jquery.js"></script> 
<script src="<?php echo PLX_CORE ?>admin/theme/js/bootstrap.min.js"></script> 
<script src="<?php echo PLX_CORE ?>admin/theme/js/bootstrap.min.ext.js"></script> 

<!--[if lte IE 8]><script src="<?php echo PLX_CORE ?>admin/theme/js/excanvas.min.js"></script><![endif]--> 

<!--Theme--> 
<script src="<?php echo PLX_CORE ?>admin/theme/js/theme-mandatory.js"></script> 
<script type="text/javascript">
setMsg();
</script>
</body></html>