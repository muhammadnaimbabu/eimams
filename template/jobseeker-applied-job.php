<?php

/**
 * Template Name: Applied Job -Jobseeker
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();
global $current_user, $wp_roles, $wpdb;
global $paged;
if (empty($paged)) $paged = 1;
wp_get_current_user();
$job_seeker_table = $wpdb->prefix . 'jobseeker';
kv_header(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/dataTables.bootstrap.js"></script>
<?php
kv_top_nav();
kv_leftside_nav();
kv_section_start();
if (is_user_logged_in() && $kv_current_role == 'job_seeker') { ?>

	<div id="page-inner">

		<div class="row">
			<div class="col-md-12">
				<h2>Applied Job</h2>
				<h5>Welcome to eImams , Love to see you back. </h5>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Advanced Tables -->

				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive">
							<?php if (isset($_GET['status']) && $_GET['status'] == 'deleted')
								echo "<div class='success'>  The Selected Ticket has been removed completely from our system. we think your problem has been solved.</div>";
							if (kv_get_user_status() == 0) {
							?>
								<table class="table table-striped table-bordered table-hover" id="applied_jobs">
									<thead>
										<tr>
											<th> </th>
											<th>Job Title </th>
											<th>Your Message</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody> <?php global $wpdb;
											$tabl_name = $wpdb->prefix . 'applied_jobs';
											$myjobs = $wpdb->get_results("SELECT * FROM " . $tabl_name . " WHERE job_seeker_id = " . $current_user->ID);
											$tr_start = '';
											foreach ($myjobs as $post) :
												if ($post->status == 0)
													$tr_start = 'unread';
												else $tr_start = '';

												echo "<tr class='" . $tr_start . "' ><td>" . $post->id . " </td><td>" . get_the_title($post->job_id) . "</td><td> " . ((strlen($post->job_seeker_cover_msg) > 50) ? substr($post->job_seeker_cover_msg, 0, 50) . '...' : $post->job_seeker_cover_msg) . " </td><td>" . (($post->status == 0) ? 'Unread' : 'Read') . "</td><td> " . (($post->status == 0) ? $post->job_id : ' ') . " </td> </tr>";
											endforeach; ?>
									</tbody>
								</table>
							<?php } elseif (kv_get_user_status() == 1 || kv_get_user_status() == 2) {
								echo '<div> Your Profile is deactivated!.</div>';
							} ?>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php //} 
	?>

	<script>
		ajax_url = location.protocol + "//" + document.domain + '/ajax';
		jQuery(document).ready(function() {
			help_table = jQuery('#applied_jobs').dataTable({
				aoColumns: [{
						"sClass": "center",
						"mRender": function(data, type, row) {
							return '<div class="input-control checkbox" /> <label><input type="checkbox" name="reference" onclick="kv_delivery_events_enable_disable(event);" class="applied_checkbox" data-ref="' + row[0] + '"   value="' + row[0] + '"> <span class="check"></span></label></div>';

						},
						"sWidth": "1%"
					},
					{
						"sWidth": "30%"
					},
					{
						"sWidth": "40%"
					},
					{
						"sWidth": "8%"
					},
					{
						"sClass": "center",
						"mRender": function(data, type, row) {
							if (row[4] > 0)
								return ' <a href="#"> <span id="applied_delete" class="fa fa-trash-o"  del_id="' + row[0] + '"> </span></a>  '; //&nbsp;&nbsp;&nbsp; <a href="#"> <span id="applied_delete" class="fa fa-trash-o"  del_id="'+row[0]+'"> </span></a> ';
							else
								return ' ';
						},
						"sWidth": "8%"
					}
				]
			});

			$("#applied_jobs").on("click", "#applied_delete", function(e) {

				var result = confirm('Are you sure?');
				if (result == true) {
					var delet_id = $(this).attr('del_id');
					jQuery.ajax({
						type: "POST",
						url: ajax_url,
						data: {
							action: "applied_delete",
							del_id: delet_id
						},
						success: function(data) {
							alert(data);
							//window.location.replace(redirect_url);
							location.reload();
						},
						error: function(errorThrown) {
							console.log(errorThrown);
						}
					});
				}
			});

		});

		function kv_delivery_events_enable_disable(e) {
			var len = $('input[type="checkbox"].applied_checkbox:checked').length;
			//alert(len);
			if (len >= 1) {
				jQuery("#delete_ticket").attr('disabled', false);
			} else {
				jQuery("#delete_ticket").attr('disabled', true);
			}
		}
	</script>
	<style>
		.dataTables_length {
			display: none !important;
		}

		.dataTables_filter label {
			float: right !important;
		}

		.pagination {
			float: right !important;
			margin-top: 0 !important;
		}

		tr.response_frm_admin td {
			background-color: rgba(105, 248, 149, 0.18) !important;
		}

		tr.unread td {
			background-color: #F8D369 !important;
		}

		.dataTable {
			width: 100% !important;
		}

		.dataTable tr th:first-child,
		.dataTable tr td:first-child {
			display: none;
		}
	</style>
<?php
	kv_footer();
} else {
	wp_redirect(kv_login_url());
	exit;
}
?>