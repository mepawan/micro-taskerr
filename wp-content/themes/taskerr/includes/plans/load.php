<?php

require dirname( __FILE__ ) . '/plans.php';
if( is_admin() ) {
	require dirname( __FILE__ ) . '/admin.php';

	new TR_Pricing_General_Box;
	new TR_Pricing_Addon_Box;
}
?>
