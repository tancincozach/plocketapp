
  <style type="text/css">
  body{
              background: url('assets/img/plocket_background.png');
              font-family: arial;
  }
  * img,a{border: 0px;outline: none;}
  #page_wrapper{

              width: 516px;
              margin: 20px auto;
              background: none;
              overflow: hidden;
   }

  header{
              border-bottom: 5px solid #007AFF;
              overflow: hidden;
              padding: 20px 0px;
  }
  header .button_container{
              width: 30%;
              float: left;
  }
  header .caption_container{
              width: 70%;
              float: left;
              color: #fff;
              font-size: 30px;

  }

  section{
              text-align: center;
              margin: 20px 0px;

  }
   
  
section .circle_wrapper{
             position: relative;
             z-index: 1;
             min-height: 344px;
  
}

section .crop_image img{
              border-radius: 50%;
              position: absolute;
              height: 191px;
              width: 188px;
              left: 165px;
              top: 76px;
              z-index: 3;
}


footer ul li{
              list-style: none;
              display: block;

              padding: 5px;

              margin-left: 15px;
  }


   .circle_image_border{
              width:200px;
              height: 200px;
              border-radius: 50%;
              border:1px solid red;
    }


  @media screen and (max-width: 561px) {
    #page_wrapper{
              width: 100%;

    }
     section .crop_image img{
              left:130px;
    }

    footer img{
              width: 100%;

    }

  }

  @media screen and (max-width: 406px) {
    footer img{
              width: 100%;

    }
     section .crop_image img{
              left:90px;
    }
  }

     @media screen and (max-width: 320px) {
    footer img{
              width: 100%;

    }

     section .crop_image img {
		left: 76px;
    }
	footer ul li {
		display: block;
		margin-left: -20px;
		padding-bottom: 5px;
		padding-left: 0;
		padding-top: 5px;
	}
    
  }

</style>
<div id="fb-root"></div>
<div id="page_wrapper">
   <header>
        
           <div class="button_container">
            <img src="assets/img/button/back.png" onclick="javascipt:window.location='sticker';">
           </div> 
          <div class="caption_container">Save Image</div>  

        </ul>
   </header>

   <section>

            <div class="circle_wrapper" align="center">
            <img   src="assets/img/white_circle.png"/>
                        
            <div class="crop_image">
                <img src="<?php echo $image_with_sticker?>" >
            </div>
            </div>
           

   </section>

   <footer>
         <ul>
           <li><a href="#"/><img id="_print" src="assets/img/button/print.png" /></a></li>
           <li><a href="#" class="startpage"/><img id="" src="assets/img/button/start_over.png"/></a></li>
           <li><a href="#" class="sharefb" /><img id="_post_facebook" src="assets/img/button/post_facebook.png"  /></a></li>
          <!--<li><a href="#" class="shareinstagram"/><img id="_post_instagram" src="img/button/post_instagram.png"  /></a></li>-->

         </ul>
   </footer>
<input type="hidden" id="session_id" value="<?php echo $hashid;?>"/>
</div>
<script  type="text/javascript" src="assets/js/plocket.js"></script>
<script type="text/javascript">

$(function(){
                       

                     $('#_print').on('click',function(e){
                         window.location='print';

                    });

                    $('#_save').on('click',function(e){
                         alert('save image');
                     e.preventDefault();
                    });

                  
             
                      $('.startpage').click(function(e){
                        
                        plocket.startPage();
                        e.preventDefault();

                      });


                       plocket.socialNetwork();

  });
</script>
