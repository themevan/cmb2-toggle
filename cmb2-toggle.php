<?php
/**
 * Plugin Name: CMB2 Toggle
 * Plugin URI: https://github.com/themevan/cmb2-toggle
 * Description: CMB2 toggle field.
 * Author: ThemeVan
 * Version: 1.0.0
 * Author URI: http://themevan.com
 * Requires at least: 5.4
 * Tested up to: 6.0
 * Requires PHP: 5.6
 * Text Domain: cmb2-toggle
 *
 * @package CMB2_Toggle
 */

defined( 'ABSPATH' ) || exit();

function tv_cmb2_toggle_render_field( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
  $field_name   = $field->_name();
  $active_value = null !== $field->args( 'active_value' ) ? ( ! empty( $field->args( 'active_value' ) ) ? $field->args( 'active_value' ) : 'on' ) : 'on';
  $inactive_value = null !== $field->args( 'inactive_value' ) ? ( ! empty( $field->args( 'inactive_value' ) ) ? $field->args( 'inactive_value' ) : 'off' ) : 'off';

  $checkbox_value = ( $escaped_value == $active_value ) ? $active_value : $inactive_value;

  $args = array(
    'type'  => 'checkbox',
    'id'    => $field_name,
    'name'  => $field_name,
    'desc'  => '',
    'value' => $active_value,
  );

  if ( $escaped_value === $active_value ) {
    $args['checked'] = 'checked="checked"';
  } else {
    $args['checked'] = '';
  }

  echo '<label class="cmb2-toggle">
        <input type="checkbox" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $checkbox_value ) . '" data-inactive-value="' . esc_attr( $inactive_value ) . '" data-active-value="' . esc_attr( $active_value ) . '" ' . $args['checked'] . ' />
        <span class="cmb2-toggle-slider round"></span>
        </label>';

  $field_type_object->_desc( true, true );
}

add_action( 'cmb2_render_toggle', 'tv_cmb2_toggle_render_field', 10, 5 );

function tv_cmb2_toggle_add_assets() {
  global $_wp_admin_css_colors;

  if ( ! empty( $_wp_admin_css_colors[ get_user_option( 'admin_color' ) ] ) ) {
    $scheme_colors = $_wp_admin_css_colors[ get_user_option( 'admin_color' ) ]->colors;
  }

  $toggle_color = ! empty( $scheme_colors ) ? end( $scheme_colors ) : '#2196F3';
  ?>
    <script>
      jQuery(document).ready(function($){

        $('.cmb2-toggle').each(function(){
          var checkbox = $(this).find('input[type="checkbox"]');
          var inactiveValue = checkbox.data('inactive-value');
          var activeValue = checkbox.data('active-value');

          $(this).on('click', function(){
            if(checkbox.prop('checked')) {
              checkbox.val(activeValue);
            }else{
              checkbox.val(inactiveValue);
            }
          });
        });

      });	
    </script>
				
    <style>
    .cmb2-toggle {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    .cmb2-toggle input {display:none;}

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

add_action( 'admin_head', 'tv_cmb2_toggle_add_assets' );

function tv_cmb2_toggle_sanitize_field( $override_value, $value, $object_id, $field_args  ) {
  $active_value = isset( $field_args['active_value'] ) && null !== $field_args['active_value'] ? ( ! empty( $field_args['active_value'] ) ? $field_args['active_value'] : 'on' ) : 'on';
  $inactive_value = isset( $field_args['inactive_value'] ) && null !== $field_args['inactive_value'] ? ( ! empty( $field_args['inactive_value'] ) ? $field_args['inactive_value'] : 'off' ) : 'off';

  if ( $value !== $active_value ) {
    $value = $inactive_value;
  }

  return $value;
}

add_action( 'cmb2_sanitize_toggle', 'tv_cmb2_toggle_sanitize_field', 10, 4 );
