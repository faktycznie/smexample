<?php
$slides = smexample_get_option('hero_slider');

if( !empty($slides) ) { ?>
<section class="hero">
	<script>
		jQuery( document ).ready(function() {
			jQuery('.hero__slider').slick({
				dots: true,
				infinite: true,
				speed: 500,
				slidesToShow: 1,
				fade: true,
				arrows: false,
				autoplay: true,
				autoplaySpeed: 5000,
			});
		});
	</script>
	<div class="hero__slider">
	<?php foreach($slides as $slide) { 
		if( empty($slide['image']) ) continue; //maybe placeholder here?
		?>
		<div class="hero__slide" style="background-image: url('<?php echo $slide['image']; ?>');">
			<div class="hero__content">
				<?php if( !empty($slide['slogan']) ) { ?>
					<div class="hero__slogan">
						<?php echo $slide['slogan'] ?>
					</div>
				<?php } ?>
				<?php if( !empty($slide['link']) ) { ?>
					<a class="hero__readmore btn btn--secondary" href="<?php echo $slide['link'];?>">
					<?php esc_html_e( 'Zobacz', 'smexample' ); ?>
					</a>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	</div>
</section>
<?php } ?>
