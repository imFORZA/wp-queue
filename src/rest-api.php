<?php

add_action( 'admin_menu', 'wp_queue_failures_menu' );

function wp_queue_failures_menu() {
	add_options_page( 'WP Queue Failures', 'WP Queue Failures', 'manage_options', 'wp-queue-failures', 'wp_queue_failures_page' );
}

function wp_queue_failures_page(){
	// TODO: https://premium.wpmudev.org/blog/wordpress-admin-tables/

	wp_enqueue_script( 'data-tables', 'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js', array('jquery'), null, true );


	?>
	<div class="wrap">
		<h2>WP Queue</h2>

		<style>
			.dataTables_info {
				float: right;
				margin-right: 7px;
				padding: 10px;
			}
			.dataTables_paginate {
				float: right;
			    height: 28px;
			    margin-top: 3px;
			    cursor: default;
			    color: #555;
			}
			th.sorting, th.sorting_desc, th.sorting_asc {
				color: #00a0d2 !important;
			}
			.sorting_asc::after {
			    content: "\f142";
			    font: 400 20px/1 dashicons;
			    speak: none;
			    display: inline-block;
			    -webkit-font-smoothing: antialiased;
			    -moz-osx-font-smoothing: grayscale;
			    text-decoration: none!important;
			    color: #444;
			    vertical-align: bottom;
			}
			.sorting_desc::after {
			    content: "\f140";
			    font: 400 20px/1 dashicons;
			    display: inline-block;
			    -webkit-font-smoothing: antialiased;
			    -moz-osx-font-smoothing: grayscale;
			    text-decoration: none!important;
			    color: #444;
			    vertical-align: bottom;
			}

			div.dataTables_filter input {
				    float: right;
					height: 28px;
					margin: 5px 0;
			}

			/* Not Best Idea. */
			#wp-queue-jobs-table {
				width: 100% !important;
			}
		</style>


	<?php

		if ( false === ( $failures = wp_cache_get( 'wp_queue_job_failures' ) ) ) {
			$failures = wp_queue_get_job_failures();
			wp_cache_set( 'wp_queue_job_failures', $failures, 'wp_queue_job_failures', '86000' );
		}

		$data = array();

		foreach( $failures as $job ) {
			$obj  = array(
				'id'         	=> $job->id ?? '',
				'job' 			=> $job->job ?? '',
				'error'  		=> $job->error ?? '',
				'failed_at'     => $job->failed_at ?? '',
			);

			$data[] = $obj;
		}

		wp_localize_script(
			'data-tables', 'jobs_data', array( 'data' => $data )
		);

		echo '<table class="table wp-list-table widefat fixed striped" id="wp-queue-jobs-table"></table>';
	?>

<script async>
jQuery(function(){
	var table = jQuery("#wp-queue-jobs-table").DataTable({
		data   : jobs_data.data,
		columns: [
			{
				data  : 'id',
				title : 'ID',
			},
			{
				data  : 'job',
				title : 'Job',
			},
			{
				data  : 'error',
				title : 'Error',
			},
			{
				data  : 'failed_at',
				title : 'Failed At',
			},
		],
		pageLength: 10,
		pagingType: "full",
		language: {
			search: '',
			searchPlaceholder: 'Search ...',
			info: '_START_ to _END_ of _TOTAL_ items',
			zeroRecords: "<strong>No items could be found.</strong>",
			lengthMenu: '',
			oPaginate: {
				sNext: '<span class="tablenav-pages-navspan next-page">›</span>',
				sPrevious: '<span class="tablenav-pages-navspan">‹</span>',
				sFirst: '<a class="tablenav-pages-navspan first-page"><span class="screen-reader-text">First page</span><span aria-hidden="true">«</span></a>',
				sLast: '<a class="tablenav-pages-navspan" last-page"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a>',
			}
		}
	});
	/*
	jQuery('#wp-queue-jobs-table').append(
	    jQuery('<tfoot/>').append(jQuery('#wp-queue-jobs-table thead tr').clone());
	);

	// Handle the click.

	jQuery("#wp-queue-jobs-table tfoot").on('click', function(e){
		// Todo: call table.handle something idk.
	});
	*/
});


</script>

		<?php

}
