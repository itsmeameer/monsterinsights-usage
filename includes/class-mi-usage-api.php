<?php
/**
 * The class responsible for handling all API calls and caching.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_API' ) ) {

	/**
	 * Handles all shortcodes.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_API {

		/**
		 * Holds instance of the main class MI_Usage.
		 *
		 * @var $MI_Usage
		 */
		private $mi_usage = null;

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->mi_usage = MI_Usage::get_instance();

		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		private function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'This action is forbidden!', 'monsterinsights-usage' ), '1.0.0' );
		}

		/**
		 * First checks if an API request should be made,
		 * then makes the API call and returns data from option.
		 *
		 * @param boolean $try_new      If true, will make a new API call, if enough time has passed.
		 * @param string  $formate_date The date format.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_data( $try_new = true, $formate_date = null ) {

			$plugin_slug = $this->mi_usage->get_plugin_slug( true );

			if ( ( $try_new && $this->should_request_for_new_data() ) || ! $try_new ) {
				$this->try_to_update_data();
			}

			$data = get_option( $plugin_slug . '_data' );

			// Formate date?
			$data = $data && ! is_null( $formate_date ) ? $this->formate_date( $data, $formate_date ) : $data;

			return $data;

		}


		/**
		 * Formate the date.
		 *
		 * @param array  $data     The data to formate.
		 * @param string $formate  The date format.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		private function formate_date( $data, $formate ) {

			$formate = 'default' === $formate ? $this->get_default_date_formate() : $formate;

			if ( isset( $data['data']['rows'] ) ) {
				foreach ( $data['data']['rows'] as $key => $value ) {
					$data['data']['rows'][ $key ]['date'] = date( $formate, $value['date'] );
				}
			}

			return $data;
		}

		/**
		 * Default headers.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public function get_default_headers() {
			return array(
				'ID',
				'First Name',
				'Last Name',
				'Email',
				'Date',
			);
		}

		/**
		 * Default date formate.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_default_date_formate() {
			return get_option( 'date_format' ) . ', ' . get_option( 'time_format' );
		}

		/**
		 * Returns the 'last saved' timestamp.
		 *
		 * @param string $formate The date format.
		 *
		 * @since 1.0.0
		 * @return string|false
		 */
		public function last_updated_on( $formate = null ) {

			$plugin_slug = $this->mi_usage->get_plugin_slug( true );
			$time        = get_option( $plugin_slug . '_data_last_saved_on' );

			if ( $time && ! is_null( $formate ) ) {
				$apply_formate = 'default' === $formate ? $this->get_default_date_formate() : $formate;
				return date( $apply_formate, $time );
			}

			return $time;
		}

		/**
		 * Checks if the API has passed the cooldown period.
		 *
		 * @since 1.0.0
		 * @return boolean
		 */
		private function should_request_for_new_data() {

			$plugin_slug = $this->mi_usage->get_plugin_slug( true );

			$last_updated_on = get_option( $plugin_slug . '_data_last_saved_on' );

			if ( ! $last_updated_on ) {
				// This is the first time the data is being requested.
				return true;
			}

			$cooldown = $last_updated_on + $this->mi_usage->get_api_cooldown_period();

			if ( time() < $cooldown ) {
				return false;
			}

			return true;

		}

		/**
		 * Requests the API for data.
		 *
		 * @since 1.0.0
		 * @return mixed
		 */
		private function try_to_get_fresh_data() {

			$api_uri = $this->mi_usage->get_api_uri();

			$request = wp_remote_get( $api_uri );

			if ( ! is_wp_error( $request ) ) {

				$body = wp_remote_retrieve_body( $request );
				$data = json_decode( $body, true );

				if ( $data ) {
					return $data;
				}
			}

			return false;

		}

		/**
		 * Validates the data received from the API.
		 *
		 * @param array $data The data received from the API.
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		private function validate_api_data( $data ) {

			if ( ! is_array( $data ) || empty( $data['data']['rows'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Saves the data to the database.
		 *
		 * @since 1.0.0
		 * @return boolean
		 */
		public function try_to_update_data() {

			// Try to get fresh data.
			$fresh_data = $this->try_to_get_fresh_data();

			if ( $fresh_data && $this->validate_api_data( $fresh_data ) ) {

				$plugin_slug = $this->mi_usage->get_plugin_slug( true );
				update_option( $plugin_slug . '_data', $fresh_data, false );

				$this->update_date();

				return true;
			}

			return false;

		}

		/**
		 * Updates the 'last saved' timestamp.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function update_date() {
			$plugin_slug = $this->mi_usage->get_plugin_slug( true );
			update_option( $plugin_slug . '_data_last_saved_on', time(), false );
		}

	}
}
