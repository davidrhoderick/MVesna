<?php
/**
 * MVesna Theme version 1.0.0
 * https://github.com/davidrhoderick/MVesna.git
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if(!class_exists('Timber')) {
  add_action('admin_notices', function() {
    echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
  });

  add_filter('template_include', function($template) {
    return get_stylesheet_directory() . '/static/no-timber.html';
  });

  return;
}

@ini_set('upload_max_size' , '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '300');

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class MVesnaThemeSite extends Timber\Site {
  /** Add timber support. */
  public function __construct() {
    add_action('after_setup_theme', array($this, 'theme_supports'));
    add_filter('timber_context', array($this, 'add_to_context'));
    add_filter('get_twig', array($this, 'add_to_twig'));
    add_filter('show_admin_bar', '__return_false');
    add_action('init', array($this, 'register_taxonomies'));
    add_action('init', array($this, 'register_post_types'));

    add_filter('get_image_tag_class',array($this, 'use_only_imgfluid_class'));
    add_filter('post_thumbnail_html', array($this, 'remove_width_attribute'), 10);
    add_filter('image_send_to_editor', array($this, 'remove_width_attribute'), 10);
    add_filter('the_content', array($this, 'filter_ptags_on_images'));
    add_filter('acf_the_content', array($this, 'filter_ptags_on_images'), 30);

    add_action('init', array($this, 'disable_emojis'));
    add_action('wp_enqueue_scripts', array($this, 'replace_jquery_with_site_js'));

    add_action('wp_enqueue_scripts', array($this, 'add_typekit'));
    add_action('wp_enqueue_scripts', array($this, 'add_googlefonts'));
    
    add_action('wp_enqueue_scripts', array($this, 'add_googleanalytics'));

    /* If having issues with password protected links, try code below: */
    // add_filter('allowed_redirect_hosts', array($this, 'amend_redirect_hosts'), 10, 2);

    acf_add_options_page('Site Options');

    parent::__construct();
  }

  function amend_redirect_hosts($allowed_hosts, $this_host) {
    $allowed_hosts[] = $this_host;

    return $allowed_hosts;
  }

  public function filter_ptags_on_images($content) {
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
  }

  public function remove_width_attribute($html) {
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
  }
  
  public function use_only_imgfluid_class($class) {
    $class = 'img-fluid';
    return $class;
  }

  /** This is where you can register custom post types. */
  public function register_post_types() {
    // PORTFOLIO
    $labels = array(
      'name'                  =>_x('Portfolio', 'post type general name', 'artlytical-media'),
      'singular_name'         =>_x('Portfolio Piece', 'post type singular name', 'artlytical-media'),
      'menu_name'             =>_x('Portfolio', 'admin menu', 'artlytical-media'),
      'name_admin_bar'        =>_x('Portfolio Piece', 'add new on admin bar', 'artlytical-media'),
      'add_new'               =>_x('Add Portfolio Piece', 'portfolio-piece', 'artlytical-media'),
      'add_new_item'          =>__('Add New Portfolio Piece', 'artlytical-media'),
      'new_item'              =>__('New Portfolio Piece', 'artlytical-media'),
      'edit_item'             =>__('Edit Portfolio Piece', 'artlytical-media'),
      'view_item'             =>__('View Portfolio', 'artlytical-media'),
      'all_items'             =>__('Whole Portfolio', 'artlytical-media'),
      'search_items'          =>__('Search Portfolio', 'artlytical-media'),
      'parent_item_colon'     =>__('Parent Portfolio Piece:', 'artlytical-media'),
      'not_found'             =>__('No portfolio pieces found.', 'artlytical-media'),
      'not_found_in_trash'    =>__('No portfolio pieces found in Trash.', 'artlytical-media')
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __('Description.', 'artlytical-media'),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'show_in_nav_menus'  => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'portfolio'),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
      'menu_icon'					 =>'dashicons-format-gallery'
    );

    register_post_type('portfolio', $args);

    // POP-UPS
    $labels = array(
      'name'                  =>_x('Pop-ups', 'post type general name', 'artlytical-media'),
      'singular_name'         =>_x('Pop-up', 'post type singular name', 'artlytical-media'),
      'menu_name'             =>_x('Pop-ups', 'admin menu', 'artlytical-media'),
      'name_admin_bar'        =>_x('Pop-up', 'add new on admin bar', 'artlytical-media'),
      'add_new'               =>_x('Add Pop-up', 'pop-up', 'artlytical-media'),
      'add_new_item'          =>__('Add New Pop-up', 'artlytical-media'),
      'new_item'              =>__('New Pop-up', 'artlytical-media'),
      'edit_item'             =>__('Edit Pop-up', 'artlytical-media'),
      'view_item'             =>__('View Pop-ups', 'artlytical-media'),
      'all_items'             =>__('All Pop-ups', 'artlytical-media'),
      'search_items'          =>__('Search Pop-ups', 'artlytical-media'),
      'parent_item_colon'     =>__('Parent Pop-up:', 'artlytical-media'),
      'not_found'             =>__('No pop-ups found.', 'artlytical-media'),
      'not_found_in_trash'    =>__('No pop-ups found in Trash.', 'artlytical-media')
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __('Description.', 'artlytical-media'),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'show_in_nav_menus'  => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'popup'),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array('title', 'editor', 'revisions'),
      'menu_icon'					 =>'dashicons-slides'
    );

    register_post_type('popup', $args);
  }

  public function register_taxonomies() {
    // PORTFOLIO TAGS
    $labels = array(
    	'name'                       => _x('Portfolio Tags', 'taxonomy general name', 'artlytical-media'),
    	'singular_name'              => _x('Portfolio Tag', 'taxonomy singular name', 'artlytical-media'),
    	'search_items'               => __('Search Portfolio Tags', 'artlytical-media'),
    	'all_items'                  => __('All Portfolio Tags', 'artlytical-media'),
    	'parent_item'                => null,
    	'parent_item_colon'          => null,
    	'edit_item'                  => __('Edit Portfolio Tag', 'artlytical-media'),
    	'update_item'                => __('Update Portfolio Tag', 'artlytical-media'),
    	'add_new_item'               => __('Add New Portfolio Tag', 'artlytical-media'),
    	'new_item_name'              => __('New Portfolio Tag Name', 'artlytical-media'),
    	'separate_items_with_commas' => __('Separate portfolio tags with commas', 'artlytical-media'),
    	'add_or_remove_items'        => __('Add or remove portfolio tags', 'artlytical-media'),
    	'not_found'                  => __('No portfolio tags found.', 'artlytical-media'),
    	'menu_name'                  => __('Portfolio Tags', 'artlytical-media'),
    	'choose_from_most_used'			 => __('Choose from most used portfolio tags', 'artlytical-media')
    );

    $args = array(
    	'hierarchical'          => false,
    	'labels'                => $labels,
    	'show_ui'               => true,
    	'show_admin_column'     => true,
    	'update_count_callback' => '_update_post_term_count',
    	'query_var'             => true,
    	'rewrite'               => array('slug' => 'portfolio-tag'),
    );

    register_taxonomy('portfolio_tags', 'portfolio', $args);
  }

  /** This is where you add some context
   *
   * @param string $context context['this'] Being the Twig's {{ this }}.
   */
  public function add_to_context($context) {
    $context['options'] = get_fields('options');
    $context['menu'] = new Timber\Menu();
    $context['site'] = $this;

    $context['popups'] = new Timber\PostQuery(array('post_type' => 'popup'));
    return $context;
  }

  public function theme_supports() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
      'html5', array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      )
    );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support(
      'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
      )
    );

    add_theme_support('menus');
  }

  /** This is where you can add your own functions to twig.
   *
   * @param string $twig get extension.
   */
  public function add_to_twig($twig) {

    $twig->getExtension('Twig_Extension_Core')->setTimezone('CEST');

    $twig->addFilter('preg_replace', new Twig_Filter_Function(function ($subject, $pattern, $replacement) {
      return preg_replace($pattern, $replacement, $subject);
    }));
    
    return $twig;
  }

  /**
  * Disable the emoji's
  */
  public function disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', array($this, 'disable_emojis_tinymce'));
    add_filter('wp_resource_hints', array($this, 'disable_emojis_remove_dns_prefetch'), 10, 2);
  }

  /**
  * Filter function used to remove the tinymce emoji plugin.
  * 
  * @param array $plugins 
  * @return array Difference betwen the two arrays
  */
  public function disable_emojis_tinymce($plugins) {
    if(is_array($plugins)) {
      return array_diff($plugins, array('wpemoji'));
    } else {
      return array();
    }
  }

  /**
  * Remove emoji CDN hostname from DNS prefetching hints.
  *
  * @param array $urls URLs to print for resource hints.
  * @param string $relation_type The relation type the URLs are printed for.
  * @return array Difference betwen the two arrays.
  */
  public function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
    if('dns-prefetch' == $relation_type) {
      /** This filter is documented in wp-includes/formatting.php */
      $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

      $urls = array_diff($urls, array($emoji_svg_url));
    }

    return $urls;
  }

  // Remove default jQuery
  public function replace_jquery_with_site_js() {
    if(!is_admin()) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', get_stylesheet_directory_uri() . '/static/site.min.js', false);
      wp_enqueue_script('jquery');
    }
  }

  public function add_typekit() {
    $adobe_typekit = get_field('adobe_typekit', 'options');
    if($adobe_typekit) {
      wp_enqueue_style('adobetypekit', $adobe_typekit);
    }
  }

  public function add_googlefonts() {
    $google_fonts = get_field('google_fonts', 'options');
    if($google_fonts) {
      wp_enqueue_style('googlefonts', $google_fonts);
    }
  }

  public function add_googleanalytics() {
    if(get_field('google_analytics_code', 'options')) {
      wp_register_script('ga', 'https://www.googletagmanager.com/gtag/js?id=' . get_field('google_analytics_code', 'options'));
      wp_enqueue_script('ga');
      ?>
          <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php the_field('google_analytics_code', 'options') ?>');
          </script>
      <?php
    }
  }
}

new MVesnaThemeSite();
