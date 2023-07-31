<?php

/**
Template Name: Donation
 */

get_header();
$sidebar_class = 'col-lg-12 col-md-12 col-sm-12';
?>



<section class="container">

	<!-- Page Heading -->
	<section class="">

		<h1><?php // echo esc_html(get_the_title());
			?></h1>

		<?php //if(get_option('sense_show_breadcrumb') == 'show') {
		?>
		<?php candidat_the_breadcrumbs(); ?>
		<?php //}
		?>


	</section>
	<!-- Page Heading -->

	<!-- Banner Ad -->
	<div style="color: #63b2f5; font-wight: bold; float: right; margin-top: -70px;"><img class="aligncenter size-full wp-image-752" src="http://eimams.com/galleries/2015/08/banner-ad.jpg" alt="banner728x90" width="728" height="90" /></div>



	<!-- Section -->
	<section class="donate-section">



		<!--   ###########   Mercy foundation  ######  -->
		<div class="donate-card">

			<div>

				<?php if (isset($_GET['donation']) && $_GET['donation'] == 'yes') { ?>
					<p class="success">Thank you for your funding and motivate us to provide better service for you.
					</p>

				<?php } ?>


				<a href="<?php echo  site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/mercy-logo.png" alt="Mercy Logo"></a>

				<p>This is a non-profit organization, so if you like the product, please make a small donation to secure the future development.
				</p>



				<form action="<?php echo site_url(); ?>/payment-page/?donation_yes=yes" method="post">
					<input type="hidden" name="name_of_org" value="Mercy Foundation">
					<select name="amount" id="donation_amt_drp" style="width:120px; margin-right:10px">
						<option value="10.00">&pound;10</option>
						<option value="20.00">&pound;20</option>
						<option value="30.00">&pound;30</option>
						<option value="40.00">&pound;40</option>
						<option value="50.00">&pound;50</option>
						<option value="60.00">&pound;60</option>
						<option value="70.00">&pound;70</option>
						<option value="80.00">&pound;80</option>
						<option value="90.00">&pound;90</option>
						<option value="100.00" selected>&pound;100</option>
						<option value="0">Custom</option>
					</select>
					<input type="text" name="custom_amt" id="custom_amt" value="" size="5" style="width: 65px;">
					<input name="submit" type="submit" class="button" style="padding:6px 10px 5px 10px;" value="Donation" title="The developers need your donation! Make payments with PayPal - it's fast, free and secure!">
				</form>

			</div>
		</div> <!-- end of mercy foundation -->

		<script type="text/javascript">
			$(function() {
				$("#custom_amt").hide();
				$("#donation_amt_drp").change(function() {
					//var selectedText = $(this).find("option:selected").text();
					var selectedValue = $(this).val();
					if (selectedValue == 0) {
						$("#custom_amt").show();
					} else {
						$("#custom_amt").hide();
					}
				});
				$("#custom_amt").keyup(function() {
					this.value = this.value.replace(/[^0-9\.]/g, '');
				});
			});
		</script>


		<!--   ###########   The masjid project  ######  -->
		<div class="donate-card">

			<div class="">

				<?php if (isset($_GET['donation']) && $_GET['donation'] == 'yes') { ?>
					<p class="success">Thank you for your funding and motivate us to provide better service for you.
					</p>

				<?php } ?>

				<a href="<?php echo  site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/masjid-project-logo.png" alt="Logo"></a>

				<p>This is a non-profit organization, so if you like the product, please make a small donation to secure the future development.
				</p>



				<form action="<?php echo site_url(); ?>/payment-page/?donation_yes=yes" method="post">
					<input type="hidden" name="name_of_org" value="Masjid Project">
					<select name="amount" id="donation_amt_drpms" style="width:120px; margin-right:10px">
						<option value="10.00">&pound;10</option>
						<option value="20.00">&pound;20</option>
						<option value="30.00">&pound;30</option>
						<option value="40.00">&pound;40</option>
						<option value="50.00">&pound;50</option>
						<option value="60.00">&pound;60</option>
						<option value="70.00">&pound;70</option>
						<option value="80.00">&pound;80</option>
						<option value="90.00">&pound;90</option>
						<option value="100.00" selected>&pound;100</option>
						<option value="0">Custom</option>
					</select>
					<input type="text" name="custom_amt" id="custom_amtms" value="" size="5" style="width: 65px;">
					<input name="submit" type="submit" class="button" style="padding:6px 10px 5px 10px;" value="Donation" title="The developers need your donation! Make payments with PayPal - it's fast, free and secure!">
				</form>

			</div>

		</div> <!-- end of masjid project  -->

		<script type="text/javascript">
			$(function() {
				$("#custom_amtms").hide();
				$("#donation_amt_drpms").change(function() {
					//var selectedText = $(this).find("option:selected").text();
					var selectedValue = $(this).val();
					if (selectedValue == 0) {
						$("#custom_amtms").show();
					} else {
						$("#custom_amtms").hide();
					}
				});
				$("#custom_amtms").keyup(function() {
					this.value = this.value.replace(/[^0-9\.]/g, '');
				});
			});
		</script>



		<!--   ###########   quatrain of saadi  ######  -->
		<div class="donate-card active-donate-card">

			<div>

				<?php if (isset($_GET['donation']) && $_GET['donation'] == 'yes') { ?>
					<p class="success">Thank you for your funding and motivate us to provide better service for you.
					</p>

				<?php } ?>

			</div> <!-- end of quatrain of saadi  -->

			<div class="bank-transfer">
				<h3 class="bank-heading"><span>Donate Via Bank Transfer</span></h3>
				<div class="bank-details-content">
					<ul class="bank-details">
						<li><span>Bank Name</span>:<strong>Tide</strong></li>
						<li><span>Account Name</span>:<strong>Eimams Online Limited</strong></li>
						<li><span>Account No</span>:<strong>18114953</strong></li>
						<li><span>Sort Code</span>:<strong>04-06-05</strong></li>
						<li><span>IBAN</span>:<strong>GB13CLR04060518114953</strong></li>
						<li><span>SWIFT</span>:<strong>CLRBGB22</strong></li>
					</ul>
				</div>
			</div>

		</div>


		<?php if (have_posts()) while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>




	</section>
	<!-- /Section -->

</section>
<?php
get_footer();

?>