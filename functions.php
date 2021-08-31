<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

 // Add a custom product setting tab to edit product pages options FOR SIMPLE PRODUCTS only
  add_filter( 'woocommerce_product_data_tabs', 'stuff_new_product_data_tab', 50, 1 );
  function stuff_new_product_data_tab( $tabs ) {
      $tabs['stuff'] = array(
          'label' => __( 'Special Stuff', 'woocommerce' ),
          'target' => 'stuff_product_data', // <== to be used in the <div> class of the content
          'class' => array('show_if_simple'), // or 'hide_if_simple' or 'show_if_variable'…
      );

      return $tabs;
  }

  // Add/display custom Fields in the custom product settings tab
 add_action( 'woocommerce_product_data_panels', 'add_custom_fields_product_options_stuff', 10 );
 function add_custom_fields_product_options_stuff() {
     global $post;

     echo '<div id="stuff_product_data" class="panel woocommerce_options_panel">'; // <== Here we use the target attribute

     //Adding some stuff in our custom settings tab
     //Title for custom field
          $args = array(
           'id' => 'custom_text_field_title',
           'label' => __( 'Custom Text Field Title', 'cfwc' ),
           'class' => 'cfwc-custom-field',
           'desc_tip' => true,
           'description' => __( 'Enter the title of your custom text field.', 'ctwc' ),
        );
         woocommerce_wp_text_input( $args );
       // Description for custom field
         $args = array(
          'id' => 'custom_text_field_description',
          'label' => __( 'Custom Text Field Description', 'cfwc' ),
          'class' => 'cfwc-custom-field',
          'desc_tip' => true,
          'description' => __( 'Enter the title of your custom text field.', 'ctwc' ),
       );
        woocommerce_wp_text_input( $args );
      }



      add_action( 'woocommerce_product_data_panels', 'add_custom_fields_product_options_stuff' );
      /**
      * Saves the custom field data to product meta data
      */
       function cfwc_save_custom_field( $post_id ) {
         $product = wc_get_product( $post_id );
         $title = isset( $_POST['custom_text_field_title'] ) ? $_POST['custom_text_field_title'] : '';
         $product->update_meta_data( 'custom_text_field_title', sanitize_text_field( $title ) );
         $product->save();

         $product = wc_get_product( $post_id );
         $description = isset( $_POST['custom_text_field_description'] ) ? $_POST['custom_text_field_description'] : '';
         $product->update_meta_data( 'custom_text_field_description', sanitize_text_field( $description ) );
         $product->save();


       }
      add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );

      /**
     * Displays custom field data after the add to cart button
     */
     function cfwc_display_custom_field() {
       global $post;

     // Check for the custom field value
       $product = wc_get_product( $post->ID );
       $title = $product->get_meta( 'custom_text_field_title' );
         if( $title ) {
           echo "<br >";
           echo get_post_meta($post->ID, 'custom_text_field_title', true);
           echo "<br >";
         }

         // Check for the custom field value
           $product = wc_get_product( $post->ID );
           $description = $product->get_meta( 'custom_text_field_description' );
             if( $description ) {
               echo get_post_meta($post->ID, 'custom_text_field_description', true);
             }


     }
     add_action( 'woocommerce_after_add_to_cart_button', 'cfwc_display_custom_field' );
