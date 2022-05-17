<?php
/**
 * The front-page
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package smexample
 */

get_header();
?>
<?php get_template_part( 'template-parts/section-slider' ); ?>
<?php get_template_part( 'template-parts/section-reports' ); ?>
<?php get_template_part( 'template-parts/section-contact' ); ?>
<?php
get_sidebar();
get_footer();