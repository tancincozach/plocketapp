<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<style>
    body{
              background: url('assets/img/plocket_background.png');
              font-family: arial;
  }
  div.container > form{
    marign:auto 0px;
  }
label {margin-top:3px;}
label.error {background:url('assets/img/x.png') no-repeat top left;padding-left:17px;color:red;}
</style>
<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
<div class="container">
      
      <form class="contact-us form-horizontal" action="" method="post" id="formsubmit">
 

        
        <p style="text-align:center;margin-top:20px"><img src="assets/img/plocket_logo.png"></p>
        <p style="text-align:center;margin-top:30px;color:red;" id="text-notice">Please fill up fields with (*) and we will send to you the PDF for printing</p>

        <div class="control-group">
            <label class="control-label">* Name</label>
            <div class="controls">
                <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span>
                    <input id="name" type="text"style="height:30px" class="input-xlarge" name="name" placeholder="Name" required>
                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">* Email</label>
            <div class="controls">
                <div class="input-prepend">
                <span class="add-on"><i class="icon-envelope"></i></span>
                    <input id="email" type="email" style="height:30px" class="input-xlarge" name="email" placeholder="Enter a valid email address" required>
                </div>
            </div>    
        </div>

        <div class="control-group">
            <label class="control-label">Comment</label>
            <div class="controls">
                <div class="input-prepend">
                <span class="add-on"><i class="icon-pencil"></i></span>
                    <textarea id="comment" name="comment" cols="80" placeholder="Comment (Max 200 characters)"></textarea>
                </div>
            </div>
        </div>
        <div style="clear:both" class="control-group">
          <div class="controls">
            <button type="submit" id="submitbtn" class="btn btn-primary">Submit &rsaquo;</button>
            <button type="button" id="cancelbtn" class="btn">&lsaquo; Cancel</button>
          </div>    
        </div>
      </form>
          <p id="text-notice-success" style="display:none;text-align:center"><img src="assets/img/loading.gif"/><br/> 
     We are currently processing your image which will arrive to your email inbox.  
     If you do not see it there, make sure to check your spam folder.  
     
      </p>
      
</div>
<script  type="text/javascript" src="assets/js/plocket.js"></script>
<script type="text/javascript">
$(function(){
     plocket.sendEmail();
    });
</script>