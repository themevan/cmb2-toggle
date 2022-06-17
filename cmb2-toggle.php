<?php
/**
 * Plugin Name: CMB2 Toggle
 * Plugin URI: https://github.com/themevan/cmb2-toggle/
 * Description: CMB2 toggle field.
 * Author: ThemeVan
 * Version: 1.0.2
 * Author URI: https://themevan.com/
 * Requires at least: 5.4
 * Tested up to: 6.0
 * Requires PHP: 5.6
 * Text Domain: cmb2-toggle
 *
 * @package CMB2_Toggle
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'TV_CMB2_Toggle' ) ) {

  /**
   * Class TV_CMB2_Toggle.
   */
  class TV_CMB2_Toggle {
    public function __construct() {
      add_action( 'cmb2_render_toggle', array( $this, 'render_field' ), 10, 5 );
      add_action( 'admin_head', array( $this, 'add_style' ) );
    }

    public function render_field( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
      $field_name = $field->_name();

      $return_value = 'on';

      if ( $field->args( 'return_value' ) && ! empty( $field->args( 'return_value' ) ) ) {
        $return_value = $field->args( 'return_value' );
      }

      $args = array(
        'type'  => 'checkbox',
        'id'    => $field_name,
        'name'  => $field_name,
        'desc'  => '',
        'value' => $return_value,
      );

      echo '<label class="cmb2-toggle">';
      echo '<input type="checkbox" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $return_value ) . '" ' . checked( $escaped_value, $return_value, false ) . ' />';
      echo '<span class="cmb2-toggle-slider round"></span>';
      echo '</label>';

      $field_type_object->_desc( true, true );
    }

    public function add_style() {
      global $_wp_admin_css_colors;

      $color_scheme = get_user_option( 'admin_color' );

      $scheme_colors = array();
  
      if ( isset( $_wp_admin_css_colors[ $color_scheme ] ) && ! empty( $_wp_admin_css_colors[ $color_scheme ] ) ) {
        $scheme_colors = $_wp_admin_css_colors[ $color_scheme ]->colors;
      }

      $toggle_color = ! empty( $scheme_colors ) ? end( $scheme_colors ) : '#72aee6';
      ?>
        <style>
        .cmb2-toggle {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 24px;
        }

        .cmb2-toggle input {
          display: none;
        }

        .cmb2-toggle-slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .cmb2-toggle-slider:before {
          position: absolute;
          content: "";
          height: 16px;
          width: 16px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        #side-sortables .cmb-row .cmb2-toggle + .cmb2-metabox-description {
          padding-bottom: 0;
        }

        input:checked + .cmb2-toggle-slider {
          background-color: <?php echo $toggle_color ?>;
        }

        input:focus + .cmb2-toggle-slider {
          box-shadow: 0 0 1px <?php echo $toggle_color ?>;
        }

        input:checked + .cmb2-toggle-slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }

        .cmb2-toggle-slider.round {
          border-radius: 34px;
        }

        .cmb2-toggle-slider.round:before {
          border-radius: 50%;
        }
        </style>
      <?php
    }
  }
}

new TV_CMB2_Toggle();
