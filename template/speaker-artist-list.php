<?php 
/** 
* Template Name: Speaker Artists List
*/ 
get_header(); ?>

<style type="text/css">
body { background:#f3f4f9}
#artist-speaker { }
.hr { border-bottom:1px solid}
h4.profile-title { font-size: 18px; font-weight: 600; margin: 10px 0 10px; }

.panel-heading { min-height:65px; float:left; width:100%; margin-bottom:0px;}
span.view-profile { margin-top:15px; display:block ; float:right; color:#23527c;}
span.view-profile:hover { text-decoration:none}
#staff-box img { float:left; margin-right:20px; }
.profile {  float:left; background:#fff; }
.panel-group .panel { border-radius: 4px; border:1px solid #eee; }
.panel-title p { color:#274472 ; }
.nav-tabs { border-bottom: 0px solid #ddd; }
.nav-tabs>li { 
	float: left;  
	margin-bottom: -1px;  
	background: #e2dfdf;  
	margin: 0 10px 0 0; 
	padding: 15px 18px;
	border-top-left-radius:4px;
	border-top-right-radius:4px;
}
.nav > li > a:hover, .nav > li > a:focus { background-color: transparent }
.nav-tabs>li.active { background:#fff ; border:1px solid #ddd; border-bottom:none }
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color:transparent;
    border: 0px solid #ddd;
    border-bottom-color: transparent;
}
.nav-tabs > li > a {
    margin-right: 2px;
    line-height: 1.42857143;
    border: 0px solid transparent;
    border-radius: 4px 4px 0 0;
}

.tab-content { 
	border:1px solid #ddd; 
	background:#fff; 
	float:left; 
	padding:20px; 
	width:100%; 
	margin-top:-1px;
	margin-bottom:10px;
}

#staff-box { margin-top:15px; }
#staff-box .staff-box-content-right {padding-top:25px; }
#staff-box .profile-title { font-size:18px; font-weight:bold }
#staff-box .designation { font-size:16px}
#staff-box .contact { font-size:18px ; font-weight:bold  }


</style>

<div id="artist-speaker" class="container">
<br>

<h1 align="center" style="color:#777">Artist & Speaker List </h1>

<p>We at eimams have teamed up with some of the most prominent scholars, intellectuals, professionals and artists. In this ever evolving world Muslims have excelled in many fronts some them such as scholars, intellectuals, professionals and artists. It’s our good fortunate that we are able to establish strong links and in doing so, we are able to give access to these very prominent individuals. Therefore, If you would like to request a speaker or an artist for your programmes or events then please complete the form below. </p><br />


<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a data-toggle="tab" href="#artist-list">Artist List</a></li>
    <li class=""><a data-toggle="tab" href="#speaker-list">Speaker List</a></li>
</ul>
        
<div class="tab-content" id="myTabContent">      

<div id="artist-list" class="tab-pane fade active in"> 

  <div class="panel-group" id="accordion">
  <?php $query = new WP_Query( array('post_type' => 'speakers-artist',  'posts_per_page' => -1, 
  	'tax_query' => array(	array(
						'taxonomy' => 'speaker_or_artist',
						'field'    => 'slug',
						'terms'    => 'artist'
					))
			) );
      
while ( $query->have_posts() ) {
		$query->the_post();?>
		<div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#artist-<?php echo get_the_ID();?>">
                    <div class="col-xs-9 col-md-10">
                        <p><?php the_title(); ?> </p>
                        <p> <?php echo get_post_meta( get_the_ID(), 'designation', true ); ?> </p>
                    </div>
                    <div class="col-xs-3 col-md-2"><span class="view-profile">View profile</span></div>
                    </a>
                </h4>
            </div>
            
            <div id="artist-<?php echo get_the_ID();?>" class="panel-collapse collapse">
                <div class="panel-body">
	 				<div class="profile col-sm-12">
                        <div id="staff-box">
                           <div class="col-sm-4">
                           <?php 	if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( get_the_ID(), array( 200, 200) );
                }else {	echo "<img src='".get_template_directory_uri()."/images/default-pic-speaker-artist.png' height='150' />";} 	?>
                              </div>
                            <div class="staff-box-content-right col-sm-8">
                            <div class="profile-title"><?php the_title(); ?></div>
                            <div class="designation"><?php echo get_post_meta( get_the_ID(), 'designation', true ); ?></div>
							<div class="conact">Contact - <a href="mailto:<?php echo get_post_meta( get_the_ID(), 'email', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'email', true ); ?></a></div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                                 <?php the_content(); ?>
                         </div>
                         <div class="col-xs-12">
							<h3>To submit a Artist Request for <?php the_title(); ?>, please click the button below:</h3>
                                    <form method="post" action="<?php echo  site_url('request');?>" > 
                                        <input type="hidden" name="person_id" value="<?php echo get_the_ID();?>" > 
                                        <input type="hidden" name="category" value="Artist">
                                        <input type="submit" class="btn btn-default" value="Artist Request Form" name="form_submit" >
                                    </form>
						 </div>						
               		</div>
            	</div>
       		</div>
      </div><div style="clear:both"> </div><?php 
} ?>
  
    </div> <!-- /end of panel group -->
</div>   <!-- /end of artist list tab  -->

<!--   #########################  Speaker List tab ###############  -->

<div id="speaker-list" class="tab-pane fade ">  
  <div class="panel-group" id="accordion">       

       <?php $query = new WP_Query( array('post_type' => 'speakers-artist',  'posts_per_page' => -1, 'tax_query' => array(	array(
						'taxonomy' => 'speaker_or_artist',
						'field'    => 'slug',
						'terms'    => 'speaker'
					)) ) );
      
while ( $query->have_posts() ) {
		$query->the_post();?>
		<div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#speaker-<?php echo get_the_ID();?>">
                    <div class="col-xs-9 col-md-10">
                        <p><?php the_title(); ?> </p>
                        <p> <?php echo get_post_meta( get_the_ID(), 'designation', true ); ?> </p>
                    </div>
                    <div class="col-xs-3 col-md-2"><span class="view-profile">View profile</span></div>
                    </a>
                </h4>
            </div>
            
            <div id="speaker-<?php echo get_the_ID();?>" class="panel-collapse collapse">
                <div class="panel-body">
	 				<div class="profile col-sm-12">
                        <div id="staff-box">
                             <div class="col-sm-4">
                             <?php 	if ( has_post_thumbnail() ) {
                    echo get_the_post_thumbnail( get_the_ID(), array( 200, 200) );
                }else {	echo "<img src='".get_template_directory_uri()."/images/default-dp-eimams.png' height='150' />";} 	?>
                             </div>
                            <div class="staff-box-content-right col-sm-8">
                             <div class="profile-title"><?php the_title(); ?></div>
                            <div class="designation"><?php echo get_post_meta( get_the_ID(), 'designation', true ); ?></div>
							<div class="conact"> <a href="mailto:<?php echo get_post_meta( get_the_ID(), 'email', true ); ?>"><?php echo get_post_meta( get_the_ID(), 'email', true ); ?></a></div>
                        </div>
                        <div class="col-xs-12">
	                        <?php the_content(); ?>
						</div>
                        <div class="col-xs-12">
    					<h3>To submit a Speaker Request for <?php the_title(); ?>, please click the button below:</h3>
						<form method="post" action="<?php echo  site_url('request');?>" > 
							<input type="hidden" name="person_id" value="<?php echo get_the_ID();?>" > 
							<input type="hidden" name="category" value="Speaker">
							<input type="submit" class="btn btn-default" value="Speaker Request Form" name="form_submit" >
						</form>
                        </div>
               		</div>
            	</div>
       		</div>
      </div><div style="clear:both"> </div><?php 
} ?>

    </div> <!-- /end of panel group -->

</div>

<!--   #########################  end of Speaker list ###############  -->

 
</div> <!-- / end of Tab Panel -->
</div> <!-- / end of contianer -->

</div>
<?php get_footer(); ?> 