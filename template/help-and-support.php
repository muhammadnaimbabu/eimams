<?php

/**
 * Template Name: Help and Support
 */
$theme_root = dirname(__FILE__);
require_once($theme_root . "/../library/user-backend-main.php");
$kv_current_role = kv_get_current_user_role();
global $current_user, $wp_roles, $wpdb;
global $paged;
if (empty($paged)) $paged = 1;
wp_get_current_user();
$job_seeker_table = $wpdb->prefix . 'jobseeker';
if (is_user_logged_in()) {
	kv_header();
	kv_top_nav();
	kv_leftside_nav();
	kv_section_start(); ?>

	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack ">

			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
					Help & support
				</h1>
				<!--end::Title-->

				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<span>Add a ticket for any support</span>
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->

			<!--end::Actions-->
		</div>
		<!--end::Toolbar container-->
	</div>
	<?php


	if (isset($_GET['add_new'])) {	// add new page.
		if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_a_ticket'])) {
			$errors = new WP_Error();
			$fields = array(
				'subject',
				'ticketcat',
				'message'
			);
			foreach ($fields as $field) {
				if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
				else $posted[$field] = '';
			}
			if ($posted['subject'] != null)
				$subject =  $posted['subject'];
			else
				$errors->add('empty_subject', __('<strong>Notice</strong>: Please enter Subject.', 'kv_project'));
			$website =  $posted['website'];

			if ($posted['ticketcat'] != -1)
				$ticketcat =  $posted['ticketcat'];
			else
				$errors->add('empty_ticketcat', __('<strong>Notice</strong>: Please Select a Type of ticket.', 'kv_project'));

			if ($posted['message'] != null)
				$message =  $posted['message'];
			else
				$errors->add('empty_message', __('<strong>Notice</strong>: Please Write your detail regarding the problem.', 'kv_project'));
			if (!$errors->get_error_code()) {
				$new_post = array(
					'post_title'	=>	$subject,
					'post_content'	=>	$message,
					'post_status'	=>	'pending',
					'post_type'		=>	'tickets',
					'post_author'	=>	$current_user->ID,
					'tax_input' 	=> array('ticketcat' => $ticketcat)
				);
				if (!function_exists('post_exists')) {
					require_once(ABSPATH . 'wp-admin/includes/post.php');
				}
				if (!post_exists($subject))
					$jid = wp_insert_post($new_post);
				kv_notify_ticket_to_admin_email($jid);
			}
			if ($jid) {
				$sub_success = 'Success';
				do_action('save_post', $jid);
				wp_safe_redirect(site_url('help-and-support') . '?status=added');
			}
		}	?>
		<!--begin::Content-->
		<div id="kt_app_content" class="app-content  flex-column-fluid ">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="container-xxl ">
				<!--begin::Card-->
				<div class="card">
					<!--begin::Card body-->
					<div class="card-body pt-0">

						<!--begin::Table-->
						<div id="az_posted_jobs" class="dataTables_wrapper dt-bootstrap4 no-footer">
							<div class="table-responsive">
								<?php
								if (isset($errors) && $errors->get_error_code()) :
									echo '<ul class="errors">';
									foreach ($errors->errors as $error) {
										echo '<li>' . $error[0] . '</li>';
									}
									echo '</ul>';
								endif; 	?>
								<div class="col-md-6 mt-10">
									<form class="form-horizontal" name="submit_a_new_job" method="post" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
										<div class="col-md-offset-4 col-md-8">
											<h3>Submit your problem as ticket to us </h3><br>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4" for="title"> Subject:</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" id="title-ref" name="subject" placeholder="Job title" value="<?php if (isset($_POST['title'])) {
																																							echo $_POST['title'];
																																						}  ?>">
												<span class="mandatory"> * </span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4" for="Name">Type: </label>
											<div class="col-sm-8">
												<?php
												$usr_job_category = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'ticketcat', 'selected' => 0, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
												$usr_job_category = str_replace("name='cat' id=", "name='ticketcat' id=", $usr_job_category);
												echo $usr_job_category; ?>
												<span class="mandatory"> * </span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-4" for="website">Details/Message:</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="message" name="message" placeholder="Message"><?php if (isset($_POST['message'])) {
																																		echo $_POST['message'];
																																	} ?></textarea>
												<span class="mandatory"> * </span>
											</div>

										</div>
										<div class="form-group">
											<div class="col-xs-12 col-md-offset-4 col-md-8">
												<input type="submit" class="btn btn-primary" name="submit_a_ticket" value=" <?php echo 'Submit'; ?>">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	} else if (isset($_GET['edit_id'])) {   // edit page
		if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['delete_this_thread'])) {
			wp_delete_post($ticket_id);
			wp_safe_redirect(site_url('help-and-support') . '?status=deleted');
		}
		if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['update_edited_ticket'])) {
			$errors = new WP_Error();
			$fields = array(
				'post_id',
				'subject',
				'ticketcat',
				'message'
			);
			foreach ($fields as $field) {
				if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
				else $posted[$field] = '';
			}
			if ($posted['subject'] != null)
				$subject =  $posted['subject'];
			else
				$errors->add('empty_subject', __('<strong>Notice</strong>: Please enter Subject.', 'kv_project'));
			$website =  $posted['website'];

			if ($posted['ticketcat'] != -1)
				$ticketcat =  $posted['ticketcat'];
			else
				$errors->add('empty_ticketcat', __('<strong>Notice</strong>: Please Select a Type of ticket.', 'kv_project'));

			if ($posted['message'] != null)
				$message =  $posted['message'];
			else
				$errors->add('empty_message', __('<strong>Notice</strong>: Please Write your detail regarding the problem.', 'kv_project'));
			if (!$errors->get_error_code()) {
				$update_post = array(
					'ID' 			=> $posted['post_id'],
					'post_title'	=>	$subject,
					'post_content'	=>	$message,
					'post_status'	=>	'pending',
					'post_type'		=>	'tickets',
					'post_author'	=>	$current_user->ID,
					'tax_input' 	=> array('ticketcat' => $ticketcat)
				);
				$jid = wp_update_post($update_post);
			}
			if ($jid) {
				$sub_success = 'Success';
				do_action('save_post', $jid);
				wp_safe_redirect(site_url('help-and-support') . '?status=updated');
			}
		}	?>
		<div id="kt_app_content" class="app-content  flex-column-fluid ">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="container-xxl ">
				<!--begin::Card-->
				<div class="card">
					<!--begin::Card body-->
					<div class="card-body pt-0">

						<?php
						if (isset($errors) && $errors->get_error_code()) :
							echo '<ul class="errors">';
							foreach ($errors->errors as $error) {
								echo '<li>' . $error[0] . '</li>';
							}
							echo '</ul>';
						endif;

						$edit_post = stripslashes(trim($_GET['edit_id']));
						$post_edit = get_post($edit_post); ?>
						<div class="col-md-6 mt-10">
							<form class="form-horizontal" name="submit_a_new_job" method="post" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
								<div class="col-sm-offset-4 col-sm-8">
									<h3>Edit your Ticket </h3><br>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4" for="title"> Subject:</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="title-ref" name="subject" placeholder="Job title" value="<?php echo $post_edit->post_title; ?>">
										<span class="mandatory"> * </span>
										<input type="hidden" name="post_id" value="<?php echo $edit_post; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4" for="Name">Type: </label>
									<div class="col-sm-8"> <?php
															$term_list = wp_get_post_terms($edit_post, 'ticketcat', array("fields" => "ids"));
															$usr_job_category = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'ticketcat', 'selected' => $term_list[0], 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
															$usr_job_category = str_replace("name='cat' id=", "name='ticketcat' id=", $usr_job_category);
															echo $usr_job_category; ?>
										<span class="mandatory"> * </span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4" for="website">Details/Message:</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="message" name="message" placeholder="Message"> <?php echo $post_edit->post_content;  ?></textarea>
										<span class="mandatory"> * </span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<input type="submit" class="btn btn-primary" name="update_edited_ticket" value=" Submit ">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div><?php
			} else if (isset($_GET['ticket_id'])) {     // response page.
				global  $current_user; //for this example only :)
				$ticket_id = stripslashes(trim($_GET['ticket_id']));
				$post_edit = get_post($ticket_id);
				if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['reply_thread'])) {
					$reply_msg =  stripslashes(trim($_POST['reply_msg']));

					$commentdata = array(
						'comment_post_ID' => $ticket_id,
						'comment_author' =>  $current_user->display_name,
						'comment_author_email' => $current_user->user_email,
						'comment_content' => $reply_msg,
						'comment_type' => '',
						'comment_karma' => 1,
						'user_id' => $current_user->ID,
					);
					$comment_id = wp_new_comment($commentdata);
					wp_set_comment_status($comment_id, 'hold');
					if ($comment_id > 0) {
						$wpdb->update($wpdb->posts, array('post_status' => 'pending'), array('ID' => $ticket_id));
						wp_safe_redirect(site_url('help-and-support') . '?status=replied');
					}
				}
				if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['delete_this_thread'])) {
					wp_delete_post($ticket_id);
					wp_safe_redirect(site_url('help-and-support') . '?status=deleted');
				}

				$ticket_cat = wp_get_post_terms($ticket_id, 'ticketcat', array("fields" => "names"));
				echo '<div class="col-md-12">

							<table id="ticket-table" class="table table-responsive table-bordered" ><tr> <th width="25%"> Ticket Subject </th> <th width="25%"> Ticket Category </th><th width="50%"> Ticket Detail</th> </tr>
							<tr><td>' . $post_edit->post_title . '</td> <td>' . $ticket_cat[0] . '</td> <td>' . $post_edit->post_content . '</td></tr>';
				echo '<tr> <td colspan="3"> <h4> Messages </h4> </td> </tr> ';
				$comments = get_comments(array('post_id' => $ticket_id, 'order' => 'ASC'));
				foreach ($comments as $comment) :
					echo '<tr> <td> ' . $comment->comment_author . '</td><td colspan="2">' . nl2br($comment->comment_content) . '</td></tr>';
					wp_update_comment(array('comment_ID' => $comment->comment_ID, 'comment_karma' => 1));
				endforeach;
				echo '</table></div>'; ?>


		<form class="form-horizontal" name="reply_thread" method="post" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
			<h3>Reply </h3><br>
			<div class="form-group">
				<div class="col-sm-8">
					<textarea class="form-control" id="message" name="reply_msg" placeholder="Message"> </textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8">
					<input type="submit" class="btn btn-primary" name="reply_thread" value=" Submit ">
					<input type="submit" class="btn btn-primary" name="delete_this_thread" value=" Delete ">
				</div>
			</div>
		</form>

	<?php
				echo ' ** <b> Note : </b>  if you think the ticket has been solved, than delete it ..';
			} else {  // master table page.
	?>
		<div id="kt_app_content" class="app-content  flex-column-fluid ">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="container-xxl ">
				<!--begin::Card-->
				<div class="card">
					<!--begin::Card body-->
					<div class="card-body pt-0">
						<div class="col-md-12 mt-10">
							<!-- Advanced Tables -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<a href="<?php echo site_url('help-and-support/?add_new=yes'); ?>" class="btn btn-primary">Add New </a>
									<a href="#" class="btn btn-info" id="edit_ticket">Edit</a>
									<a href="#" id="delete_ticket" class="btn btn-success" return_url="<?php echo site_url('help-and-support/?status=deleted'); ?>">Delete</a>
									<!--<a href="#" id="respond_ticket" class="btn btn-warning">Reply</a> -->
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<?php if (isset($_GET['status'])) {
											if ($_GET['status'] == 'added') {
												echo "<div class='success'> You have added a Ticket, We will get back to you soon. </div>";
											} else if ($_GET['status'] == 'updated') {
												echo "<div class='success'> Your ticket Updated Successfully</div>";
											} else if ($_GET['status'] == 'replied')
												echo "<div class='success'> Your Reply Submitted Successfully </div>";
											else if ($_GET['status'] == 'deleted')
												echo "<div class='success'>  The Selected Ticket has been removed completely from our system. we think your problem has been solved.</div>";
										} ?>
										<table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="help-support">
											<thead>
												<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
													<th> </th>
													<th>Subject</th>
													<th>Ticket</th>
													<th>Comments</th>
												</tr>
											</thead>
											<tbody> <?php global $post;

													$args = array(
														'post_type' => 'tickets',
														'author' => $current_user->ID,
														'paged' => $paged,
														'post_status' => 'any'
													);
													$myposts = get_posts($args);
													foreach ($myposts as $post) :  setup_postdata($post);
														$args = array(
															'number' => '1',
															'post_id' => $post->ID
														);
														$tr_start = $last_comment = '';
														$comments = get_comments($args);
														foreach ($comments as $comment) {
															$last_comment =  $comment->comment_content;
															if ($comment->user_id != $current_user->ID) {
																$tr_start = 'response_frm_admin';
																if ($comment->comment_karma == 0)
																	$tr_start = 'unread';
															}
														}
														$excerpt = get_the_excerpt();
														echo "<tr class='" . $tr_start . "' ><td>" . get_the_ID() . " </td><td> <a href='" . site_url('help-and-support') . "?ticket_id=" . get_the_ID() . "' >" . get_the_title() . " </a></td><td><a href='" . site_url('help-and-support') . "?ticket_id=" . get_the_ID() . "' >" . ((strlen($excerpt) > 50) ? substr($excerpt, 0, 50) . '...' : $excerpt) . "</a></td><td><a href='" . site_url('help-and-support') . "?ticket_id=" . get_the_ID() . "' >" . ((strlen($last_comment) > 50) ? substr($last_comment, 0, 50) . '...' : $last_comment) . "</a></td></tr>";
													endforeach;
													wp_reset_postdata(); ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div><?php
						}





						echo '</div>';

						kv_footer();
					} else {
						wp_redirect(kv_login_url());
						exit;
					}
							?>
			<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/jquery.dataTables.js"></script>
			<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dataTables/dataTables.bootstrap.js"></script>
			<script>
				ajax_url = location.protocol + "//" + document.domain + '/ajax';
				jQuery(document).ready(function() {
					help_table = jQuery('#help-support').dataTable({
						aoColumns: [{
								"sClass": "center",
								"mRender": function(data, type, row) {
									return '<div class="input-control checkbox px-5" /> <label><input type="checkbox" class="pl-2" name="reference" onclick="kv_delivery_events_enable_disable(event);" class="job_checkbox" data-ref="' + row[0] + '"   value="' + row[0] + '"> <span class="check"></span></label></div>';

								},
								"sWidth": "1%"
							},
							{
								"sWidth": "20%"
							},
							{
								"sWidth": "30%"
							},
							{
								"sWidth": "30%"
							}

						]
					});

					jQuery("#delete_ticket").attr('disabled', true);
					jQuery("#edit_ticket").attr('disabled', true);


					$("#delete_ticket").click(function(e) {
						var rowcollection = help_table.$(".job_checkbox:checked", {
							"page": "all"
						});
						rowcollection.each(function(index, elem) {
							delete_id = $(elem).val();

						});
						redirect_url = $("#delete_ticket").attr('return_url');
						//ert(redirect_url);

						jQuery.ajax({
							type: "POST",
							url: ajax_url,
							data: {
								action: "delete_ticket",
								del_id: delete_id
							},
							success: function(data) {
								//alert(data);
								window.location.replace(redirect_url);
							},
							error: function(errorThrown) {
								console.log(errorThrown);
							}
						});
					});
					$("#edit_ticket").click(function(e) {
						var rowcollection = help_table.$(".job_checkbox:checked", {
							"page": "all"
						});
						rowcollection.each(function(index, elem) {
							edit_id = $(elem).val();
						});

						$(this).attr("href", '?edit_id=' + edit_id);
					});

				});

				function kv_delivery_events_enable_disable(e) {
					var len = $('input[type="checkbox"].job_checkbox:checked').length;
					//alert(len);
					if (len == 1) {
						jQuery("#delete_ticket").attr('disabled', false);
						jQuery("#edit_ticket").attr('disabled', false);
					} else if (len > 1) {
						jQuery("#delete_ticket").attr('disabled', false);
						jQuery("#edit_ticket").attr('disabled', true);
					} else {
						jQuery("#edit_ticket").attr('disabled', true);
						jQuery("#delete_ticket").attr('disabled', true);
					}

				}
			</script>
			<style>
				h3 {
					font-family: 'Open Sans', Arial;
					color: #666
				}

				.panel {
					border: 0px solid transparent
				}

				.dataTables_length {
					display: none !important;
				}

				.dataTables_filter label {
					float: right !important;
				}

				#ticket-table th {
					background-color: #CCC
				}

				.success {
					padding: 10px;
					background-color: #f5ca8c;
					border-radius: 3px;
					box-shadow: 0px 0px 3px #f5ca8c;
					text-align: center;
					border: 0px solid transparent;
					color: #333;
					font-weight: bold;

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
			</style>