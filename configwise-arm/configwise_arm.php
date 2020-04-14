<?php

class configwise_arm{

	public  $option_name; //SAME as in configwise_arm_admin.php

	public function __construct() {

		$this->option_name = 'configwise_arm_name';  //SAME as in configwise_arm_admin.php

		add_action( 'woocommerce_after_main_content', array($this, 'place_ar_button'), 5 );
		
	}


	public function get_sku(){

		$product = wc_get_product(get_the_ID());
		$sku = $product->get_sku();
		return $sku;
	}

	public function place_ar_button() {

		if ($this->get_option_bool('configwise_ar_enable')){

			$product = wc_get_product(get_the_ID());
			$ar_enabled = $product->get_meta( 'configwise_product_ar_enabled' );

			if($ar_enabled){
				$sku = $this->get_sku();

				$base_path = $this->get_option( 'configwise_deeplink_url' );
				$arbutton_title = "View in AR";

				$html = '';


				if ( wp_is_mobile() ) { /* Display and echo mobile specific stuff here */
					$url = $base_path.'?productNr='. $sku .'&amp;android_passive_deepview=false&amp;ios_passive_deepview=false" class="button is-alternate is-fullwidth';
					$html .=  '<div id="configwise_btn"><a href="'.$url.'" class="button is-alternate is-fullwidth">'.$arbutton_title.'</a></div>';


					
				}
				else {
	    		/* Display and echo desktop stuff here */	


					$url = $base_path.'?productNr='.$sku.'&android_passive_deepview=false&ios_passive_deepview=false';
	   				$encodedUrl = urlencode($url);

	                $html .= '<h3>' . $arbutton_title . '</h3>';
	   				$html .= '<img src="https://chart.googleapis.com/chart?chs=300x300&amp;cht=qr&amp;chl=' .$encodedUrl . '&amp;choe=UTF-8" title="AR">';

				}
				echo $html;
			}
		}
	}


	public function get_option_bool($key){

		$options = get_option($this->option_name);
		if (isset($options[$key])){
			return true;
		} else {
			return false;
		}
	}


	public function get_option($key){

		$options = get_option($this->option_name);
		return $options[$key];
	}

}

