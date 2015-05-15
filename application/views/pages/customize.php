<link href="assets/css/style.css" rel="stylesheet">
<style>   
.carousel {
    margin-top: 20px;
}
.item .thumb {
	width: 25%;
	cursor: pointer;
	float: left;
	padding:5px;
}
.item .thumb img {
	width: 100%;
	margin: 2px;
}
.item img {
	width: 100%;	
}

.carousel-inner{
	width:500px;color:white;
}

@media screen and (min-width : 381px) and (max-width : 599px){
.carousel-inner{width:300px}
}
@media screen and (min-width : 340px) and (max-width : 380px){  
.carousel-inner{width:300px}
}
@media screen and (max-width : 339px){
.carousel-inner{width:239px}
}


</style>
<header>
      <div class="button_container">
		<img src="assets/img/button/back.png" class="back" onclick="javascipt:window.location='crop';">
      </div> 
      <div class="caption_container">Customize</div>  
</header>
<section>
 <div class="container">  
	 <div class="row" align="center">			  
	  <div id="img-container" class="img-holder-circle circle-mask "  >
		<img id="pic"  class="img-responsive" src="<?php echo $filter['normal'];?>"  alt="">
	  </div>
	  </div>
 </div>
</section>
<footer>
 <div class="container" align="center">  
	<div class="row ">
	 <div data-interval="false" class="carousel slide" id="thumbcarousel">
            <div class="carousel-inner" >
			
			   <?php
				$ctr=0;
				$i=0;
				$item='<div class="item active">';
			 	foreach($filter as $key=>$filterUrlImage)
			   {
			   
			   if($ctr%4==0 && $ctr!=0){
					
					$item.='</div><div class="item">';
					$i++;
				}
					$item.='<div class="thumb" data-slide-to="'.$ctr.'" data-target="#carousel"> <a href="#" class="thumbnail filter"><img src="'.$filterUrlImage.'" alt="'.ucfirst($key).'"></a><span>'.ucfirst($key).'</span></div>';													
				$ctr++;
			 }
			echo $item;			 
			?>
            </div><!-- /carousel-inner -->
            <a data-slide="prev" role="button" href="#thumbcarousel" class="left carousel-control">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a data-slide="next" role="button" href="#thumbcarousel" class="right carousel-control">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
  </div>
  </div>
  <div class="row ">
	<div class="button-container"> 
	<button type="button" class="btn btn-primary btn-sm pull-left undo">Undo</button>
	<button type="button" class="btn btn-primary btn-sm pull-right select-filter">Next</button>
	</div>				
  </div>
</div> 	
</footer>
<script  type="text/javascript" src="assets/js/plocket.js"></script>
<script type="text/javascript" >
	 $(function(){	 
	   plocket.filterUtils();
	 });  
</script>