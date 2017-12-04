<?php

/**
 * Plugin Name: Desconto automático Weasy
 * Plugin URI: http://www.qrcomunicacao.com.br
 * Description: Desconto automático Weasy
 * Version: 1.0
 * Author: QR Comunicação
 * Author URI: http://www.qrcomunicacao.com.br
 */
 
/* DESCONTO AUTOMATICO NO CARRINHO */
	function weasy_bulk_discount_coupons() {
		global 	$woocommerce; // [a]
		if (is_cart() || is_checkout()) :
			if (weasy_is_individual_coupon_applied() != true) :
				$10boleto = '10% discount'; // [b]
				$natal15off  = '15% discount'; // [b]
				$50weasy  = '50% discount'; // [b]
				$cart_contents_count = $woocommerce->cart->cart_contents_count; // Get cart contents
				
				/**
				 * Check quantities and add coupons if valid
				 *
				 * [1]	If cart has 1 or less items
				 * 		[a]	Remove 50% coupon
				 *
				 * [2]	If cart has 2 or more items
				 * 		[a]	If 15% coupon is applied
				 * 			[i]		Remove 15% coupon
				 * 			[ii]	Apply 50% coupon
				 * 		[b]	If 50% coupon is applied, do nothing
				 * 		[c]	If neither coupon is applied
				 * 			[i]		Add 50% coupon
				 * 
				 * [3]	If payment is boleto
				 * 		[a]	Apply 10% coupon
				 */
				
				if ($cart_contents_count <= 1) : // [1]
					if ($woocommerce->cart->has_discount($50weasy)) : $woocommerce->cart->remove_coupon($50weasy); endif; // [1][a]
				
				else : // [2]
					if ($cart_contents_count >= 2) : // [1]
					if ($woocommerce->cart->has_discount($natal15off)) : // [2][a]
						$woocommerce->cart->remove_coupon($natal15off); // [2][a][i]
						$woocommerce->cart->add_discount($50weasy); // [2][a][ii]
					
					elseif ($woocommerce->cart->has_discount($50weasy)) : // [2][b]
						// do nothing();
					else : // [2][c]
						$woocommerce->cart->add_discount($50weasy); // [2][c][i]
					endif;
				endif;

			
			else : // If individual coupons are applied, remove default coupons
				if     ($woocommerce->cart->has_discount($natal15off)) : $woocommerce->cart->remove_coupon($natal15off);
				endif;
			endif;
		endif;
	}
	
	
	add_action('wp_head', 'weasy_bulk_discount_coupons');
	
?>
