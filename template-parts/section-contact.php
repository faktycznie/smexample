<?php
$contact_name = smexample_get_option('contact_name');
$contact_subname = smexample_get_option('contact_subname');
$contact_email = smexample_get_option('contact_email');
$contact_img = smexample_get_option('contact_img');
?>
<section class="contact" id="contact">
	<div class="contact__container container-fluid container-fluid--small">
		<h2 class="contact__title section-title"><?php _e('Formularz kontaktowy', 'smexample'); ?></h2>
		<div class="contact__content row row--small">
			<?php if( !empty($contact_name) || !empty($contact_subname) || !empty($contact_email) || !empty($contact_img) ) { ?>
			<div class="contact__desc person col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<h3 class="person__title"><?php _e('Osoba do kontaktu', 'smexample'); ?></h3>
				<?php if( !empty($contact_name) ) { ?>
					<div class="person__name"><strong><?php echo $contact_name; ?></strong></div>
				<?php } ?>
				<?php if( !empty($contact_subname) ) { ?>
					<div class="person__subname"><?php echo $contact_subname; ?></div>
				<?php } ?>
				<?php if( !empty($contact_email) ) { //not safe for spam - need to be improved in future :) ?>
					<div class="person__email">email: <a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a></div>
				<?php } ?>
				<?php if( !empty($contact_img) ) { ?>
					<div class="person__img"><img src="<?php echo $contact_img; ?>" alt=""></div>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="contact__form col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<form class="contact-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
					<div class="contact-form__row">
						<input class="contact-form__input contact-form__input--name" type="text" name="name" id="name" autocomplete="name" placeholder="<?php _e('Twoje imię i nazwisko', 'smexample'); ?>" required>
					</div>
					<div class="contact-form__row">
						<input class="contact-form__input contact-form__input--email" type="text" name="email" id="email" autocomplete="email" placeholder="<?php _e('Twój email biznesowy', 'smexample'); ?>" required>
					</div>
					<div class="contact-form__row">
						<textarea class="contact-form__textarea contact-form__textarea--message" name="message" id="message" cols="50" rows="10" required><?php _e('Dzień dobry, chciałbym uzyskać więcej informacji. Proszę o kontakt.', 'smexample'); ?></textarea>
					</div>
					<div class="contact-form__row">
						<label class="contact-form__checkbox checkbox">
							<input class="checkbox__input" type="checkbox" name="consent" id="consent" value="1" required>
							<span class="checkbox__mark"></span>
							<span class="checkbox__label">Wyrażam zgodę na przetwarzanie moich danych osobowych w celach marketingowych. <a class="checkbox__more js-consent" href="#" data-lang="<?php _e('Mniej', 'smexample'); ?>"><?php _e('Więcej', 'smexample'); ?></a></span>
						</label>
						<div class="contact-form__consent">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce rhoncus consectetur pellentesque. Aliquam bibendum ultricies felis, sed venenatis arcu rutrum sit amet. Nullam pulvinar faucibus elit quis elementum. Etiam nec nisl at erat efficitur tincidunt hendrerit sit amet felis. In semper ligula vel erat ultrices, quis elementum ipsum sagittis. Aenean lacinia laoreet turpis, non volutpat metus. In porttitor malesuada tempor. Etiam tempor augue at ante sodales varius. <a href="#">Quisque</a> efficitur porta gravida.</div>
					</div>
					<input type="submit" value="<?php _e('Wyślij', 'smexample'); ?>" class="contact-form__btn btn" name="submit">
					<input type="hidden" name="action" value="smexample_send" />
					<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('smexample_send'); ?>"/>
				</form>
			</div>
		</div>
	</div>
</section>