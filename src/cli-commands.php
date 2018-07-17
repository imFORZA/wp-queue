<?php

if ( defined( 'WP_CLI' ) && WP_CLI ) {



/**
 * Implements example command.
 */
class WP_Queue_Command extends WP_CLI_Command {

	/* TODO:
	*
	* Command to list jobs
	* Command to list failures
	* Command to process job
	* etc
	*
	*/


    function hello( $args, $assoc_args ) {
        list( $name ) = $args;

        // Print the message with type
        $type = $assoc_args['type'];
        WP_CLI::$type( "Hello, $name!" );
    }
}

// https://make.wordpress.org/cli/handbook/internal-api/wp-cli-add-command/
WP_CLI::add_command( 'queue', 'WP_Queue_Command', array(
	'shortdesc' => 'Useful commands to manage jobs in WP Queue.',
	'longdesc' => 'Useful commands to manage jobs in WP Queue.'
) );

}
