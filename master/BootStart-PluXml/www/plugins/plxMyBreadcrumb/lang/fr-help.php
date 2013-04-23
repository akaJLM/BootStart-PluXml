<h2>Aide</h2>
<p>
Ajouter la ligne suivante dans votre thème (ex fichier header.php) à l'endroit où vous voulez afficher le fil d'ariane:
</p>
<pre style="font-size:12px; padding-left:40px">
&lt;?php eval($plxShow->callHook('MyBreadcrumb')) ?&gt;
</pre>
