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
					,showWindow :function (c){
						$.fn.buttonEffect = function(action){
											 if(action=='hide'){
												$(this).animate({ 'marginTop': '-119px', opacity: 0.1 },500,function() {
													jcrop_api.destroy();  
													plocket.initJcrop();	
													$(this).remove();
												});
												}else{
													$(this).animate({ 'marginTop': '0px', opacity: 1.0 },500)
												
												}												
											}
							   
					   
							if($('.crop-btn').length==0)
							{
							  $('.caption_container').append('<button type="button"  style="width:100px;margin-right:5px; opacity: 0.1;filter: alpha(opacity=10);margin-top:-119px" class="btn btn-success btn-sm crop-btn ">Crop</button><button type="button"  style="width:100px;margin-right:5px;opacity: 0.1;filter: alpha(opacity=10);margin-top:-119px" class="btn btn-primary crop-btn-cancel btn-sm">Cancel</button>');

							  $('.crop-btn,.crop-btn-cancel').buttonEffect('show');

							  $('.crop-btn').on('click',function()
							  {
							    $('.crop-btn,.crop-btn-cancel').buttonEffect('hide');
							    plocket.ajaxcrop();
							  });
							  $('.crop-btn-cancel').on('click',function()
							  {
							   $('.crop-btn,.crop-btn-cancel').buttonEffect('hide');
							  });
							}
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
										ajaxReqObj.url  	   = '_crop';
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
																			window.location='custom'; 	
																		}
																		else
																		{
																			modal.mode='alert';
																			modal.msg=jsonOutput.success;
																			plocket.boostrapModal(modal);
																	//	setTimeout(function(){ window.location='crop'},2000); 	
																		}		
																
																	
																}
										ajaxReqObj.error      = function(jsonOutput){
																		bootbox.hideAll();
																		var modal = {};																
																		modal.mode='alert';
																		modal.msg=jsonOutput.error;
																		plocket.boostrapModal(modal);
																		//setTimeout(function(){ window.location='index'},5000); 
																}
									
									plocket.ajaxRequest(ajaxReqObj);
									
					},
					resetFilters:function(  final_image_filter ){					
						if(final_image_filter){
										var ajaxReqObj = {}	;
										ajaxReqObj.type 	   = 'post';
										ajaxReqObj.url  	   = '_filter';
										ajaxReqObj.data        = {selected_image_filter:final_image_filter,'action':'resetfilter'};
										ajaxReqObj.beforeSend  = function()	{
																	var modalbox={};
																	modalbox.title=  'Plocket Plug - Custom Image';
																	modalbox.msg  ='<p>Applying the selected filter.. Please wait...</p>';																														
																	plocket.boostrapModal(modalbox);		
															   }
										ajaxReqObj.success     = function(jsonOutput){ 
																		setTimeout(function(){  bootbox.hideAll();},2000); 
																		setTimeout(function(){ 	window.location='text'; },2000); 
																	
													
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
							

							 ajaxInfo.data = {'imgwidth':imgSize[0],'imgheight':imgSize[1]}							
							
							if ( element.length && element.text()!='' ) {
								plocket.coordinates('.input-text');
								ajaxInfo.data = {'x_post':xval,'y_post':yval,'text':$( ".input-text" ).text(),'action':'addText','color':$('.color_hidden').val(),'size':parseInt($('.input-text > span').css("font-size").replace(/[^-\d\.]/g, '')),'font':$('.font_hidden').val(),'imgwidth':imgSize[0],'imgheight':imgSize[1]}
							}
						
								
								ajaxInfo.type = 'POST'
								ajaxInfo.dataType ='json'
								ajaxInfo.url ='_addtext'
								
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
																//self.location='add-text.php';
															  },3000);	
														}
														else
														{																		
																								
															setTimeout(function(){  bootbox.hideAll();},2000); 
															setTimeout(function(){ window.location='sticker'; },2000); 															  
															//setTimeout(function(){ window.location='final'; },2000); 															  
														}																											
													}
								plocket.ajaxRequest(ajaxInfo);	
										
					}					
					,
						renderInputStyle:function(object,font,size,color){
								var inputCss = {'font-family':font,'color':color};								
								$(object).css(inputCss);

					},					
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
							
							$('.output,.add-text,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':fontSize});	
					}
					,
					populateText:function( textVal  , destObj){
							var text = textVal;
							if ( $( ".input-text" ).length ) 
							{
								$( ".input-text" ).remove();
							}
						  
							if(text!='')
							{
								var $container = $('#img-container'),left  = parseInt(($container.width() / 2)-66)+"px" ,top  = parseInt($container.height() / 2)+"px";
								
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
							
							selBox.append("<option value=\""+obj.fontname+"\" "+($('.font_hidden').val()==obj.fontname ? "selected=\"selected\"":"")+">"+obj.fontname+"</option>");
						});	
						
						selBox.change(function(){
							$('.font_hidden').val( $(this).val());	
							$("head").prepend("<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=" +  $(this).val() + ":400,700,400italic,700italic'></link>");
									$('.output,.add-text,.input-text > span').css({'font-family': $(this).val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
						});						
					}
					
				},
				initWebFonts:function (){
					var ajaxInfo = {};
					    ajaxInfo.type 	   = 'post';
						ajaxInfo.url	  	= '_loadfonts';
						ajaxInfo.dataType 	='json';						
						ajaxInfo.beforeSend =function(){
							 $('.font-container').html("Loading Fonts......");
						}
						ajaxInfo.success 	= function (data) {
								plocket.renderFonts(data.fonts);
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
					modal.msg = "<form  method=\"post\" class=\"form-horizontal\"><div class=\"form-group\"><div class=\"col-xs-12\"><input type=\"text\" class=\"form-control add-text\" style=\"height:50px\" title=\"Add Text\" data-placement=\"bottom\" placeholder=\"Add Text\" value=\""+($('.input-text').length > 0 ? $('.input-text').text():"")+"\"></div></div><div class=\"form-group\"><label class=\"col-xs-2 control-label\">Font</label><div class=\"col-xs-5 font-container\"></div></div><div class=\"form-group\"><label class=\"col-xs-2 control-label\">Color</label><div class=\"col-xs-8\"><input type=\"text\"  class=\"form-control flatcolorpicker color\" title=\"Pick color\" data-placement=\"bottom\" placeholder=\"#000000\" value=\"#000000\"></div></div><div class=\"form-group\"><label class=\"col-xs-2 control-label\">Size</label><div class=\"col-xs-5\"><button class=\"btn btn-success btn-size\" style=\"margin-right:3px;font-weight:bold\"  type=\"button\"><span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span></button><button class=\"btn btn-danger btn-size\"  style=\"font-weight:bold\" type=\"button\"><span class=\"glyphicon glyphicon glyphicon-minus \" aria-hidden=\"true\"></span></button></div></div></form>";
					modal.button={
						success: {
							label: "Ok",
							className: "btn-primary",
							callback: function () {
									$('.color_hidden').val($('.color').val());
									plocket.populateText( $('.add-text').val(),$('#img-container'));
									$('.output,.add-text,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
									
							}	
						},
						 main: {
							  label: "Cancel",
							  className: "btn-default",
							  callback: function() {
									$('.color_hidden').val($('.color').val());
									plocket.populateText( $('.add-text').val(),$('#img-container'));			
									$('.output,.add-text,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':parseInt($('.size_hidden').val())+'px'});	
									
								
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
					$('.output,.add-text,.input-text > span').css({'font-family':$('.font_hidden').val(),'color':$('.color_hidden').val(),'font-size':$('.size_hidden').val()+'px'});			
				}				
				,
				textImageUtils:function(){
									
					$('.next').on('click',function(event){ 						
						plocket.requestAddTextAjax('.input-text');
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
				windowFB:function(){
				
					var modal={};
												modal.msg="<form  method=\"post\" class=\"form-horizontal\"><div class=\"form-group\"><div col=\"col-xs-8\"><img src=\"assets/uploads/social-output/"+$('#session_id').val()+".png\" class=\"img-responsive\"/></div></div><div class=\"form-group\"><label class=\"col-xs-3 control-label\">Message:</label><div class=\"col-xs-8\"><textarea class=\"form-control message\" rows=\"3\" id=\"comment\" placeholder=\"Message\"></textarea></div></div></div></form>";
												
											
												
												modal.title=  'Plocket Plug - Share To Facebook';
												
												modal.button={
																success: {
																	label: "Ok",
																	className: "btn-success",
																	callback: function () {
																	
																	album = $('.album').val();
																	message = $('#comment').val();

																						var ajaxReqObj = {};
																						FB.api('/me', {fields: 'birthday,cover,devices,email,first_name,gender,id,last_name,link,location,name,name_format,timezone,verified,website,locale'}, function(response) {
																								
																						ajaxReqObj.type 	   = 'post';
																						ajaxReqObj.url  	   = '_postfb';
																						ajaxReqObj.dataType	   = 'json';
																						ajaxReqObj.data        = {'action':'sharefb','message':message,'user':response}
																						ajaxReqObj.beforeSend  = function()	{
																							var modal={}
																							modal.msg ='Posting to Facebook'
																							modal.title=  'Plocket Plug - Share To Facebook';
																							plocket.boostrapModal(modal);	
																						},
																						ajaxReqObj.success     = function(jsonOutput){ 
																								bootbox.hideAll();		
																								var modal={}
																								modal.title=  'Plocket Plug - Share To Facebook';																																
																								if(jsonOutput.message!='')
																								{
																								modal.msg =jsonOutput.message;
																								plocket.boostrapModal(modal);
																								setTimeout(function(){ window.location='final'; },2000); 																								
																								}else{
																								
																								modal.msg =jsonOutput.error																								
																								plocket.boostrapModal(modal);
																								
																								}
																								
																						}
																								plocket.ajaxRequest(ajaxReqObj);
																								
																						
																					});
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
				
				}
				,
				shareFacebook:function(){

						   window.fbAsyncInit = function() {
							FB.init({
							  appId      : '1005498782797911',
							  xfbml      : true,
							  version    : 'v2.2',
							  status     : true,
							  cookie     : true
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
							 
										FB.login(function(response) 
											{
											 if(response.status=='connected'){
												plocket.windowFB();
											 }															
											}, 
											{
												scope: 'publish_actions,public_profile,user_friends',
												return_scopes: true
											});
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
								main: 
								{
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
					bigImg = $('#pic');	
					
					$('a.filter').on('click',function(e){
					  e.preventDefault();						
					  e.stopPropagation();
						var bigImg = $('#pic'),currentSrc = $(this).find('img').attr('src');										
							bigImg.attr('src',currentSrc);
					});
					$('button.undo').on('click',function(){
						bigImg.attr('src',origImg);										
					});
					$('button.select-filter').on('click',function(){			
						plocket.resetFilters(bigImg.attr('src'));														
					});				
				},
				InitSticker:function(container)
				{
				  var imgWidth = $('#pic').width();
				  $('.thumb').on('click',
				  	function(e)
					{
					  e.preventDefault();						
					  e.stopPropagation();
				  		var $parent = $('.img-holder-circle'),$sticker = $(this).find('img');
				  		
				  		var top = $parent.height() / 2;
						
				  		var left = $parent.width() / 2;

				  		 $('<img/>',{
					          src       :   $sticker.attr('src'),
					          style     :   'position:absolute;left:'+left+'px;top:'+top+'px;width:100px;height:100px',
					          class     :   'stickers'
					        })
							.appendTo('.img-holder-circle')
							.resizable(
										{
										handles : 'se'
										})
							.parent('.ui-wrapper')
							.draggable({containment:'parent'})
							.hover(function()
							{
							  $(this).addClass('mouseoverSticker');
							  $(this).removeClass('mouseoutSticker');
							},
							function()
							{
							 $(this).addClass('mouseoutSticker');
							 $(this).removeClass('mouseoverSticker');
						   });				
					return false;
				    }
				  );
				  
				  $('.undo')
				  .on('click',function()
				  {
					$('.stickers').remove();
				  });
				  plocket.saveSticker();
				},
				saveSticker:function()
				{
				  $('.add-sticker').click(function(){
				  	var postdata = {'total_images':0,'src':[],'x':[],'y':[],'width':[],'height':[]};
				  	 $('.stickers').each(function(i,obj){
								var contOffset   = $('#img-container').offset(),
									imageOffset  = $(this).offset();						    																			 
									postdata.src.push($(this).attr('src'));						
									postdata.x.push(imageOffset.left - contOffset.left);						
									postdata.y.push(imageOffset.top - contOffset.top);
									postdata.width.push($(this).width());
									postdata.height.push($(this).height());
						 });
							postdata.total_images = postdata.src.length;						
							var ajaxReqObj = {};
							ajaxReqObj.type 	   = 'post';
							ajaxReqObj.url  	   = '_addsticker';
							ajaxReqObj.data        = postdata;
							ajaxReqObj.beforeSend  = function()	{
								var modal={}
								modal.msg ='Adding stickers...'
								modal.title=  'Plocket Plug - Add Stickers';
								plocket.boostrapModal(modal);	
							};		
							ajaxReqObj.success     = function(jsonOutput)
							{ 
								bootbox.hideAll();			
								if(jsonOutput.error)
								{
								  modal.mode='alert';
								  modal.msg=jsonOutput.success;
								  plocket.boostrapModal(modal);
								}
								else
								{
								  window.location='final';
								}																																													
							};
							plocket.ajaxRequest(ajaxReqObj);
								
				  });
				}
				,
				startPage:function(){
				
						var ajaxReqObj = {};
						ajaxReqObj.type 	   = 'post';
						ajaxReqObj.url  	   = '_startover';
						ajaxReqObj.dataType	   = 'json';						
						ajaxReqObj.beforeSend  = function()	{};		
						ajaxReqObj.success     = function(jsonOutput){ 
							if(jsonOutput){
								window.location='index'; 
							}
						};
						plocket.ajaxRequest(ajaxReqObj);						
							
				
				},
	  			sendEmail:function(){

	               $('#formsubmit').validate({

		                rules:{
		                    
		                    email:{required:true,email:true}


		                },
		                messages:{
		                   email:{
		                    required: "Please provide your email address"
		                    }
		                },
		                submitHandler:function(){
		    
		                        var   datapost = $('#formsubmit').serialize();

		                        $.ajax({
		                            type:'post',
		                            url: '_sendemail',
		                            data: datapost,
		                            beforeSend:function(){
		                                 $('#text-notice').html('');
		                                 $('#text-notice-success').css({'color':'green','font-size':'18px'}).show();
		                            },
		                            success:function(){
		                               $('#text-notice').html('');
		                               $('#text-notice-success').hide();
		                                setTimeout(function(){
		                                  bootbox.dialog({
		                                   title: 'Plocket Plug',
		                                    message:'Your PDF for printing has been sent to your email. Thanks for using the Plocket Plug Web App!',
		                                    buttons:{
		                                        success: {
		                                          label: "Ok",
		                                          className: "btn-success",
		                                          callback: function () {
		                                                  }
		                                          
		                                        }                                        
		                                    }
		                                   }
		                                );
		                                      },1000)
		                                setTimeout(function(){
		                                         window.location = 'final';
		                                      },5000);
		                        
		                            }

		                        });
		                    
		                      }
		              });    
	        }
}
	 
	 
	 
	 
	 
	 
	 
							 