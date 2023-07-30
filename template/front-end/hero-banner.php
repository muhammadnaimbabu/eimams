<section <?php $imagePath = get_template_directory_uri() . "/img/BG.png";
            echo 'style="background-image: url(\'' . $imagePath . '\')"' ?> class="hero-section">
    <div class="container">
        <div class="hero-section-content">
            <div class="hero-content">
                <h1 class="hero-h1">Find Your Jobs With <span class="eimams-brand">Eimams</span></h1>
                <span class="hero-info">For thousands of jobs in different Islamic sectors visit
                    <strong>www.eimams.com</strong>
                    ,where you will find
                    employers advertising new openings locally and nationally.</span>
                <div class="hero-buttons">
                    <a href="<?php echo site_url('employer-sign-up') ?>" class="primary-btn employer-btn">
                        <i class="feather-16" data-feather="briefcase"></i>
                        <span>Employers Signup</span>
                    </a>

                    <a href="<?php echo site_url("jobseeker-sign-up") ?>" class="secondary-btn jobseeker-btn">
                        <i class="feather-16" data-feather="user"></i>
                        <span>Jobseekers Signup</span>
                    </a>
                </div>
            </div>
            <div class="hero-img">
                <img src="<?php echo get_template_directory_uri() ?>/img/Image.png" width="100%" alt="">
            </div>
        </div>
    </div>
</section>