<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/cropstyle.css" rel="stylesheet">
<link href="assets/css/jquery.Jcrop.min.css" rel="stylesheet" />

 <header>
	<div class="button_container">
	<img src="assets/img/button/back.png" class="back" onclick="javascipt:window.location='index.php';">
	</div> 
	<div class="caption_container">Crop Image  </div>  
 </header>
<section>
  <div class="container vertical-center-crop">  
	<div class="row ">
	  <div class="img-holder">
		<img id="pic"  class="img-responsive" src="<?php echo UPLOAD.$img?>" alt="">
	  </div>
	</div>
  </div> 
</section>
<form id="imageForm" name="imageForm" method="POST">	
	  <input type="hidden" id="x" value="0"/>
	  <input type="hidden" id="y" value="0"/>
	  <input type="hidden" id="w" value="0"/>
	  <input type="hidden" id="h" value="0"/>
</form>
<img id="pic_hide" style="display:none" src="<?php echo UPLOAD.$img?>" >
<script  type="text/javascript" src="assets/js/jquery.js"></script>
<script  type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script  type="text/javascript" src="assets/js/jquery.Jcrop.min.js"></script>
<script  type="text/javascript"src= "assets/js/jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script  type="text/javascript" src="assets/js/plocket.js"></script>
<script  type="text/javascript" src="assets/js/bootbox.min.js"></script>
<script type="text/javascript" >

	$(window).load(function(){ 
							 
			var jcrop_api;
			
			plocket.initJcrop();
			

	});


	$(window).resize(function(){ 
			
				jcrop_api.destroy();  
				
				plocket.resetWidth();
										
				plocket.initJcrop();	

	   });
	</script>