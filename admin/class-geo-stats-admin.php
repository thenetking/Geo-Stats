<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       micahpress.com
 * @since      1.0.0
 *
 * @package    Geo_Stats
 * @subpackage Geo_Stats/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Geo_Stats
 * @subpackage Geo_Stats/admin
 * @author     Micah Coffey <thenetking@gmail.com>
 */
class Geo_Stats_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Add new menu item to Wordpress Admin Settings Menu.
	 *
	 * @since    1.0.0
	 */
	function gs_add_settings_page() {
		add_options_page( 'User Geo Stats', 'User Geo Stats', 'manage_options', 'geo_stats', array( $this, 'gs_render_settings_page' )  );
	}

	/**
	 * Create settings page user is sent to when clicking the menu item.
	 *
	 * @since    1.0.0
	 */
	function gs_render_settings_page() {
		
		?>
		<form action="options.php" method="post">
			<?php 

				settings_fields( 'gs_options' );

				do_settings_sections( 'gs_settings' ); 

			?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php

	}
	
	/**
	 * Register configuration options
	 *
	 * Create gs_options entry in wp_options.
	 * Intro text description
	 * Population checkbox
	 * Income checkbox
	 *
	 * @since    1.0.0
	 */
	function gs_register_settings() {

		register_setting( 
			'gs_options', 
			'gs_options', 
			'array' 
		);

		add_settings_section( 
			'gs_api_settings', 
			'Add US Census data to user info', 
			array( $this, 'gs_settings_intro_text' ), 
			'gs_settings' 
		);
	
		add_settings_field( 
			'gs_options_api_key',
			'US Census API key:', 
			array( $this, 'gs_options_api_key' ), 
			'gs_settings', 
			'gs_api_settings'
		);

		add_settings_field( 
			'gs_options_population',
			'Pull user population? ', 
			array( $this, 'gs_options_population' ), 
			'gs_settings', 
			'gs_api_settings',
			[
				'label_for' => 'gs_options_population'
			]
		);

		add_settings_field( 
			'gs_options_income',
			'Pull user income? ', 
			array( $this, 'gs_options_income' ), 
			'gs_settings', 
			'gs_api_settings',
			[
				'label_for' => 'gs_options_income'
			]
		);

	}

	
	/**
	 * Intro text at top of settings page
	 *
	 * @since    1.0.0
	 */
	function gs_settings_intro_text() {

		echo '<p>Select which census data points you would like attached to users.</p>';
		echo '<p>Please enter a valid API key from the US Census</p>';
		echo '<p>A key can be request here <a target="_blank" href="https://api.census.gov/data/key_signup.html">https://api.census.gov/data/key_signup.html</a> and usually only takes a few minutes to be processed';

	}
	
	function gs_options_api_key() {

		$options = get_option( 'gs_options' );

		if (!isset($options['api_key'])) 
			$options['api_key'] = null;
		
		echo "<input id='gs_options_api_key' name='gs_options[api_key]' type='text' size='50' value='" . esc_attr( $options['api_key'] ) . "' />";

	}

	/**
	 * Checkbox to attach population data to user
	 *
	 * @since    1.0.0
	 */
	function gs_options_population() {

		$options = get_option( 'gs_options' );
		
		if (!isset($options['population'])) 
  			$options['population'] = 0;

		echo "<input type='checkbox' id='gs_options_population' name='gs_options[population]' value='1'";

		if ( $options['population'] == '1' ) {

			echo ' checked';

		}

		echo '/>';
	}
	
	/**
	 * Checkbox to attach income data to user
	 *
	 * @since    1.0.0
	 */
	function gs_options_income() {

		$options = get_option( 'gs_options' );

		if (!isset($options['income'])) 
  			$options['income'] = 0;

		echo "<input type='checkbox' id='gs_options_income' name='gs_options[income]' value='1'";

		if ( $options['income'] == '1' ) {

			echo ' checked';

		}

		echo '/>';
	}

	/**
	 * Add postal code field to user profile page
	 * 
	 * Add state dropdown to user profile page
	 * 
	 * TODO: Build array of states as well as add dynamic highlight to <option> for user's selected state
	 *
	 * @since    1.0.0
	 */
	function gs_add_postal_code_user_profile( $user ) {

		// $user is array when editing profile and string when adding new user
		if( is_array( $user ) ) {
			$postal_code = get_the_author_meta( 'postal_code', $user->ID );
			$short_state = get_the_author_meta( 'state_short', $user->ID );

		} else {
			$postal_code = null;
			$short_state = null;
		}

		?>
		<h3><?php _e("Postal Code", "blank"); ?></h3>

		<table class="form-table">
		<tr>
		<th><label for="postal_code"><?php _e("Postal Code"); ?></label></th>
			<td>
				<input type="text" name="postal_code" id="postal_code" value="<?php echo esc_attr( $postal_code ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e("Please enter your postal code."); ?></span>
			</td>
		</tr>
		<tr>
		<th><label for="state_short"><?php _e("State"); ?></label></th>
			<td>
				<select name="state_short">
					<option value="AL">Alabama</option>
					<option value="AK">Alaska</option>
					<option value="AZ">Arizona</option>
					<option value="AR">Arkansas</option>
					<option value="CA">California</option>
					<option value="CO">Colorado</option>
					<option value="CT">Connecticut</option>
					<option value="DE">Delaware</option>
					<option value="FL">Florida</option>
					<option value="GA">Georgia</option>
					<option value="HI">Hawaii</option>
					<option value="ID">Idaho</option>
					<option value="IL">Illinois</option>
					<option value="IN">Indiana</option>
					<option value="IA">Iowa</option>
					<option value="KS">Kansas</option>
					<option value="KY">Kentucky</option>
					<option value="LA">Louisiana</option>
					<option value="ME">Maine</option>
					<option value="MD">Maryland</option>
					<option value="MA">Massachusetts</option>
					<option value="MI">Michigan</option>
					<option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option>
					<option value="MO">Missouri</option>
					<option value="MT">Montana</option>
					<option value="NE">Nebraska</option>
					<option value="NV">Nevada</option>
					<option value="NH">New Hampshire</option>
					<option value="NJ">New Jersey</option>
					<option value="NM">New Mexico</option>
					<option value="NY">New York</option>
					<option value="NC">North Carolina</option>
					<option value="ND">North Dakota</option>
					<option value="OH">Ohio</option>
					<option value="OK">Oklahoma</option>
					<option value="OR">Oregon</option>
					<option value="PA">Pennsylvania</option>
					<option value="RI">Rhode Island</option>
					<option value="SC">South Carolina</option>
					<option value="SD">South Dakota</option>
					<option value="TN">Tennessee</option>
					<option value="TX">Texas</option>
					<option value="UT">Utah</option>
					<option value="VT">Vermont</option>
					<option value="VA">Virginia</option>
					<option value="WA">Washington</option>
					<option value="WV"  <?php if( esc_attr( $short_state ) == "WV" ) echo 'selected="selected"'; ?> >West Virginia</option>
					<option value="WI">Wisconsin</option>
					<option value="WY">Wyoming</option>
					</select>
									
				<span class="description"><?php _e("Please enter your state."); ?></span>
			</td>
		</tr>
		</table>
	<?php

	}

	/**
	 * Save postal code field on user profile page submit
	 *
	 * @since    1.0.0
	 */
	function gs_update_postal_code_user_profile( $user_id ) {

		$postal_code = filter_var( $_POST['postal_code'], FILTER_VALIDATE_INT );

		$state_short = filter_var( $_POST['state_short'], FILTER_SANITIZE_STRING );

		// Security check
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {

			return;

		}
		
		// Permissions check
		if ( !current_user_can( 'edit_user', $user_id ) ) { 

			return false; 

		}

		// Only allow 5 digit postal code
		if ( !preg_match( '/^\d{5}$/', $postal_code )) {

			return false;

		}

		//Save postal code with user record
		update_user_meta( $user_id, 'postal_code', $postal_code );
		
		// If 2 character state has been provided by dropdown
		if ( strlen( $state_short ) == 2 ) {

			// Save the user's state
			update_user_meta( $user_id, 'state_short', $state_short );

			// If both postal and state are set, call census for additional data

			// Get settings data to enable api calls
			$options = get_option( 'gs_options' );

			// Avoid error if setting has never been set
			if ( !isset($options['income']) )
				$options['income'] = 0;

			if ( !isset($options['population']) )
				$options['population'] = 0;


			if ( $options['income'] == 1 ) {

				// Call census API for imcome
				$this->gs_update_user_postal_code_income( $user_id, $postal_code, $state_short, 'B06011_001E' );

			} else {

				// Potential TODO: delete metadata if checkbox is disabled

			}

			if ( $options['population'] == 1 ) {

				// Call census API for population
				$this->gs_update_user_postal_code_population( $user_id, $postal_code, $state_short, 'B01001_001E' );

			} else {

				// Potential TODO: delete metadata if checkbox is disabled

			}

		}

	}

	/**
	 * Return error message on user profile page if postal code is malformed
	 *
	 * @since    1.0.0
	 */
	function check_user_profile_update($errors) {

		if ( $_POST['postal_code'] ) {
		$postal_code = filter_var( $_POST['postal_code'], FILTER_VALIDATE_INT );

		// Only allow 5 digit postal code
		if ( !preg_match( '/^\d{5}?/', $postal_code )) {

			$errors->add('postal_code_error',__('Postal code must be 5 numbers only and formated as xxxxx'));

			return $errors;

		}
	}

	}

	/**
	 * Call census data API for median household income by postal code
	 *
	 * @since    1.0.0
	 */
	function gs_update_user_postal_code_income( $user_id, $postal_code, $state_short, $api_group_label ) {

		// Call census API
		$api_data = $this->gs_get_api_data( $postal_code, $state_short, $api_group_label );

		if ( $api_data ) {

			// Format income number and add $
			$postal_income = "$" . number_format( $api_data );

			// Add results to database
			update_user_meta( $user_id, 'postal_income', $postal_income );

		} else {
			
			// "Log" error
			update_user_meta( $user_id, 'postal_income', 'API error' );

		}

	}

	/**
	 * Call census data API for community population by postal code
	 *
	 * @since    1.0.0
	 */
	function gs_update_user_postal_code_population( $user_id, $postal_code, $state_short, $api_group_label ) {

		// Call census API
		$api_data = $this->gs_get_api_data( $postal_code, $state_short, $api_group_label );

		if ( $api_data ) {

			// Format population number
			$postal_population = number_format( $api_data );

			// Add results to database
			update_user_meta( $user_id, 'postal_population', $postal_population );

		} else {
			
			// "Log" error
			update_user_meta( $user_id, 'postal_population', 'API error' );

		}

	}

	/**
	 * Call census data API
	 *
	 * @since    1.0.0
	 */
	function gs_get_api_data( $postal_code, $state_short, $api_group_label ) {

		// Call options to get API key
		$options = get_option( 'gs_options' );

		// Do not continue if key is not set
		if (!isset($options['api_key'])) {

			return false;
		}
		
		// Compile URL
		$api_url = 'https://api.census.gov/data/2019/acs/acs5?get=NAME,' . urlencode( $api_group_label ) .
			'&key=' . urlencode( $options['api_key'] ) .
			'&for=zip+code+tabulation+area:' . urlencode( $postal_code ) .
			'&in=state:' . urlencode( $this->convert_state_census_code( $state_short ) );
		
		// Call census url with zip and state
		$response = wp_remote_get( $api_url, array( 'timeout' => 5, 'sslverify' => false ) );

		// If successful
		if ( json_last_error() == JSON_ERROR_NONE ) {

			$body = json_decode($response['body']);

			// Get the data point we are looking for
			$api_data = $body[1][1];

			return $api_data;
			
		} else {
			
			return false;

		}
	}

	/**
	 * Add columns to Admin users list
	 *
	 * @since    1.0.0
	 */
	function gs_add_users_columns( $columns ) {

		// Add column for postal code
		$columns['sg_user_postal_code'] = __('Postal Code');

		// Get settings data to enable columns
		$options = get_option( 'gs_options' );

		// Avoid error if setting has never been set
		if ( !isset($options['income']) )
			$options['income'] = 0;

		if ( !isset($options['population']) )
			$options['population'] = 0;


		// If Geo Stats settings page has income checked
		if ( $options['income'] == 1 ) {

			// Add column for income
			$columns['sg_user_postal_income'] = __('Postal Income');

		}

		// If Geo Stats settings page has population checked
		if ( $options['population'] == 1 ) {

			// Add column for population
			$columns['sg_user_postal_population'] = __('Postal Population');

		}

		return $columns;
	}

	/**
	 * Add data to custom columns in Admin users list
	 *
	 * @since    1.0.0
	 */
	function gs_add_data_users_columns( $value, $column_name, $user_id ) {

		// Add user's postal code data to postal code column on users list
		if ( 'sg_user_postal_code' == $column_name ) {

			if( $postal_code = get_user_meta( $user_id, 'postal_code', true ) ) {

				$value = $postal_code;

			} else {

				$value = '';

			}

		// Add user's income data to income column on users list
		} elseif ( 'sg_user_postal_income' == $column_name ) {

			if( $postal_income = get_user_meta( $user_id, 'postal_income', true ) ) {

				$value = $postal_income;

			} else {

				$value = '';

			}

		// Add user's population data to population column on users list
		} elseif ( 'sg_user_postal_population' == $column_name ) {

			if( $postal_population = get_user_meta( $user_id, 'postal_population', true ) ) {

				$value = $postal_population;

			} else {

				$value = '';

			}
		}
	
		return $value;
	}

	/**
	 * Convert state abbreviation to census state code
	 * 
	 * TODO: Use array from state dropdown to also return census code for more than WV
	 *
	 * @since    1.0.0
	 */
	function convert_state_census_code( $state_short ) {

		switch( $state_short ) {
			case "WV":
				return 54;
				break;
			
				/*
				* TODO
				AL 01
				AK 02
				AZ 04
				AR 05
				CA 06
				CO 08
				CT 09
				DE 10
				FL 12
				GA 13
				HI 15
				ID 16
				IL 17
				IN 18
				IA 19
				KS 20
				KY 21
				LA 22
				ME 23
				MD 24
				MA 25
				MI 26
				MN 27
				MS 28
				MO 29
				MT 30
				NE 31
				NV 32
				NH 33
				NJ 34
				NM 35
				NY 36
				NC 37
				ND 38
				OH 39
				OK 40
				OR 41
				PA 42
				RI 44
				SC 45
				SD 46
				TN 47
				TX 48
				UT 49
				VT 52
				VA 51
				WA 53
				DC 11
				WV 54
				WI 55
				WY 56
				*/

		}
	}

	/**
	 * Register stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/geo-stats-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/geo-stats-admin.js', array( 'jquery' ), $this->version, false );

	}

}
