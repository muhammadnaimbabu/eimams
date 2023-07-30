<div class="col-md-12" style="padding:10px;">
    <?php $Notifications =  get_latest_notifications_for_current_user($current_user->ID, $kv_current_role);
    //var_dump($Notifications.$kv_current_role);
    if (!empty($Notifications)) {
        echo '<ul class="success">';
        foreach ($Notifications as $notify)
            echo '<div class="example1"><li style="margin-left:18px;">

				' . $notify['Message'] . '

				</li> <div>';
    }
    echo '</ul>';


    ?>

</div>

<?php

if (isset($_GET['verified_email']) && $_GET['verified_email'] == 'yes')
    echo '<div class="alert-success alert" > Congrats, Your email Verified Successfully ! </div> ';
?>

<div class="row  g-5 g-xl-10">
    <div class="col-xl-4 col-md-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-body p-0">
                <div class="px-9 pt-7 card-rounded h-275px w-100 bg-primary">
                    <div class="d-flex flex-stack">
                        <h3 class="m-0 text-white fw-bold fs-3">Hi, <?php $current_user = wp_get_current_user();
                                                                    echo $current_user->display_name;
                                                                    ?>
                    </div>
                    <div class="d-flex text-center flex-column text-white pt-8">
                        <span class="fw-semibold fs-7">Total Job posted on eimams</span>
                        <span class="fw-bold fs-2x pt-1"><?php echo  kv_get_total_jobs_counts(); ?></span>
                    </div>
                </div>
                <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1" style="margin-top: -100px">
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-45px w-40px me-5">
                            <span class="symbol-label bg-lighten">
                                <i class="ki-duotone ki-tablet-text-down fs-1"><span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap w-100">
                            <div class="mb-1 pe-3 flex-grow-1">
                                <a href="<?php echo site_url('posted-jobs') ?>" class="fs-5 text-gray-800 text-hover-primary fw-bold">Total Jobs
                                </a>
                                <div class="text-gray-400 fw-semibold fs-7">Total published jobs
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold fs-5 text-gray-800 pe-1"><?php
                                                                                $summa = wp_count_posts('job', 'publish');
                                                                                echo $summa->publish;
                                                                                ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-45px w-40px me-5">
                            <span class="symbol-label bg-lighten">
                                <i class="ki-duotone ki-check-square fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap w-100">
                            <div class="mb-1 pe-3 flex-grow-1">
                                <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">Notifications</a>
                                <div class="text-gray-400 fw-semibold fs-7">Total New Notifications
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold fs-5 text-gray-800 pe-1"><?php echo get_current_help_and_support_count(); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-45px w-40px me-5">
                            <span class="symbol-label bg-lighten">
                                <i class="ki-duotone ki-people fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap w-100">
                            <div class="mb-1 pe-3 flex-grow-1">
                                <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">Available Jobs</a>
                                <div class="text-gray-400 fw-semibold fs-7">Jobs that made for you
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold fs-5 text-gray-800 pe-1"> <?php
                                                                                $query = get_available_job_and_count();
                                                                                $count = $query->post_count;
                                                                                if ($count != 0) $count;
                                                                                else $count = 'NO JOBS';
                                                                                echo $count;
                                                                                ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ">
                        <div class="symbol symbol-45px w-40px me-5">
                            <span class="symbol-label bg-lighten">
                                <i class="ki-duotone ki-document fs-1"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap w-100">
                            <div class="mb-1 pe-3 flex-grow-1">
                                <a href="<?php echo site_url('applied-resumes') ?>" class="fs-5 text-gray-800 text-hover-primary fw-bold">Applied Jobs</a>
                                <div class="text-gray-400 fw-semibold fs-7">You have applied for
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="fw-bold fs-5 text-gray-800 pe-1">
                                    <?php echo kv_get_applied_jobs_counts($current_user->ID); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8  mb-5 mb-xl-10">
        <div class="row g-5 g-xl-10">
            <div class="col-xl-6 mb-xl-10">
                <div id="kt_sliders_widget_1_slider" class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="card-header pt-5">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Featured
                                Job</span>
                            <span class="text-gray-400 mt-1 fw-bold fs-7">2.5k People
                                applied for this</span>
                        </h4>
                        <div class="card-toolbar">
                            <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                                <li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="0" class="active ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="1" class=" ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_1_slider" data-bs-slide-to="2" class=" ms-1"></li>
                            </ol>
                        </div>
                    </div>
                    <div class="card-body py-6">
                        <div class="carousel-inner mt-n5">
                            <?php
                            $i = 0;
                            // 0 = Not Featured , 1 = Featured 'featured_type' => 1,
                            $args = array(
                                'post_type' => 'job',
                                'meta_key' => 'featured_status',
                                'meta_value' => 'yes',
                            );
                            // Execute query
                            $featured_query = new WP_Query($args);

                            // Create cpt loop, with a have_posts() check!
                            if ($featured_query->have_posts()) :
                                while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                                    <!-- Insert featured job here -->
                                    <!-- <div class="carousel-item show"> -->
                                    <div class="carousel-item show <?php if ($i == 0) {
                                                                        $i = 1;
                                                                        echo 'active';
                                                                    } ?>">
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="w-80px flex-shrink-0 me-2 ">
                                                <?php if (has_post_thumbnail()) {
                                                    echo get_the_post_thumbnail(get_the_ID(), array('class' => ' rounded-circle border border-primary ', 80, 80));
                                                } else {
                                                    echo "<img  class='rounded-circle' src='" . get_template_directory_uri() . "/images/default-dp-eimams.png' style='width: 80px;'/>";
                                                }     ?>
                                            </div>
                                            <div class="m-0">
                                                <h4 class="fw-bold text-gray-800 mb-3" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;"> <?php the_title(); ?></h4>
                                                <div class="d-flex d-grid gap-5">
                                                    <span style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                        <?php echo the_excerpt(); ?> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-0">
                                            <a href="<?php echo the_guid() ?>" class="btn btn-sm btn-light me-2 mb-2">Details</a>
                                            <a href="<?php echo the_guid() ?>" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Apply</a>
                                        </div>
                                    </div>
                            <?php endwhile;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div id="kt_sliders_widget_2_slider" class="card card-flush carousel carousel-custom carousel-stretch slide h-xl-100" data-bs-ride="carousel" data-bs-interval="5500">
                    <div class="card-header pt-5">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Featured
                                Employer</span>
                            <span class="text-gray-400 mt-1 fw-bold fs-7">24 Job
                                published from this employer</span>
                        </h4>
                        <div class="card-toolbar">
                            <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-success">
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="0" class="active ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="1" class=" ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="2" class=" ms-1"></li>
                            </ol>
                        </div>
                    </div>
                    <div class="card-body py-6">
                        <div class="carousel-inner mt-n5">
                            <?php
                            $i = 0;
                            // 0 = Not Featured , 1 = Featured 'featured_type' => 1,
                            $args = array(
                                'author' => 'employer',
                                'meta_key' => 'featured_status',
                                'meta_value' => 'yes',
                            );
                            // Execute query
                            $featured_users = get_users($args);

                            // Create cpt loop, with a have_posts() check!
                            foreach ($featured_users as $user) { ?>
                                <!-- Insert featured job here -->
                                <!-- <div class="carousel-item show"> -->
                                <div class="carousel-item show <?php if ($i == 0) {
                                                                    $i = 1;
                                                                    echo 'active';
                                                                } ?>">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="w-80px flex-shrink-0 me-2">
                                            <?php
                                            $get_user_image = get_user_meta($user->ID, 'user_image', true);
                                            $get_user_image_id = get_user_meta($user->ID, 'company_logo_attachment_id', true);

                                            $user_status = '';
                                            if (kv_get_user_status() == 0) {
                                                $user_status = 'success';
                                                $user_opacity = 1;
                                            } else {
                                                $user_status = 'warning';
                                                $user_opacity = 0.1;
                                            }

                                            $url = wp_get_attachment_image_src($get_user_image_id);
                                            // echo '<img src="' .get_template_directory_uri() . "/images/default-dp-eimams.png" class="user-image rounded-circle border-success img-responsive" width="80px" style="opacity:' . $user_opacity . ';" />';
                                            echo "<img src=" . get_template_directory_uri() . "/images/default-dp-eimams.png' class='user-image rounded-circle border-success img-responsive' width='80px' style='opacity:' . $user_opacity . ';'/>";


                                            //echo '<img src="'.get_template_directory_uri().'/assets/img/find_user.png" class="user-image img-responsive"  style="opacity:'.$user_opacity.';"/>';

                                            ?>
                                        </div>
                                        <div class="m-0">
                                            <h4 class="fw-bold text-gray-800 mb-3" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;"> <?php echo esc_html($user->display_name) ?></h4>
                                            <div class="d-flex d-grid gap-5">
                                                <span style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                    <?php echo esc_html($user->user_email) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-0">
                                        <a href="<?php echo esc_html($user->user_url) ?>" target="_blank" class="btn btn-sm btn-success mb-2">Website</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-transparent " data-bs-theme="light" style="background-color: #1C325E;">
            <div class="card-body d-flex ps-xl-15">
                <div class="m-0">
                    <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7">
                        <span class="me-2">
                            You have got
                            <span class="position-relative d-inline-block text-danger">
                                <a href="../pages/user-profile/overview.html" class="text-danger opacity-75-hover">2300 bonus</a>
                                <span class="position-absolute opacity-50 bottom-0 start-0 border-4 border-danger border-bottom w-100"></span>
                            </span>
                        </span>
                        points.<br />
                        Feel free to use them in your lessons
                    </div>
                    <div class="mb-3">
                        <a href='#' class="btn btn-danger fw-semibold me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">
                            Get Reward
                        </a>
                        <a href="../apps/support-center/overview.html" class="btn btn-color-white bg-white bg-opacity-15 bg-hover-opacity-25 fw-semibold">
                            How to
                        </a>
                    </div>
                </div>
                <img src="../assets/media/illustrations/sigma-1/17-dark.png" class="position-absolute me-3 bottom-0 end-0 h-200px" alt="" />
            </div>
        </div>
    </div>
</div>
<div class="row g-5 g-xl-10">
    <?php
    $i = 0;
    // 0 = Not Featured , 1 = Featured 'featured_type' => 1,
    $args = array(
        'post_type' => 'job',
        'orderby' => 'post_date',
        'posts_per_page' => 4,
    );
    // Execute query
    $featured_query = new WP_Query($args);

    // Create cpt loop, with a have_posts() check!
    if ($featured_query->have_posts()) :
        while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
            <!-- Job section start -->
            <div class="col-xl-3">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-body d-flex flex-column pb-10 pb-lg-15">
                        <div class="flex-grow-1 mb-10">
                            <div class="d-flex align-items-center justify-content-between pe-2 mb-5">
                                <div class="symbol symbol-50px">
                                    <span class="symbol-label bg-light">
                                        <?php if (has_post_thumbnail()) {
                                            echo get_the_post_thumbnail(get_the_ID(), array('class' => ' align-self-center ', "50px", "50px"));
                                        } else {
                                            echo "<img class='align-self-center' src='" . get_template_directory_uri() . "/images/default-dp-eimams.png' width='50px'/>";
                                        }     ?>

                                    </span>
                                </div>
                                <span class="badge badge-lg badge-success">New</span>
                            </div>
                            <a target="_blank" href="<?php echo the_guid() ?>" class="text-dark fw-bold text-hover-primary fs-4">
                                <?php echo the_title() ?> </a>
                            <p class="pb-4">Agency - <?php echo get_the_author() ?></p>
                            <div class="d-flex gap-5">
                                <span class="border fw-bold  border-danger text-danger px-6 py-2 rounded ">EXP: <?php echo get_post_meta(get_the_ID(), "ad_close_date", true) ?></span>
                            </div>
                        </div>
                        <div class="m-0">
                            <a target="_blank" href="<?php echo the_guid() ?>" class="btn btn-sm btn-light me-2 mb-2">Details</a>
                            <a target="_blank" href="<?php echo the_guid() ?>" class="btn btn-sm btn-success mb-2">Apply</a>
                        </div>
                    </div>
                </div>
            </div>

    <?php endwhile;
    endif; ?>

</div>