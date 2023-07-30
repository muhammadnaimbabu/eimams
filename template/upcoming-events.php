<!-- Upcoming Events -->
<div class="sidebar-box white animate-onscroll">
	<div>
		<form method="post" action="<?php echo site_url('jobs'); ?>">
			<h2 style="color:#084a86;margin-top:0px;">Job Search </h2><br>
			<label>Job Classifications </label><br>
			<?php //$usr_job_category = wp_dropdown_categories( array('show_option_none'=> 'Any/All' ,  'echo' => 0, 'taxonomy' => 'job_category','selected' =>$_POST['usr_job_category'], 'hierarchical' => true,'class'  => 'form-control usr_job_category',  'hide_empty' => 0,'orderby'            => 'NAME',
			//	'order'              => 'ASC', ) );
			//$usr_job_category = str_replace( "name='cat' id='cat'", " name='usr_job_category' ", $usr_job_category );
			//echo $usr_job_category;

			if (isset($_POST['usr_job_category']) &&  $_POST['usr_job_category'] != -1) {
				$usr_job_category = $_POST['usr_job_category'];
				$sel_term = get_term($usr_job_category);
				$sel_term_name = $sel_term->name;
				$usr_gen_job_category = -1;
			} elseif (isset($_POST['usr_gen_job_category']) &&  $_POST['usr_gen_job_category'] != -1) {
				$usr_gen_job_category = $_POST['usr_gen_job_category'];
				$sel_term = get_term($usr_gen_job_category);
				$sel_term_name = $sel_term->name;
				$usr_job_category = -1;
			} else {
				$usr_job_category = $usr_gen_job_category = -1;
				$sel_term_name = 'Any/All';
			} ?>

			<button id="job_Cate_button" class="btn btn-default"><a href="#" id="btn-job-classification"> <?php echo $sel_term_name; ?> </a> <span class="caret caret-reversed"></span> </button>
			<input type="hidden" name="usr_job_category" value="<?php echo $usr_job_category; ?>" id="job_Cate_hidden">
			<input type="hidden" name="usr_gen_job_category" value="<?php echo $usr_gen_job_category; ?>" id="gen_job_Cate_hidden">
			<div id="multi-column-wrapper">
				<ul id="multi-column">
					<?php $terms = get_terms('job_category', array('hide_empty' => 0, 'orderby' => 'name'));
					if ($terms) {
						$terms_count =  count($terms);

						/*	if($terms_count < 20)
					$terms_limit = 1;
				elseif($terms_count > 20)
					$terms_limit = 3;
				else*/
						$terms_limit = 1;

						$term_breaker = 20; //round($terms_count/$terms_limit);
						$kv = 0;
						echo ' <div class="col-sm-6"><h3> Specialist Jobs </h3>';
						foreach ($terms  as $taxonomy) {
							$kv++;
							echo '<li type="special" value="' . $taxonomy->term_id . '" ' . ($taxonomy->term_id == $usr_job_category ? 'style="color: red;"' : '') . '  >' . $taxonomy->name . '</li>';
							if ($kv == $term_breaker) {
								$kv = 1;
								echo '</div> <div class="col-sm-6"> ';
							}
						}
					}
					if ($kv > 20)
						echo '</div>';
					$general_terms = get_terms('gen_job_category', array('hide_empty' => 0, 'orderby' => 'name'));
					if ($general_terms) {
						$general_terms_count =  count($general_terms);
						/* if($general_terms_count < 20)
					$general_terms_limit = 1;
				elseif($general_terms_count > 20)
					$general_terms_limit = 3;
				else*/
						$general_terms_limit = 1;

						$term1_breaker = 20; //round($general_terms_count/$general_terms_limit);
						if ($kv > 20)
							echo ' <div class="col-sm-6">';


						echo '<h3> General Jobs </h3>';
						foreach ($general_terms  as $taxonomy1) {
							$kv++;
							echo '<li type="general" value="' . $taxonomy1->term_id . '" ' . ($taxonomy1->term_id == $usr_job_category ? 'style="color: red;"' : '') . '  >' . $taxonomy1->name . '</li>';
							if ($kv == $term1_breaker) {
								$kv = 1;
								echo '</div> <div class="col-sm-6"> ';
							}
						}
					}
					?>
			</div>
			</ul>
	</div>

	<style type="text/css">
		#multi-column-wrapper {
			position: relative
		}

		#multi-column {
			position: absolute;
			width: 600px;
			background: #e2eaf2;
			border-radius: 10px;
			padding: 10px;
			z-index: 10000
		}

		#multi-column-wrapper h3 {
			color: #63b2f5
		}

		#btn-job-classification {
			padding: 0px;
			min-width: 180px;
			display: inline-block;
		}

		#btn-job-classification .btn {}

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
	<div class="divider"> </div>
	<label>Gender : </label> <br />
	<label>
		<input type="radio" name="gender" value="male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'male') echo 'checked'; ?>> Male </label> <label> <input type="radio" name="gender" value="female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'female') echo 'checked'; ?>> Female </label>
	<label> <input type="radio" name="gender" value="any" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'any') echo 'checked'; ?>> Any </label>
	<div class="divider"> </div>
	<label>Qualification:</label><br>
	<?php $usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select Qualification', 'echo' => 0, 'taxonomy' => 'qualification', 'selected' => (isset($_POST['usr_qualification']) ? $_POST['usr_qualification'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
	$usr_qualification = str_replace("name='cat' id='cat'", "name='usr_qualification' ", $usr_qualification);
	echo $usr_qualification; ?>
	<div class="divider"> </div>

	<label> Type: </label><br>
	<?php $usr_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => (isset($_POST['usr_types']) ? $_POST['usr_types'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'  => 'NAME', 'order' => 'ASC',));
	$usr_types = str_replace("name='cat' id='cat'", "name='usr_types' ", $usr_types);
	echo $usr_types;  ?>

	<div class="divider"> </div>

	<label> Years of Experience: </label>

	<?php $usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Experience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => (isset($_POST['usr_yr_of_exp']) ? $_POST['usr_yr_of_exp'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
	$usr_yr_of_exp = str_replace("name='cat' id='cat'", "name='usr_yr_of_exp' ", $usr_yr_of_exp);
	echo $usr_yr_of_exp; 	?>

	<div class="divider"> </div>
	<?php if ($enable_shia_subscription == 'yes') { ?>
		<style>
			.shia_selection {
				visibility: visibile;
				display: block;
			}
		</style>
	<?php } else { ?>
		<style>
			.shia_selection {
				display: none;
				visibility: hidden;
				line-height: 0;
			}
		</style>
	<?php } ?>
	<h3 style="color:#084a86;margin-top:15px;font-size:16px " class="showhideJobCategory "> Denomination : <label> <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" checked="checked"> Sunni </label> <label class="shia_selection"> <input name="denominationSelection shia_selection" type="radio" value="shia" class="denominationSelection"> Shia </label> </h3>
	<div class="SunniDenomination">
		<label>Madhab/School of Law: </label><br>

		<?php $usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => (isset($_POST['usr_madhab']) ? $_POST['usr_madhab'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
		$usr_madhab = str_replace("name='cat' id='cat'", "name='usr_madhab' ", $usr_madhab);
		echo $usr_madhab; ?>

		<br>
		<label> Aqeeda/Creed </label><br>

		<?php $usr_aqeeda = wp_dropdown_categories(array('show_option_none' => 'Select Aqeeda/Belief', 'echo' => 0, 'taxonomy' => 'aqeeda', 'selected' => (isset($_POST['usr_aqeeda']) ? $_POST['usr_aqeeda'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
		$usr_aqeeda = str_replace("name='cat' id='cat'", "name='usr_aqeeda' ", $usr_aqeeda);
		echo $usr_aqeeda;    ?>
	</div>

	<div class="ShiaDenomination">
		<label>Madhab/School of Law: </label><br>

		<?php $usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $_POST['usr_madhab_shia'], 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
		$usr_madhab_shia = str_replace("name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia);
		echo $usr_madhab_shia; ?>
		<br>
		<label> Aqeeda/Creed </label><br>
		<select name="usr_aqeeda_shia" id="usr_aqeeda_shia">
			<?php Shia_Aqeeda_select($_POST['usr_aqeeda_shia']); ?>
		</select>
	</div>
	<div class="divider"> </div>

	<label> Languages: </label>

	<?php $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => (isset($_POST['usr_language']) ? $_POST['usr_language'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
	$usr_language = str_replace("name='cat' id='cat'", "name='usr_language' ", $usr_language);
	echo $usr_language; 	?>

	<div class="divider"> </div>

	<label> Country </label><br>


	<?php $usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => (isset($_POST['usr_zone']) ? $_POST['usr_zone'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' =>  0, 'orderby' => 'NAME', 'order' => 'ASC',));
	$usr_zone = str_replace("name='cat' id='cat'", "name='usr_zone' ", $usr_zone);
	echo $usr_zone;   ?>

	<div class="divider"> </div>


	<label> Salary </label>
	<input type="text" style="width:50px;border:1px solid #eee;"> to <input type="text" style="width:50px;border:1px solid #eee;">
	<label> Per </label>
	<div style="padding:3px;"></div>
	<?php $pref_sal_prd = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => (isset($_POST['pref_sal_prd']) ? $_POST['pref_sal_prd'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
	$pref_sal_prd = str_replace("name='cat' id='cat'", "name='pref_sal_prd' ", $pref_sal_prd);
	echo $pref_sal_prd; ?> OR

	<?php $pref_sal_optn = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_optn', 'selected' => (isset($_POST['pref_sal_optn']) ? $_POST['pref_sal_optn'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
	$pref_sal_optn = str_replace("name='cat' id='cat'", "name='pref_sal_optn' ", $pref_sal_optn);
	echo $pref_sal_optn;  	?>

	<!-- <br> -->
	<div style="text-align: center;"> <input id="submit-search" type="submit" name="submit_filter" value="Search" style="padding:5px 25px;"> </div>
	<!-- <br> -->
	</form>
	<div class="divider"> </div>
	<!-- <br> -->
</div>
</div>
<style>
	.SunniDenomination,
	.ShiaDenomination {
		display: none;
	}
</style>
<script>
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
	});
</script>