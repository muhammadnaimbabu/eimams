<div class="job__banner" <?php $imagePath = get_template_directory_uri() . "/img/jobBannerbg.png";
                            echo 'style="background-image: url(\'' . $imagePath . '\')"' ?>>
    <div class="container">
        <div class="job__banner__contents">
            <h2 class="job__banner_header"><?php $query = new WP_Query($args);

                                            echo "We found <span style='color:#e6b706;'>" . $query->found_posts . "</span> available job(s) for you ";
                                            ?></h2>
            <p class="job__banner_paragraph job__banner_paragraph--width ">For thousands of jobs in different Islamic sectors visit <a href="#" class="job__banner_link">www.eimams.com</a>, where you will find employers.</p>
        </div>
    </div>
</div>