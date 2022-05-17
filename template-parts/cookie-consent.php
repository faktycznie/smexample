<?php 
$message = smexample_get_option('cookie_consent');
if( !isset($_COOKIE['smexample-consent']) && !empty($message) ) { ?>
<div class="cookie-consent js-cookie">
	<div class="cookie-consent__container container-fluid">
		<div class="cookie-consent__content"><?php echo $message; ?></div>
		<div class="cookie-consent__button"><button class="btn btn--cookie"><?php esc_html_e( 'Akceptuję', 'smexample' ); ?></button></div>
	</div>
</div>
<?php } ?>