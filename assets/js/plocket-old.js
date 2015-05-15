postobj= {  x:0, y :0, w :0, h :0 };
 var  plocket = {
				ajaxRequest:function( ajaxInfo ){
					
							if(ajaxInfo){
									$.ajax({
												type: ajaxInfo.type,     
												dataType:ajaxInfo.dataType,
												url :ajaxInfo.url, 
												data:ajaxInfo.data,    
												beforeSend: ajaxInfo.beforeSend,
												success:ajaxInfo.success,
												error:ajaxInfo.error												
										});
							
							}
					
				},
					resetWidth:function(){
					
						$("#pic").removeAttr("style").css({'width':'100%','visibility':'visible'});
						
					},
					storeCoordinate :function (c){					
					
						 $('#x').val(c.x); 
						 $('#y').val(c.y); 
						 $('#w').val(c.w); 
						 $('#h').val(c.h); 
						 
						postobj.x = $('#x').val();
						postobj.y = $('#y').val();
						postobj.w = $('#w').val();
						postobj.h = $('#h').val();
						
					},
					boostrapModal:function( modConfig){
					
						if(modConfig){
						
							switch(modConfig.mode){
								case "alert":
									bootbox.alert(modConfig.msg,modConfig.event);
								break;
								case 'confirm':
									bootbox.confirm(modConfig.msg,modConfig.event);
								break;

								default:
								
									bootbox.dialog({
											title: modConfig.title,
											message:modConfig.msg,
											buttons: modConfig.button
										}
									);
									
								break;
								
							}
						
							  
						}
					}
					,
					showWindow :function (c){
						  var modal = {}
							modal.title=  'Plocket Plug - Crop Image';
							modal.msg='<p>Would you like to crop this image ?</p>';
							modal.button={
											success: {
												label: "Crop",
												className: "btn-success",
												callback: function () {
														plocket.ajaxcrop();							
												}	
											},
											 main: {
												  label: "Cancel",
												  className: "btn-primary",
												  callback: function() {
													
											
													
												  }
												}
											
										};
					
							plocket.boostrapModal(modal);
						
					},
					initJcrop:function (){
					
							var heightImg = $('#pic_hide').height();
							var widthImg  = $('#pic_hide').width();

						  this.resetWidth();												

						  var jcropObj = $('#pic');
						  
						  $(jcropObj).Jcrop({
											aspectRatio: 1,
											onSelect: this.showWindow,
											onChange: this.storeCoordinate,
											trueSize: [widthImg,heightImg],											
											allowMove: true, 
											allowResize: true, 
											allowSelect: true
											},function(){
													var bounds = this.getBounds();
													boundx = bounds[0];
													boundy = bounds[1];
													jcrop_api = this;          				 
											
										  });
					},
					ajaxcrop:function(){
					
								  
									$imgwidth = $('#pic').width();
									
										var ajaxReqObj = {}	;
											
										ajaxReqObj.type 	   = 'post';
										ajaxReqObj.url  	   = 'ajax/ajaxrequest.php';
										ajaxReqObj.data        = {coordinates:postobj,action:'crop'};
										ajaxReqObj.dataType    = 'json',
										ajaxReqObj.beforeSend  = function(){
																var modal={};
																modal.title=  'Plocket Plug - Crop Image';
																modal.msg  ='<p>Cropping your image. Please wait...</p>';																														
																plocket.boostrapModal(modal);																
																}
										ajaxReqObj.success     = function(jsonOutput){
																		bootbox.hideAll();
																		var modal = {};
																		if(jsonOutput.success)
																		{																			
																			 	
																			setTimeout(function(){  bootbox.hideAll();},2000); 
																			setTimeout(function(){ window.location='edit-image.php'; },2000); 	
																		}
																		else
																		{
																			modal.mode='alert';
																			modal.msg=jsonOutput.success;
																			plocket.boostrapModal(modal);
																		setTimeout(function(){ window.location='crop-image.php'},5000); 	
																		}		
																
																	
																}
										ajaxReqObj.error      = function(jsonOutput){
																		bootbox.hideAll();
																		var modal = {};																
																		modal.mode='alert';
																		modal.msg="Cropping image cannot be processed , you will be redirected to index page";
																		plocket.boostrapModal(modal);
																		setTimeout(function(){ window.location='index.php'},5000); 
																}
									
									plocket.ajaxRequest(ajaxReqObj);
									
					},
					resetFilters:function(  final_image_filter ){					
						if(final_image_filter){
										var ajaxReqObj = {}	;
										ajaxReqObj.type 	   = 'post';
										ajaxReqObj.url  	   = 'ajax/ajaxrequest.php';
										ajaxReqObj.data        = {selected_image_filter:final_image_filter,'action':'resetfilter'};
										ajaxReqObj.beforeSend  = function()	{
																	var modalbox={};
																	modalbox.title=  'Plocket Plug - Custom Image';
																	modalbox.msg  ='<p>Applying the selected filter.. Please wait...</p>';																														
																	plocket.boostrapModal(modalbox);		
															   }
										ajaxReqObj.success     = function(jsonOutput){ 
																		setTimeout(function(){  bootbox.hideAll();},2000); 
																		setTimeout(function(){ 	window.location='add-text.php'; },2000); 
																	
													
																}
										plocket.ajaxRequest(ajaxReqObj);
						}												
					},
					
					 coordinates : function(element) {
							element = $(element);
						    var containerOffset = $('#img-container').offset(),textContainerOffset    = $('.input-text').offset(),x = textContainerOffset.left - containerOffset.left , y   = textContainerOffset.top - containerOffset.top;						    														
							$('#x').val(x);
							$('#y').val(y);
					}
					,
					identifyImageDimension :function(){
					
							var $windowWidth = $(window).innerWidth() ,image_width=500,image_height=500;
							
							
							if($windowWidth <=599 && $windowWidth >= 381){
							
									image_width  = 380;
									image_height = 380;
									
							}else if($windowWidth <=380 && $windowWidth >= 340){
							
									image_width  = 258;
									image_height = 258; 
							
							}else if($windowWidth <=339){
							
									image_width  = 246;
									image_height = 246; 
									
							}
							return [image_width,image_height];
					}
					,
					requestAddTextAjax :function ( element ){
						element = $(element);
								

						
						var 	$x 		= $('#x') ,
								$y 		= $('#y') , 
								xval 	= 0,
								yval 	= 0 , 
								imgSize = plocket.identifyImageDimension(),
								ajaxInfo = {};	
								
								xval 	= $x.val();
								yval 	= $y.val();
							

							 ajaxInfo.data = {'action':'addText','imgwidth':imgSize[0],'imgheight':imgSize[1]}							
							
							if ( element.length && element.text()!='' ) {
								plocket.coordinates('.input-text');
								ajaxInfo.data = {'x_post':xval,'y_post':yval,'text':$( ".input-text" ).text(),'action':'addText','color':$('.color_hidden').val(),'size':parseInt($('.input-text > span').css("font-size").replace(/[^-\d\.]/g, '')),'font':$('.font_hidden').val(),'imgwidth':imgSize[0],'imgheight':imgSize[1]}
							}
						
								
								ajaxInfo.type = 'POST'
								ajaxInfo.dataType ='json'
								ajaxInfo.url ='ajax/ajaxrequest.php'
								
								ajaxInfo.beforeSend =  function(){ 
														var modalbox={};
															modalbox.title=  'Plocket Plug - Custom Image';
															if ( element.length && element.text()!='' ) {
																modalbox.msg  ='<p>Rendering Text on Image. Please Wait....</p>';																														
															}else{
																modalbox.msg  ='<p>Redirecting ....</p>';																														
															}
															
															plocket.boostrapModal(modalbox);	
													}
								ajaxInfo.success =  function(jsonOutput){ 																		
														if(jsonOutput.error)
														{																		
															var modalbox={};
															bootbox.hideAll();
															modalbox.mode='alert';
															modalbox.msg="<img src=\"assets/img/error.png\">"+jsonOutput.error;
															plocket.boostrapModal(modalbox);
																setTimeout(function(){
																			bootbox.hideAll();							
															  },2000);
															  setTimeout(function(){
															self.location='add-text.php';
															  },3000);	
														}
														else
														{																		
																								
															setTimeout(function(){  bootbox.hideAll();},2000); 
															setTimeout(function(){ window.location='add-sticker.php'; },2000); 															  
														}																											
													}
								plocket.ajaxRequest(ajaxInfo);	
										
					}					
					,
						renderInputStyle:function(object,font,size,color){
								var inputCss = {'font-family':font,'color':color};								
								$(object).css(inputCss);

					},
					addText:function (){

							$('.input-text').remove();


							var $textObj = $('input.add-text-input'),$destinationObj = $('#img-container');
							
							if($textObj!==''){
								plocket.populateText($textObj ,$destinationObj);
								$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':$('.size_hidden').val()});	

								$('.font').show();

							}				
						  }
					,
					fontSizing:function(){
					

						$('#incfont').click(function(){   
							curSize= parseInt($('#content').css('font-size')) + 2;
							if(curSize<=20)
								$('#content').css('font-size', curSize);

							}); 

						$('#decfont').click(function(){   
							curSize= parseInt($('#content').css('font-size')) - 2;
							if(curSize>=12)
								$('#content').css('font-size', curSize);

						});

					}
					,
					divResizeText : function(object) {

								var $_me = $(object);
								var $_parent = $_me.parent();

								var int_my_width = $_me.width();
								var int_parent_width = $_parent.width();
		
								rl_ratio =   int_parent_width / int_my_width;

								var int_my_fontSize = $_me.css("font-size").replace(/[^-\d\.]/g, '');

								int_my_fontSize = Math.floor(int_my_fontSize * rl_ratio);

								
								$_me.css("font-size", parseInt(int_my_fontSize) + "px");				
								$('.size_hidden').val(parseInt(int_my_fontSize));
							
					}
					,
					
					buttonResizeText:function( object ){
					
						var  mode= object.attr('class'), fontSize = parseInt($('.size_hidden').val());

							if(object.hasClass("glyphicon-plus")){								

								if(fontSize==40){
								
									var modalbox={};
										modalbox.mode='alert';
										modalbox.msg='You have reached the maximum font size..';
										plocket.boostrapModal(modalbox);
										return false;
								}
								fontSize+=2;
							}
							if(object.hasClass("glyphicon-minus")){
								if(fontSize==16){
									var modalbox={};
										modalbox.mode='alert';
										modalbox.msg='You have reached the minimun font size..';
										plocket.boostrapModal(modalbox);
										return false;
								}
								fontSize-=2;	
							}
							
							$('.size_hidden').val(fontSize);
							
							$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':fontSize});	
					}
					,
					populateText:function( textObj  , destObj){
							var text = textObj.val();
							if ( $( ".input-text" ).length ) 
							{
								$( ".input-text" ).remove();
							}
						  
							if(text!='')
							{
								var $container = $('#img-container'),left  = parseInt(($container.width() / 2)-66)+"px" , top = "100px";
								
								$("<div class=\"input-text\" style=\"font-family:"+$('.font_hidden').val()+";color:"+$('.color_hidden').val()+";font-size:"+parseInt($('.size_hidden').val())+'px'+";left:"+left+" ; top:"+top+" ;color:"+$('.color').val()+";position:absolute;\"><span>"+text+"</span>  <div class=\"ui-resizable-handle ui-resizable-n\"></div><div class=\"ui-resizable-handle ui-resizable-e\"></div><div class=\"ui-resizable-handle ui-resizable-s\"></div><div class=\"ui-resizable-handle ui-resizable-w\"></div> <div class=\"ui-resizable-handle ui-resizable-ne\"></div><div class=\"ui-resizable-handle ui-resizable-se\"></div><div class=\"ui-resizable-handle ui-resizable-sw\"></div><div class=\"ui-resizable-handle ui-resizable-nw\"></div></div>").appendTo(destObj);
								$( ".input-text" ).text();	
																
								$(".input-text > span").css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.color_hidden').val()+'px')});
								plocket.coordinates('.input-text');				
									
								
								var set_position = function(width, height){
										  $('.ui-resizable-n').css('left', (width/2-4)+'px');
										  $('.ui-resizable-e').css('top', (height/2-4)+'px');
										  $('.ui-resizable-s').css('left', (width/2-4)+'px');
										  $('.ui-resizable-w').css('top', (height/2-4)+'px');
										};
										$( ".input-text" ).resizable({ 
										  handles: {
											'n':'.ui-resizable-n', 
											'e':'.ui-resizable-e',
											's':'.ui-resizable-s',
											'w':'.ui-resizable-w',
											'ne': '.ui-resizable-ne',
											'se': '.ui-resizable-se',
											'sw': '.ui-resizable-sw',
											'nw': '.ui-resizable-nw'
										  },
										  grid: [ 10, 10 ],
										  create: function( event, ui ) {
							
											
											var width = $(event.target).width();
											var height = $(event.target).height();
											set_position(width, height);
												$(".input-text > span").flowtype({minimum   : 500, maximum   : 1200,minFont   : 16,maxFont   : 40,fontRatio : 30,lineRatio : 1.45});											
										  },
										  resize: function(event, ui){
											var width = $(event.target).width();
											var height = $(event.target).height();
											set_position(width, height);											
											plocket.divResizeText('.input-text > span');
										  }
										});

										$( ".input-text" ).draggable({
										  grid: [ 10, 10 ],
										  containment: "parent",
											refreshPositions: true, 
											drag: function( event, ui ) {
														plocket.coordinates('.input-text');


											},											
											 stop: function(event, ui ){
														plocket.coordinates('.input-text');						
											}
										});
										
										
										
										
								
							 }
							textObj.val("");					
					}
				,
				getCss :function (fontStyle){					
					var sheets = document.styleSheets;
					console.log(sheets);
					if(sheets.length > 0){
					$.each(sheets,function(i,object){
						if( object.length > 0 ){
							var source = object.href;						
							if(source.indexOf('fonts.googleapis.com')!=-1 )
							{
							 if(source.indexOf(fontStyle)!=-1)
							 {									
								$('head').append("<link href=\""+object.href+"\" type=\"text/css\" rel=\"stylesheet\">");																		
							 }
							}
						}
						
					});
					}
					
				}
				,
				renderFonts:function( objFont ){					
					$('.font-container,.selboxHidden').html("");					
					$('.font-container').append("<select id=\"fonts\" class=\"font-selector\"></select>");
					var selBox = $('#fonts');
					if(Object.keys(objFont).length > 0 )
					{
						selBox.find('option').remove().end();						
						selBox.append("<option value=\"Arial\"  "+($('.font_hidden').val()=='Arial' ? "selected=\"selected\"":"")+">Arial</option>");		
						selBox.append("<option value=\"Tahoma\"  "+($('.font_hidden').val()=='Tahoma' ? "selected=\"selected\"":"")+">Tahoma</option>");		
						$.each(objFont,function(index,obj){	
											selBox.append("<option value=\""+obj.family+"\" "+($('.font_hidden').val()==obj.family ? "selected=\"selected\"":"")+">"+obj.family+"</option>");
						});	
						selBox.fontSelector({
									options: {
									  inSpeed: 500,
									  outSpeed: "fast",
									},
									fontChange: function(e, ui) {									 
										$('.font_hidden').val(ui.font);		
										$('.output').text(ui.font);	
										$('.output,.input-text > span').css({'font-family':ui.font,'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	

									},
									styleChange: function(e, ui) {								
										$('.font_hidden').val(ui.font);		
										$('.output').text(ui.font);	
										$('.output,.input-text > span').css({'font-family':ui.font,'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
									}
						});							
					}
					
					var $newObj = $('.font-container').clone();		
					
					$('.selboxHidden').html($newObj);

				},
				initWebFonts:function (){
					var url = ['https://www.googleapis.com/webfonts/v1/webfonts'], ajaxInfo = {};
					url.push('?key=AIzaSyCcjgkpNdQrWRSJTdpRW8rTq6H6WQNxcWU');
					ajaxInfo.url	  	=  url.join('');
					ajaxInfo.dataType 	='jsonp';						
					ajaxInfo.beforeSend =function(){
											$('.font-container').html("Loading Fonts......");
										 }
					ajaxInfo.success 	= function (data) {
											plocket.renderFonts(data.items);
										 }					
					plocket.ajaxRequest(ajaxInfo);									
				}
				,
				loadImg:function( imgInfo ){
					var imgSize = plocket.identifyImageDimension() , ajaxInfo = {};
					
					ajaxInfo.type 		=  'post';
					ajaxInfo.dataType 	='json';
					ajaxInfo.url	  	='ajax/ajaxrequest.php';
					ajaxInfo.data  		= {'action':'loadImg','imgwidth':imgSize[0],'imgheight':imgSize[1]};				
					plocket.ajaxRequest(ajaxInfo);	
					
				},
				showFont:function(){				
					var modal ={} 
					modal.title='Plocket Plug - Add Text';
					modal.msg = "<div class=\"container\"><div  class=\" output text-center\"><span>"+$('.font_hidden').val()+"</span></div><div class=\"row bottom\"><div class=\"col-xs-2 col-sm-2 pull-left\">Font</div><div class=\"col-xs-10 col-sm-10 pull-left font-container\"></div></div><div class=\"row bottom\"><div class=\"col-xs-2 col-sm-2 pull-left\">Color</div><div class=\"col-xs-10 col-sm-10 pull-left\"><form><input type=\"text\" style=\"max-width:208px\" class=\"form-control flatcolorpicker color\" title=\"Pick color\" data-placement=\"bottom\" placeholder=\"#000000\" value=\"#000000\"></form></div></div><div class=\"row bottom\"><div class=\"col-xs-2 col-sm-2 pull-left\">Size</div><div class=\"col-xs-10 col-sm-10 pull-left\"><button class=\"btn btn-success btn-size\" style=\"margin-right:3px;font-weight:bold\"  type=\"button\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></button><button class=\"btn btn-danger btn-size\"  style=\"font-weight:bold\" type=\"button\"><span class=\"glyphicon glyphicon glyphicon-minus \" aria-hidden=\"true\"></span></button></div></div></div>";
					modal.button={
						success: {
							label: "Ok",
							className: "btn-primary",
							callback: function () {
									$('.color_hidden').val($('.color').val());
									$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
																
							}	
						},
						 main: {
							  label: "Cancel",
							  className: "btn-default",
							  callback: function() {
									$('.color_hidden').val($('.color').val());
									$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
												
								
							  }
							}
						
					};
					plocket.boostrapModal(modal);
					plocket.initWebFonts();				
					$('.flatcolorpicker').flatcolorpicker({});					
					$('.color').val($('.color_hidden').val());
					$('#font').val($('.font_hidden').val());
					$('button.btn-size').click(function(){													
							plocket.buttonResizeText($(this).children());
					});
					$('.output,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':$('.size_hidden').val()+'px'});			
				}				
				,
				textImageUtils:function(){
				
					$('button.add-text-btn').click(function(){
						plocket.addText();
					});
					$('.next').on('click',function(event){ 						
						plocket.requestAddTextAjax('.input-text');
					});
					$('input.add-text-input').keypress(function(event){ 
					  if(event.keyCode == 13){ 								 
						plocket.addText();
					  }							
					});
					$('.save').click(function(){
						plocket.requestAddTextAjax('.input-text');
					});

					$('.font').on('click',function(){
						plocket.showFont();
					});
					
					$('.undo').on('click',function(){
							$('.input-text').remove();
							$('#x,#y').val(0);
							$('.color_hidden').val('#000000');
							$('.font_hidden').val('Arial');
							$('.size_hidden').val(16);
							$('.selboxHidden').html("");
					});
					
					$('.circle').on('click',function(){
					
							$('.input-text').circleType({radius: 100}).css({'position':'absolute'});
							
					});
					$('.color').on('change blur',function(){					
					
						$('.output,.input-text').css({'font-family':$('.font_hidden').val(),'color':$(this).val(),'font-size':$('.size_hidden').val()+'px'});	
					})
				
				}
				,
				shareFacebook:function(){
				      // window.fbAsyncInit = function() {
							// FB.init({
							  // appId      : '435438029945847',
							  // xfbml      : true,
							  // version    : 'v2.2'
							// });
						  // };

						  // (function(d, s, id){
							 // var js, fjs = d.getElementsByTagName(s)[0];
							 // if (d.getElementById(id)) {return;}
							 // js = d.createElement(s); js.id = id;
							 // js.src = "//connect.facebook.net/en_US/sdk.js";
							 // fjs.parentNode.insertBefore(js, fjs);
						   // }(document, 'script', 'facebook-jssdk'));
						   window.fbAsyncInit = function() {
							FB.init({
							  appId      : '1005498782797911',
							  xfbml      : true,
							  version    : 'v2.2'
							});
						  };

						  (function(d, s, id){
							 var js, fjs = d.getElementsByTagName(s)[0];
							 if (d.getElementById(id)) {return;}
							 js = d.createElement(s); js.id = id;
							 js.src = "//connect.facebook.net/en_US/sdk.js";
							 fjs.parentNode.insertBefore(js, fjs);
						   }(document, 'script', 'facebook-jssdk'));
						   
						   
						   $('.sharefb').click(function(e){
						   
							e.preventDefault();
						   
									  var modal = {}
											modal.title=  'Plocket Plug - Social Network';
											modal.msg='<p>Would you like to share this on Facebook?</p>';
											modal.button={
															success: {
																label: "Share",
																className: "btn-success",
																callback: function () {
																	FB.login(function(){
									
																		
																		
																			 FB.api('/me/feed', 'post', {message: 'Hello, world!'});
																			 
																			}, {scope: 'publish_actions'});			
																}	
															},
															 main: {
																  label: "Cancel",
																  className: "btn-primary",
																  callback: function() {
																	
															
																	
																  }
																}
															
														};
						
								plocket.boostrapModal(modal);
						   
								

						   });
				
				},
				shareInstagram:function(){
				
				
						  $('.shareinstagram').click(function(e){
						   
							e.preventDefault();
						   
									  var modal = {}
											modal.title=  'Plocket Plug - Social Network';
											modal.msg='<p>Would you like to share this on Instagram?</p>';
											modal.button={
															success: {
																label: "Share",
																className: "btn-success",
																callback: function () {
																	FB.login(function(){
									
																		
																		
																			 FB.api('/me/feed', 'post', {message: 'Hello, world!'});
																			 
																			}, {scope: 'publish_actions'});			
																}	
															},
															 main: {
																  label: "Cancel",
																  className: "btn-primary",
																  callback: function() {
																	
															
																	
																  }
																}
															
														};
						
								plocket.boostrapModal(modal);
						   
								

						   });
				}
				,socialNetwork:function(){
				
						plocket.shareFacebook();
						plocket.shareInstagram();				
				}
				,
				filterUtils:function(){
				var site_url = 'http://www.plocketplug.com/plocketapp/print_image.php';
					bigImg = $('#pic');	
					origImg = $('#origSrc').val();
					
					$('div.filter-container ul > li >  a > img').on('click',function(){
						var bigImg = $('#pic'),currentSrc = $(this).attr('src');										
							bigImg.attr('src',currentSrc);
					});
					$('button.undo').on('click',function(){
						bigImg.attr('src',origImg);										
					});
					$('button.select-filter').on('click',function(){			
						plocket.resetFilters(bigImg.attr('src'));														
					});
					$('#printbtn').click(function(){
					  window.location=site_url;
					 });					
				}
}
	 
	 
	 
	 
	 
	 
	 
							 