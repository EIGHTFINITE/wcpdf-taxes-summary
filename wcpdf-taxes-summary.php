<?php
/**
 * Plugin Name:      PDF Invoices & Packing Slips for WooCommerce - Taxes Summary
 * Requires Plugins: woocommerce-pdf-invoices-packing-slips
 * Plugin URI:       https://github.com/EIGHTFINITE/wcpdf-taxes-summary
 * Description:      Add a taxes summary table to the PDF Invoices & Packing Slips for WooCommerce plugin.
 * Version:          1.0.0
 * Author:           Kevin van Rijn
 * Author URI:       https://github.com/EIGHTFINITE/wcpdf-taxes-summary
 * License:          GPLv2 or later
 * License URI:      https://github.com/EIGHTFINITE/wcpdf-taxes-summary/blob/main/LICENSE
 */

add_action( 'wpo_wcpdf_after_order_details', 'wpo_wcpdf_display_taxes_summary', 12, 2 );

function wpo_wcpdf_display_taxes_summary( $document_type, $order ) {
	$allowed_document_types = apply_filters( 'wpo_wcpdf_taxes_summary_allowed_document_types', array( 'invoice' ) );

	if ( ! in_array( $document_type, $allowed_document_types ) || ! $order || $order->get_payment_method() !== 'bacs' ) {
		return;
	}

	$general_settings = get_option( 'wpo_wcpdf_settings_general' );
	if( $general_settings && isset( $general_settings['extra_1'] ) && isset( $general_settings['extra_1']['default'] ) ) {
		$extra_1 = (String) $general_settings['extra_1']['default'];
		$extra_1 = htmlspecialchars( wp_check_invalid_utf8( $extra_1 ) );
		$extra_1 = preg_replace( '/(\r\n|\r|\n)/', '<br>', $extra_1 );
		$extra_1 = str_replace( array( '&lt;b&gt;', '&lt;/b&gt;', '&lt;i&gt;', '&lt;/i&gt;' ), array( '<b>', '</b>', '<i>', '</i>' ), $extra_1 );
		$extra_1 = trim( preg_replace( '/\s+/', ' ', $extra_1 ) );
	}
	else {
		$extra_1 = '';
	}

	// output
	?>
		<div><?php echo $extra_1; ?></div>
	<?php
}
