<?php

/**
 * Template Name: Applied Resumes
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();

if (is_user_logged_in() && $kv_current_role == 'employer') {
	kv_header(); ?>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/jquery.dataTables.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/dataTables.bootstrap.js"></script>
	<?php
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();

	global $wpdb;
	$post_ids_author = array();
	$tabl_name = $wpdb->prefix . 'applied_jobs';
	$args = array(
		'author'        =>  $current_user->ID,
		'orderby'       =>  'post_date',
		'order'         =>  'DESC',
		'post_type' 	=> 'job',
		'post_status'	=> 'any',
		'posts_per_page' => -1
	);
	$current_user_posts = get_posts($args);
	foreach ($current_user_posts as $post_ids)
		$post_ids_author[] = $post_ids->ID;
	$ids = join(',', $post_ids_author);

	//$wpdb->get_var( "SELECT user_status FROM $wpdb->users WHERE ID=".$current_user->ID );

	$deactivated_user_list = kv_get_deactivated_users_list();
	//print_r($deactivated_user_list);
	//echo $count  = $wpdb->get_var("SELECT COUNT(*) FROM ".$tabl_name." WHERE job_id IN ($ids) AND status =0");

	?>

	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack ">

			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
					Applied Resumes
				</h1>
				<!--end::Title-->

				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<span>All your Applied Resumes will be display here.</span>
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->

			<!--end::Actions-->
		</div>
		<!--end::Toolbar container-->
	</div>
	<!--end::Toolbar-->

	<div id="kt_app_content" class="app-content  flex-column-fluid ">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="container-xxl ">
			<!--begin::Card-->
			<div class="card">
				<div class="col-md-12">
					<!-- Advanced Tables -->

					<div class="panel panel-default">
						<div class="panel-body">
							<?php if (isset($_GET['status']) && $_GET['status'] == 'deleted')
								echo "<div class='success'>  The Selected Ticket has been removed completely from our system. we think your problem has been solved.</div>";

							if (!empty($ids) && kv_get_user_status() == 0) {	?>
								<div class="table-responsive">

									<table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="applied_jobs">
										<thead>
											<tr>
												<th> </th>
												<th>Name of Candidate</th>
												<th>Job Title</th>
												<th>Message</th>
												<th> View CV</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (!empty($deactivated_user_list))
												$myjobs = $wpdb->get_results("SELECT * FROM " . $tabl_name . " WHERE job_id IN ($ids) AND job_seeker_id NOT IN ( $deactivated_user_list )");
											else
												$myjobs = $wpdb->get_results("SELECT * FROM " . $tabl_name . " WHERE job_id IN ($ids)");


											$tr_start = '';
											foreach ($myjobs as $post) :
												if ($post->status == 0)
													$tr_start = 'unread';
												else
													$tr_start = '';
												$user_info = get_userdata($post->job_seeker_id);

												echo "<tr class='" . $tr_start . "' ><td>" . $post->id . " </td> <td> " . $user_info->display_name . " </td> <td> " . get_the_title($post->job_id) . " </td> <td> " . ((strlen($post->job_seeker_cover_msg) > 50) ? substr($post->job_seeker_cover_msg, 0, 50) . '...' : $post->job_seeker_cover_msg) . " </td><td>";
												//<td>".((strlen($post->reply_msg_from_employer) > 50) ? substr($post->reply_msg_from_employer,0,50).'...' : $post->reply_msg_from_employer)."</td>
												//if(eimams_has_current_user_cv($post->job_seeker_id)){
												echo '<a class="view_resume" data-fancybox-type="iframe"  href="' . site_url('view-resume') . '?job_seeker_id=' . $post->job_seeker_id . '&apply_id=' . $post->id . '" ><i class="fa fa-file"> </i> </a>';
												//} else
												//echo 'No CV ';

												echo "</td><td> " . $post->job_id . " </td> </tr>";
											endforeach;

											?>
										</tbody>
									</table>

								</div>

							<?php } elseif (kv_get_user_status() == 1 || kv_get_user_status() == 2) {
								echo '<span class="btn btn-warning"><h3 style="color:#eee;margin:0;padding:0;">Your Profile is deactivated! </h3></span>';
							} else
								echo '<div> No Available resumes for you!</div>';
							?>

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
		$(document).ready(function() {
			//$(".view_resume").on("click", function(e) {
			//e.preventDefault();
			$(".view_resume").fancybox({
				maxWidth: 900,
				maxHeight: 900,
				fitToView: false,
				width: '70%',
				height: '90%',
				autoSize: false,
				closeClick: false,
				openEffect: 'none',
				closeEffect: 'none',
				type: "iframe",
				afterClose: function() {
					//$("#applied_jobs").dataTable().fnDraw();
					location.reload();
				}
			});



			help_table = $('#applied_jobs').dataTable({
				aoColumns: [{
						"sClass": "center",
						"mRender": function(data, type, row) {
							return '<div class="input-control checkbox" /> <label></label></div>';

						},
						"sWidth": "1%"
					},
					{
						"sWidth": "15%"
					},
					{
						"sWidth": "20%"
					},
					{
						"sWidth": "20%"
					},
					{
						"sWidth": "8%"
					},
					{
						"sClass": "center",
						"mRender": function(data, type, row) {
							return '<a href="#"> <span id="applied_delete" class="fa fa-trash-o"  del_id="' + row[0] + '"> </span>';
						},
						"sWidth": "8%"
					}
				]
			});

			$("#applied_jobs").on("click", "#applied_delete", function(e) {

				var result = confirm('Are you sure?');
				if (result == true) {
					var delet_id = $(this).attr('del_id');
					$.ajax({
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
				$("#delete_ticket").attr('disabled', false);
			} else {
				$("#delete_ticket").attr('disabled', true);
			}
		}
	</script>
	<style>
		@media (max-width: 767px) {
			.table-responsive {
				overflow-x: hidden;
				overflow-y: hidden
			}
		}

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

		@media screen and (max-width: 560px) {

			.dataTable tr th:first-child,
			.dataTable tr td:first-child,
			.dataTable tr th:nth-child(4),
			.dataTable tr td:nth-child(4) {
				display: none;
			}
		}
	</style>

<?php

	kv_footer();
} else {
	wp_redirect(kv_login_url());
	exit;
}
?>