


                      
                       <div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
                        
                            <div id="job-board-button2" class="banner-wrapper">
								<a class="banner animate-onscroll" href="javascript:void(0)">
                                       <h3>Free</h3>
                                       <h3>Registration</h3>
								</a>
							</div>
			
            			</div>
                        
     <div class="col-lg-12 col-md-12 col-sm-12">                
<div class="banner-wrapper" id="job-board">
<h3 style="text-align:center;">Subscription</h3>

<?php  global $wpdb; 

global $current_user, $wp_roles, $wpdb;
wp_get_current_user();
$kv_current_role=kv_get_current_user_role();

$pack_tble = $wpdb->prefix.'jbs_subpack'; 

$jobseeker_packs = $wpdb->get_results( "SELECT * FROM ".$pack_tble."  where status='Active' and role='job_seeker' AND left_offer = 'yes' ORDER BY price");
$employer_packs = $wpdb->get_results( "SELECT * FROM ".$pack_tble."  where status='Active' and role='employer' AND left_offer = 'yes' ORDER BY price");

$bg_color_iteration=array('panel-red','panel-blue','panel-green','panel-grey','panel-white');

$jb_pack_Count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$pack_tble."  where status='Active' and role='job_seeker'");
$emplr_pack_Count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$pack_tble."  where status='Active' and role='employer'");

 if($jb_pack_Count == 1 || $emplr_pack_Count == 1)
	$Class = $emplr_Class= 'col-xs-12';
 else if($jb_pack_Count == 2 || $emplr_pack_Count == 2)
	$Class = $emplr_Class= 'col-xs-6';
 else if($jb_pack_Count == 3 || $emplr_pack_Count == 3)
	$Class = $emplr_Class= 'col-xs-6 col-sm-4 col-md-4 col-lg-4 ';
 else if($jb_pack_Count == 4 || $emplr_pack_Count == 4)
 	$Class = $emplr_Class= 'col-xs-12 col-sm-4 col-md-3 col-lg-3'; 
 else if($jb_pack_Count == 5 || $emplr_pack_Count == 5)
 	$Class = $emplr_Class= 'col-xs-6 col-sm-4 col-md-3 col-lg-2'; 	

	
 ?>
             <ul class="nav nav-tabs" id="myTab">
                   <li class="active"><a data-toggle="tab" href="#services">Employer</a></li>
				 <?php if($enable_subscription == 'yes'){ ?>  <li class=""><a data-toggle="tab" href="#home">Jobseeker</a></li>   <?php } ?>
     		</ul>
   
       
                       <div class="tab-content" id="myTabContent">
                       
                          <?php if($enable_subscription == 'yes'){ ?>   <div id="home" class="tab-pane fade">   
                          
								<?php $kv = 0;
								 foreach ( $jobseeker_packs as $jobseeker_pack ) { ?>
								  <div class="<?php echo $emplr_Class; ?>">
									 <form  action="<?php  echo  get_site_url();?>/jobseeker-sign-up"  method="post" >
										<div class="panel price <?php echo $bg_color_iteration[$kv]?>" >
                                        <div class="panel-heading  text-center">
											<h3><?php if($jobseeker_pack->per_post == 0){ echo $jobseeker_pack->duration ." ". $jobseeker_pack->period; } else 
													echo $jobseeker_pack->per_post.' Jobs'; ?></h3>
											</div>
											<div class="panel-body">
												<p class="lead" style="font-size:40px;text-align:center;"><strong><?php echo "&pound;".$jobseeker_pack->price?></strong></p>
											</div>
											<ul class="text-center" style="margin:0;padding:10px 0;list-style:none;">
												<li  style="margin:0;padding:0;"><span class="glyphicon glyphicon-time" style="margin-right:10px;"></span><?php echo $jobseeker_pack->pack_name?></li>
												
											</ul>
											<div class="panel-footer">
											<input type="hidden" name="submit_pack_id" value="<?php echo $jobseeker_pack->id; ?>">							
											<input type="submit" class="btn btn-lg btn-block" name="submit_pack" value="Buy Now">							
											</div>
										</div>
									</form>
									</div>
							<?php $kv++; } ?> 
							                            
                                  
                                 </div>
								 <?php } ?>
                            
                         <div id="services" class="tab-pane fade active in">          
								 
								
								<?php
								$kv = 0; 
								 foreach ( $employer_packs as $employer_pack ) { ?>
								  <div class="<?php echo $emplr_Class; ?>">
										<form  action="<?php  echo  get_site_url();?>/employer-sign-up"  method="post" >
											  <div class="panel price <?php echo $bg_color_iteration[$kv]?>" >
															<div class="panel-heading  text-center">
															<h3><?php if($employer_pack->per_post == 0){ echo $employer_pack->duration ." ". $employer_pack->period; } else 
																	echo $employer_pack->per_post.' Jobs'; ?></h3>
															</div>
															<div class="panel-body">
																<p class="lead" style="font-size:40px;text-align:center;"><strong><?php echo "&pound;".$employer_pack->price?></strong></p>
															</div>
															<ul class="text-center"  style="margin:0;padding:10px 0;list-style:none;">
																<li  style="margin:0;padding:0"><span class="glyphicon glyphicon-time" style="margin-right:10px;"></span><?php echo $employer_pack->pack_name?></li>
															
															</ul>
															<div class="panel-footer">
															<input type="hidden" name="submit_pack_id" value="<?php echo $employer_pack->id; ?>">							
															<input type="submit" class="btn btn-lg btn-block" name="submit_pack" value="Buy Now ">	
                                                            
                                                       <?php //  	echo '<input type="submit" class="btn btn-lg btn-block '. $bg_color_iteration[$kv].' " value="Buy Now">';
							 $kv++;?>
                                                            						
										 					</div>
														</div>
												 </form> 
										</div>
											<?php } ?>
												                  
								
								</div>
							</div>		
                            
                             </div>
                
                      </div>
