<h2>Help</h2>
<p>
Add this following line in your theme (ex file header.php) where you want to dispay the breadcrumb:
</p>
<pre style="font-size:12px; padding-left:40px">
&lt;?php eval($plxShow->callHook('MyBreadcrumb')) ?&gt;
</pre>