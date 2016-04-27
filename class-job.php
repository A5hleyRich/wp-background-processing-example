<?php

class WP_Example_Job extends WP_Job {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * WP_Example_Job constructor.
	 *
	 * @param $name
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	public function handle() {
		$rand = rand( 1, 5 );

		if ( 3 === $rand ) {
			throw new Exception();
		}

		$message = $this->get_message( $this->name );

		$this->really_long_running_task();
		$this->log( $message );
	}

	/**
	 * Really long running process
	 *
	 * @return int
	 */
	protected function really_long_running_task() {
		return sleep( 5 );
	}

	/**
	 * Log
	 *
	 * @param string $message
	 */
	protected function log( $message ) {
		error_log( $message );
	}

	/**
	 * Get lorem
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected function get_message( $name ) {
		$response = wp_remote_get( esc_url_raw( 'http://loripsum.net/api/1/short/plaintext' ) );
		$body     = trim( wp_remote_retrieve_body( $response ) );

		if ( empty( $body ) ) {
			$body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quippe: habes enim a rhetoribus; Eaedem res maneant alio modo.';
		}

		return $name . ': ' . $body;
	}

}