<?php
global $current_user, $wp_roles, $wpdb;
wp_get_current_user();
$kv_current_role=kv_get_current_user_role();
?>	
	<!--  ######################################################################## -->
	<style type="text/css">
	@import url("http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css");
	@import url("http://fonts.googleapis.com/css?family=Roboto:400,300,700italic,700,500&subset=latin,latin-ext");
		
		/* COMMON PRICING STYLES */
			.panel.price,
			.panel.price>.panel-heading{
				border-radius:0px;
				 -moz-transition: all .3s ease;
				-o-transition:  all .3s ease;
				-webkit-transition:  all .3s ease;
			}
			.panel.price:hover{
				box-shadow: 0px 0px 30px rgba(0,0,0, .2);
			}
			.panel.price:hover>.panel-heading{
				box-shadow: 0px 0px 30px rgba(0,0,0, .2) inset;
			}			
					
			.panel.price>.panel-heading{
				box-shadow: 0px 5px 0px rgba(50,50,50, .2) inset;
				text-shadow:0px 3px 0px rgba(50,50,50, .6);
			}
				
			.price .list-group-item{
				border-bottom-:1px solid rgba(250,250,250, .5);
			}
			
			.panel.price .list-group-item:last-child {
				border-bottom-right-radius: 0px;
				border-bottom-left-radius: 0px;
			}
			.panel.price .list-group-item:first-child {
				border-top-right-radius: 0px;
				border-top-left-radius: 0px;
			}
			
			.price .panel-footer {
				color: #fff;
				border-bottom:0px;
				background-color:  rgba(0,0,0, .1);
				box-shadow: 0px 3px 0px rgba(0,0,0, .3);
			}			
			
			.panel.price .btn{
				box-shadow: 0 -1px 0px rgba(50,50,50, .2) inset;
				border:0px;
			}
			
		/* green panel */		
			
			.price.panel-green>.panel-heading {
				color: #fff;
				background-color: #57AC57;
				border-color: #71DF71;
				border-bottom: 1px solid #71DF71;
			}			
				
			.price.panel-green>.panel-body {
				color: #fff;
				background-color: #65C965;
			}					
			
			.price.panel-green>.panel-body .lead{
					text-shadow: 0px 3px 0px rgba(50,50,50, .3);
			}
			
			.price.panel-green .list-group-item {
				color: #333;
				background-color: rgba(50,50,50, .01);
				font-weight:600;
				text-shadow: 0px 1px 0px rgba(250,250,250, .75);
			}
			
			/* blue panel */		
			
			.price.panel-blue>.panel-heading {
				color: #fff;
				background-color: #608BB4;
				border-color: #78AEE1;
				border-bottom: 1px solid #78AEE1;
			}			
				
			.price.panel-blue>.panel-body {
				color: #fff;
				background-color: #73A3D4;
			}					
			
			.price.panel-blue>.panel-body .lead{
					text-shadow: 0px 3px 0px rgba(50,50,50, .3);
			}			
			.price.panel-blue .list-group-item {
				color: #333;
				background-color: rgba(50,50,50, .01);
				font-weight:600;
				text-shadow: 0px 1px 0px rgba(250,250,250, .75);
			}
			
			/* red price */	
			.price.panel-red>.panel-heading {
				color: #fff;
				background-color: #D04E50;
				border-color: #FF6062;
				border-bottom: 1px solid #FF6062;
			}		
				
			.price.panel-red>.panel-body {
				color: #fff;
				background-color: #EF5A5C;
			}
			.price.panel-red>.panel-body .lead{
					text-shadow: 0px 3px 0px rgba(50,50,50, .3);
			}
			
			.price.panel-red .list-group-item {
				color: #333;
				background-color: rgba(50,50,50, .01);
				font-weight:600;
				text-shadow: 0px 1px 0px rgba(250,250,250, .75);
			}
			
			/* grey price */	
			.price.panel-grey>.panel-heading {
				color: #fff;
				background-color: #6D6D6D;
				border-color: #B7B7B7;
				border-bottom: 1px solid #B7B7B7;
			}			
				
			.price.panel-grey>.panel-body {
				color: #fff;
				background-color: #808080;
			}
			
			.price.panel-grey>.panel-body .lead{
					text-shadow: 0px 3px 0px rgba(50,50,50, .3);
			}			
			.price.panel-grey .list-group-item {
				color: #333;
				background-color: rgba(50,50,50, .01);
				font-weight:600;
				text-shadow: 0px 1px 0px rgba(250,250,250, .75);
			}			
			/* white price */
			.price.panel-white>.panel-heading {
				color: #333;
				background-color: #f9f9f9;
				border-color: #ccc;
				border-bottom: 1px solid #ccc;
				text-shadow: 0px 2px 0px rgba(250,250,250, .7);
			}			
			.panel.panel-white.price:hover>.panel-heading{
				box-shadow: 0px 0px 30px rgba(0,0,0, .05) inset;
			}
				
			.price.panel-white>.panel-body {
				color: #fff;
				background-color: #dfdfdf;
			}
					
			.price.panel-white>.panel-body .lead{
					text-shadow: 0px 2px 0px rgba(250,250,250, .8);
					color:#666;
			}
			
			.price:hover.panel-white>.panel-body .lead{
					text-shadow: 0px 2px 0px rgba(250,250,250, .9);
					color:#333;
			}			
			.price.panel-white .list-group-item {
				color: #333;
				background-color: rgba(50,50,50, .01);
				font-weight:600;
				text-shadow: 0px 1px 0px rgba(250,250,250, .75);
			}
	</style>

	<div class="col-xs-12">
	<?php 
	if(($kv_current_role == 'employer' && $enable_employer_subscription == 'yes' ) || ($kv_current_role == 'job_seeker' && $enable_subscription == 'yes')){ ?>
		<h2 align="center"> Here you can switch to subscription level  </h2><br />
	<!--	<div class="row">-->
<?php 

$bg_color_iteration=array('panel-red','panel-blue','panel-green','panel-grey','panel-white');
$pack_tble = $wpdb->prefix.'jbs_subpack'; 
$sub_active_tbl = $wpdb->prefix. 'jbs_subactive' ; 
$pack = $wpdb->get_results( "SELECT * FROM ".$pack_tble."  where status='Active' and role='".$kv_current_role."' AND left_count = 0");


$pack_Count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$pack_tble."  where status='Active' and role='".$kv_current_role."' AND left_count = 0");

 if($pack_Count == 1)
	$Class = 'col-xs-12';
 else if($pack_Count == 2)
	$Class = 'col-xs-6';
 else if($pack_Count == 3)
	$Class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4 ';
 else if($pack_Count == 4)
 	$Class = 'col-xs-6 col-sm-4 col-md-3 col-lg-3'; 
 else if($pack_Count == 5)
 	$Class = 'col-xs-6 col-sm-4 col-md-3 col-lg-2'; 
	
	$kv=0;	
	$sub_start_date = kv_get_sub_start_date($current_user->ID); 
		foreach ( $pack as $pack_details ) {
			$pack_id = $wpdb->get_row( "SELECT * FROM ".$sub_active_tbl." where wp_user_id=".$current_user->ID .' AND pack_id='.$pack_details->id, ARRAY_A); 
			//$end_of_pack_date = kv_get_end_date_for_pack($pack_details->id, $sub_start_date, true); 
					?>
	
					<div class="<?php echo $Class; ?>">
					<form id="gateway_form" method="POST" name="gateway_form" action="https://www.paypal.com/cgi-bin/webscr">
					
						<input type="hidden" name="rm" value="1"/>
						<input type="hidden" name="cmd" value="_cart"/>
						<input type="hidden" name="charset" value="utf-8"/>
						<input type="hidden" name="business" value="<?php  /*echo 'XXU987WD8USFE'; */ echo '8TFU4YF3PXSBW'; ?>"/>
						<input type="hidden" name="currency_code" value="GBP"/>
						<input type="hidden" name="notify_url" value="<?php echo site_url(); ?>/ajax?action=paypal_listener"/>
						<input type="hidden" name="item_name_1" value="<?php echo $pack_details->pack_name; ?>" />
						<input type="hidden" name="item_number" value="<?php echo $pack_details->id; ?>" />
						<input type="hidden" name="item_number1" value="<?php echo $pack_details->id; ?>" />
						<input type="hidden" name="amount_1" value="<?php echo $pack_details->price; ?>" />
						<input type="hidden" name="quantity_1" value="1"/>
						<input type="hidden" name="custom" value="<?php echo'pack_id='.$pack_details->id.'&wp_user_id='. $current_user->ID.'&date_subscribed='.$sub_start_date.'&per_post='.$pack_details->per_post.'&status=Active'; ?>"/>
						<input type="hidden" name="return" value="<?php echo site_url('subscription');?>?subscribed=yes"/>
						<input type="hidden" name="upload" value="1"/>
						<!-- PRICE ITEM -->
						
						<div class="panel price <?php echo $bg_color_iteration[$kv]?>" >
							<div class="panel-heading  text-center">
							<h3><?php if($pack_details->per_post == 0){ echo $pack_details->duration ." ". $pack_details->period; } else 
									echo $pack_details->per_post.' Jobs'; ?></h3>
							</div>
							<div class="panel-body text-center">
								<p class="lead" style="font-size:40px"><strong><?php echo "&pound;".$pack_details->price?></strong></p>
							</div>
							<ul class="list-group list-group-flush text-center">
								<li class="list-group-item"><i class="icon-ok text-danger"></i> <?php echo $pack_details->pack_name?></li>
								<li class="list-group-item"><i class="icon-ok text-danger"></i> <?php echo $pack_id['status']; ?></li>
							</ul>
							<div class="panel-footer">
							<?php
							/*if (count ($pack_id) > 0) { 
								if($pack_id['status'] == 'Active' || $pack_id['status'] == 'Yet To Activate') { 
									echo '<a class="btn btn-lg btn-block '.$bg_color_iteration[$kv].' " href="#">'. $pack_id['status'].'</a>';
								} else{
									echo '<input type="submit" class="btn btn-lg btn-block '. $bg_color_iteration[$kv].' " value="Buy Now">';
								}
							} else{
								echo '<input type="submit" class="btn btn-lg btn-block '. $bg_color_iteration[$kv].' " value="Buy Now">';
							}*/ 
							echo '<input type="submit" class="btn btn-lg btn-block '. $bg_color_iteration[$kv].' " value="Buy Now">';
							 $kv++;?>
							</div>
						</div>
						<!-- /PRICE ITEM -->
						
						</form>
					</div>
					
			
					<?php } 
	}
	else {?>
						<div class="no_subscription_msg" >  There is no subscription required to access the system. </div> 
					<?php } ?>
					
					<!--</div>-->
				</div>