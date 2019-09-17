<?php
/*
Plugin Name: Cryptocurrency Converter
Plugin URI: https://www.nettantra.com/wordpress/?utm_src=cryptocurrency-converter
Description: This plugin allows to add shortcode on your WordPress site and convert over 1,000 crypto currencies. [Cryptocurrency_Converter title="Your Title"]
Version: 0.6
Author: NetTantra
Author URI: https://www.nettantra.com/wordpress/?utm_src=cryptocurrency-converter
Text Domain: cryptocurrency-converter
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class CryptoCurrencyConverter {

  public $ccc_option_setting_api;
  public $ccc_option_default_from;
  public $ccc_option_default_to;
  public $ccc_options_widget_title;
  public $ccc_option_from_label;
  public $ccc_option_amount_label;
  public $ccc_option_to_label;
  public $ccc_option_result_label;

  public $default_api_key = '';

  function __construct() {
    $this->default_api_key = 'a8s9pqXrseifkK4FbYG1r6qbx7KonG9fqYcgKlwLzQu0KzTq1EaaY0lcs0cc2n5C';
    add_action( 'admin_enqueue_scripts', array( $this, 'ccc_load_wp_admin_scripts' ) );
  }

  public function ccc_load_wp_admin_scripts( $hook ) {
    if($hook != 'settings_page_cryptocurrency-converter') {
        return;
    }
    wp_enqueue_style( 'select2-css',  plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', '4.0.6' );
    
    wp_enqueue_script( 'select2-js',  plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', '4.0.6' );
    
    wp_enqueue_script( 'ccc-currencies',  plugin_dir_url( __FILE__ ) . 'assets/js/all.currencies.js', '1.0' );

    wp_enqueue_style( 'wp-color-picker' );

    wp_enqueue_script( 'ccc-admin-main', plugins_url('/assets/js/admin-ccc-main.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

  }

  public function ccc_admin_page() {
    $this->ccc_get_otptions();
    ?>
    <div class="wrap">
      <h1><?php echo __('Cryptocurrency Converter Settings', 'cryptocurrency-converter'); ?></h1>
      <form method="post" action="admin-post.php?action=cryptocurrency-converter-options">
      <?php wp_nonce_field('cryptocurrency-converter-options'); ?>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="ccc_option_api"><?php echo esc_html__('API', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_api]" type="text" id="ccc_option_api" value="<?php echo (get_option('ccc_option_api')) ? esc_attr(get_option('ccc_option_api')) : esc_attr($this->default_api_key); ?>" class="regular-text">
            <p class="description"><?php echo _e( 'We Provide a default api key but please use your own api key for better performance. <a href="https://apiseeds.com/" target="_blank">To claim your API key visit the url</a>, it\'s <strong>free</strong>', 'cryptocurrency-converter' ); ?></p>
          </td>
          </tr>
          <tr>
            <th scope="row"><label for="ccc_option_widget_title"><?php echo esc_html__('Widget Title', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_widget_title]" type="text" id="ccc_option_widget_title" value="<?php echo (get_option('ccc_option_widget_title')) ? esc_attr(get_option('ccc_option_widget_title')) : esc_attr__('Cryptocurrency Converter', 'cryptocurrency-converter'); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <script type="text/javascript">
            var ccc_option_default_from_value =  "<?php echo $this->ccc_option_default_from; ?>";
            var ccc_option_default_to_value =  "<?php echo $this->ccc_option_default_to; ?>";
            </script>
            <th scope="row"><label for="ccc_option_default_from"><?php echo esc_html__('Default "From" Field Value', 'cryptocurrency-converter'); ?></label></th>
            <td>
              <select id="nt_ccc_from" name="options[ccc_default_from]"></select>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="ccc_option_default_to"><?php echo esc_html__('Default "To" Field Value', 'cryptocurrency-converter'); ?></label></th>
            <td>
              <select id="nt_ccc_to" name="options[ccc_default_to]"></select>
            </td>
          </tr>

					<tr>
            <th scope="row"><label for="ccc_option_theme_section"><?php echo esc_html__('Select Theme', 'cryptocurrency-converter'); ?></label></th>
            <td>
							<p><label for="default-theme">
								<input type="radio" id="default-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == '') ? 'checked' : ''; ?> value="">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/default.png'; ?>" alt="Pomegranate">
								Default
							</label></p>
							<p><label for="green-theme">
								<input type="radio" id="green-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == 'green_sea') ? 'checked' : ''; ?> value="green_sea">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/green_sea.png'; ?>" alt="Pomegranate">
								Green Sea
							</label></p>
							<p><label for="orange-theme">
								<input type="radio" id="orange-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == 'orange') ? 'checked' : ''; ?> value="orange">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/orange.png'; ?>" alt="Pomegranate">
								Orange
							</label></p>
							<p><label for="blue-theme">
								<input type="radio" id="blue-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == 'peter_river') ? 'checked' : ''; ?> value="peter_river">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/peter_river.png'; ?>" alt="Pomegranate">
								Peter River
							</label></p>
							<p><label for="pomegranate-theme">
								<input type="radio" id="pomegranate-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == 'pomegranate') ? 'checked' : ''; ?> value="pomegranate">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/pomegranate.png'; ?>" alt="Pomegranate">
								Pomegranate
							</label></p>
							<p><label for="pumpkin-theme">
								<input type="radio" id="pumpkin-theme" name="options[ccc_option_theme]" <?php echo (get_option('ccc_option_theme') == 'pumpkin') ? 'checked' : ''; ?> value="pumpkin">
								<img style="height: 18px; vertical-align: bottom;" src="<?php echo plugin_dir_url( __FILE__ ).'/assets/images/pumpkin.png'; ?>" alt="pumpkin">
								Pumpkin
							</label></p>
              <p>- OR -</p>
              <p><label for="custom-color">
                <?php
                  $default_color = (get_option('ccc_option_custom_color')) ? get_option( 'ccc_option_custom_color' ) : '';
                ?>
                <input data-default-color=""  name="options[ccc_option_custom_color]" type="text" id="ccc_option_custom_color" value="<?php echo esc_attr( $default_color ); ?>" class="regular-text ccc-option-custom-color">
              </label></p>
            </td>
          </tr>


          <tr><th colspan="2"><hr> <h3><?php echo esc_html__('Custom Form Field Labels', 'cryptocurrency-converter'); ?></h3></th></tr>
          <tr>
            <th scope="row"><label for="ccc_option_amount_label"><?php echo esc_html__('Amount Field Label', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_amount_label]" type="text" id="ccc_option_amount_label" value="<?php echo (get_option('ccc_option_amount_label')) ? esc_attr(get_option('ccc_option_amount_label')) : esc_attr__('Amount', 'cryptocurrency-converter'); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="ccc_option_from_label"><?php echo esc_html__('From Field Label', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_from_label]" type="text" id="ccc_option_from_label" value="<?php echo (get_option('ccc_option_from_label')) ? esc_attr(get_option('ccc_option_from_label')) : esc_attr__('From', 'cryptocurrency-converter'); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="ccc_option_to_label"><?php echo esc_html__('To Field Label', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_to_label]" type="text" id="ccc_option_to_label" value="<?php echo (get_option('ccc_option_to_label')) ? esc_attr(get_option('ccc_option_to_label')) : esc_attr__('To', 'cryptocurrency-converter'); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="ccc_option_result_label"><?php echo esc_html__('Result Field Label', 'cryptocurrency-converter'); ?></label></th>
            <td><input name="options[ccc_result_label]" type="text" id="ccc_option_result_label" value="<?php echo (get_option('ccc_option_result_label')) ? esc_attr(get_option('ccc_option_result_label')) : esc_attr__('Result', 'cryptocurrency-converter'); ?>" class="regular-text"></td>
          </tr>

        </tbody>
      </table>
      <?php submit_button( __('Save Changes', 'cryptocurrency-converter') ); ?>
      </form>
    </div>

    <?php
  }

  static function ccc_save_options() {

		check_Admin_referer('cryptocurrency-converter-options');

		$data = stripslashes_deep($_POST['options']);
    update_option('ccc_option_api', sanitize_text_field($data['ccc_api']));
    update_option('ccc_option_default_from', sanitize_text_field($data['ccc_default_from']));
    update_option('ccc_option_default_to', sanitize_text_field($data['ccc_default_to']));
    update_option('ccc_options_widget_title', sanitize_text_field($data['ccc_widget_title']));
    update_option('ccc_option_amount_label', sanitize_text_field($data['ccc_amount_label']));
    update_option('ccc_option_from_label', sanitize_text_field($data['ccc_from_label']));
    update_option('ccc_option_to_label', sanitize_text_field($data['ccc_to_label']));
		update_option('ccc_option_result_label', sanitize_text_field($data['ccc_result_label']));
    update_option('ccc_option_theme', sanitize_text_field($data['ccc_option_theme']));
    
    update_option('ccc_option_custom_color', sanitize_text_field($data['ccc_option_custom_color']));

		wp_safe_redirect( add_query_arg('updated', 'true', wp_get_referer() ) );
	}

  public function ccc_enqueue_public_scripts() {
    $ajax_nonce = wp_create_nonce( "cryptocurrency_converter_nonce" );
    wp_enqueue_style( 'select2-css',  plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', '4.0.6' );
    wp_enqueue_style( 'ccc-main-css',  plugin_dir_url( __FILE__ ) . 'assets/css/ccc-main.css', '1.0' );

    wp_enqueue_script( 'select2-js',  plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', '4.0.6' );
    wp_enqueue_script( 'ccc-currencies',  plugin_dir_url( __FILE__ ) . 'assets/js/all.currencies.js', '1.0' );

    wp_register_script( 'ccc-main-js',  plugin_dir_url( __FILE__ ) . 'assets/js/ccc-main.js', '1.0' );
    wp_localize_script( 'ccc-main-js', 'cryptocurrency_converter', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'           => $ajax_nonce,
     ) );
    wp_enqueue_script( 'ccc-main-js' );
  }

  public function ccc_get_otptions() {
    $this->ccc_option_setting_api        = get_option( 'ccc_option_api' );
    $this->ccc_option_default_from       = get_option( 'ccc_option_default_from' );
    $this->ccc_option_default_to         = get_option( 'ccc_option_default_to' );
    $this->ccc_options_widget_title      = get_option( 'ccc_options_widget_title' );
    $this->ccc_option_amount_label       = get_option( 'ccc_option_amount_label' );
    $this->ccc_option_from_label         = get_option( 'ccc_option_from_label' );
    $this->ccc_option_to_label           = get_option( 'ccc_option_to_label' );
		$this->ccc_option_result_label       = get_option( 'ccc_option_result_label' );
    $this->ccc_option_theme              = get_option( 'ccc_option_theme' );
    $this->ccc_option_custom_color              = get_option( 'ccc_option_custom_color' );

  }

  public function ccc_shortcode($atts) {

    $this->ccc_get_otptions();
    $this->ccc_enqueue_public_scripts();

    $extract_shortcode = shortcode_atts(array(
      'title' => ''
    ), $atts);

    $override_widget_title = esc_html($extract_shortcode['title']);

    include_once plugin_dir_path( __FILE__ ) . 'includes/ccc_calculator_view.php';

    return $calc_template;
  }


  /*
  * Adjust color brightness
  * @params: color_code and brightness
  */
    public function ccc_adjust_color_brightness( $hex, $steps ) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));
        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        //#81d742-
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';
        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal

            $color   = max(0,min(255,$color + $steps)); // Adjust color

            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code

        }
        return $return;
    }


  public function ccc_get_ajax_data() {
    $this->ccc_get_otptions();

    $posted_data = $_POST;
    $posted_data = stripslashes_deep( $posted_data );

    if ( ! wp_verify_nonce( $_POST['nonce'], 'cryptocurrency_converter_nonce' ) ) {
      die( 'Security check' );
    }

    $default_api_key = isset($this->ccc_option_setting_api) ? $this->ccc_option_setting_api : $this->default_api_key;
    $from_value = isset($posted_data['from']) ? $posted_data['from'] : $this->ccc_option_default_from;
    $to_value = isset($posted_data['to']) ? $posted_data['to'] : $this->ccc_option_default_to;
    $amount_value = isset($posted_data['amount']) ? $posted_data['amount'] : '1';

    $from_value = sanitize_text_field($from_value);
    $to_value = sanitize_text_field($to_value);
    $amount_value = sanitize_text_field($amount_value);

    if(!$from_value && !$to_value ) {
      $error_output = json_encode(array(
        'error' => true,
        'msg' =>  __('Both From and To values are required.', 'cryptocurrency-converter'),
        'data' => ''
      ));
      echo $error_output;
      exit;
    }

    $post_url = 'https://orion.apiseeds.com/api/exchangerates/convert/'.$from_value.'/'.$to_value.'?amount='.$amount_value.'&apikey='.$default_api_key;
    $response = wp_remote_get( $post_url );
    if ( is_array( $response ) ) {
      $data = wp_remote_retrieve_body($response);
    }

    $result = json_decode( $data, true );
    $result = array_values( $result );

    if(is_array($result[2])){
      $result_data         = json_encode(array(
        'error' => false,
        'msg' =>  __('Data fetched successfully!', 'cryptocurrency-converter'),
        'data' => $result[2]
       ));
       echo $result_data;
    } else {
      $error_msg = isset($result[1]) ? $result[1] : 'An Error Occurred!';
      $error_output = json_encode(array(
        'error' => true,
        'msg' =>  __($error_msg, 'cryptocurrency-converter'),
        'data' => ''
      ) );
      echo $error_output;
    }
    exit;
  }
}

function ccc_admin_menu() {
  $wp_ccc_plugin = new CryptoCurrencyConverter;
  add_options_page(__('Cryptocurrency Converter', 'cryptocurrency-converter'), __('Cryptocurrency Converter', 'cryptocurrency-converter'), 'manage_options', 'cryptocurrency-converter', array($wp_ccc_plugin, 'ccc_admin_page'));
}

function ccc_init() {
  $wp_ccc_plugin = new CryptoCurrencyConverter;
  add_action('admin_post_cryptocurrency-converter-options', array($wp_ccc_plugin, 'ccc_save_options'));
}

$wp_ccc_plugin = new CryptoCurrencyConverter;
add_shortcode('Cryptocurrency_Converter', array($wp_ccc_plugin, 'ccc_shortcode'));
add_action( 'wp_ajax_cryptocurrency_converter_ajax', array($wp_ccc_plugin,'ccc_get_ajax_data' ));
add_action( 'wp_ajax_nopriv_cryptocurrency_converter_ajax', array($wp_ccc_plugin,'ccc_get_ajax_data' ));
add_action('admin_init', 'ccc_init');
add_action('admin_menu', 'ccc_admin_menu');

?>
