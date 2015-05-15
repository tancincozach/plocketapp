<link href="assets/css/style.css" rel="stylesheet">
	<div class="container">  
  	  <div class="row ">
	    <div class=" col-2 col-lg-12" align="center">
		  <img class="img-responsive" src="<?php echo base_url(); ?>assets/img/plocket_logo.png" alt="">
	    </div>
	 </div>
	<div class="row">
	  <form id="upload" method="post" action="upload" enctype="multipart/form-data">
	    <div id="drop">
	      <a>Upload Pictures</a>
	      <input type="file" name="upl" multiple />
	    </div>
	    <ul  class="loading-info"></ul>           
	  </form>    
	</div>
	</div> <!-- /container -->
	<!-- JavaScript Includes -->
    <script src="assets/js/jquery.knob.js"></script>
    <!-- jQuery File Upload Dependencies -->
    <script src="assets/js/jquery.ui.widget.js"></script>
    <script src="assets/js/jquery.iframe-transport.js"></script>
    <script src="assets/js/jquery.fileupload.js"></script>
	<script src="assets/js/script.js"></script>
