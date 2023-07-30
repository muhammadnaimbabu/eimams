<form method="post" action="<?php echo site_url('jobs'); ?>">
    <section class="search-section" <?php $imagePath = get_template_directory_uri() . "/img/searcingbg.png";
                                    echo 'style="background-image: url(\'' . $imagePath . '\')"' ?>>
        <div class="container">
            <div class="search-content">
                <h2>Searching Your Dream Job</h2>
                <span>We have 26 available Job(s) for you.</span>
            </div>
            <div class="search-filters">
                <!-- // Item  -->
                <div class="filters">
                    <i class="feather-16" data-feather="briefcase"></i>
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

                    <button id="job_Cate_button" class=""><a href="#" id="btn-job-classification"> <?php echo $sel_term_name; ?> </a> <span class="caret caret-reversed"></span> </button>
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
            </div>
            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="briefcase"></i>
                <?php $usr_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => (isset($_POST['usr_types']) ? $_POST['usr_types'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'  => 'NAME', 'order' => 'ASC',));
                $usr_types = str_replace("name='cat' id='cat'", "name='usr_types' ", $usr_types);
                echo $usr_types;  ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>
            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="users"></i>
                <select name="usr_types" id="">
                    <option value="-1">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="any">Any</option>
                </select>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>


            <div class="SunniDenomination">
                <div class="filters">
                    <h3 style="color:#084a86;margin-top:15px;font-size:16px " class="showhideJobCategory "><label> <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" checked="checked"> </label> <label class="shia_selection"> <input name="denominationSelection shia_selection" type="radio" value="shia" class="denominationSelection"></label> </h3>
                    <?php $usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => (isset($_POST['usr_madhab']) ? $_POST['usr_madhab'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                    $usr_madhab = str_replace("name='cat' id='cat'", "name='usr_madhab' ", $usr_madhab);
                    echo $usr_madhab; ?>
                    <i class="feather-16" data-feather="chevron-down"></i>
                </div>
                <!-- <i class="feather-16" data-feather="chevron-down"></i> -->
            </div>

            <div class="SunniDenomination">
                <div class="filters">
                    <?php $usr_aqeeda = wp_dropdown_categories(array('show_option_none' => 'Select Aqeeda/Belief', 'echo' => 0, 'taxonomy' => 'aqeeda', 'selected' => (isset($_POST['usr_aqeeda']) ? $_POST['usr_aqeeda'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                    $usr_aqeeda = str_replace("name='cat' id='cat'", "name='usr_aqeeda' ", $usr_aqeeda);
                    echo $usr_aqeeda; ?>
                    <i class="feather-16" data-feather="chevron-down"></i>
                </div>
                <!-- <i class="feather-16" data-feather="chevron-down"></i> -->
            </div>

            <div class="ShiaDenomination">
                <div class="filters">
                    <?php $usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $_POST['usr_madhab_shia'], 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                    $usr_madhab_shia = str_replace("name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia);
                    echo $usr_madhab_shia; ?>
                    <i class="feather-16" data-feather="chevron-down"></i>
                </div>
            </div>
            <div class="ShiaDenomination">
                <div class="filters">
                    <select name="usr_aqeeda_shia" id="usr_aqeeda_shia">
                        <?php Shia_Aqeeda_select($_POST['usr_aqeeda_shia']); ?>
                    </select>
                </div>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>


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
            <!-- // Item  -->
            <div class="filters">
                <div class="feather-16">
                    <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" id="graduation-cap">
                        <path d="M21.49,10.19l-1-.55h0l-9-5-.11,0a1.06,1.06,0,0,0-.19-.06l-.19,0-.18,0a1.17,1.17,0,0,0-.2.06l-.11,0-9,5a1,1,0,0,0,0,1.74L4,12.76V17.5a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V12.76l2-1.12V14.5a1,1,0,0,0,2,0V11.06A1,1,0,0,0,21.49,10.19ZM16,17.5a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V13.87l4.51,2.5.15.06.09,0a1,1,0,0,0,.25,0h0a1,1,0,0,0,.25,0l.09,0a.47.47,0,0,0,.15-.06L16,13.87Zm-5-3.14L4.06,10.5,11,6.64l6.94,3.86Z">
                        </path>
                    </svg>
                </div>
                <?php $usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select Qualification', 'echo' => 0, 'taxonomy' => 'qualification', 'selected' => (isset($_POST['usr_qualification']) ? $_POST['usr_qualification'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                $usr_qualification = str_replace("name='cat' id='cat'", "name='usr_qualification' ", $usr_qualification);
                echo $usr_qualification; ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>
            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="trending-up"></i>
                <?php $usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Experience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => (isset($_POST['usr_yr_of_exp']) ? $_POST['usr_yr_of_exp'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                $usr_yr_of_exp = str_replace("name='cat' id='cat'", "name='usr_yr_of_exp' ", $usr_yr_of_exp);
                echo $usr_yr_of_exp;     ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>
            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="map"></i>
                <?php $usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => (isset($_POST['usr_zone']) ? $_POST['usr_zone'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' =>  0, 'orderby' => 'NAME', 'order' => 'ASC',));
                $usr_zone = str_replace("name='cat' id='cat'", "name='usr_zone' ", $usr_zone);
                echo $usr_zone;   ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>

            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="globe"></i>
                <?php $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => (isset($_POST['usr_language']) ? $_POST['usr_language'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                $usr_language = str_replace("name='cat' id='cat'", "name='usr_language' ", $usr_language);
                echo $usr_language;     ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>
            <div class="filters">
                <input type="text" style="width:50%"> to <input type="text" style="width:50%">
            </div>

            <!-- // Item  -->
            <div class="filters">
                <i class="feather-16" data-feather="dollar-sign"></i>
                <?php $pref_sal_prd = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => (isset($_POST['pref_sal_prd']) ? $_POST['pref_sal_prd'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                $pref_sal_prd = str_replace("name='cat' id='cat'", "name='pref_sal_prd' ", $pref_sal_prd);
                echo $pref_sal_prd; ?>
                <i class="feather-16" data-feather="chevron-down"></i>
            </div>

            <div class="filters">
                <?php $pref_sal_optn = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_optn', 'selected' => (isset($_POST['pref_sal_optn']) ? $_POST['pref_sal_optn'] : -1), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                $pref_sal_optn = str_replace("name='cat' id='cat'", "name='pref_sal_optn' ", $pref_sal_optn);
                echo $pref_sal_optn;      ?>
            </div>
            <div class="filters">
                <button class="submit-btn" name="submit_filter" type="submit">Search</button>
            </div>
        </div>
        </div>

    </section>
</form>

<style>
    .SunniDenomination,
    .ShiaDenomination {
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
    });
</script>