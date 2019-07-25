<?php
/**
 * Template Name: Portfolio
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

$pieces = new Timber\PostQuery(array('post_type' => 'portfolio'));
$context['pieces'] = $pieces;

Timber::render(array('portfolio.twig'), $context );
