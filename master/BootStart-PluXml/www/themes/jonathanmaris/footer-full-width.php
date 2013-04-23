<?php if (!defined('PLX_ROOT')) exit; ?>

</div>
<!-- .span12 main -->
</div>
<!-- .row-fluid -->
</div>
<!-- .container -->
</section>
<!-- .content -->

<!--Footer-->

<footer class="footer">
  <p class="text-center">
  	<?php $plxShow->mainTitle('link'); ?> <?php $plxShow->subTitle(); ?> - &copy;2013 <?php $plxShow->mainTitle('link'); ?> html5/css3 admin UI & theme development - license <a title="License publique générale GNU Version 3" target="_new" href="http://org.rodage.com/gpl-3.0.fr.txt">GNU v3</a>
  </p>
  <p class="text-center">
	<?php $plxShow->lang('POWERED_BY') ?> <a href="http://www.pluxml.org" title="<?php $plxShow->lang('PLUXML_DESCRIPTION') ?>">PluXml</a>
    <?php $plxShow->lang('IN') ?> <?php $plxShow->chrono(); ?>&nbsp;
    <a href="<?php echo $plxShow->urlRewrite('#top') ?>" title="<?php $plxShow->lang('GOTO_TOP') ?>"><?php $plxShow->lang('TOP') ?></a>&nbsp;
    <?php $plxShow->httpEncoding() ?>
   </p>
</footer>

<!--Javascript--> 

<!--libraries--> 
<script src="<?php $plxShow->template(); ?>/js/jquery.js"></script> 
<script src="<?php $plxShow->template(); ?>/js/bootstrap.min.js"></script> 
<script src="<?php $plxShow->template(); ?>/js/bootstrap.min.ext.js"></script> 

<!--[if lte IE 8]><script src="<?php $plxShow->template(); ?>/js/excanvas.min.js"></script><![endif]--> 

<!--Theme--> 
<script src="<?php $plxShow->template(); ?>/js/theme-mandatory.js"></script> 
<script type="text/javascript">
setMsg();
</script>
</body></html>