## Prerequisites

WP_Queue requires PHP __7.0+__.

To get started you must install first install the WP-Queue Library by either:

1. Adding the files to your own plugin and then including the `wp-queue.php` file.
2. Installing this library as a standalone plugin.


Then you must proceed to install the database tables by calling the `wp_queue_install_tables()` helper function. If using WP_Queue bundled into your own plugin, you may opt to call the helper from within your `register_activation_hook`. Alternatively, you may also opt to install the [WP Queue Debug](https://github.com/wp-queue/wp-queue-debug) add-on plugin which will also install the database tables upon plugin activation.

## Jobs

Job classes should extend the `WP_Queue\Job` class and normally only contain a `handle` method which is called when the job is processed by the queue worker. Any data required by the job should be passed to the constructor and assigned to a public property. This data will remain available once the job is retrieved from the queue. Let's look at an example job class:

```PHP
<?php

use WP_Queue\Job;

if ( ! class_exists( 'Subscribe_User_Job' ) {

	/**
	 * Subscribe_User_Job class.
	 *
	 * @extends Job
	 */
	class Subscribe_User_Job extends Job {

		/**
		 * @var int
		 */
		public $user_id;

		/**
		 * Subscribe_User_Job constructor.
		 *
		 * @param int $user_id
		 */
		public function __construct( $user_id ) {
			$this->user_id = $user_id;
		}

		/**
		 * Handle job logic.
		 */
		public function handle() {
			$user = get_user_by( 'ID', $this->user_id );

			// Process the user...
		}

	}
}
```

## Dispatching Jobs

Jobs can be pushed to the queue like so:

```PHP
<?php
wp_queue()->push( new Subscribe_User_Job( 12345 ) );
```

You can pass in a Category Name, and you can create delayed jobs by passing an optional second parameter to the `push` method. This job will be delayed by 60 minutes in a category called example-category:

```PHP
<?php
wp_queue()->push( new Subscribe_User_Job( 12345 ), 3600, 'example-category' );
```

## Cron Worker

Jobs need to be processed by a queue worker. You can start a cron worker like so, which piggy backs onto WP cron:

```PHP
<?php
if( wp_queue_has_jobs() ){ // Only trigger worker when jobs are available.
	wp_queue()->cron();
}
```

You can also specify the number of times a job should be attempted before being marked as a failure.

```PHP
<?php
if( wp_queue_has_jobs() ){
	wp_queue()->cron( 3 );
}
```

## Local Development

When developing locally you may want jobs processed instantly, instead of them being pushed to the queue. This can be useful for debugging jobs via Xdebug. Add the following filter to use the `sync` connection:

```PHP
<?php
if ( WP_DEBUG ) {
	add_filter( 'wp_queue_default_connection', function() {
		return 'sync';
	} );
}
```

## Troubleshooting

Error) *Class 'WP_Queue\Job' not found*.

Solution) Include your custom job class after wp-queue has been included. If using the library as a standalone plugin with your Job class defined in a separate plugin, you will want to include your Job files inside of the `plugins_loaded` action.

## Uninstall

When you are ready to uninstall WP Queue from your project you can completely remove the tables using the `wp_queue_uninstall_tables()` helper function, or simply empty the tables with the `wp_queue_empty_tables()` helper function.

## License

WP Queue is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
