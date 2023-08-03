<?php

/**
Template Name: Jobs Front end
 */
$job_increment  = 1;

global $paged;
if (empty($paged)) $paged = 1;

get_header();
if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['submit_filter'])) {

	// print_r($_POST);
	$category = trim($_POST['usr_job_category']);
	$qualification = trim($_POST['usr_qualification']);
	$types = trim($_POST['usr_types']);
	$usr_yr_of_exp = trim($_POST['usr_yr_of_exp']);
	$usr_madhab = trim($_POST['usr_madhab']);
	$usr_madhab_shia = trim($_POST['usr_madhab_shia']);
	$usr_aqeeda = trim($_POST['usr_aqeeda']);
	$usr_aqeeda_shia = trim($_POST['usr_aqeeda_shia']);
	$usr_language = trim($_POST['usr_language']);
	$usr_zone = trim($_POST['usr_zone']);
	$pref_sal_prd = trim($_POST['pref_sal_prd']);
	$pref_sal_optn = trim($_POST['pref_sal_optn']);
	$to_amount = (isset($_POST['to_amount']) ? (int)trim($_POST['to_amount']) : null);
	$from_amount = (isset($_POST['from_amount']) ? (int)trim($_POST['from_amount']) : null);
	if (isset($_POST['gender'])) {
		$gender = trim($_POST['gender']);
		$meta_key_array =  array(
			'key' => 'gender',
			'value' => $gender
		);
	}

	$args = array(
		'post_type' => 'job',
		'meta_key' => (isset($gender)) ?  'gender'  : '',
		'meta_value' => (isset($gender)) ?  $gender  : '',

		'tax_query' => array(

			($category != -1) ? array(
				'taxonomy' => 'job_category',
				'field'    => 'term_id',
				'terms'    => $category
			) : '',
			($qualification != -1) ? array(
				'taxonomy' => 'qualification',
				'field'    => 'term_id',
				'terms'    => $qualification
			) : '',
			($types != -1) ? array(
				'taxonomy' => 'types',
				'field'    => 'term_id',
				'terms'    => $types
			) : '',
			($usr_yr_of_exp != -1) ? array(
				'taxonomy' => 'yr_of_exp',
				'field'    => 'term_id',
				'terms'    => $usr_yr_of_exp
			) : '',
			($usr_madhab != -1) ? array(
				'taxonomy' => 'madhab',
				'field'    => 'term_id',
				'terms'    => $usr_madhab
			) : '',
			($usr_aqeeda != -1) ? array(
				'taxonomy' => 'aqeeda',
				'field'    => 'term_id',
				'terms'    => $usr_aqeeda
			) : '',

			($usr_madhab_shia != -1) ? array(
				'taxonomy' => 'Shiamadhab',
				'field'    => 'term_id',
				'terms'    => $usr_madhab_shia
			) : '',
			($usr_aqeeda_shia != -1) ? array(
				'taxonomy' => 'Shiaaqeeda',
				'field'    => 'term_id',
				'terms'    => $usr_aqeeda_shia
			) : '',


			($usr_language != -1) ? array(
				'taxonomy' => 'languages',
				'field'    => 'term_id',
				'terms'    => $usr_language
			) : '',
			($usr_zone != -1) ? array(
				'taxonomy' => 'zone',
				'field'    => 'term_id',
				'terms'    => $usr_zone
			) : '',
			($pref_sal_prd != -1) ? array(
				'taxonomy' => 'sal_prd',
				'field'    => 'term_id',
				'terms'    => $pref_sal_prd
			) : '',
			($pref_sal_optn != -1) ? array(
				'taxonomy' => 'sal_optn',
				'field'    => 'term_id',
				'terms'    => $pref_sal_optn
			) : '',
		),
		'post_status' => array('publish'),
		'paged' => $paged
	);

	$from_amount = (isset($_POST['from_amount']) ? (int)trim($_POST['from_amount']) : null);
	$to_amount = (isset($_POST['to_amount']) ? (int)trim($_POST['to_amount']) : null);
	if ($from_amount != null &&  $to_amount != null) {

		$args['meta_query'] = array(
			array(
				'key' 	  => 'sal_amount',
				'value'   => array($from_amount, $to_amount),
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			),
		);
	} else if (isset($from_amount)) {
		//$from_amount = (int)trim($_POST['from_amount']);

		$args['meta_query'] = array(
			array(
				'key' 	  => 'sal_amount',
				'value'   =>  $from_amount,
				'type'    => 'numeric',
				'compare' => '>=',
			),
		);
	} else if (isset($to_amount)) {
		//$to_amount = (int)trim($_POST['to_amount']);

		$args['meta_query'] = array(
			array(
				'key' 	  => 'sal_amount',
				'value'   =>  $to_amount,
				'type'    => 'numeric',
				'compare' => '<=',
			),
		);
	}
} elseif (isset($_POST['apply_this_job'])) {

	echo "Dtrhdrt";

	/*	global $wpdb;
		$jobseeker_msg = trim($_POST['jobseeker_msg']);
		$job_id = trim($_POST['job_id']);
		$show_contact = trim($_POST['show_contact']);
		$notify_email = trim($_POST['notify_email']);

		$tbl_name = $wpdb->prefix."applied_jobs";
		//echo $job_id;
		$wpdb->insert($tbl_name, array(
			'job_id' => $job_id,
			'job_seeker_id' => $current_user->ID,
			'job_seeker_cover_msg' => $jobseeker_msg,
			'status' => 0,
			'job_status' => 0,
		));
		employer_notification_new_user_applied($wpdb->insert_id);
		wp_safe_redirect(trim($_POST['current_url'])); */
} else {
	$args = array(
		'post_type' => 'job',
		'post_status' => array('publish'),
		'paged' => $paged
	);
}

?>
<!-- Page Heading -->

<?php include('template/front-end/job-top-banner.php'); ?>
<?php include('template/front-end/search-bar.php'); ?>

<div class="py-2em">
	<section class="container">

		<?php
		//print_r($args);

		while ($query->have_posts()) {
			$query->the_post();

			$term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "names"));

			if ($term_list[0] == 'Full Time') {
				$colorclass = 'border_full_time';
			}
			if ($term_list[0] == 'Part Time') {
				$colorclass = 'border_part_time';
			}
			if ($term_list[0] == 'Cover') {
				$colorclass = 'border_cover';
			}
			if ($term_list[0] == 'Replacement') {
				$colorclass = 'border_replacement';
			}
			if ($term_list[0] == 'Short Terms') {
				$colorclass = 'border_short_terms';
			} 	   ?>


			<div class="card__contents">
				<div class="card__img">
					<?php if (has_post_thumbnail()) {
						echo get_the_post_thumbnail(get_the_ID(), array(200, 200));
					} else {
						echo "<img src='" . get_template_directory_uri() . "/images/default-dp-eimams.png'/> ";
					} 	?>
				</div>
				<div class="card__item">
					<a href="#" class="card__Fulltime">
						<?php $term_list = wp_get_post_terms($post->ID, 'types', array("fields" => "names"));
						if ($term_list[0] == 'Full Time') {
							echo ' <span>' . $term_list[0] . '</span>';
						}
						if ($term_list[0] == 'Part Time') {
							echo ' <span>' . $term_list[0] . '</span>';
						}
						if ($term_list[0] == 'Cover') {
							echo ' <span>' . $term_list[0] . '</span>';
						}
						if ($term_list[0] == 'Replacement') {
							echo ' <span>' . $term_list[0] . '</span>';
						}
						if ($term_list[0] == 'Short Terms') {
							echo ' <span>' . $term_list[0] . '</span>';
						}
						if ($term_list[0] == null) {
							echo ' <sp>' . 'Undefined' . '</sp an>';
						} ?>
					</a>
					<h2 class="card__header">
						<span class="job-title"> <?php echo get_the_title(); ?> </span>
					</h2>
					<p class="card__text short_desc">
						<?php echo strip_tags(get_the_content()); ?>
					</p>
					<a href="
				<?php echo get_post_meta(get_the_ID(), 'website', true); ?>
				" class="card__link card__text"><?php echo get_post_meta(get_the_ID(), 'company_name', true); ?></a>
				</div>
				<div class="card__ItemsButton">
					<p class="card__date">Posted <?php $start_date = strtotime(get_post_meta(get_the_ID(), 'ad_start_date', true));
													echo $start_date = date('d-M-Y', $start_date); ?></p>
					<p class="card__payment"><?php
												$salray = get_post_meta(get_the_ID(), 'sal_amount', true);
												$prd = get_post_meta(get_the_ID(), 'sal_prd', true);
												if ($salray == !null) {
													$final_sal = '$' . $salray;
													if ($prd == !null) {
														echo $final_sal . ' / ' . $prd;
													} else {
														echo 'Negotiable';
													}
													// $final_output = $final_sal .
												} else {
													echo 'Negotiable';
												}
												?>

					</p>
					<div class="card__button">
						<a href="#" id="details-btn" class="cardButton__job">Details</a>
						<a href="#" id="apply-now-btn" class="cardButton__apply">Apply Now</a>
					</div>
				</div>
			</div>

			<div class="apply__now">
				<form action="">
					<textarea name="" id class="apply" placeholder="Write your cover massage to the employer."></textarea>
					<button type="submit" class="cover-submit-btn cardButton__apply">Submit</button>
				</form>
			</div>

			<div class="job__details job_details<?php static $i = 0;
												echo $i++ ?>">
				<div class="job__details__contents">
					<h2 class="jobDetails__subheader">Job Description</h2>
					<div class="details__items">
						<div class="details__innerItems">
							<i class="feather-16" data-feather="minus"></i>
							<p>Content Creation And Social Media Marketing Campaigns.</p>
						</div>
						<div class="details__innerItems">
							<i class="feather-16" data-feather="minus"></i>
							<p>Implement Marketing Tactics To Enhance Relationships With Donors, Building Confidence In The Brand And It’s Projects.</p>
						</div>
						<div class="details__innerItems">
							<i class="feather-16" data-feather="minus"></i>
							<p>Generate, Distribute, And Address Online Feedback From Reviews.
								.</p>
						</div>
						<div class="details__innerItems">
							<i class="feather-16" data-feather="minus"></i>
							<p>Gain And Maintain Thorough, Up-To-Date Knowledge Of Company Services On Offer.</p>
						</div>
						<div class="details__innerItems">
							<i class="feather-16" data-feather="minus"></i>
							<p>Keep Up To Date With And Analyse Trends In Activity From Both Customers And Competitors.</p>
						</div>
					</div>
					<div class="company__details">
						<div class="company__innerItems">
							<div class="company__inner">
								<p>company name</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>Goreeb Yateem Trust Fund</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Company Description</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>Humanitrian Organisation (Charity)</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Address</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>259 (2nd Floor) White Chapel Road</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>City</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>London</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Country</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>United Kingdom</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Website</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<a href="#">Www.Goreebfund.Com</a>
							</div>
						</div>
					</div>
					<div class="acomodation__details">
						<h2 class="jobDetails__subheader">ACCOMODATION DETAILS </h2>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Legal Check</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>Not Applicable</p>
							</div>
						</div>
						<div class="company__innerItems">
							<div class="company__inner">
								<p>Enter Your Descriptoin Here</p>
								<span>:</span>
							</div>
							<div class="company__inner">
								<p>NULL</p>
							</div>
						</div>
					</div>
				</div>
			</div>



		<?php }	?>
		<div class="card__slider card__slider--flex">
			<div class="card__pages">
				<p class="card_job">Jobs per page</p>
				<div class="card__counts">
					<p class="card__time card_counts">20 <i class="fa-solid fa-chevron-down"></i></p>
				</div>

			</div>
			<div class="card__arrows">
				<?php if ($query->max_num_pages > 1) { ?>
					<div class="numeric-pagination">
						<?php if (function_exists("pagination")) {
							pagination($query->max_num_pages);
						} ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
</div>

<style>
	.SunniDenomination,
	.ShiaDenomination {
		display: none;
	}

	.job-details {
		display: none;
	}
</style>



<style type="text/css">
	#multi-column-wrapper {
		position: relative
	}

	#multi-column {
		position: absolute;
		width: 600px;
		background: #162c53;
		border-radius: 10px;
		padding: 10px;
		z-index: 10000
	}

	#multi-column-wrapper h3 {
		color: #63b2f5
	}

	#btn-job-classification {
		padding: 0px;
		/* min-width: 180px; */
		display: inline-block;
		color: #162c53;
	}

	#job_Cate_button:hover {
		background: #63b2f5
	}

	.caret.caret-reversed {
		border-bottom-width: 0;
		border-top: 4px solid #000000;
		/*    float:right;
    margin-top:7px;*/
	}

	a:active {
		text-decoration: none
	}

	a:hover {
		text-decoration: none
	}

	ul {
		list-style: none
	}

	#multi-column li:before {
		content: "> "
	}

	li {
		color: white;
	}

	li:hover {
		cursor: pointer;
		color: red
	}

	@media only screen and (max-width:767px) {

		#multi-column {
			width: 100%
		}

	}
</style>
<script>
	// alert('W');
	$(function() {
		$("#multi-column").hide();

		$("#job_Cate_button").click(function(e) {
			e.preventDefault();
			$("#multi-column").slideToggle();
		});
		$(".showhideJobCategory").hide();
		$(".denominationSelection").on("change", function(e) {
			var SelectedDenomination = $('.denominationSelection:checked').val();
			//alert(SelectedDenomination);
			if (SelectedDenomination == 'sunni') {
				$(".SunniDenomination").show();
				$(".ShiaDenomination").hide();
			} else {
				$(".SunniDenomination").hide();
				$(".ShiaDenomination").show();
			}
		});

		$("#btn-job-classification").click(function(e) {
			e.preventDefault();
			// $("#multi-column").slideToggle();
		});

		$("#multi-column li").on("click", function() {
			var txt = $(this).text();
			var txt_val = $(this).attr('value');
			var typE = $(this).attr('type');
			$("#btn-job-classification").text(txt);
			$("#multi-column").slideUp();
			if (typE == 'special') {
				$("#job_Cate_hidden").val(txt_val);
				$("#gen_job_Cate_hidden").val(-1);
				$(".showhideJobCategory").show();
				$(".denominationSelection").trigger('change');
			} else {
				$("#gen_job_Cate_hidden").val(txt_val);
				$("#job_Cate_hidden").val(-1);
				$(".showhideJobCategory, .SunniDenomination, .ShiaDenomination").hide();
			}
		});
		// $(".showhideJobCategory").show();
		// $(".SunniDenomination").show();

		$("#details-btn").click(function() {
			event.preventDefault();
			var i = 0;

			$('.job_details' + $(this).attr('job_details')).show();
			// console.log();
		})
	});
</script>

<?php
echo "</section>";
get_footer();
?>