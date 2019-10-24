<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;

if(post_password_required($timber_post->ID)) {
	Timber::render('single-password.twig', $context);
} else if($timber_post->post_type == 'portfolio') {
	$context['title_font_styles'] = get_field('portfolio_font_styles_project_title_font_styles', 'options');
	$context['description_font_styles'] = get_field('portfolio_project_description_font_styles', 'options');
	$context['details_heading_font_styles'] = get_field('portfolio_project_details_heading_font_styles', 'options');
	$context['details_list_font_styles'] = get_field('portfolio_project_details_list_font_styles', 'options');
	$context['navigation_font_styles'] = get_field('portfolio_navigation_font_styles', 'options');

	Timber::render('single-portfolio.twig', $context);
} else {
	Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig'), $context);
}
