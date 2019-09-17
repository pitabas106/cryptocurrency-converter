<?php
/* *
* Author: NetTantra
* @package         cryptocurrency-converter
*/

defined( 'ABSPATH' ) or die();
?>

<script type="text/javascript">
var ccc_option_default_from_value =  "<?php echo $this->ccc_option_default_from; ?>";
var ccc_option_default_to_value =  "<?php echo $this->ccc_option_default_to; ?>";
var ccc_option_theme =  "<?php echo $this->ccc_option_theme; ?>";
</script>

<?php
  if($this->ccc_option_theme == 'green_sea') {
    $theme_bg_color = '#16a085';
    $theme_border_color = '#16a085';
    $theme_boxshadow_color = '#7cc7b2';
    $theme_selected_bg_color = '#1abc9c';
  }
  if($this->ccc_option_theme == 'orange') {
    $theme_bg_color = '#f39c12';
    $theme_border_color = '#f39c12';
    $theme_boxshadow_color = '#f1c40f';
    $theme_selected_bg_color = '#ffbb4e';
  }
  if($this->ccc_option_theme == 'peter_river') {
    $theme_bg_color = '#3498db';
    $theme_border_color = '#3498db';
    $theme_boxshadow_color = '#5cabe0';
    $theme_selected_bg_color = '#52b2f3';
  }
  if($this->ccc_option_theme == 'pomegranate') {
    $theme_bg_color = '#c0392b';
    $theme_border_color = '#c0392b';
    $theme_boxshadow_color = '#da4c32';
    $theme_selected_bg_color = '#de4629';
  }
  if($this->ccc_option_theme == 'pumpkin') {
    $theme_bg_color = '#d35400';
    $theme_border_color = '#d35400';
    $theme_boxshadow_color = '#ef7e2e';
    $theme_selected_bg_color = '#ea6a0f';
  }

  if( $this->ccc_option_custom_color ) {

    $custom_color = $this->ccc_option_custom_color;
    $this->ccc_option_theme = $custom_color; //set custom color to option_theme

    $theme_bg_color = $custom_color;
    $theme_border_color = $this->ccc_adjust_color_brightness( $custom_color, 60 );
    $theme_boxshadow_color = $this->ccc_adjust_color_brightness( $custom_color, 100 );
    $theme_selected_bg_color = $custom_color;

  }


?>
<?php if($this->ccc_option_theme) : ?>
  <style media="screen">
  body .nt-ccc-widget {
    background: <?php echo $theme_bg_color; ?>;
    color: #FFF;
    padding: 20px;
    box-shadow: 1px 1px 8px <?php echo $theme_boxshadow_color; ?>;
  }
  body .nt-ccc-widget .ccc-title {
    margin-top: 0;
  }
  
  <?php if( $this->ccc_option_custom_color ) :?>
    body #nt_ccc_result.ccc-loading {
      background-color: <?php echo $this->ccc_adjust_color_brightness( $custom_color, 210 ); ?>; 
    }
  <?php endif; ?>

  .select2-container--default .ccc-select2-dropdown .select2-search--dropdown .select2-search__field,
  .nt-ccc-widget .select2-container--default .ccc-select2-dropdown .select2-selection--single,
  .nt-ccc-widget input[type="text"],
  .nt-ccc-widget input[type="number"] {
    border: 1px solid <?php echo $theme_border_color; ?>;
  }
  .select2-container--default .ccc-select2-dropdown .select2-results__option[aria-selected=true] {
    background-color: <?php echo $theme_selected_bg_color; ?>;;
    color: #FFF;
  }
   .select2-container--default .ccc-select2-dropdown .select2-results__option--highlighted[aria-selected],
  .nt-ccc-widget .select2-container--default .ccc-select2-dropdown .select2-results__option--highlighted[aria-selected] {
    background-color: <?php echo $theme_bg_color; ?>;
  }

  .select2-container--default.select2-container--open .ccc-select2-dropdown .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent <?php echo $theme_bg_color; ?> transparent;
  }
  </style>

<?php endif; ?>

<?php
$ccc_option_theme_class = ($this->ccc_option_theme) ? 'ccc-theme-'.$this->ccc_option_theme : '';

$calc_template = '';
$calc_template .= '<div class="nt-ccc-widget '.$ccc_option_theme_class.'">';
$ccc_widget_title = ($override_widget_title) ? $override_widget_title : $this->ccc_options_widget_title;
if($ccc_widget_title) {
  $calc_template .= '<h3 class="ccc-title">'.esc_html__($ccc_widget_title, 'cryptocurrency-converter').'</h3>';
}
$ccc_option_amount_label = ($this->ccc_option_amount_label) ? $this->ccc_option_amount_label : 'Amount';
$ccc_option_from_label = ($this->ccc_option_from_label) ? $this->ccc_option_from_label : 'From';
$ccc_option_to_label = ($this->ccc_option_to_label) ? $this->ccc_option_to_label : 'To';
$ccc_option_result_label = ($this->ccc_option_result_label) ? $this->ccc_option_result_label : 'Result';


$calc_template .= '<p class="ccc-col">
  <label for="nt_ccc_amount">'.esc_html__($ccc_option_amount_label, 'cryptocurrency-converter' ).'</label>
  <input type="number" min="1" max="999" id="nt_ccc_amount" value="1">
</p>';

$calc_template .= '<p class="ccc-col">
<label for="nt_ccc_from">'.esc_html__($ccc_option_from_label, 'cryptocurrency-converter' ).'</label>
<select id="nt_ccc_from"></select>
</p>';

$calc_template .= '<p class="ccc-col">
<label for="nt_ccc_to">'.esc_html__($ccc_option_to_label, 'cryptocurrency-converter' ).'</label>
<select id="nt_ccc_to"></select>
</p>';
$calc_template .= '<p class="ccc-col result">
<label for="nt_ccc_result">'.esc_html__($ccc_option_result_label, 'cryptocurrency-converter' ).'</label>
<input type="text" id="nt_ccc_result" readonly placeholder="'.esc_html__($ccc_option_result_label, 'cryptocurrency-converter' ).'" value="">
</p>';

$calc_template .= '</div>';
?>
