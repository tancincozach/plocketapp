
<link rel="stylesheet" type="text/css" href="assets/css/jquery.ui.fontSelector.css" />
<link href="assets/css/flatcolorpicker.css" rel="stylesheet" type="text/css">
<link type="text/css" href="assets/css/sticker/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="assets/css/sticker/jquery.ui.core.css" rel="stylesheet" />
<link href="assets/css/style.css" rel="stylesheet">
 <header>
    <div class="button_container">
     <img src="assets/img/button/back.png" class="back" onclick="javascipt:window.location='custom';">
    </div> 
     <div class="caption_container">Add Text</div>  
 </header>

<section>
 <div class="container">
  <div class="vertical-center-crop">  
	 <div class="row" align="center">
	  <div id="img-container" class="img-holder-circle circle-mask img-responsive" style="text-align:center">
		<img id="pic" src="<?php echo $filtered_dir.$filtered_img?>" >	  
	  </div>    
	</div>
   </div>
 </div>
</section>

<footer>
<div class="container ">
 <input id="x" type="hidden" value="0" />
 <input id="y" type="hidden" value="0" />
 <input class="color_hidden" type="hidden" value="#000000" />
 <input class="font_hidden" type="hidden" value="Arial" />
 <input class="size_hidden" type="hidden" value="16" />
 <div class="selboxHidden" style="display:none"></div>
<div class="vertical-center-crop"  style="margin-top:20px">
	<div class="row default-width-container">
		<div class="col-xs-4 col-sm-4" > <button type="button"  class="btn btn-primary btn-sm pull-left undo">Undo</button></div>
		<div class="col-xs-4 col-sm-4 text-center" > <button type="button"  class="btn btn-primary btn-sm font">Add Text</button></div>
		<div class="col-xs-4 col-sm-4" > <button type="button" class="btn btn-primary btn-sm pull-right next">Next</button></div>
	</div>
</div>
</div>
</footer>
<script  type="text/javascript" src= "assets/js/jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script  type="text/javascript" src="assets/js/plocket.js"></script>
<script  type="text/javascript" src="assets/js/jquery.ui.fontSelector.js"></script>
<script  type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script  type="text/javascript" src="assets/js/jquery.xcolor.min.js"></script>
<script  type="text/javascript" src="assets/js/flatcolorpicker.js"></script>
<script  type="text/javascript" src="assets/js/jquery.lettering-0.6.1.min.js"></script>
<script  type="text/javascript" src="assets/js/circletype.js"></script>
<script  type="text/javascript" src="assets/js/flowtype.js"></script>



<script type="text/javascript" >
$(document).ready(function() {

	plocket.textImageUtils();

	plocket.socialNetwork();

			$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
 });  
</script>