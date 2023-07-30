<div class="container">

    <hr />
    <?php
    if (isset($errors) && $errors->get_error_code()) :
        echo '<ul class="error text-center">';
        foreach ($errors->errors as $error) {
            echo '<li>' . $error[0] . '</li>';
        }
        echo '</ul>';
    endif;     ?>
    <!-- ##############################   Form start here ################################################################################# -->

    <h2 class="section__heading form__heading">Jobseeker Registration</h2>
    <div class="col-md-6"><a target="_blank" href="<?php echo get_template_directory_uri() ?>/images/jobseeker-pdf-sunni.pdf"><button class="btn btn-default pull-right"> Download a PDF version of this form </button></a></div>

    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="form__contents personal__Item">
            <h2 class="section__subheading form__subheading">PERSONAL DETAILS</h2>
            <form class="form-horizontal" enctype='multipart/form-data' name="submit_a_new_job" method="post" action="<?php echo "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
                <div class="form-group">
                    <label class="from__para form__label" for="Name">Name:<span class="req">*</span> </label>
                    <div class="form__input">
                        <input type="text" id="Name" placeholder="Name" name="jobseeker_name" value="<?php echo $posted['jobseeker_name']; ?>">
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Address1: <span class="req">*</span> </label>
                        <div class="form__input">
                            <input type="text" id="Name" name="address1" placeholder="Address1" value="<?php echo $posted['address1']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Address2: </label>
                        <div class="form__input">
                            <input type="text" id="Name" name="address2" placeholder="Address2" value="<?php echo $posted['address2']; ?>">
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="city">City: </label>
                        <div class="form__input">
                            <input type="text" id="city" name="city" value="<?php echo $posted['city']; ?>" placeholder="Address">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label" for="state_pro_reg">State/Province/Region: </label>
                        <div class="form__input">
                            <input type="text" id="state_pro_reg" name="state_pro_reg" value="<?php echo $posted['state_pro_reg']; ?>" placeholder="Address">
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Zip/Post Code:</label>
                        <div class="form__input">
                            <input type="text" id="Name" name="post_code" placeholder="Post Code" value="<?php echo $posted['post_code']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Country:<span class="req">*</span> </label>
                        <div class="form__input">
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
                        <label class="from__para form__label" for="phoneNumber">Phone:<span class="req">*</span> </label>
                        <div class="form__input">
                            <input type="tel" id="phoneNumber" name="phone_number" placeholder="Phone Number" value="<?php echo $posted['phone_number']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label for="inputEmail" class="from__para form__label">Username:<span class="req">*</span> </label>
                        <div class="form__input">
                            <input type="text" name="UserName" placeholder="Username" value="<?php echo $posted['UserName']; ?>">
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label for="inputEmail" class="from__para form__label">Email:<span class="req">*</span> </label>
                        <div class="form__input">
                            <input type="email" id="inputEmail" name="usr_email" placeholder="Email" value="<?php echo $posted['usr_email']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label for="inputPassword" class="from__para form__label">Password:<span class="req">*</span> </label>
                        <div class="form__input">
                            <input type="password" id="inputPassword" name="PassWord" placeholder="Password" value="<?php echo $posted['PassWord']; ?>">
                        </div>
                    </div>
                </div>
                <br>

                <h2>Registration Form </h2>


                <style type="text/css">
                    #job-classifications optgroup[label] {
                        color: #3c763d;
                        font-size: 25px;
                    }

                    #job-classifications optgroup[label] * {
                        color: #333;
                        font-size: 15px;
                    }
                </style>


                <div class="form__items flex--items">
                    <div id="job-classifications" class="forms from__width">
                        <label class="from__para form__label" for="Name">Job Classificatioins:<span class="req">*</span> </label>
                        <div class="form__input">
                            <?php
                            if (isset($posted['usr_job_category']) &&  $posted['usr_job_category'] != -1) {
                                $selected_cat = $usr_job_category = $posted['usr_job_category'];
                                $usr_gen_job_category = -1;
                            } elseif (isset($posted['usr_gen_job_category']) &&  $posted['usr_gen_job_category'] != -1) {
                                $selected_cat = $usr_gen_job_category = $posted['usr_gen_job_category'];
                                $usr_job_category = -1;
                            } else {
                                $selected_cat = $usr_job_category = $usr_gen_job_category = -1;
                            }

                            echo kv_merged_taxonomy_dropdown('job_category', 'gen_job_category', $selected_cat);
                            echo '<input type="hidden" name="usr_job_category" value="-1" id="usr_job_category" >
					  <input type="hidden" name="usr_gen_job_category" value="-1" id="usr_gen_job_category" > ';
                            ?>
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label">Gender:<span class="req">*</span> </label>
                        <div class="col-sm-2">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="male" <?php if ($posted['gender'] == 'male') echo 'checked';  ?>> Male
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="female" <?php if ($posted['gender'] == 'female') echo 'checked';  ?>> Female
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Qualification:<span class="req">*</span> </label>
                        <div class="form__input">
                            <?php if ($posted['usr_qualification'] == '' || $posted['usr_qualification'] == null)
                                $selected_qul = 0;
                            else
                                $selected_qul = $posted['usr_qualification'];
                            $usr_qualification = wp_dropdown_categories(array('show_option_none' => 'Select category', 'echo' => 0, 'taxonomy' => 'qualification', 'id' => 'job_qualification',  'selected' => $selected_qul, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $usr_qualification = str_replace("name='cat' id=", "name='usr_qualification' id=", $usr_qualification);
                            echo $usr_qualification; ?>
                            <input type="text" id="custom_qualification" name="custom_qualification" value="<?php echo $posted['custom_qualification']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Type:<span class="req">*</span> </label>
                        <div class="form__input">
                            <?php if ($posted['job_types'] == '' || $posted['job_types'] == null)
                                $selected_type = 0;
                            else
                                $selected_type = $posted['job_types'];
                            $job_types = wp_dropdown_categories(array('show_option_none' => 'Select Type', 'echo' => 0, 'taxonomy' => 'types', 'selected' => $selected_type, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                            $job_types = str_replace("name='cat' id=", "name='job_types' id=", $job_types);
                            echo $job_types; ?>
                        </div>
                    </div>
                </div>



                <div class="forms from__width">
                    <label class="from__para form__label" for="Name">Years of Exprerience:<span class="req">*</span> </label>
                    <div class="form__input">
                        <?php if ($posted['usr_yr_of_exp'] == '' || $posted['usr_yr_of_exp'] == null)
                            $selected_yr_exp = 0;
                        else
                            $selected_yr_exp = $posted['usr_yr_of_exp'];
                        $usr_yr_of_exp = wp_dropdown_categories(array('show_option_none' => 'Select Years of Exprerience', 'echo' => 0, 'taxonomy' => 'yr_of_exp', 'selected' => $selected_yr_exp, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                        $usr_yr_of_exp = str_replace("name='cat' id=", "name='usr_yr_of_exp' id=", $usr_yr_of_exp);
                        echo $usr_yr_of_exp; ?>
                    </div>
                </div>
                <hr class="showhideJobCategory">
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
                <h2 class="showhideJobCategory "> Denomination : <label><input type="radio" value="sunni" class="denominationSelection shia_selection" name="denominationSelection" checked="checked">Sunni </label> <label class="shia_selection"> <input name="denominationSelection" type="radio" value="shia" class="denominationSelection"> Shia </label> </h2>
                <div class="SunniDenomination">
                    <div class="form__items flex--items">
                        <div class="forms from__width showhideJobCategory">
                            <label class="from__para form__label" for="Name">Madhab/School of Law:<span class="req">*</span> </label>
                            <div class="form__input">
                                <?php if ($posted['usr_madhab'] == '' || $posted['usr_madhab'] == null)
                                    $selected_usr_madhab = 0;
                                else
                                    $selected_usr_madhab = $posted['usr_madhab'];
                                $usr_madhab = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'madhab', 'selected' => $selected_usr_madhab, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0));
                                $usr_madhab = str_replace("name='cat' id=", "name='usr_madhab' id=", $usr_madhab);
                                echo $usr_madhab; ?>
                            </div>
                        </div>

                        <div class="forms from__width showhideJobCategory">
                            <label class="from__para form__label" for="Name">Aqeeda/Belief<span class="req">*</span> </label>
                            <div class="form__input">
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
                </div>

                <div class="ShiaDenomination">

                    <div class="form__items flex--items">
                        <div class="forms from__width showhideJobCategory">
                            <label class="from__para form__label" for="Name">Madhab/School of Law:<span class="req">*</span> </label>
                            <div class="form__input">
                                <?php if ($posted['usr_madhab_shia'] == '' || $posted['usr_madhab_shia'] == null)
                                    $selected_usr_madhab_shia = 0;
                                else
                                    $selected_usr_madhab_shia = $posted['usr_madhab_shia'];
                                $usr_madhab_shia = wp_dropdown_categories(array('show_option_none' => 'Select Madhab', 'echo' => 0, 'taxonomy' => 'Shiamadhab', 'selected' => $selected_usr_madhab_shia, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby' => 'NAME', 'order' => 'ASC',));
                                $usr_madhab_shia = str_replace("name='cat' id='cat'", "name='usr_madhab_shia' ", $usr_madhab_shia);
                                echo $usr_madhab_shia; ?>
                            </div>
                        </div>

                        <div class="forms from__width showhideJobCategory">
                            <label class="from__para form__label" for="Name">Aqeeda/Belief<span class="req">*</span> </label>
                            <div class="form__input">
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
                </div>

                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Language:<span class="req">*</span> </label>
                        <div class="form__input">
                            <?php if (isset($_POST['usr_language']) && $_POST['usr_language'] != '' || $_POST['usr_language'] != null) {

                                if (isset($_POST['usr_language'])) {
                                    $final_array = $_POST['usr_language'];
                                } else
                                    $final_array = 4;

                                $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => array(4, 122, 124), 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0,  'orderby' => 'name', 'order'      => 'ASC'));
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
                                $usr_language = wp_dropdown_categories(array('show_option_none' => 'Select Language', 'echo' => 0, 'taxonomy' => 'languages', 'selected' => 0, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0,  'orderby' => 'name', 'order'      => 'ASC'));
                                $usr_language = str_replace("name='cat' id=", "name='usr_language[]'  multiple='multiple' id=", $usr_language);
                                echo $usr_language;
                            } ?>
                        </div>
                    </div>




                    <div class="forms from__width">

                        <label class="from__para form__label" for="Name">Salary:</label>
                        <div class="form__input">
                            <div style="float:left;width:27%;">
                                <input type="text" style="width:90px;" class="form-control2" name="sal_amount" placeholder="amount" value="<?php echo $posted['sal_amount']; ?>">&nbsp;&nbsp;per
                            </div>

                            <div style="float:left;width:35%;">
                                <?php if ($posted['sal_period'] == '' || $posted['sal_period'] == null)
                                    $selected_sal_period = 0;
                                else
                                    $selected_sal_period = $posted['sal_period'];
                                $sal_period = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'sal_prd', 'selected' => $selected_sal_period, 'hierarchical' => true, 'class'  => 'form-control2',  'hide_empty' => 0));
                                $sal_period = str_replace("name='cat' id=", "name='sal_period' id=", $sal_period);
                                echo $sal_period; ?> </div>

                            <span style="display:inline-block;">&nbsp;&nbsp;OR&nbsp;&nbsp;</span>

                            <div style="float:left;width:30%;">

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

                <div class="forms from__width">
                    <label class="from__para form__label">Do you have any valid legal disclosures?<span class="req">*</span></label>
                    <div class="form__input">
                        <div class="col-sm-2">
                            <label class="radio-inline">
                                <input type="radio" name="dbs" id="dbs_yes" value="yes" <?php if ($posted['dbs'] == 'yes') echo 'checked';  ?>> Yes
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <label class="radio-inline">
                                <input type="radio" name="dbs" id="dbs_no" value="no" <?php if ($posted['dbs'] == 'no') echo 'checked';  ?>> No
                            </label>
                        </div>
                        <br />

                        <div id="dbs_upload" style="margin-top:10px;">
                            <textarea id="dbs_info_box" name="dbs_info_box" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;">Enter your descriptoin here</textarea>
                            <input type="file" id="dbs_file_upload" name="dbs_file_upload" accept='.doc,.docx, .rtf, .pdf'>
                        </div>
                    </div>
                    <p class="help-block" style="margin-top:15px;">i.e. DBS, Police Check or any other legal checks </p>

                </div>


                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Upload a CV:<!--<span class="req">*</span>--> </label>
                        <div class="form__input">
                            <input type="file" id="upload-cv" name="upload_cv" accept=".pdf, .doc, .docx, .rtf" value="<?php echo $_FILE['upload_cv']; ?>">
                        </div>
                    </div>

                    <div class="forms from__width">
                        <label class="from__para form__label" for="Name">Upload your photo : </label>
                        <div class="form__input">
                            <input type="file" name="profile_photo" accept="image/*" id="profile_photo">
                        </div>
                    </div>
                </div>


                <div class="form__items flex--items">
                    <div class="forms from__width">
                        <label class="from__para form__label" for="minimum-age">Job Alerts: </label>
                        <div style="display: flex; flex-direction:column">
                            <label><input type="checkbox" id="job-alert-wage" name="job_alert" value="1" checked> Email me, when new jobs posted and which matches my profile</label>
                            <label><input type="checkbox" id="job-alert-wage" name="common_alert" value="1" checked> Common Newsletters and Special Offers</label>
                        </div>
                    </div>


                    <div class="forms from__width">
                        <label class="from__para form__label" for="minimum-age">How did you hear about us: <span class="req">*</span> </label>
                        <div class="form__input">
                            <?php
                            if ($posted['marketing_area'] == '' || $posted['marketing_area'] == null)
                                $selected_sa_option = 0;
                            else
                                $selected_sa_option = $posted['marketing_area'];
                            $sa_option = wp_dropdown_categories(array('show_option_none' => 'Select An Option', 'echo' => 0, 'taxonomy' => 'marketing_area', 'selected' => $selected_sa_option, 'hierarchical' => true, 'class'  => 'form-control',  'hide_empty' => 0, 'orderby'            => 'name', 'order'      => 'ASC'));
                            $sa_option = str_replace("name='cat' id=", "name='marketing_area' id=", $sa_option);
                            echo $sa_option;
                            ?>
                        </div>
                    </div>
                </div>


                <div class="forms">
                    <div class="checkbox">
                        <label><input style="margin-left:5px;" type="checkbox" name="term_check" value="checked" <?php if (isset($posted['term_check']) && $posted['term_check']  == 'checked') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?>>&nbsp;&nbsp; Terms and conditions of registering <span class="req">*</span></label>



                        <div style="font-size:11px; text-align:justify; background:#eee; border-radius:5px; padding:10px;margin-bottom:20px;"> By choosing to continue you will become a registered user and be able to sign into eimams.com site. We will use your contact information to send you relevant jobs that match your criteria, as well as contact you about other relevant goods and services available from eimams.com site. By continuing you are giving consent for us and our partner organisations to store cookies on your device to personalise your experience.
                        </div>
                    </div>
                </div>

                <?php if ($enable_subscription == 'yes') { ?>
                    <div class="forms from__width">
                        <h1> Subscription </h1>
                        <?php


                        $myrow = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "jbs_subpack WHERE role='job_seeker' AND status='Active' ");
                        echo '<div class="forms from__width">
					<label class="from__para form__label" for="Name">Select Your Desired Pack <span class="req">*</span></label>
					<div class="form__input">
					<select  id="select_pack"  name="pack_name">';
                        echo '<option id="pack_name" value="0">Select pack</option>';
                        foreach ($myrow as $pack) {
                            echo '<option value="' . $pack->id . '" ' . (($posted['pack_name'] == $pack->id) ? 'selected' : '') . '>' . $pack->pack_name . ' ( &pound' . $pack->price . ' : ' . $pack->duration . ' ' . $pack->period;

                            if ($pack->left_count < 0)
                                $left_count = ': ' . abs($pack->left_count) . ' Subscriptions Available';
                            else
                                $left_count = '';

                            echo $left_count . ')</option>';
                        }
                        echo '</select></div></div><br><br>';
                        ?>
                    </div> <?php } ?>

                <div class="">
                    <input type="submit" class="btn btn-primary" name="submit_a_job" value="Submit">
                    <input type="reset" class="btn btn-default" value="Reset Form">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Form Elements -->
</div>

</div>
<style>
    .SunniDenomination,
    .ShiaDenomination {
        display: none;
    }
</style>