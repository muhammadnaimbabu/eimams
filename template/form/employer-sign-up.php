<!-- -------------------------------
    The employer sign up template
 ---------------------------------- -->

<script src="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.min.js"></script>

<link href="<?php echo get_template_directory_uri(); ?>/assets/jquery-te-1.4.0.css" rel="stylesheet" type="text/css">

<div style="padding:10px;margin:10px; background:#fff;">

    <?php
    if (isset($errors) && $errors->get_error_code()) :
        echo '<ul class="error text-center">';
        foreach ($errors->errors as $error) {
            echo '<li>' . $error[0] . '</li>';
        }
        echo '</ul>';
    endif;
    ?>

</div>
<section class="registration container">
    <div class="form-upper-part">
        <h4>Employer Registration</h4>
        <a href="#myModal" class="button">Open Modal</a>
        <a href="<?php echo get_template_directory_uri(); ?>/images/employer-pdf-sunni.pdf">Download a PDF Version of this form</a>
    </div>
    <div class="">
        <form action="" method="post">
            <div class=" form__contents">
                <h2 class="section__subheading form__subheading">DETAILS OF THE COMPANY/ORGANISATION</h2>
                <div class="forms">
                    <label class="from__para form__label">Company/Organisation Name<span class="req">*</span></label>
                    <div class="form__input">
                        <input type="text" class="form-control" id="Name" name="company_name" placeholder="Name" value="<?php echo $posted['company_name']; ?>">
                    </div>
                </div>
            </div>
            <div class="form__contents">
                <h2 class="section__subheading form__subheading">JOB LOCATION</h2>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Email<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="email" class="form-control" id="inputEmail" name="usr_email" value="<?php echo $posted['usr_email']; ?>" placeholder="Email">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">UserName<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="inputUsername" name="UserName" value="<?php echo $posted['UserName']; ?>" placeholder="Username">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Password<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="inputPassword" name="PassWord" value="<?php echo $posted['PassWord']; ?>" placeholder="Password">
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">address1 <span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $posted['address1']; ?>" placeholder="Address">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">address2</label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $posted['address2']; ?>" placeholder="Address">
                        </div>
                    </div>
                </div>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">City</label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $posted['city']; ?>" placeholder="Address">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">State/Province/Region:</label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="state_pro_reg" name="state_pro_reg" value="<?php echo $posted['state_pro_reg']; ?>" placeholder="Address">
                        </div>
                    </div>

                </div>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Zip/Post Code</label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="post-code" name="post_code" value="<?php echo $posted['post_code']; ?>" placeholder="Post Code">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Country<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php if ($posted['usr_zone'] == '' || $posted['usr_zone'] == null)
                                $selected_usr_zone = 0;
                            else
                                $selected_usr_zone = $posted['usr_zone'];
                            $usr_zone = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $selected_usr_zone, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
                            $usr_zone = str_replace("name='cat' id=", "name='usr_zone' id=", $usr_zone);
                            echo $usr_zone; ?>
                        </div>
                    </div>
                </div>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Website</label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="website" name="website" value="<?php echo $posted['website']; ?>" placeholder="Website">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Phone<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="tel" class="form-control" id="phoneNumber" value="<?php echo $posted['phone_number']; ?>" name="phone_number" placeholder="Phone Number">
                        </div>
                    </div>
                </div>
                <div class="forms textarea">
                    <label class="from__para form__label">Company Description</label>
                    <div class="form__input-textarea">
                        <textarea class="form-control" id="message" cols="30" rows="10" name="company_description" placeholder="Company Description"><?php echo $posted['company_description']; ?></textarea>

                    </div>
                </div>
            </div>
            <div class="form__contents">
                <h2 class="section__subheading form__subheading">PERSONAL DETAILS OF REPRESENTATIVE OF THE
                    COMPANY/ORGANISATION</h2>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Full name <span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="representative-name" name="representative_name" value="<?php echo $posted['representative_name']; ?>" placeholder="Name">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Position<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="representative-position" name="rep_position" value="<?php echo $posted['rep_position']; ?>" placeholder="Name">
                        </div>
                    </div>
                </div>

            </div>
            <div class="form__contents">
                <h2 class="section__subheading form__subheading">CREATING A NEW VACANCY</h2>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Job Title<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="employer-ref" name="title" value="<?php echo $posted['title']; ?>" placeholder="Job Title">
                        </div>
                        <div class="form__input--lowerFeild">
                            <p class="from__para form__lowerItems">Enter the title for the job vacancy.</p>
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Number Of Vacancy<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="text" class="form-control" id="employer-ref" name="no_of_vacancy" value="<?php echo $posted['no_of_vacancy']; ?>" placeholder="No.of Vacancy">
                        </div>
                        <div class="form__input--lowerFeild">
                            <p class="from__para form__lowerItems">Enter the title for the job vacancy.</p>
                        </div>
                    </div>
                </div>
                <div class="forms">
                    <label class="from__para form__label">Job Reference<span class="req">*</span></label>
                    <div class="form__input from__reference">
                        <input type="text" class="form-control" id="employer-ref" name="employer_ref" value="<?php echo get_next_reference();  ?>" disabled="disabled">
                        <input type="hidden" class="form-control" id="employer-ref" name="employer_ref" value="<?php echo get_next_reference();  ?>">
                    </div>
                </div>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Advertisement Start Date<span class="req">*</span></label>
                        <div class="form__input">
                            <?php $posted['ad_start_date'] = date('d-M-Y', strtotime("+1 days")); ?>
                            <input type="date" class="datepicker form-control" id="dt1" name="ad_start_date" value="<?php echo $posted['ad_start_date']; ?>" placeholder="Advertisement Start Date">
                        </div>
                        <div class="form__input--lowerFeild">
                            <p class="from__para form__lowerItems">Enter the date from which the vacancy is to be
                                advertised i.e. date the vacancy will be made available to jobseekers.</p>
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Advertisement close Date<span class="req">*</span></label>
                        <div class="form__input">
                            <input type="date" class="datepicker form-control" id="dt2" name="ad_close_date" value="<?php echo $posted['ad_close_date']; ?>" placeholder="Advertisement Close Date">
                        </div>
                        <div class="form__input--lowerFeild">
                            <p class="from__para form__lowerItems">Enter the date on which the vacancy will be withdrawn
                                from display.</p>
                        </div>
                    </div>
                </div>
                <div class="form__items flex--items radio__align">
                    <div class="forms from__width">
                        <label class="from__para form__label">Interview Start Date</label>
                        <div class="form__input">
                            <input type="date" class="datepicker form-control" id="dt3" name="in_start_date" value="<?php echo $posted['in_start_date']; ?>">
                        </div>
                        <div class="form__input--lowerFeild">
                            <p class="from__para form__lowerItems">Enter the date on which the interview will be started
                            </p>
                        </div>
                    </div>

                </div>
                <div class="form__items flex--items ">
                    <div class="forms from__width">
                        <label class="from__para form__label">Job Classifications<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php
                            /*if($posted['usr_job_category'] == -1 || $posted['usr_job_category'] == null )
					$selected_cat = -1;
				else
					$selected_cat =$posted['usr_job_category'];*/
                            if (isset($posted['usr_job_category']) &&  $posted['usr_job_category'] != -1) {
                                $selected_cat = $usr_job_category = $posted['usr_job_category'];
                                $usr_gen_job_category = -1;
                            } elseif (isset($posted['usr_gen_job_category']) &&  $posted['usr_gen_job_category'] != -1) {
                                $selected_cat = $usr_gen_job_category = $posted['usr_gen_job_category'];
                                $usr_job_category = -1;
                            } else {
                                $selected_cat = $usr_job_category = $usr_gen_job_category = null;
                            }
                            //	echo $selected_cat;
                            echo kv_merged_taxonomy_dropdown('job_category', 'gen_job_category', $selected_cat);
                            echo '<input type="hidden" name="usr_job_category" value="' . $usr_job_category . '" id="usr_job_category" >
					<input type="hidden" name="usr_gen_job_category" value="' . $usr_gen_job_category . '" id="usr_gen_job_category" > ';    ?>
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Qualification<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php if ($posted['usr_qualification'] == '' || $posted['usr_qualification'] == null)
                                $selected_qul = 0;
                            else
                                $selected_qul = $posted['usr_qualification'];
                            $usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'qualification', 'id' => 'job_qualification', 'selected' => $selected_qul, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_qualification = str_replace("name='cat' id=", "name='usr_qualification' id=", $usr_qualification);
                            echo $usr_qualification; ?>
                            <input type="text" class="form-control" placeholder="Custom Qualification" id="custom_qualification" name="custom_qualification" value="<?php echo $posted['custom_qualification']; ?>">
                        </div>
                    </div>
                </div>
                <div class="form__items flex--items ">
                    <div class="forms from__width">
                        <label class="from__para form__label">Type<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php if ($posted['usr_types'] == '' || $posted['usr_types'] == null)
                                $selected_type = 0;
                            else
                                $selected_type = $posted['usr_types'];
                            $usr_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => $selected_type, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_types = str_replace("name='cat' id=", "name='usr_types' id=", $usr_types);
                            echo $usr_types; ?>
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">Years of Exprerience<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php if ($posted['usr_yr_of_exp'] == '' || $posted['usr_yr_of_exp'] == null)
                                $selected_yr_exp = 0;
                            else
                                $selected_yr_exp = $posted['usr_yr_of_exp'];
                            $usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Exprerience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => $selected_yr_exp, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_yr_of_exp = str_replace("name='cat' id=", "name='usr_yr_of_exp' id=", $usr_yr_of_exp);
                            echo $usr_yr_of_exp; ?>
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <div class="forms textarea">
                            <label class="from__para form__label">Experience Details</label>
                            <div class="form__input-textarea">
                                <textarea class="form-control" id="job-duties" cols="30" rows="10" name="experience_details" placeholder="Experience Details"><?php echo $posted['experience_details']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="forms from__width">
                        <div class="form__radio">
                            <div class="form__radioItems">
                                <label class="from__para form__label form-radio">Gender<span class="req">*</span>
                                </label>
                            </div>
                            <div class="form__radioItems">
                                <label class="from__para form__label form-radio">
                                    <input type="radio" name="gender" value="male" <?php if ($posted['gender'] == 'male') echo 'checked';  ?>> Male
                                </label>
                            </div>
                            <div class="form__radioItems">
                                <label class="from__para form__label form-radio">
                                    <input type="radio" name="gender" value="female" <?php if ($posted['gender'] == 'female') echo 'checked';  ?>> Female
                                </label>
                            </div>
                            <div class="form__radioItems">
                                <label class="from__para form__label form-radio">
                                    <input type="radio" name="gender" value="any" <?php if ($posted['gender'] == 'any') echo 'checked';  ?>> Any
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="showhideJobCategory" style="border-top:1px solid #ddd; width:100%; height:4px; margin:15px 0; float:left"> </div>
                <?php if ($enable_shia_subscription == 'yes') { ?>
                    <style>
                        .shia_selection {
                            visibility: visibile;
                        }
                    </style>
                <?php } else { ?>
                    <style>
                        .shia_selection {
                            visibility: hidden;
                        }
                    </style>
                <?php } ?>

                <h3 class="showhideJobCategory"> Denomination : <label class=" "> <input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" checked="checked"> Sunni </label> <label class="shia_selection"> <input name="denominationSelection" type="radio" value="shia" class="denominationSelection"> Shia </label> </h3>
                <div class="SunniDenomination form__items flex--items">
                    <div class="form-group showhideJobCategory forms from__width">
                        <label class="control-label col-sm-4" for="Name">Madhab/School of Law:<span class="mandatory">*</span> </label>
                        <div class="col-sm-8">
                            <?php if ($posted['usr_madhab'] == '' || $posted['usr_madhab'] == null)
                                $selected_usr_madhab = 0;
                            else
                                $selected_usr_madhab = $posted['usr_madhab'];
                            $usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => $selected_usr_madhab, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_madhab = str_replace("name='cat' id=", "name='usr_madhab' id=", $usr_madhab);
                            echo $usr_madhab; ?>
                        </div>
                    </div>

                    <div class="form-group showhideJobCategory forms from__width">
                        <label class="control-label col-sm-4" for="Name">Aqeeda/Belief<span class="mandatory">*</span> </label>
                        <div class="col-sm-8">
                            <?php if ($posted['usr_aqeeda'] == '' || $posted['usr_aqeeda'] == null)
                                $selected_usr_aqeeda = 0;
                            else
                                $selected_usr_aqeeda = $posted['usr_aqeeda'];
                            $usr_aqeeda = wp_dropdown_categories(array('show_option_none' => 'Select Aqeeda/Belief', 'echo' => 0, 'taxonomy' => 'aqeeda', 'selected' => $selected_usr_aqeeda, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_aqeeda = str_replace("name='cat' id=", "name='usr_aqeeda' id=", $usr_aqeeda);
                            echo $usr_aqeeda;  ?>
                        </div>
                    </div>
                </div>

                <div class="ShiaDenomination form__items flex--items">
                    <div class="form-group showhideJobCategory forms from__width">
                        <label class="control-label col-sm-4" for="Name">Madhab/School of Law:<span class="mandatory">*</span> </label>
                        <div class="col-sm-8">
                            <?php if ($posted['usr_madhab_shia'] == '' || $posted['usr_madhab_shia'] == null)
                                $selected_usr_madhab_shia = 0;
                            else
                                $selected_usr_madhab_shia = $posted['usr_madhab_shia'];
                            $usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab ', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $selected_usr_madhab_shia, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                            $usr_madhab_shia = str_replace("name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia);
                            echo $usr_madhab_shia; ?>
                        </div>
                    </div>

                    <div class="form-group showhideJobCategory forms from__width">
                        <label class="control-label col-sm-4" for="Name">Aqeeda/Belief<span class="mandatory">*</span> </label>
                        <div class="col-sm-8">
                            <?php if ($posted['usr_aqeeda_shia'] == '' || $posted['usr_aqeeda_shia'] == null)
                                $selected_usr_aqeeda_shia = 0;
                            else
                                $selected_usr_aqeeda_shia = $posted['usr_aqeeda_shia']; ?>

                            <select name="usr_aqeeda_shia" id="usr_aqeeda_shia">
                                <?php Shia_Aqeeda_select($selected_usr_aqeeda_shia); ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="forms">
                    <label class="from__para form__label">Language<span class="req">*</span></label>
                    <div class="form__input">
                        <?php if (isset($_POST['usr_language']) && $_POST['usr_language'] != '' || $_POST['usr_language'] != null) {

                            if (isset($_POST['usr_language'])) {
                                $final_array = array_values($_POST['usr_language']);
                            } else
                                $final_array = 4;

                            //var_dump($final_array);
                            $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => array(4, 122, 124), 'hierarchical' => true, 'class'  => 'multiple-language',  'hide_empty' => 0,  'orderby'  => 'name', 'order'      => 'ASC'));
                            $usr_language = str_replace("name='cat' id=", "name='usr_language[]' multiple='multiple' id=", $usr_language);

                            if (is_array($final_array)) {
                                foreach ($final_array as $post_term) {
                                    $usr_language = str_replace(' value="' . $post_term . '"', ' value="' . $post_term . '" selected="selected"', $usr_language);
                                }
                            } else {
                                $usr_language = str_replace(' value="' . $final_array . '"', ' value="' . $final_array . '" selected="selected"', $usr_language);
                            }

                            echo $usr_language;
                        } else {
                            $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => 0, 'hierarchical' => true, 'class'  => 'multiple-language',  'hide_empty' => 0,  'orderby'  => 'name', 'order'      => 'ASC'));
                            $usr_language = str_replace("name='cat' id=", "name='usr_language[]'  multiple='multiple' id=", $usr_language);
                            echo $usr_language;
                        } ?>
                    </div>
                    <div class="form__input--lowerFeild">
                        <p class="from__para form__lowerItems">To select multiple language press CTRL + click </p>
                    </div>
                </div>

                <div class="forms">
                    <label class="from__para form__label">Salary<span class="req">*</span></label>
                    <div class="salary__forms">
                        <div class="form__input salary__width">
                            <input type="text" class="form-control2" name="sal_amount" placeholder="amount" value="<?php echo $posted['sal_amount']; ?>">
                        </div>

                        <span>PER</span>

                        <div class="form__input--select  salary__width">
                            <?php if ($posted['sal_period'] == '' || $posted['sal_period'] == null)
                                $selected_sal_period = 0;
                            else
                                $selected_sal_period = $posted['sal_period'];
                            $sal_period = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => $selected_sal_period, 'hierarchical' => true, 'class'  => 'form-control2',  'hide_empty' => 0));
                            $sal_period = str_replace("name='cat' id=", "name='sal_period' id=", $sal_period);
                            echo $sal_period; ?>
                        </div>

                        <span>OR</span>

                        <div class="form__input--select  salary__width">
                            <?php if ($posted['sa_option'] == '' || $posted['sa_option'] == null)
                                $selected_sa_option = 0;
                            else
                                $selected_sa_option = $posted['sa_option'];
                            $sa_option = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_optn', 'selected' => $selected_sa_option, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $sa_option = str_replace("name='cat' id=", "name='sa_option' id=", $sa_option);
                            echo $sa_option;  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form__items flex--items">
                <div class="forms from__width">
                    <label class="from__para form__label">Work Time<span class="req">*</span></label>
                    <div class="form__input">
                        <input type="text" class="form-control" id="work-time" name="work_time" value="<?php echo $posted['work_time']; ?>" />
                    </div>
                    <div class="form__input--lowerFeild">
                        <p class="from__para form__lowerItems">Enter a breakdown of working hours eg. 9.00am to
                            5.00pm Mon - Fri, 2.00pm to 9.00pm Wed, Thur & Fri etc. or negotiable.</p>
                    </div>
                </div>
                <div class="forms from__width">
                    <label class="from__para form__label">Hours Per Weeky<span class="req">*</span></label>
                    <div class="form__input">
                        <input type="text" class="form-control" id="hours-per-week" name="hours_per_week" value="<?php echo $posted['hours_per_week']; ?>" />
                    </div>
                    <div class="form__input--lowerFeild">
                        <p class="from__para form__lowerItems">Enter the total minimum number of hours to be worked
                            each week. Any variation can be included under "other information" (see below). Hours
                            offered should comply with current legislation.</p>
                    </div>
                </div>
            </div>
            <div class="forms">
                <div class="forms textarea">
                    <label class="from__para form__label">Job Duties</label>
                    <div class="form__input-textarea">
                        <textarea class="form-control jqte-test" rows="10" cols="30" id="rich_text" name="job_duties" placeholder="Job Duties"><?php if (isset($_POST['job_duties'])) {
                                                                                                                                                    echo $_POST['job_duties'];
                                                                                                                                                } ?></textarea>
                    </div>
                </div>
                <div class="form__input--lowerFeild">
                    <p class="from__para form__lowerItems">Enter the duties of the job. These should be clear and
                        concise. Unfamiliar abbreviations should not be used. A hyperlink or reference to a more
                        detailed job specification can be included in the "other information" field (see below).
                        Capital letters should be used at the beginning of every sentence with one space after each
                        comma, two spaces after each full stop and capitals at the beginning of person or place
                        names.
                        You may find it useful before saving your vacancy to select the text in the Job Description
                        window, copy (Ctrl C) and paste it (Ctrl V) into a blank Word Document. Spelling and
                        grammatical errors will be identified and can be corrected and copied back to appropriate
                        box (es).</p>
                </div>
            </div>

            <div class="forms">
                <div class="">
                    <div class="form__radioItems--apply">
                        <label class="from__para form__label form-radio">How to Apply :<span class="req">*</span>
                        </label>
                    </div>
                    <div class="how-to-apply">
                        <?php if ($selected_id != '') {
                            $apply_online = get_post_meta($selected_id, 'how_to_apply', true);
                        }  ?>
                        <label class="radio-inline"> <input type="radio" id="apply-online" name="how_to_apply" value="apply_online" <?php if ($apply_online == 'apply_online') {
                                                                                                                                        echo  'checked';
                                                                                                                                    } else if ($_POST['how_to_apply'] == 'apply_online') {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>> Apply through eimams </label>

                        <label class="radio-inline"> <input type="radio" id="manual-method" name="how_to_apply" value="manual_mtd" <?php if ($apply_online == 'manual_mtd') {
                                                                                                                                        echo  'checked';
                                                                                                                                    } else if ($_POST['how_to_apply'] == 'manual_mtd') {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>> Apply Manually </label>


                        <textarea class="form-control" id="manual-application-method" name="manual_apply_details" placeholder="Manual Application Method"><?php if ($selected_id != '') {
                                                                                                                                                                echo get_post_meta($selected_id, 'manual_apply_details', true);
                                                                                                                                                            } else if (isset($_POST['manual_apply_details'])) {
                                                                                                                                                                echo $_POST['manual_apply_details'];
                                                                                                                                                            } ?></textarea>

                    </div>
                </div>
                <div class="form__input--lowerFeild">
                    <span class="help-block apply-through-eimams">By clicking this option you will only receive profile information of potential employees held by eimams. </span>
                    <span class="help-block apply-online">Enter clear instructions on how to apply for the job: e.g. where application forms can be obtained and, where completed, forms are to be sent.</span>
                </div>
            </div>
            <div class="forms my-2em" id="application_form">
                <div class="form__radioItems">
                    <label class="from__para form__label form-radio">Application Form/Pack:<span class="req">*</span>
                    </label>
                </div>
                <div class="how-to-apply">
                    <input type="file" class="form-control" id="rtf-pdf" name="app_form" accept='.doc,.docx, .rtf, .pdf'>

                </div>
                <p class="help-block"> Covering Note, Person Specification, Application Form
                    (RTF and PDF format) can be uploaded here
                </p>
            </div>



            <div class="forms  my-2em">
                <label class="from__para form__label">Pension Provision:<span class="req">*</span></label>
                <div class="form__input--select ">
                    <label class="radio-inline"> <input type="radio" name="pension_provision" id="pension_provision_no" value="no" <?php if ($_POST['pension_provision'] == 'no') echo  'checked';   ?>> No </label>
                    <label class="radio-inline"> <input type="radio" name="pension_provision" id="pension_provision_yes" value="yes" <?php if ($_POST['pension_provision'] == 'yes') echo  'checked';  ?>> Yes </label>

                    <label class="radio-inline"> <input type="radio" name="pension_provision" id="pension_provision_not_applicable" value="Not Applicable" <?php if ($_POST['pension_provision'] == 'Not Applicable') echo  'checked';  ?>> Not Applicable </label>

                    <?php global  $pension_provisions_ar;
                    echo ' <select class="form-control" name="pension_provision_dropdown" id="pension_provision_dropdown">';
                    foreach ($pension_provisions_ar as $key => $value) {
                        echo '<option value="' . $key . '">' . $value . '</option>';
                    }
                    echo '</select>'; ?>
                    <input type="text" name="pension_provision_dropdown" class="form-control" id="PensionProvisionText" value="">
                    <p class="help-block">Select from Yes/No . If Yes then a further drop down will become available to you to select the appropriate pension type. </p>
                </div>
            </div>

            <div class="forms">
                <label class="from__para form__label">Monitoring / Equality:<span class="req">*</span></label>
                <div class="form__input--select ">
                    <label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_no" value="no" <?php if ($_POST['monitoring_equalty'] == 'no') {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>> No </label>
                    <label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_yes" value="yes" <?php if ($_POST['monitoring_equalty'] == 'yes') {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>> Yes </label>

                    <label class="radio-inline"><input type="radio" name="monitoring_equalty" id="monitoring_equality_not_applicable" value="Not Applicable" <?php if ($_POST['monitoring_equalty'] == 'Not Applicable') {
                                                                                                                                                                    echo 'checked';
                                                                                                                                                                } ?>> Not Applicable </label>


                    <p class="help-block"> Indicate whether a monitoring form is required. A monitoring form can be uploaded here. </p>
                    <div id="monitoring_equality_upload" style="margin-top:10px;">
                        <input type="file" class="form-control" id="monitoring-equality" name="monitoring_equality" accept='.doc,.docx, .rtf, .pdf'>
                    </div>
                </div>
            </div>

            <div class="forms ">
                <label class="from__para form__label">Equality Statement:<span class="req">*</span></label>
                <div class="form__input--select ">
                    <label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_no" value="no" <?php if ($_POST['equalty_statement'] == 'no') {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>> No </label>
                    <label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_yes" value="yes" <?php if ($_POST['equalty_statement'] == 'yes') {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?>> Yes </label>

                    <label class="radio-inline"><input type="radio" name="equalty_statement" id="equality_statement_not_applicable" value="Not Applicable" <?php if ($_POST['equalty_statement'] == 'Not Applicable') {
                                                                                                                                                                echo 'checked';
                                                                                                                                                            } ?>> Not Applicable </label>



                    <p class="help-block"> If an employer equality statement is available, it should be entered here and will appear under "other information" in the published vacancy.
                    </p>
                    <div id="equality_statement_upload" style="margin-top:10px;">
                        <input type="file" class="form-control" id="equality-statement" name="equality_statement" accept='.doc,.docx, .rtf, .pdf'>
                    </div>
                </div>
            </div>

            <div class="forms ">
                <label class="from__para form__label">Eligible to work in<span class="req">*</span></label>
                <div class="form__input--select ">
                    <?php if ($posted['eligible_work_in'] == '' || $posted['eligible_work_in'] == null)
                        $selected_usr_zone = 0;
                    else
                        $selected_usr_zone = $posted['eligible_work_in'];
                    $eligible_work_in = wp_dropdown_categories(array('show_option_none' => 'Select Country', 'echo' => 0, 'taxonomy' => 'zone', 'selected' => $selected_usr_zone, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
                    $eligible_work_in = str_replace("name='cat' id=", "name='eligible_work_in' id=", $eligible_work_in);
                    echo $eligible_work_in; ?>
                </div>
            </div>

            <div class="forms">
                <div class="form__radio">
                    <div class="form__radioItems-legal">
                        <label class="from__para form__label form-radio">Are legal disclosures required?:<span class="req">*</span>
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <label class="radio-inline">
                            <input type="radio" name="dbs" id="dbs_yes" value="yes" <?php if ($posted['dbs'] == 'yes') echo 'checked';  ?>> Yes
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <label class="radio-inline">
                            <input type="radio" name="dbs" id="dbs_no" value="no" <?php if ($posted['dbs'] == 'no') echo 'checked';  ?>> No
                        </label>
                    </div>

                    <div class="col-sm-3">
                        <label class="radio-inline">
                            <input type="radio" name="dbs" id="dbs_not_applicable" value="Not Applicable" <?php if ($posted['dbs'] == 'Not Applicable') echo 'checked';  ?>> Not Applicable
                        </label>
                    </div>
                </div>

                <br />
                <p class="help-block">It is strongly recommended that you (as employer) understand what legal check requirements is and when recruiting an individual for tuition or working with children and or vulnerable individuals, that they (employee) have legal check clearance. </p>

                <br />

                <div id="dbs_upload" style="margin-top:10px;">
                    <textarea class="form-control" id="dbs_info_box" name="dbs_info_box" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">Enter your descriptoin here</textarea>

                    <input type="file" class="form-control" id="dbs_file_upload" name="dbs_file_upload" accept='.doc,.docx, .rtf, .pdf'>
                </div>


                <div class="forms">
                    <div class="forms">
                        <div class="form__radio">
                            <div class="form__radioItems">
                                <label class="from__para form__label form-radio">Accomodation<span class="req">*</span>
                                </label>
                            </div>
                            <label class="radio-inline"> <input type="radio" name="accomodation" value="yes" <?php if ($selected_id != '') {
                                                                                                                    $accomodation = get_post_meta($selected_id, 'accomodation', true);
                                                                                                                    if ($accomodation == 'yes') echo  'checked';
                                                                                                                } else if ($_POST['accomodation'] == 'yes') echo 'checked';  ?>>Yes</label>
                            <label class="radio-inline"> <input type="radio" name="accomodation" value="no" <?php if ($selected_id != '') {
                                                                                                                $accomodation = get_post_meta($selected_id, 'accomodation', true);
                                                                                                                if ($accomodation == 'no') echo  'checked';
                                                                                                            } else if ($_POST['accomodation'] == 'no') echo 'checked';  ?>>No</label>

                            <br /><br />


                        </div>
                        <textarea class="form-control" name="accomodation-details" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">If accommodation is provided or assistance given with the sourcing of accommodation, this should be entered here.</textarea>
                    </div>
                </div>

                <div class="forms">
                    <label class="from__para form__label form-radio">Other Information<span class="req">*</span>
                    </label>
                    <div class="forms textarea">
                        <div class="form__input-textarea">
                            <textarea class="form-control wysiwyg-editor" id="other-information" name="other_information" placeholder="Other Information"> <?php echo $posted['other_information']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label">Upload your logo <span class="req">*</span></label>
                        <div class="form__input">
                            <input type="file" class="form-control" id="upload-logo" name="company_logo" value="<?php echo $_FILE['company_logo']; ?>">
                        </div>
                    </div>
                    <div class="forms from__width">
                        <label class="from__para form__label">How did you hear about us:<span class="req">*</span></label>
                        <div class="form__input--select ">
                            <?php if ($posted['marketing_area'] == '' || $posted['marketing_area'] == null)
                                $selected_sa_option = 0;
                            else
                                $selected_sa_option = $posted['marketing_area'];
                            $sa_option = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'marketing_area', 'selected' => $selected_sa_option, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
                            $sa_option = str_replace("name='cat' id=", "name='marketing_area' id=", $sa_option);
                            echo $sa_option;  ?>
                        </div>
                    </div>


                </div>
            </div>
            <div class="form__contents">
                <h2 class="section__subheading form__subheading">ONGOING CONTRACT</h2>
                <div class="form__input--lowerFeild">
                    <p class="from__para form__lowerItems">Ongoing contact will be maintained with you throughout the
                        life of the vacancy. When the vacancy closes you will be asked to provide feedback information
                        on the applications who have applied through a Jobs & Benefits Office / Jobcentre or directly
                        via Jobcentreonline. You should therefore maintain records of the applications received and the
                        outcomes..</p>
                </div>
            </div>
            <div class="form__button">
                <div class="button__Items">
                    <button name="submit_a_job" type="submit">Submit</button>
                </div>
                <div class="button__Items">
                    <a href="#" class="primary__button">reset</a>
                </div>
            </div>
        </form>
    </div>
</section>