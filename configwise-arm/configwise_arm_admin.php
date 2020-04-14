<?php 


class configwise_arm_admin {
	/**
	 * Holds the values to be used in the fields callbacks
	 */

	/**
	 * Start up
	 */
	public function __construct()
	{

		$this->option_name          = 'configwise_arm_name';
		$this->option_group_name    = 'configwise_arm_group';
		$this->option_page_name     = 'configwise_arm_page';
		$this->option_section_name  = 'configwise_arm_section';

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

		add_action( 'woocommerce_product_options_general_product_data', array($this,'configwise_product_ar_field' ));
		add_action( 'woocommerce_process_product_meta', array($this,'configwise_product_save_ar_field' ));
	}

	/**
	 * Display the custom text field
	 * @since 1.0.0
	 */
	function configwise_product_ar_field() {
		
		$product = wc_get_product(get_the_ID());

		$ar_enabled = $product->get_meta( 'configwise_product_ar_enabled' );

		$args = array(
			'id'            => 'configwise_product_ar_enabled',
			'label'         => __( 'Enable AR', 'cwar' ),
			'class'					=> 'cwar-enable-ar-field',
			'desc_tip'      => true,
			'description'   => __( 'Enable AR button for this product', 'cwar' ),
			'value' 		=> $ar_enabled
		);
		woocommerce_wp_checkbox( $args );
	}
	
	/**
 	* Save the custom field
 	* @since 1.0.0
 	*/
	function configwise_product_save_ar_field( $post_id ) {
		$product = wc_get_product( $post_id );
		$ar_enabled = isset( $_POST['configwise_product_ar_enabled'] ) ? $_POST['configwise_product_ar_enabled'] : '';
		
		$product->update_meta_data( 'configwise_product_ar_enabled', $ar_enabled );
		$product->save();
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'ConfigWise AR Module Settings Admin',
			'ConfigWise AR Module',
			'manage_options',
			$this->option_page_name, 
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option($this->option_name);

		?>
		<div class="wrap">
			<h1>ConfigWise AR Module</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( $this->option_group_name );
				do_settings_sections( $this->option_page_name );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			$this->option_group_name, // Option group
			$this->option_name, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
		add_settings_section(
			$this->option_section_name, // ID
			'ConfigWise AR Module settings', // Title
			array( $this, 'print_section_info' ), // Callback
			$this->option_page_name // Page
		);


		//Hooks fields
		//1: AR page
		
		add_settings_field(
			'configwise_ar_enable', // ID
			'Enable AR module on product page', // Title
			array( $this, 'ar_enable_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);

		add_settings_field(
			'configwise_deeplink_url', // ID
			'Deep Link URL', // Title
			array( $this, 'deeplink_url_callback' ), // Callback
			$this->option_page_name, // Page
			$this->option_section_name // Section
		);

        
	}


	/**
     *
	 */
	public function sanitize($input)
	{

		$new_input = array();
		
		if(isset($input['configwise_ar_enable']))
			$new_input['configwise_ar_enable'] = absint(intval($input['configwise_ar_enable']));

		if(isset($input['configwise_deeplink_url']))
			$new_input['configwise_deeplink_url'] = sanitize_text_field($input['configwise_deeplink_url']);

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Setup the ConfigWise AR Module button options here.';
	}

	/**
	 *
	 */
	public function configwise_ar_enable_callback()
	{
		printf(
			'<input type="checkbox" id="configwise_ar_enable" name="%s[configwise_ar_enable]" %s />',
			$this->option_name,
			isset( $this->options['configwise_ar_enable']) ? 'checked' : ''
		);
	}

	/**
	 *
	 */
	public function configwise_deeplink_url_callback()
	{
		printf(
			'<input type="text" id="configwise_deeplink_url" name="%s[configwise_deeplink_url]" value="%s" />',
			$this->option_name,
			isset( $this->options['configwise_deeplink_url']) ? $this->options['configwise_deeplink_url'] : 'https://configwise-ipd.app.link/AR'
		);
	}
}