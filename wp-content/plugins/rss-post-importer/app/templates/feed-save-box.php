<div class="postbox">
	<div class="inside">
		<div class="misc-pub-section">
			<h3 class="version">V. <?php echo RSS_PI_VERSION; ?></h3>
			<ul>
				<li>
					<i class="icon-calendar"></i> <?php _e("Latest import:", 'rss-post-importer'); ?> <strong><?php echo $this->options['latest_import'] ? $this->options['latest_import'] : 'never' ; ?></strong>
				</li>
				<li><i class="icon-eye-open"></i> <a href="#" class="load-log"><?php _e("View the log", 'rss-post-importer'); ?></a></li>
			</ul>
		</div>
		<div id="major-publishing-actions">
			<input class="button button-primary button-large right" type="submit" name="info_update" value="<?php _e('Save', 'rss-post-importer'); ?>" />
			<input class="button button-large" type="submit" name="info_update" value="<?php _e('Save and import', "rss-post-importer"); ?>" id="save_and_import" />
		</div>
	</div>
</div>
<?php if ($this->options['imports'] > 10) : ?>
	<div class="rate-box">
		<h4><?php printf(__('%d posts imported and counting!', "rss-post-importer"), $this->options['imports']); ?></h4>
		<i class="icon-star"></i>
		<i class="icon-star"></i>
		<i class="icon-star"></i>
		<i class="icon-star"></i>
		<i class="icon-star"></i>
		<p class="description"><a href="http://wordpress.org/plugins/rss-post-importer/" target="_blank">Please support this plugin by rating it!</a></p>
	</div>
<?php endif; ?>

<?php if (!$this->is_key_valid) : ?>
<?php $banner_url = RSS_PI_URL . "app/assets/img/rss-post-importer_280x600.jpg"; ?>
<a target="_blank" href="http://www.feedsapi.com/?utm=rsspostimporter_banner">
	<img class='rss_pi_banner_img' src="<?php echo $banner_url; ?>" />
</a>
<?php endif; ?>

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?1JkI9crULWPOzNzvAJ6SYbeghH5FjhVV';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->

<!--Start of Feedback Box-->
<!--
<script src="http://www.jotform.com/min/?g=feedback2" type="text/javascript"></script>
<script type="text/javascript">
    new JotformFeedback({
        formId      : "50873505454962",
        buttonText  : "Get Help!",
        windowTitle : "Mark up the screenshot to describe a problem or suggestion",
        base        : "http://jotformpro.com/",
        background  : "#F59202",
        fontColor   : "#FFFFFF",
        buttonSide  : "bottom",
        buttonAlign : "right",
        type        : false,
        width       : 280,
        height      : 420,
        instant     : true
    });
</script> -->
<!--End of Feedback Box-->

<!--Perfect Audience Start-->
<script type="text/javascript">
  (function() {
    window._pa = window._pa || {};
    // _pa.orderId = "myOrderId"; // OPTIONAL: attach unique conversion identifier to conversions
    // _pa.revenue = "19.99"; // OPTIONAL: attach dynamic purchase values to conversions
    // _pa.productId = "myProductId"; // OPTIONAL: Include product ID for use with dynamic ads
    var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
    pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.perfectaudience.com/serve/52c8aa7b965728ddac000007.js";
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
  })();
</script>
<!--Perfect Audience End-->