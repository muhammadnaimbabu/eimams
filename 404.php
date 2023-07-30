<?php
get_header();
?>

<style type="text/css">
body {
background:none;
background:#f9fee8;	
}

</style>



<?php

echo '
<div id="wrapper_404">
<div align="center" id="page_404">
<img alt="Kidmondo_face_sad" src="'.get_template_directory_uri().'/img/kidmondo_face_sad.gif">
<h1>We\'re sorry...</h1>
<p align="center">The page you are looking for cannot be found.</p>

</div>
</div>

' ;



get_sidebar();

get_footer(); 

?>