<form method="post" action="" id="newsletter-footer">
	<section class="subscribe">
		<div class="container">
			<div class="subscribe__contents">
				<div class="subscribe__Items">
					<div class="subcribe__header">
						<img src="<?php echo get_template_directory_uri(); ?>/img/Logo.svg" alt="eimams">
					</div>
					<p>Join our newsletter and receive the best job openings of the week, right on your inbox.
					</p>
				</div>
				<div class="subscribe__form">
					<input type="text" placeholder="Subscribe to our newsletter" class="subscribe__link">
					<button type="submit" class="subscribe__button">Subscribe</button>
				</div>
			</div>
		</div>
	</section>
</form>




<footer <?php $imagePath = get_template_directory_uri() . "/img/footerbg.png";
		echo 'style="background-image: url(\'' . $imagePath . '\')"' ?>>
	<div class="container">
		<div class="footer__contents">
			<div class="footer__items">
				<h2 class="footer__header">About Us</h2>
				<p class="footer__about footer__text--font">We have job vacancies from all sectors including
					both,
					Muslim and mainstream organizations.</p>
			</div>
			<div class="footer__items">
				<h2 class="footer__header">Quick Link</h2>
				<ul>
					<li><a href="#" class="footer__text--font">Home</a></li>
					<li><a href="#" class="footer__text--font">Career Advice</a></li>
					<li><a href="#" class="footer__text--font">Consultancy</a></li>
					<li><a href="#" class="footer__text--font">Create Account</a></li>
				</ul>
			</div>
			<div class="footer__items">
				<h2 class="footer__header">Information</h2>
				<ul>
					<li>
						<a href="#" class="footer__text--font">
							<i data-feather="phone"></i>
							075 0765 3582
						</a>
					</li>
					<li><a href="mailto:" class="footer__text--font">
							<i data-feather="mail"></i>
							info@eimams.com</a></li>
					<li><a href="#" class="footer__text--font">
							<i data-feather="map-pin"></i>
							55 Station Road, Swindon, SW1 4BG, UK</a></li>
			</div>
			<div class="footer__items">
				<h2 class="footer__header">Connect</h2>
				<div class="footer__iconItems">
					<div class="footer__icon">
						<i class="feather-16" data-feather="facebook"></i>
					</div>
					<div class="footer__icon">
						<i class="feather-16" data-feather="instagram"></i>
					</div>
					<div class="footer__icon">
						<i class="feather-16" data-feather="twitter"></i>
					</div>
					<div class="footer__icon">
						<i class="feather-16" data-feather="youtube"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="copyrights">
		<div class="container">
			<div class="copyright__contents">
				<div class="copyright__items">
					<p class="footer__text--font">Copyright © <?php echo date('Y') ?> eimams. All Rights Reserved.</p>
				</div>
				<div class="copyright__logo">
					<img src="<?php echo get_template_directory_uri(); ?>/img/copyright.png" alt="copyright">
				</div>
			</div>
		</div>
	</div>

</footer>


</main>



<!-- Swiper js  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

<!-- cookie plugin -->

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.cookieBar.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.cookie-message').cookieBar();
	});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/cookieBar.css">

<!-- This is the start of the super important snippet -->
<div class="ui-widget">
	<div class="cookie-message ui-widget-header">
		<p>By browsing this site you accept cookies used to improve and personalise our services. Read our updated <a href="<?php echo site_url('privacy-policy') ?>">privacy policy</a> for more about what we do with your data.
		</p>
	</div>
</div>

<!-- /cookie plugin -->

</body>

</html>