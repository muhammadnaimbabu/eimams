<?php

/**
 * Template Name: Subscription
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();
global $current_user, $wp_roles, $wpdb, $enable_subscription, $enable_employer_subscription;
$enable_subscription = get_option('enable_jobseeker_subscription');
$enable_employer_subscription = get_option('enable_employer_subscription');

wp_get_current_user();
if (is_user_logged_in()) {

	$sub_active_tbl = $wpdb->prefix . 'jbs_subactive';
	$sub_pack_tbl   = $wpdb->prefix . 'jbs_subpack';
	kv_header();
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start();
	$result = $wpdb->get_results("SELECT * FROM " . $sub_active_tbl . " WHERE wp_user_id = '" . $current_user->ID . "' AND p_status='Completed' OR p_status='Pending'");
	$disabled = '';
	if (kv_get_user_status() != 0) {
		$disabled = 'disabled';
	}

	//echo $enable_subscription.$kv_current_role;
?>
	<div class="row mt-8">
		<div class="col-md-12">
			<h2>Subscription</h2>
			<h5>Welcome to eImams , Love to see you back. </h5>
			<?php if (isset($_GET['subscribed']) && $_GET['subscribed'] == 'yes') {
				echo "<div class='success' style='margin:20px auto'> You are subscribed a new pack and sometimes, it won't  show your new subscriptions. be patience, we will send you mail with confirmations. And Please refresh your page.  </div> ";
			}
			if (($kv_current_role == 'employer' && $enable_employer_subscription == 'yes') || ($kv_current_role == 'job_seeker' && $enable_subscription == 'yes')) { ?>
				<div class='alert alert-warning box-shadow'> If you see any pending Status for your new subscriptions, Please use ticket and contact us with Transaction details. </div>
			<?php } ?>
		</div>
	</div> <?php
			if (count($result) > 0) {
				echo '<hr> ';

				if (isset($_GET['add_new']) && ($_GET['add_new'] == 'yes')) {
					if (($kv_current_role == 'employer' && $enable_employer_subscription == 'yes') || ($kv_current_role == 'job_seeker' && $enable_subscription == 'yes')) {
						require_once($theme_root . "/new_subscription.php");
					}
				} else { ?>
			<div class="row">
				<div class="col-md-12">
					<!-- Advanced Tables -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php if (($kv_current_role == 'employer' && $enable_employer_subscription == 'yes') || ($kv_current_role == 'job_seeker' && $enable_subscription == 'yes')) { ?>
								<a href="<?php echo site_url('subscription/?add_new=yes'); ?>" class="btn btn-primary" <?php echo $disabled; ?>>Add New</a>
								<button id="delete_job" class="btn btn-success">Delete</button>
							<?php } ?>
							<!--  <a href="#" class="btn btn-warning" <?php //echo $disabled;
																		?> >Clone</a>-->
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<?php if (($kv_current_role == 'employer' && $enable_employer_subscription == 'yes') || ($kv_current_role == 'job_seeker' && $enable_subscription == 'yes')) { ?>
									<table class="table table-striped table-bordered table-hover" id="jobseeker-example">
										<thead>
											<tr>
												<th> </th>
												<th>Pack Name</th>
												<th>Start Date(DD/MM/YY)</th>
												<th>Price</th>
												<?php if (is_user_logged_in() && $kv_current_role == 'job_seeker') { ?>
													<th>End Date</th>
												<?php } else { ?>
													<th>End Date/Jobs Count</th>
												<?php } ?>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$myrows = $wpdb->get_results("SELECT * FROM " . $sub_active_tbl . " WHERE wp_user_id=" . $current_user->ID . " AND (p_status='Completed' OR p_status='Pending'  OR p_status='Refunded') ORDER BY id DESC");

											foreach ($myrows as $fivesdraft) {
												$sub_pack_name = $wpdb->get_row("SELECT pack_name,price FROM " . $sub_pack_tbl . " WHERE id=" . $fivesdraft->pack_id, ARRAY_A);
												//print_r($sub_pack_name);
												if ($fivesdraft->status == 'Expired')
													echo '<tr class="odd gradeX color_change expir_color" >';
												else if ($fivesdraft->status == 'Yet To Active')
													echo '<tr class="odd gradeX color_change pending_color" >';
												else
													echo '<tr class="odd gradeX color_change" >';

												//converting start and end date format here
												$start_date = strtotime($fivesdraft->start_date);
												$start_date = date('d-m-Y', $start_date);

												if ($fivesdraft->per_post > 0) {
													$end_date = $fivesdraft->per_post;
												} else {
													$end_date = strtotime($fivesdraft->end_date);
													$end_date = date('d-m-Y', $end_date);
												}

												echo "<td>" . $fivesdraft->id . "</td>
												  <td>" . $sub_pack_name['pack_name'] . "</td>
												  <td>" . $start_date . "</td>
												  <td> &pound;" . $sub_pack_name['price'] . "</td>
												  <td>" . $end_date . "</td>
												  <td>" . $fivesdraft->status . "</td>
												 </tr>";
											} ?>
										</tbody>
									</table>
								<?php } else { ?>
									<div class="no_subscription_msg"> There is no subscription required to access the system. </div>
								<?php } ?>
							</div>

						</div>
					</div>
					<!--End Advanced Tables -->
				</div>
			</div>
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

				tr.expir_color td {
					background-color: #F87969 !important;
					background-color: #fae7b2 !important;
				}

				tr.pending_color td {
					background-color: #e9eaeb !important;
					background-color: #fae7b2 !important;
				}

				.table-striped>tbody>tr:nth-child(odd)>td,
				.table-striped>tbody>tr:nth-child(odd)>th {
					background-color: #fff;
				}
			</style>
			<script>
				ajax_url = location.protocol + "//" + document.domain + '/ajax';
				jQuery(document).ready(function() {

					$("#delete_job").click(function(e) {
						//alert("data");
						//e.preventDefault();
						var rowcollection = jobseeker_table.$(".subs_checkbox:checked", {
							"page": "all"
						});
						rowcollection.each(function(index, elem) {
							delete_id = $(elem).val();
						});
						jQuery.ajax({
							type: "POST",
							url: ajax_url,
							data: {
								action: "delete_subscription",
								del_id: delete_id
							},
							success: function(data) {
								//alert(data);
								location.reload();
							},
							error: function(errorThrown) {
								console.log(errorThrown);
							}
						});
					});
				});
			</script>
<?php
				}
			} else {
				require_once($theme_root . "/new_subscription.php");
			}
			kv_footer();
		} else {
			wp_redirect(kv_login_url());
			exit;
		}
?>