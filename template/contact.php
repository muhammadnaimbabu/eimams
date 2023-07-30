<?php

/**
 * Template Name:  Contact Us
 */

get_header();

?>

<script type="text/javascript">
  $(function() {

    // reset form
    $.fn.resetForm = function() {
      return this.each(function() {
        this.reset();
      });
    }

    $('.clear_form').click(function() {

      $("form").resetForm();

    });

  }); // end
</script>



<div class="container">
  <?php candidat_the_breadcrumbs(); ?>

  <div class="">
    <img class="aligncenter size-full wp-image-752" src="http://eimams.com/wp-content/uploads/2015/08/banner-ad.jpg" alt="banner728x90" width="728" height="90" />
    <h1 style="margin: 2em 0;" class="section__heading">Contact Us</h1>
  </div>

  <form id="contact-form" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form__items contact-us-gap">
      <div class="forms from__width">
        <label class="from__para form__label" for="Name">Name:</label>
        <div class="form__input">
          <input type="text" name="name" id="Name" placeholder="Name">
        </div>
      </div>

      <div class="forms from__width">
        <label class="control-label col-xs-3" for="inputEmail">Email:</label>
        <div class="form__input">
          <input type="email" name="email" id="inputEmail" placeholder="Email">
        </div>
      </div>

      <div class="forms from__width">
        <label class="from__para form__label" for="phoneNumber">Phone:</label>
        <div class="form__input">
          <input type="tel" name="phone" id="phoneNumber" placeholder="Phone Number">
        </div>
      </div>
    </div>

    <div class="forms ">
      <label class="from__para form__label" for="message">Message:</label>
      <div class="form__input-textarea">
        <textarea name="message" rows="10" cols="30" id="message" placeholder="Message"></textarea>
      </div>
    </div>

    <div class="">
      <input type="submit" class="primary-btn" value="Submit">
      <input type="reset" class="secondary-btn clear_form" value="Reset">
    </div>


  </form>


  <div class="col-xs-12 col-sm-offset-3 col-sm-8">
    <p style="font-size:22px; color:#162C53; margin-top:15px; margin-left:5px;">
      <span class="fa fa-envelope"> </span> &nbsp; e-mail: info@eimams.com
    </p>
  </div>



  <div class="col-xs-offset-1 col-xs-11 col-sm-offset-3 col-sm-8">
    <p style="font-size:22px; color:#162C53; margin-top:15px; margin-left:5px;"> <span class="fa fa-mobile fa-2x" style="display:block;float:left;margin-top:-13px;"> &nbsp;</span> +44 75 0765 3582 &nbsp; &nbsp; &nbsp; </p>
  </div>

  <div id="response"></div>
</div>


<script>
  // Handle the form and send data trow ajax
  jQuery("#contact-form").submit(function() {
    event.preventDefault();
    var link = "<?php echo admin_url('admin-ajax.php') ?>";
    var contactform = jQuery("#contact-form").serialize();
    var formData = new FormData();
    formData.append('action', 'wpse_sendmail');
    formData.append('wpse_sendmail', contactform);
    jQuery.ajax({
      url: link,
      data: formData,
      processData: false,
      contentType: false,
      type: "post",
      success: function(result) {
        jQuery("#response").html(result);
        // jQuery("#contact-form").resetForm();
        // console.log(result);
      }
    });

  });
</script>

<?php get_footer();  ?>