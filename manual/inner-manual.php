<h3><span class="dashicons dashicons-book-alt"></span> <?php _e('Instructions', 'wp-adzonia' ); ?></h3>
<p><?php _e('Advertisements added using AdZonia, can be displayed in 3 alternative ways &mdash;', 'wp-adzonia' ); ?></p>
<ul class="ul-disc">
	<li><?php _e('<strong>Shortcode &mdash;</strong> The simplest is using a shortcode. The shortcode is that simple, just put <code>[wp-adzonia id="#"]</code> into the body of any post or page or shortcode enabled widget. Just add the ID of the ad into the hash (#).', 'wp-adzonia' ); ?></li>
	<li><?php _e('<strong>Widget &mdash;</strong> Using the AdZonia widget into any widget enabled area or sidebar. Just drag and drop the "AdZonia" widget into the sidebar, and choose the active (published) ad from the list.', 'wp-adzonia' ); ?></li>
	<li><?php _e('<strong>PHP Code &mdash;</strong> If you are a developer and want to use the PHP code into your template (theme) directly, just use this: <code>&lt;?php if ( function_exists( &quot;show_adzonia&quot; )  ) show_adzonia( # ); ?&gt;</code>. Just add the ID of the ad into the hash (#).', 'wp-adzonia' ); ?></li>
</ul>
<p><?php _e('For more detailed instructions see our <a href="https://github.com/nanodesigns/wp-adzonia/wiki/User-Manual">AdZonia GitHub manual</a>. To reduce the plugin size we shifted the manual there.', 'wp-adzonia' ); ?></p>