<?php
$reports = smexample_get_option('reports');
if( !empty($reports) ) {
?>
<section class="reports" id="reports">
	<div class="reports__container container-fluid">
		<h2 class="reports__title section-title"><?php _e('Wyniki firmy', 'smexample'); ?></h2>
		<div class="reports__items">
			<div class="reports__items-inner">
				<ul class="reports__list">
					<?php 
					$i = 0;
					foreach($reports as $report) { ?>
						<li class="reports__item">
							<a class="reports__link" href="#report<?php echo $i; ?>">
								<?php if( !empty($report['image']) ) { ?>
									<span class="reports__icon"><img src="<?php echo $report['image']; ?>" alt=""></span>
								<?php } ?>
								<?php if( !empty($report['title']) ) { ?>
									<span class="reports__name"><?php echo $report['title']; ?></span>
								<?php } ?>
							</a>
						</li>
					<?php $i++; } ?>
				</ul>
			</div>
			<div class="reports__content reports__content--empty report js-reports"></div>
		</div>
	</div>
</section>
<?php } ?>