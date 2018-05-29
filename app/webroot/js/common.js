var default_zoom_level = 10;
var geocoder;
var geocoder1;
var map;
var bounds;
var marker;
var marker1;
var markerimage;
var infowindow;
var locations;
var latlng;
var searchTag;
var ws_wsid;
var ws_lat;
var ws_lon;
var ws_width;
var ws_industry_type;
var ws_map_icon_type;
var ws_transit_score;
var ws_commute;
var ws_map_modules;
var styles = [];
var markerClusterer = null;
var map = null;
var markers = [];
function split( val ) {
	return val.split( /,\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}
function __l(str, lang_code) {
    return(__cfg && __cfg('lang') && __cfg('lang')[str]) ? __cfg('lang')[str]: str;
}
function __cfg(c) {
    return(cfg && cfg.cfg && cfg.cfg[c]) ? cfg.cfg[c]: false;
}
function calcTime(offset) {
	d = new Date();
	utc = d.getTime() + (d.getTimezoneOffset() * 60000);
	return date('Y-m-d', new Date(utc + (3600000*offset)));
}
var common_options = {
        map_frame_id: 'mapframe',
        map_window_id: 'mapwindow',
		area: 'js-street_id',
        state: 'StateName',
        city: 'CityName',
        country: 'js-country_id',
        lat_id: 'latitude',
        lng_id: 'longitude',
        postal_code: 'PropertyPostalCode',
        ne_lat: 'ne_latitude',
        ne_lng: 'ne_longitude',
        sw_lat: 'sw_latitude',
        sw_lng: 'sw_longitude',
        button: 'js-sub',
        error: 'address-info',
		mapblock: 'mapblock',
        lat: '37.7749295',
        lng: '-122.4194155',
        map_zoom: 13
    }
function loadGeoSearch() {
    loadSideSearchMap();
    var options = common_options;	
    $('#DealCityNameSearch').autogeocomplete(options);
}
function loadSideSearchMap() {
    //generate the side map
   lat = $('.js-search-lat').metadata().cur_lat;
   lng = $('.js-search-lat').metadata().cur_lng;
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
        lat = $('.js-map-data').metadata().lat;
        lng = $('.js-map-data').metadata().lng;
    }
    var zoom = 9;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_TOP
        },
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById('js-map-container'), myOptions);
    map.setCenter(latlng);
    if (lat != 0 && lng != 0) {
        var imageUrl = __cfg('path_absolute') + 'img/center_point.png';
        var markerImage = new google.maps.MarkerImage(imageUrl);
        var j = 0;
        eval('var marker' + j + ' = new google.maps.Marker({ position: latlng,  map: map, icon: markerImage, zIndex: i});');
        var marker_obj = eval('marker' + j);
    }
    var i = 1;
    $('a.js-map-data', document.body).each(function() {
        lat = $(this).metadata().lat;
        lng = $(this).metadata().lng;
        url = $(this).attr('href');
        title = $(this).attr('title');
        updateMarker(lat, lng, url, i, title);
        i++ ;	
    });
}

function updateMarker(lat, lnt, url, i, title) {
    var store_count = i;
    if (lat != null) {
        myLatLng = new google.maps.LatLng(lat, lnt);
        var imageUrl = __cfg('path_absolute') + 'img/red/' + store_count + '.png';
        var markerImage = new google.maps.MarkerImage(imageUrl);
        eval('var marker' + i + ' = new google.maps.Marker({ position: myLatLng,  map: map, icon: markerImage, zIndex: i});');
        var marker_obj = eval('marker' + i);
        marker_obj.title = title;
        var li_obj = '.js-map-num' + i;
        //one time map listener to handle the zoom
        google.maps.event.addListenerOnce(map, 'resize', function() {
            map.setCenter(center);
            map.setZoom(zoom);
        });
        //properties marker hover, point the properties list active
        $(li_obj).bind('mouseenter', function() {
            var imagehover = __cfg('path_absolute') + 'img/black/' + store_count + '.png';
            marker_obj.setIcon(imagehover);
        });
        $(li_obj).bind('mouseleave', function() {
            var imageUrlhout = __cfg('path_absolute') + 'img/red/' + store_count + '.png';
            marker_obj.setIcon(imageUrlhout);
        });
        //properties list mouse over/leave changing the hover marker icon
        google.maps.event.addListener(marker_obj, 'mouseenter', function() {
            li_obj.addClass('active');
        });
        google.maps.event.addListener(marker_obj, 'mouseleave', function() {
            li_obj.removeClass('active');
        });
        var li_obj_request = '.js-map-request-num' + i;
        //requests
        $(li_obj_request).bind('mouseenter', function() {
            var imagehover = __cfg('path_absolute') + 'img/black/' + store_count + '.png';
            marker_obj.setIcon(imagehover);
        });
        $(li_obj_request).bind('mouseleave', function() {
            var imageUrlhout = __cfg('path_absolute') + 'img/red/' + store_count + '.png';
            marker_obj.setIcon(imageUrlhout);
        });
        google.maps.event.addListener(marker_obj, 'click', function() {
            window.location.href = url;
        });
    }
}

function geocodePosition(position) {	
	geocoder1 = new google.maps.Geocoder();
    geocoder1.geocode( {
        latLng: position
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $('#latitude').val(marker1.getPosition().lat());
            $('#longitude').val(marker1.getPosition().lng());
            map1.setCenter(results[0].geometry.location);  
        } 
    });
}

function loadSideMap() {
    lat = $('#' + common_options.lat_id).val();
    lng = $('#' + common_options.lng_id).val();		
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
            lat = 13.314082;
            lng = 77.695313;
    }
    var zoom = common_options.map_zoom;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions1 = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map1 = new google.maps.Map(document.getElementById('js-map-container'), myOptions1);
	marker1 = new google.maps.Marker( {
			draggable: true,
			map: map1,
			position: latlng
	});
    map1.setCenter(latlng);
	google.maps.event.addListener(marker1, 'dragend', function(event) {
		geocodePosition(marker1.getPosition());
	});
	google.maps.event.addListener(map1, 'mouseout', function(event) {
		$('#zoomlevel').val(map1.getZoom());
	});    
}
function loadCityMap() {
	lat = $('#latitude').val(); 
	lng = $('#longitude').val();	
    if ((lat == 0 && lng == 0) || (lat == '' && lng == '')) {
            lat = 13.314082;
            lng = 77.695313;
    }
    var zoom = common_options.map_zoom;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions1 = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map1 = new google.maps.Map(document.getElementById('js-map-container'), myOptions1);
	marker1 = new google.maps.Marker( {
			draggable: true,
			map: map1,
			position: latlng
	});
    map1.setCenter(latlng);
	google.maps.event.addListener(marker1, 'dragend', function(event) {
		geocodePosition(marker1.getPosition());		
	});
	google.maps.event.addListener(map1, 'mouseout', function(event) {
		$('#zoomlevel').val(map1.getZoom());
	});      
}

function loadGeo() {
	geocoder1 = new google.maps.Geocoder();
	var options = common_options;	
    $('#PropertyAddressSearch').autogeocomplete(options);
    loadSideMap();
}


function loadGeoAddress(selector) {
    geocoder = new google.maps.Geocoder();
    var address = $(selector).val();
    geocoder.geocode( {
        'address': address
    }, function(results, status) {
        $.map(results, function(results) {
            var components = results.address_components;
            if (components.length) {
                for (var j = 0; j < components.length; j ++ ) {
                    if (components[j].types[0] == 'locality' || components[j].types[0] == 'administrative_area_level_2') {
                        city = components[j].long_name;
                        $('#CityName').val(city);
                    }
                    if (components[j].types[0] == 'administrative_area_level_1') {
                        state = components[j].long_name;
                        $('#StateName').val(state);
                    }
                    if (components[j].types[0] == 'country') {
                        country = components[j].short_name;
                        $('#js-country_id').val(country);

                    }
                    if (components[j].types[0] == 'postal_code') {
                        postal_code = components[j].long_name;
                        if (selector == '#PropertyAddressSearch') {
                            $('#PropertyPostalCode').val(postal_code);
                        } else {
                            $('#RequestPostalCode').val(postal_code);
                        }
                    }
                }
            }
        });
    });
}

function noErr() {
    status = 'Done';
    return true;
}
onerror = noErr;
//Forcing loading images
function loadImages(r) {
    var i,
    n,
    s,
    q;
    q = 0;
    for (i = 0; i < r.document.images.length; i ++ ) {
        s = r.document.images[i].src;
        if ( ! r.document.images[i].complete || r.document.images[i].fileSize < 0) {
            r.document.images[i].src = __cfg('path_absolute') + 'img/empty.gif';
            r.document.images[i].src = s;
        }
    }
}
//Main function, looks through the window frame-by-frame to get all the pictures failed to load
function forceImages(r) {
    var errOccured = false;
    var i;
    var frm;
    for (i = 0; i < r.frames.length; i ++ ) {
        frm = r.frames[i];
        var bdy = null;
        //trying to open the document.
        try {
            bdy = frm.document.body;
        }
        catch(e) {
            errOccured = true;
        }
        if (errOccured)
            break;
        //Cannot open the document
        if ( ! bdy)
        //Not yet loaded? Wait and retry
         {
            window.r = r;
            r.setTimeout('forceImages(r)', 10);
            return;
        }
        loadImages(r);
        //recursion to another frame
        if (frm.frames.length > 0)
            forceImages(frm);
    }
    if (r.document.body)
        loadImages(r);
}

$.fx.speeds._default = 1000;

(function($) {
    $.fn.ftinyMce = function() {
		if(typeof tinyMCE != 'undefined'){
			$('textarea.js-editor').each(function(e){
				   tinyMCE.execCommand('mceAddControl',true, $(this).attr('id'));
		   });
		}else{
			$(this).tinymce( {
				// Location of TinyMCE script
				script_url: __cfg('path_relative') + 'js/libs/tiny_mce/tiny_mce.js',
				mode: "textareas",
			   // General options
				theme: "advanced",
				plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			   // Theme options
			   //newdocument,|,
				theme_advanced_buttons1: "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect, |, cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,",
				theme_advanced_buttons2: "undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolortablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,fullscreen,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,visualchars,nonbreaking,pagebreak",
				theme_advanced_buttons3: "",
				theme_advanced_buttons4: "",

				theme_advanced_toolbar_location: "top",
				theme_advanced_toolbar_align: "left",
				theme_advanced_statusbar_location: "bottom",
				theme_advanced_resizing: true,
			  // Example content CSS (should be your site CSS)
				//content_css: "css/content.css",
			   // Drop lists for link/image/media/template dialogs
				template_external_list_url: "lists/template_list.js",
				external_link_list_url: "lists/link_list.js",
				external_image_list_url: "lists/image_list.js",
				media_external_list_url: "lists/media_list.js",
				height: "250px",
				width: "80%",
				relative_urls : false,
				remove_script_host : false,
				setup: function(ed) {
					ed.onChange.add(function(ed) {
						tinyMCE.triggerSave();
					});
				}
			});
		}
    };
    $.fpropertyaddform = function(selector) {
		loadGeoAddress('#PropertyAddressSearch');
	};
    var jk = 300;
    $.fn.ftimepicker = function() {
		$ttis = $(this);
		$ttis.each(function (e) {
            var $this = $(this);
            var class_for_div = $this.attr('class');
            if ($this.find('select[id$="Hour"]').filter(':first').html()) {
                var label = $this.find('label').filter(':first').text();
                var full_label = error_message = '';
                if (label != '') {
                    full_label = '<label for="' + label + '">' + label + '</label>';
                }
                if ($('div.error-message', $this).html()) {
                    var error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
                }

                hour = $this.find('select[id$="Hour"]').filter(':first').val();
                minute = $this.find('select[id$="Min"]').filter(':first').val();
                meridian = $this.find('select[id$="Meridian"]').filter(':first').val();
                var selected_time = overlabel_class = overlabel_time = '';
                if (hour == '' && minute == '' && meridian == '') {
                    overlabel_class = 'js-overlabel';
                    overlabel_time = '<label for="caketime' + jk + '">No Time Set</label>';
                } else {
                    selected_time = hour + ':' + minute + ' ' + meridian;
                }
                $this.hide().after(full_label + '<div class="timepicker ' + overlabel_class + '">' + overlabel_time + '<span class="timepicker_button_trigger'+jk+'"></span><input type="text" class="timepickr" id="caketime' + jk + '" title="Select time" readonly="readonly" size="10" value="' + selected_time + '"/></div>' + error_message);
				$('#caketime' + jk).timepicker({
					showOn: 'both',  
					button: '.timepicker_button_trigger'+jk,
                    showPeriod: true,
                    showLeadingZero: true,
					defaultTime: selected_time,
					amPmText: ['am', 'pm'],
					onSelect: function() {
									$this.parent('div').filter(':first').find('label.overlabel-apply').css('text-indent','-3000px');
									var value = $(this).val();
									var newmeridian = value.split(' ');
									var newtime = newmeridian[0].split(':');
									$this.parent().find("select[id$='Hour']").val(newtime[0]);
									$this.parent().find("select[id$='Min']").val(newtime[1]);
									$this.parent().find("select[id$='Meridian']").val(newmeridian[1]);
				                }
                }).blur ( function() {
					$this.parent('div').filter(':first').find('label.overlabel-apply').css('text-indent','-3000px');
                    var value = $(this).val();
                    var newmeridian = value.split(' ');
                    var newtime = newmeridian[0].split(':');
                    $this.parent().find("select[id$='Hour']").val(newtime[0]);
                    $this.parent().find("select[id$='Min']").val(newtime[1]);
                    $this.parent().find("select[id$='Meridian']").val(newmeridian[1]);
                });
            }
            jk = jk + 1;
        });
    };
		  
	$.floadgeomaplisting = function(selector) {
		if($(selector, 'body').is(selector)){				
			var script = document.createElement('script');
			var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadGeoSearch&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
		}
	};
	
	$.floadGeo = function(selector) {
		if($(selector, 'body').is(selector)){				
			var $country = 0;
			$this = $(selector);
			var script = document.createElement('script');
			var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadGeo&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
			
		}
	};


	$.fn.dialogMultiple = function() {
		var ids = '';
		$(".js-multiple-sub-deal").each(function(){
			if(ids == ''){
		        ids = '#' + $(this).metadata().opendialog
			}
			else{
				ids = ids + ', ' + '#' + $(this).metadata().opendialog
			}
    	});
		if(ids != ''){
			$(ids).dialog({
				autoOpen: false,
				modal: true
			});
		}

    };
		  
		  
    $.fn.setflashMsg = function($msg, $type) {
        switch($type) {
            case 'auth': $id = 'authMessage';
            break;
            case 'error': $id = 'errorMessage';
            break;
            case 'success': $id = 'successMessage';
            break;
            default: $id = 'flashMessage';
        }
        $flash_message_html = '<div class="message"> <div id="' + $id + '" class="flash-message-inner">' + $msg + '</div></div>';
        $('#main').prepend($flash_message_html);
    };		  
		 
    $.fn.confirm = function() {
		$('body').delegate('a.js-delete', 'click', function(event) {
            return window.confirm(__l('Are you sure you want to ') + this.innerHTML.toLowerCase() + '?');
        });
    };
    $.fn.flashMsg = function() {
        $this = $(this);
        $alert = $this.parents('.js-flash-message');
        var alerttimer = window.setTimeout(function() {
            $alert.trigger('click');
        }, 3000);
        $alert.click(function() {
            window.clearTimeout(alerttimer);
            $alert.animate( {
                height: '0'
            }, 200);
            $alert.children().animate( {
                height: '0'
            }, 200).css('padding', '0px').css('border', '0px');
			$this.animate( {
                height: '0'				
            }, 200).css('padding', '0px').css('border', '0px').css('display', 'none');
        });
    };
	$.fn.companyprofile = function(is_enabled) {
        if (is_enabled == 0) {
            $('.js-company_profile_show').hide();
        }
        if (is_enabled == 1) {
            $('.js-company_profile_show').show();
        }
    };
	$.fn.fuploadajaxform = function() {
		$('body').delegate('form.js-upload-form', 'submit', function(e) {
            var content1 = $('.wuI').html();
            $flash_disabled = false;
            $('input:file').each(function(index) {
                if (($this).val())
                    return true;
            });
            var validate = false;
			if($(this).metadata().is_required == 'false' && $('#DealCloneDealId').val()!=''){
				var checked_image = $('.attachment-delete-block input:checked').length;
				var total_image = $('.attachment-delete-block input:checkbox').length;
				if(checked_image == total_image){
					validate = true;
				}
			}
            if (($(this).metadata().is_required == 'true' || validate)  && (content1 == '' || content1 == null)) {
                $('.js-flashupload-error').remove();
				$.fn.setflashMsg(__l('Please select atleast one file.'), 'error');
                $('.js-uploader').append('<span class="js-flashupload-error notice">'+__l("Please select atleast one file")+'</span>');
                $('.js-flashupload-error').flashMsg();
				aftersubmitdeal(true);
                return false;
            } else if ($(this).metadata().is_required == 'false' && (content1 == '' || content1 == null)) {				
                return true;
            } else {
                $('.js-flashupload-error').remove();
            }
            var $this = $(this);
            $this.find('.js-validation-part').block();
            $("#js-update-order-field-add-id").attr("disabled", true);
			$("#js-update-order-field-draft-id").attr("disabled", true);
			$("#js-update-order-field-preview-id").attr("disabled", true);
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {
						$(formData).each(function(i) {
							if(formData[i]['name'] == "data[Deal][description]"){
								if(formData[i]['value'] == ''){
									$('textarea', jqForm[0]).each(function(j) {
										if ($('textarea', jqForm[0]).eq(j).attr('name') == 'data[Deal][description]') {
										   formData[i]['value'] = $('textarea', jqForm[0]).eq(j).val(); 
										}
										
									});
									
								}
							}
						});	
					},
                success: function(responseText, statusText) {
					$('textarea.js-editor').each(function(e){
						   tinyMCE.execCommand('mceRemoveControl',false, $(this).attr('id'));
				   });
                    if (responseText == 'flashupload') {
                        $('.js-upload-form .flashUploader').each(function() {
                            this.__uploaderCache.upload('', this.__uploaderCache._settings.backendScript);
                        });
                    } else {
						$("#js-update-order-field-add-id").attr("disabled", false);
						$("#js-update-order-field-draft-id").attr("disabled", false);
						$("#js-update-order-field-preview-id").attr("disabled", false);
                        var validation_part = $(responseText).find('.js-validation-part', $this).html();
                        if (validation_part != '') {
                            $this.parents('.js-responses').find('.js-validation-part', $this).html(validation_part);
                        }
						aftersubmitdeal(false);
                    }
                }
            });
            return false;
        });
    };	
    $.fn.fajaxform = function() {
		$('body').delegate('form.js-ajax-form', 'submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
				cache: false,
                beforeSubmit: function(formData, jqForm, options) {
                    $('input:file', jqForm[0]).each(function(i) {
                        if ($('input:file', jqForm[0]).eq(i).val()) {
                            options['extraData'] = {
                                'is_iframe_submit': 1
                            };
                        }
                    });
                    $this.block();
                },
                success: function(responseText, statusText) {
					$('textarea.js-editor').each(function(e){
						   tinyMCE.execCommand('mceRemoveControl',false, $(this).attr('id'));
				   });
                    redirect = responseText.split('*');
                    if (redirect[0] == 'redirect') {
                        location.href = redirect[1];
                    }
					else if (responseText == 'success') {
                        window.location.reload();
                    }
					else if (responseText.indexOf($this.metadata().container) != '-1') {
                        $('.' + $this.metadata().container).html(responseText);
                    } 
					else if (responseText == 'index') {
                        $.get(__cfg('path_relative') + 'user_cash_withdrawals/index/', function(data) {
                            $('.js-withdrawal_responses').html(data);
                        });
                    }else if ($this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    }					 
					else {
					   if($('div.js-preview-responses').length){
					     $('div.js-preview-responses').html(responseText);
					   }else{
						 $this.parents('div.js-responses').eq(0).html(responseText);
					   }
                    }					
					aftersubmitdeal(false);
                    $this.unblock();
                }
            });
            return false;
        });
    };
    $.fn.fcommentform = function() {
		$('body').delegate('#topics-add form.js-comment-form, #users-view form.js-comment-form, #companies-view form.js-comment-form', 'submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
					$('textarea.js-editor').each(function(e){
						   tinyMCE.execCommand('mceRemoveControl',false, $(this).attr('id'));
				   });
                    if (responseText.indexOf($this.metadata().container) != '-1') {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $('.js-comment-responses').prepend(responseText);
                        $('.' + $this.metadata().container + ' div.input').removeClass('error');
                        $('.error-message', $('.' + $this.metadata().container)).remove();
                    }
                    if (typeof($('.js-captcha-container').find('.captcha-img').attr('src')) != 'undefined') {
                        captcha_img_src = $('.js-captcha-container').find('.captcha-img').attr('src');
                        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
                        $('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
                    }
					aftersubmitdeal(false);
                    $this.unblock();
                },
                clearForm: true
            });
            return false;
        });
    };
    $.fn.fcolorbox = function() {
            $(this).colorbox( {
                opacity: 0.30,
				width:'930px',
				height:'490px'
            });
    };
    var i = 1;
    $.fn.fdatepicker = function() {
		$(this).each(function (e) {
            var $this = $(this);
            var class_for_div = $this.attr('class');
            var year_ranges = $this.children('select[id$="Year"]').text();

            var start_year = end_year = '';
            $this.children('select[id$="Year"]').find('option').each(function() {
                $tthis = $(this);
                if ($tthis.attr('value') != '') {
                    if (start_year == '') {
                        start_year = $tthis.attr('value');
                    }
                    end_year = $tthis.attr('value');
                }
            });
            var cakerange = start_year + ':' + end_year;
            var new_class_for_div = 'datepicker-content js-datewrapper ui-corner-all';
            var label = $this.children('label').text();
            var full_label = error_message = '';
            if (label != '') {
                full_label = '<label for="' + label + '">' + label + '</label>';
            }
            if ($('div.error-message', $this).html()) {
                var error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
            }
            var img = '<div class="time-desc datepicker-container clearfix"><img title="datepicker" alt="[Image:datepicker]" name="datewrapper' + i + '" class="picker-img js-open-datepicker" src="' + __cfg('path_relative') + 'img/date-icon.png"/>';
            year = $this.children('select[id$="Year"]').val();
            month = $this.children('select[id$="Month"]').val();
            day = $this.children('select[id$="Day"]').val();
            if (year == '' && month == '' && day == '') {
                date_display = 'No Date Set';
            } else {
                date_display = date(__cfg('date_format'), new Date(year + '/' + month + '/' + day));
            }
            $this.hide().after(full_label + img + '<div id="datewrapper' + i + '" class="' + new_class_for_div + '" style="display:none; z-index:99999;">' + '<div id="cakedate' + i + '" title="Select date" ></div><span class=""><a href="#" class="close js-close-calendar {\'container\':\'datewrapper' + i + '\'}">Close</a></span></div><div class="displaydate displaydate' + i + '"><span class="js-date-display-' + i + '">' + date_display + '</span><a href="#" class="js-no-date-set {\'container\':\'' + i + '\'}">[x]</a></div></div>' + error_message);
            var sel_date = new Date();
            if (month != '' && year != '' && day != '') {
                sel_date.setFullYear(year, (month - 1), day);
            } else {
                splitted = calcTime(__cfg('timezone')).split('-');
                sel_date.setFullYear(splitted[0], splitted[1] - 1, splitted[2]);
            }
            $('#cakedate' + i).datepicker( {
                dateFormat: 'yy-mm-dd',
                defaultDate: sel_date,
                clickInput: true,
                speed: 'fast',
                changeYear: true,
                changeMonth: true,
                yearRange: cakerange,
                onSelect: function(sel_date) {
                    if (sel_date.charAt(0) == '-') {
                        sel_date = start_year + sel_date.substring(2);
                    }
                    var newDate = sel_date.split('-');
                    $this.children("select[id$='Day']").val(newDate[2]);
                    $this.children("select[id$='Month']").val(newDate[1]);
                    $this.children("select[id$='Year']").val(newDate[0]);
                    $this.parent().find('.displaydate span').show();
                    $this.parent().find('.displaydate span').html(date(__cfg('date_format'), new Date(newDate[0] + '/' + newDate[1] + '/' + newDate[2])));
                    $this.parent().find('.js-datewrapper').hide();
                    $this.parent().toggleClass('date-cont');
                }
            });
            if ($this.children('select[id$="Hour"]').html()) {
                hour = $this.children('select[id$="Hour"]').val();
                minute = $this.children('select[id$="Min"]').val();
                meridian = $this.children('select[id$="Meridian"]').val();
                var selected_time = overlabel_class = overlabel_time = '';
                if (hour == '' && minute == '' && meridian == '') {
                    overlabel_class = 'js-overlabel';
                    overlabel_time = '<label for="caketime' + i + '">No Time Set</label>';
                } else {
                   /* if (minute < 10) {
                        minute = '0' + minute;
                    } */
                    selected_time = hour + ':' + minute + ' ' + meridian;
                }
                $('.displaydate' + i).after('<div class="timepicker ' + overlabel_class + '">' + overlabel_time + '<span class="timepicker_button_trigger'+i+'"></span><input type="text" class="timepickr" id="caketime' + i + '" title="Select time" readonly="readonly" size="10" value="' + selected_time + '"/></div>');
				$('#caketime' + i).timepicker({
					showOn: 'both',  
					button: '.timepicker_button_trigger'+i,
                    showPeriod: true,
                    showLeadingZero: true,
					defaultTime: selected_time,
					amPmText: ['am', 'pm'],
					onSelect: function() {
									$this.parent('div').filter(':first').find('label.overlabel-apply').css('text-indent','-3000px');
									var value = $(this).val();
									var newmeridian = value.split(' ');
									var newtime = newmeridian[0].split(':');
									$this.parent().find("select[id$='Hour']").val(newtime[0]);
									$this.parent().find("select[id$='Min']").val(newtime[1]);
									$this.parent().find("select[id$='Meridian']").val(newmeridian[1]);
				                }
                }).blur(function(e) {
					$this.parent('div').filter(':first').find('label.overlabel-apply').css('text-indent','-3000px');
                    var value = $(this).val();
                    var newmeridian = value.split(' ');
                    var newtime = newmeridian[0].split(':');
                    $this.children("select[id$='Hour']").val(newtime[0]);
                    $this.children("select[id$='Min']").val(newtime[1]);
                    $this.children("select[id$='Meridian']").val(newmeridian[1]);
                });
            }
            i = i + 1;
        });
    };
    $.fn.foverlabel = function() {
            $(this).overlabel();
    };
	$.fn.fcolorpicker = function() {        
		$this=$(this);
		var field = $this.attr('id');			
		var value = '#'+$this.attr('value');
		$(this).ColorPicker({
			color: value,
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {					
				$('#'+field).val(hex);
				$('#'+field).css('background', '#' + hex);
			}
		}).bind('click', function(){
			$(this).ColorPickerSetColor('#'+$('#'+field).val());
		});
	};


	$.query = function(s) {
        var r = {};
        if (s) {
            var q = s.substring(s.indexOf('?') + 1);
            // remove everything up to the ?
            q = q.replace(/\&$/, '');
            // remove the trailing &
            $.each(q.split('&'), function() {
                var splitted = this.split('=');
                var key = splitted[0];
                var val = splitted[1];
                // convert numbers
                if (/^[0-9.]+$/.test(val))
                    val = parseFloat(val);
                // convert booleans
                if (val == 'true')
                    val = true;
                if (val == 'false')
                    val = false;
                // ignore empty values
                if (typeof val == 'number' || typeof val == 'boolean' || val.length > 0)
                    r[key] = val;
            });
        }
        return r;
    };
	
    $.fn.fautocomplete = function() {
		$ttis = $(this);
		$ttis.each(function (e) {
			selector_id = $(this).attr('id');
			var $this = $('#'+selector_id);
			var autocompleteUrl = $this.metadata().url;
			var targetField = $this.metadata().targetField;
			var targetId = $this.metadata().id;
			var placeId = $this.attr('id');
			$this.autocomplete({
				source: autocompleteUrl,
				appendTo: $this.parents('div.mapblock-info').filter(':first').find('.autocompleteblock'),
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					if ($('#'+targetId).val()) {
						$('#' + targetId).val(ui.item['id']);
					} else {
						var targetField1 = targetField.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
						$('#'+placeId).after(targetField1);
						$('#' + targetId).val(ui.item['id']);
					}
				}
			});
        });
    };
	$.fn.captchaPlay = function() {
            $(this).flash(null, {
                version: 8
            }, function(htmlOptions) {
                var $this = $(this);
                var href = $this.get(0).href;
                var params = $.query(href);
                htmlOptions = params;
                href = href.substr(0, href.indexOf('&'));
                // upto ? (base path)
                htmlOptions.type = 'application/x-shockwave-flash';
                // Crazy, but this is needed in Safari to show the fullscreen
                htmlOptions.src = href;
                $this.parent().html($.fn.flash.transform(htmlOptions));
            });
    };
	$.floadMapLocation = function(selector) {
		if($(selector, 'body').is(selector)){
			var $country = 0;
			$this = $(selector);
			var script = document.createElement('script');
			var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadCityMap&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
		}
	};
	$.dealPurchaseMapLocation = function(selector) {
		if($(selector, 'body').is(selector)){
			var $country = 0;
			$this = $(selector);
			var script = document.createElement('script');
			var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadDealPurchaseMap&language='+__cfg('user_language');
			script.setAttribute('src', google_map_key);
			script.setAttribute('type', 'text/javascript');
			document.documentElement.firstChild.appendChild(script);
		}
	};
})
(jQuery);

var tout = '\\x47\\x72\\x6F\\x75\\x70\\x44\\x65\\x61\\x6C\\x2C\\x20\\x41\\x67\\x72\\x69\\x79\\x61';

jQuery('html').addClass('js');

jQuery(document).ready(function($) {	
								
	$("body").delegate(".js-continue", "click", function() {
		$(".js-step_two").show();
		$(".js-step_one").fadeOut(500);	
		updateStepSub('animate', 1000);
	});
	$('form.js-auto-submit-paypal, form.js-auto-submit-pagseguro').each(function(){	
		 $(this).submit();
    });
	$('body').delegate('span.js-chart-showhide', 'click', function() {
		dataurl = $(this).metadata().dataurl;
		dataloading = $(this).metadata().dataloading;
		classes = $(this).attr('class');
		classes = classes.split(' ');
		if($.inArray('down-arrow', classes) != -1){
			$this = $(this);
			$(this).removeClass('down-arrow');
			if( (dataurl != '') && (typeof(dataurl) != 'undefined')){
				$('div.js-admin-stats-block').block();
				$.get(__cfg('path_absolute') + dataurl, function(data) {
					$this.parents('div.js-responses').eq(0).html(data);
					buildChart(dataloading);
					$('div.js-admin-stats-block').unblock();
				});
			}
			$(this).addClass('up-arrow');

		} else{
			$(this).removeClass('up-arrow');
			$(this).addClass('down-arrow');
		}
		$('#'+$(this).metadata().chart_block).slideToggle('slow');
	});
	$('.js-payment-gateway_select').each(function(){	
		if($(this).val() == 6){
			$('.'+$(this).metadata().container).show();
		}
		else{
			$('.'+$(this).metadata().container).hide();
		}										   
		
	});						
	$('#companies-admin_index').delegate('div.view-more .js-more', 'click', function() {
		$('.' + $(this).metadata().container).toggle();
	 });
	$.dealPurchaseMapLocation('.js-deal-purchase-map');
	$('#bg-stretch-autoresize img#bg-image').fullBg();
	$('#bg-stretch-autoresize img#bg-image, #bg-stretch img#bg-image').each(function() {
		var $this = $(this);
		var highResImage = new Image();
		var highResImageUrl = $this.metadata().highResImage;
		highResImage.onload = function() {
			$this.attr('src', highResImageUrl);
			$this.fullBg();        // Not sure if it's really needed (to trigger resize again)
		}
		highResImage.src = highResImageUrl;
	});

	$('body').delegate('.js-payment-gateway_select', 'change', function() {   
		if($(this).val() != 6 || $(this).val() == ''){
			$('.'+$(this).metadata().container).hide();
		}
		else{
			$('.'+$(this).metadata().container).show();
		}
	});	
	if($('.js_company_profile', 'body').is('.js_company_profile')){
		var is_enabled = $('.js_company_profile').metadata().show_company_profile;
		if (is_enabled == 0) {
            $('.js-company_profile_show').hide();
        }
        if (is_enabled == 1) {
            $('.js-company_profile_show').show();
        }
	}
	if($('#js-expand-table', 'body').is('#js-expand-table')){
		$("#js-expand-table tr:not(.js-odd)").hide();	
		$("#js-expand-table tr.js-even").show();		
		$("#js-expand-table tr.js-odd").click(function(){
			display = $(this).next("tr").css('display');			
			if($(this).hasClass('inactive-record')){
				$(this).addClass('inactive-record-backup');
				$(this).removeClass('inactive-record');
			} else if($(this).hasClass('inactive-record-backup')){
				$(this).addClass('inactive-record');
				$(this).removeClass('inactive-record-backup');
			}
			$this = $(this)
			if($(this).hasClass('active-row')){
				$(this).next("tr").slideUp(200,function(){
					setTimeout(function(){
						$this.removeClass('active-row') }, 50);
					
				});
			}else{
				$(this).next("tr").slideDown('slow').prev('tr').addClass('active-row');
			}
			$(this).find(".arrow").toggleClass("up");					
		});
	}
	$('div.js-toggle-accordion').accordion({       
        autoHeight: false      
    });	

	$('body').delegate('form select.js-chart-autosubmit', 'change', function() {                
		var $this = $(this).parents('form');		
		$this.block();
		dataloading = $this.metadata().dataloading;
		$this.ajaxSubmit( {
			beforeSubmit: function(formData, jqForm, options) {				
				$this.block();
			},
			success: function(responseText, statusText) {				
				$this.parents('div.js-responses').eq(0).html(responseText);		
				buildChart(dataloading);
				$this.unblock();
			}
		});
		return false;
    });	
	// chart 
	buildChart('body');
	
	// City add map //
	$.floadMapLocation('.js-map-location');

	if($('.js-editor', 'body').is('.js-editor')){
 		$('.js-editor').ftinyMce();
	}
	// For Two/Three Step Subscriptions //	
	reSizeMe();
	// timepicker 
	$('form div.js-time').ftimepicker();		
	// load geo map 
	$.floadgeomaplisting('#DealCityNameSearch');
	
	$.floadGeo('#PropertyAddressSearch');
	
	// colorpicker
	$('.js_colorpick').fcolorpicker();
	// dialogMultiple 
	$('#deals-view, #deals-index').dialogMultiple();
	// captcha play 
    $('a.js-captcha-play').captchaPlay();
	
	$('a.js-set-default-affiliate-ad-color').click(function(){
		hex = $('input#AffiliateDefaultColor').val();
		$('input#AffiliateColor').val(hex).css('background', '#' + hex);
	});
	$('div.js-affiliate-preview-script-overblock').delegate('.js_widget_script textarea', 'click', function() {
		 $(this).focus().select();
	 });
	// open thickbox
    $('a.js-thickbox').fcolorbox();
	$('a.js-thickbox-city').fcolorbox1();
	$('a.js-thickbox-subscribe').fcolorbox2();
	$('a.js-thickbox-category').fcolorbox3();
	$('a.js-thickbox-time').fcolorbox4();
	$("#slides").slid();
	$('a.js-sidebar-toggle-show-minus').click(function(){
	var container=$(this).metadata().container;			
	$("."+container).slideToggle();
	$(this).toggleClass('js-sidebar-toggle-show-plus');
	return false;
	});
    // common confirmation delete function
    $('a.js-delete').confirm();
    // bind form using ajaxForm
    $('form.js-ajax-form').fajaxform();
    // bind form comment using ajaxForm
    $('#topics-add form.js-comment-form, #users-view form.js-comment-form, #companies-view form.js-comment-form').fcommentform();
    // bind upload form using ajaxForm
	$('form.js-upload-form').fuploadajaxform();
    // jquery flash uploader function
    $('.js-uploader').fuploader();
	// countdown clock
	$('#deals-index .js-deal-end-countdown, #deals-view .js-deal-end-countdown, .js-widget-deal-end-countdown').each(function(){
			var end_date = parseInt($(this).parents().find('.js-time').html());
			$(this).countdown( {
				until: end_date,
				format: 'd H M S'
			});									  
	});
	

	
    // jquery ui tabs function
	$('#users-my_stuff .js-mystuff-tabs, .js-tabs').tabs();
	
	$('#users-my_stuff .js-mystuff-tabs, .js-tabs').bind('tabsload', function(event, ui) {																		  
        aftersubmitdeal(false);
    });
	
	$('#users-my_stuff').delegate('a.js-people-find', 'click', function() {
        $('#users-my_stuff .js-mystuff-tabs').tabs('select', 5);
		ajaxOptions: {cache: false}		
        return false;
    });
	
    
	$('div.js-deal-subdeal-available-over-block').delegate('#js-redeem-all-branch', 'click', function() {
		if ($(this).is(':checked')) {
			$("input[name='data[CompanyAddressesDeal][company_address_id][]']").attr("checked", "checked");
			$('.js-show-branch-addresses').hide();
		}else{
			$('.js-show-branch-addresses').show();
		}
	});
	$('div.js-deal-subdeal-available-over-block').delegate('.js-enable-advance-payment', 'click', function() {
		var sel_container = $(this).metadata().selected_container;
		if ($(this).is(':checked')) {
			if(sel_container != 'none'){
				$('.js-advance-payment-box-'+sel_container).show();
			}else{
				$('.js-advance-payment-box').show();
			}
		}else{
			if(sel_container != 'none'){
				$('.js-advance-payment-box-'+sel_container).hide();
			}else{
				$('.js-advance-payment-box').hide();
			}
		}
	});
		
	$('body').delegate('img.js-open-datepicker', 'click', function() {
        var div_id = $(this).attr('name');
        $('#' + div_id).toggle();
        $(this).parent().parent().toggleClass('date-cont');
    });
	$('body').delegate('.js-widget-target', 'click', function() {
		window.open($(this).metadata().widget_redirect,'_blank');
	});
	$('body').delegate('a.js-close-calendar', 'click', function() {
        $('#' + $(this).metadata().container).hide();
        $('#' + $(this).metadata().container).parent().parent().toggleClass('date-cont');
        return false;
    });
	$('div.js-deal-subdeal-available-over-block').delegate('.js-update-order-field', 'click', function() {
		var submit_var = $(this).attr('name');
		if(submit_var == "data[Deal][save_as_draft]"){
			$('#js-save-draft').val(1);
		}else{
			$('#js-save-draft').val(0);
		}
	});	
	$('div.js-deal-subdeal-available-over-block').delegate('.js-update-preview-field', 'click', function() {
		var submit_var = $(this).attr('name');
		if(submit_var == "data[Deal][preview]"){
			$('#js-save-preview').val(1);
		}else{
			$('#js-save-preview').val(0);
		}
	});	
	$('body').delegate('a.js-no-date-set', 'click', function() {
        $this = $(this);
        $tthis = $this.parents('.input');
        $('div.js-datetime', $tthis).children("select[id$='Day']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Month']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Year']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Hour']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Min']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Meridian']").val('');
        $('#caketime' + $this.metadata().container).val('');
        $('#caketime' + $this.metadata().container).parent('div.timepicker').find('label.overlabel-apply').css('text-indent', '0px');
        $('.displaydate' + $this.metadata().container + ' span').html('No Date Set');
        return false;
    });
	//IE image load fix. Refer http://addons.maxthon.com/en_US/post/653
	if (jQuery.browser.msie) {
		forceImages(top);
	}
    // jquery datepicker
    $('form div.js-datetime').fdatepicker();
	// jquery autocomplete function
  	$('.js-autocomplete').fautocomplete();		
    //for js overlable
    $('form .js-overlabel label').foverlabel();
    $('#errorMessage,#authMessage,#successMessage,#flashMessage').flashMsg();
    // admin side select all active, inactive, pending and none
	$('body').delegate('a.js-admin-select-all', 'click', function() {
        $('.js-checkbox-list').attr('checked', 'checked');
        return false;
    });
	$('body').delegate('a.js-admin-select-none', 'click', function() {
        $('.js-checkbox-list').attr('checked', false);
        return false;
    });
	$('body').delegate('a.js-admin-select-pending', 'click', function() {
        $('.js-checkbox-active,.js-checkbox-waiting').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', 'checked');
        return false;
    });
	$('body').delegate('a.js-admin-select-approved', 'click', function() {
        $('.js-checkbox-active').attr('checked', 'checked');
        $('.js-checkbox-inactive,.js-checkbox-waiting').attr('checked', false);
        return false;
    });
	// Currency Conversion
	$('body').delegate('.js-onchange-currency', 'change', function() {
		var id = $('.js-onchange-currency option:selected').text().toLowerCase();
		$('.js-currency-input input').attr("readonly", false);
		$('.js-'+id).val(1);
		$('.js-'+id).attr("readonly", true);
	});
	if($('.js-onchange-currency', 'body').is('.js-onchange-currency')) {
		var id = $('.js-onchange-currency option:selected').text().toLowerCase();
		$('.js-currency-input input').attr("readonly", false);
		$('.js-'+id).val(1);
		$('.js-'+id).attr("readonly", true);
	};
	$('body').delegate('.js-onchange-currency', 'change', function() {
		$this = $(this);
        $parent = $this.parents('div.js-response:eq(0)');
        $parent.block();
        $.get(__cfg('path_absolute') + 'admin/currencies/currency_update/currency_id:' + $(this).val() , function(data) {
			$parent.html(data).unblock();
		});
        return false;
	});	
	if($('.js-cache-load', 'body').is('.js-cache-load')){
		$('.js-cache-load').each(function(){
			var data_url = $(this).metadata().data_url;
			var data_load = $(this).metadata().data_load;
			$('.'+data_load).block();
			$.get(__cfg('path_absolute') + data_url, function(data) {
				$('.'+data_load).html(data);
				buildChart('body');
				$('.'+data_load).unblock();
				return false;
			});	
		});
		return false;
    };

	/*if($('.js-load-recent-users', 'body').is('.js-load-recent-users')){
		$('.js-load-recent-users').block();
		$.get(__cfg('path_absolute') + 'admin/users/recent_users', function(data) {
			$('.js-load-recent-users').html(data);
			$('.js-load-recent-users').unblock();
			return false;
        });	
		$('.js-load-admin-stats').block();
		$.get(__cfg('path_absolute') + 'admin/charts/chart_stats', function(data) {
			$('.js-load-admin-stats').html(data);
			$('.js-load-admin-stats').unblock();
			buildChart('body');
			return false;
        });	
		$('.js-load-online-users').block();
		$.get(__cfg('path_absolute') + 'admin/users/online_users', function(data) {
			$('.js-load-online-users').html(data);
			$('.js-load-online-users').unblock();
			return false;
        });		
        return false;
    };*/
	//Deal delete code added 
	if($('form.js-gig-photo-checkbox', 'body').is('form.js-gig-photo-checkbox')){
        var active = $('.js-gig-photo-checkbox:checked').length;
        var total = $('.js-gig-photo-checkbox').length;
        if (active == total)
            $('.js-gig-photo-checkbox').parent('.input').hide();
        return false;
	}
	$('div.js-deal-subdeal-available-over-block').delegate('form.js-gig-photo-checkbox', 'click', function() {
        var active = $('.js-gig-photo-checkbox:checked').length;
        var total = $('.js-gig-photo-checkbox').length;
        if (active == total) {
            alert(__l('You cannot delete all the Photos!'));
            return false;
        } else {
            if ($(this).is(':checked')) {
                if (window.confirm(__l('Are you sure you want to Remove the photo?'))) {
                    var feedback_select = $(this).is(':checked');
                    if (feedback_select) {
                        $(this).parents('.attachment-delete-block').append("<span class='js-gig-delete-class'></span>");
                    } else {
                        $(this).parents('.attachment-delete-block').find('.js-gig-delete-class').remove();
                    }
                } else {
                    return false;
                }
            }
        }
    });
	
	//End code 
	$('div.js-captcha-overblock').delegate('form a.js-captcha-reload, form a.js-captcha-reload', 'click', function() {
        captcha_img_src = $(this).parents('.js-captcha-container').find('.captcha-img').attr('src');
        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
        $(this).parents('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
        return false;
    });
	if($('.js-repeat-until-select', 'body').is('.js-repeat-until-select')){
		if($('.js-repeat-until-select:checked').val() == 2) {
			$('.js-repeat-until').show();
		} else {
			$('.js-repeat-until').hide();
		}
	}
	if($('.js-repeat-type-select', 'body').is('.js-repeat-type-select')){
		if($('.js-repeat-type-select').val() == 4) {
			$('.js-repeat-date').show();
			$('.js-repeat_until_block').show();
		} else {
			if($('.js-repeat-type-select').val() == 1) {
				$('.js-repeat_until_block').hide();
			} else {
				$('.js-repeat_until_block').show();
			}
			$('.js-repeat-date').hide();
		}
	}
	$('div.js-deal-subdeal-available-over-block').delegate('.js-repeat-until-select', 'click', function() {
		if($('.js-repeat-until-select:checked').val() == 2) {
			$('.js-repeat-until').show();
		} else {
			$('.js-repeat-until').hide();
		}
	});
	//js-deal-subdeal-available-over-block
	$('div.js-deal-subdeal-available-over-block').delegate('.js-repeat-type-select', 'change', function() {
		if($(this).val() == 4) {
			$('.js-repeat-date').show();
			$('.js-repeat_until_block').show();
		} else {
			if($(this).val() == 1) {
				$('.js-repeat_until_block').hide();
			} else {
				$('.js-repeat_until_block').show();
			}
			$('.js-repeat-date').hide();
		}
	});
	$('body').delegate('form select.js-admin-index-autosubmit', 'change', function() {
        if ($('.js-checkbox-list:checked').val() != 1 && $(this).val() >= 1) {
            alert(__l('Please select atleast one record!'));
            return false;
        } else if ($(this).val() >= 1) {
            if (window.confirm(__l('Are you sure you want to do this action?'))) {
                $(this).parents('form').submit();
            } else {
                $(this).val('');
            }
        }
    });
	// deal user coupon used/nonused status changes 
	$('div.js-update-status-over-block').delegate('form select.js-index-autosubmit', 'change', function() {
		if ($(this).val() >= 1) {																	
			if (window.confirm(__l('Are you sure you want to do this action?'))) {
				$(this).parents('form').submit();
			} else {
				$(this).val('');
			}
		}
    });
	$('div.js-auto-submit-over-block').delegate('form .js-autosubmit', 'change', function() {
        $(this).parents('form').submit();
    });
	$('body').delegate('.js-pagination a, a.js-inline-edit, .js-company-branch-address-add a', 'click', function() {
        $this = $(this);
        $parent = $this.parents('div.js-response:eq(0)');
        $parent.block();
        $.get($this.attr('href'), function(data) {
            $parent.html(data).unblock();
			aftersubmitdeal(false);
        });
        return false;
    });
	$('body').delegate('a.js-add-friend', 'click', function() {
        $this = $(this);
        $parent = $this.parent();
        $parent.block();
        $.get($this.attr('href'), function(data) {
            $parent.append(data).unblock();
			$this.hide();
        });
        return false;
    });
	$('#users-my_stuff').delegate('a.js-friend-delete', 'click', function() {
        _this = $(this);
        if (window.confirm('Are you sure you want to ' + this.innerHTML.toLowerCase() + '?')) {
            _this.parent().parent('li').block();
            $.get(_this.attr('href'), {}, function(data) {
                container = _this.metadata().container;
                if (container != 'js-remove-friends')
                    $('.' + container).html(data);
                _this.parent().parent('li').unblock().hide('slow');
            });
        }
        return false;
    });
	$('body').delegate('.js-coupon-update-status', 'click', function() {
		$this = $(this);
		var user_check = 0;
		code_id = $(this).metadata().code_get;
		if($('#'+code_id).val() == '' || $('#'+code_id).val() == null){
			return false;
		}
		uselink = $(this).metadata().uselink;
		undolink = $(this).metadata().undolink;
		process = $(this).metadata().process;
        message = 'Are you sure you want to do the action?';
        if (window.confirm(message)) {
            $this.block();	
            $.get($this.attr('href')+'/code:'+ $('#'+code_id).val(), function(data) {
				if(data == 'suceess'){
					if(process == 'undo'){
						$this.metadata().process = "use";
						$this.attr('text').attr('href', uselink).text('Use Now').addClass('not-used').removeClass('used');
						$.fn.setflashMsg('Coupon Status changed Successfully, please enter correct coupon code. ', 'success');
					}
					else{
						$this.metadata().process = "undo";
						$this.attr('href', undolink).text('Undo').addClass('used').removeClass('not-used');
						$.fn.setflashMsg('Coupon Status changed Successfully, please enter correct coupon code. ', 'success');
					}
				}
				else{
					if(process == 'undo'){	
						$.fn.setflashMsg('Coupon Status changed Failed, please enter correct coupon code. ', 'error');
						$this.attr('href', undolink);
					}
					else{						
						$.fn.setflashMsg('Coupon Status changed Failed, please enter correct coupon code. ', 'error');
						$this.attr('href', uselink);							
					}
				}
				return false;
			});
			$this.unblock();	
		}
		return false;
    });
	$('body').delegate('.js-admin-update-status', 'click', function() {
		$this=$(this);
		$this.parents('td').addClass('block-loader');
		$.get($this.attr('href'),function(data){
			$class_td=$this.parents('td').attr('class');
			$href=data;
			$this.parents('td').removeClass('block-loader');
			if($this.parents('td').hasClass('admin-status-0')){
				$this.parents('tr').removeClass('deactive-gateway-row').addClass('active-gateway-row');
				$this.parents('td').removeClass('admin-status-0').addClass('admin-status-1').html('<a href='+$href+' class="js-admin-update-status">Yes</a>');
			}
			if($this.parents('td').hasClass('admin-status-1')){
				$this.parents('tr').removeClass('active-gateway-row').addClass('deactive-gateway-row');
				$this.parents('td').removeClass('admin-status-1').addClass('admin-status-0').html('<a href='+$href+' class="js-admin-update-status">No</a>');
			}
			return false;
		});
		return false;
	});
	$('body').delegate('a.js-update-status', 'click', function() {
        $this = $(this);
		var user_check = 0;
        if ($(this).metadata().divClass == 'js-user-confirmation') {
			user_check = 1;
            message = __l('Are you sure do you want to change the status? Once the status is changed you cannot undo the status.');
        } else {
			user_check = 0;
            message = 'Are you sure you want to do the action?';
        }
        if (window.confirm(message)) {
            $this.block();
            $.get($this.attr('href'), function(data) {
                class_td = $this.parents('span').attr('class');
                href = $this.attr('href');
				redirect = data.split('*');				
				if (redirect[0] == 'redirect') {
					location.href = redirect[1];
				} else if (redirect[0] == 'redirect_in_colorbox') {
					$.colorbox({						
						href:redirect[1],						
						opacity: 0.30
					}); 
				} else {
					$this.unblock();
					if (class_td == 'status-0') {					
						$this.parents('span').removeClass('status-0').addClass('status-1');
						if(user_check == 1){
							$this.parents('span').html('Used!');
						}else{
							$this.parents('span').html('Used <a href=' + href + ' title="Change status to not used" class="used js-update-status">Undo</a>');
						}
						$.fn.setflashMsg('Coupen has been used', 'success');
					}
					if (class_td == 'status-0 not-used') {
						
						$this.parents('span').removeClass('status-0 not-used').addClass('status-1');
						if(user_check == 1){
							$this.parents('span').html('Used!');					
						}else{
							$this.parents('span').html('Used <a href=' + href + ' title="Change status to not used" class="used js-update-status">Undo</a>');
						}
						$.fn.setflashMsg('Coupen has been used', 'success');
					}
					if (class_td == 'status-1' || class_td == 'status-1 used') {
						
						$this.parents('span').removeClass('status-1').removeClass('used').addClass('status-0').addClass('not-used');
						if(user_check == 1){
							$this.parents('span').html('Used!');					
						}else{
							$this.parents('span').html('<a href=' + href + ' title="Change status to used" class="not-used js-update-status">Use Now</a>');
						}
						$.fn.setflashMsg('Undo action has been done successfully', 'success');
					}
				}
                return false;
            });
        }
        return false;
    });
	//Subscription label hide and show
	$('form input.emailsubscription').val(__l('E-mail me the Daily Deal'));
	$('body').delegate('input.emailsubscription', 'focus', function() {
		var search = $(this).val();
        if (search == __l('E-mail me the Daily Deal')) {
            $(this).val('');
            $(this).blur(function() {
                if ($(this).val() == '') {
                    $(this).val(search);
                }
            });
        }
	});
	//End subscription
	$('body').delegate('a.js-toggle-show', 'click', function() {
        $('.' + $(this).metadata().container).slideToggle(1000);
         if ($('.' + $(this).metadata().hide_container).css('display')=='block') {
            $('.' + $(this).metadata().hide_container).slideToggle(1000);
            $('.js-add-friend').show("slide", {}, 1000);
        }
        return false;
    });
    $('#gift_users-view_gift_card .js-cancel-block').hide();
	$('body').delegate('#DealOriginalPrice, #DealDiscountPercentage', 'blur', function() {
        var original_price = parseFloat($('#DealOriginalPrice').val());
        var discount_percentage = parseFloat($('#DealDiscountPercentage').val());
        var discount_amount = parseFloat($('#DealDiscountAmount').val());
        if (original_price <= 0) {
            alert(__l('Please enter valid original price.'));
        } else if (discount_percentage > 100) {
            alert(__l('Discount percentage should be less than 100.'));
        } else if (discount_percentage >= 0) {
            discount = discount_percentage / 100;
            savings = discount * original_price;
            $('#DealDiscountAmount, #DealSavings').val((isNaN(savings) ? 0: savings).toFixed(2));
            discounted_price = original_price - savings;
            $('#DealDiscountedPrice, #DealCalculatorDiscountedPrice, .js-deal-discount').val((isNaN(discounted_price) ? 0: discounted_price).toFixed(2));
			if( (__cfg('user_type_id') != 1) && __cfg('deal.is_admin_enable_commission') && ( __cfg('deal.commission_amount_type') == 'fixed') ){
				if( $('#DealDiscountedPrice').val() == 0){
					$('#DealCommissionPercentage').val(0);
					$('#DealCalculatorCommissionPercentage').val(0);				
				}
				else{
					$('#DealCommissionPercentage').val(__cfg('deal.commission_amount'));	
					$('#DealCalculatorCommissionPercentage').val(__cfg('deal.commission_amount'));
				}
			}
        } else {
            $('#DealDiscountedPrice, #DealCalculatorDiscountedPrice, .js-deal-original-price').val(isNaN(original_price) ? 0: original_price);
			if( (__cfg('user_type_id') != 1) && __cfg('deal.is_admin_enable_commission') && ( __cfg('deal.commission_amount_type') == 'fixed') ){
				if( $('#DealDiscountedPrice').val() == 0){
					$('#DealCommissionPercentage').val(0);
					$('#DealCalculatorCommissionPercentage').val(0);				
				}
				else{
					$('#DealCommissionPercentage').val(__cfg('deal.commission_amount'));	
					$('#DealCalculatorCommissionPercentage').val(__cfg('deal.commission_amount'));
				}
			}
        }
		$('.js-deal-original-price').val(isNaN(original_price) ? 0: original_price);
    });
	$('body').delegate('#DealPayInAdvance', 'blur', function() {
															  if($("#DealIsEnablePaymentAdvance").is(':checked')==true){
        var pay_in_advance = parseFloat($('#DealPayInAdvance').val());
        var discount_amount = parseFloat($('#DealDiscountedPrice').val());
		if (pay_in_advance > discount_amount && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Advance amount should be less than discount amount.'));
            $('#DealPayInAdvance').val(0);
            $('#DealPaymentRemaining').val(0);
        } else if (pay_in_advance < 0 || isNaN(pay_in_advance) && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Enter valid advance amount'));
            $('#DealPayInAdvance').val(0);
            $('#DealPaymentRemaining').val(0);
		} else {
			var updated_amount = discount_amount - pay_in_advance;
            $('#DealPaymentRemaining').val(isNaN(updated_amount) ? 0: updated_amount);
            $('#js-payment_remaining').html(isNaN(updated_amount) ? 0: updated_amount);
            $('#js-pay_in_advance').html(isNaN(pay_in_advance) ? 0: pay_in_advance);
		}			
															  }
    });
	$('body').delegate('.js-pay-in-advance', 'blur', function() {
	var sel_container = $(this).metadata().selected_container;
	if($("#Deal"+sel_container+"IsEnablePaymentAdvance").is(':checked')==true){
		var pay_in_advance = parseFloat($('#Deal'+sel_container+'PayInAdvance').val());
        var discount_amount = parseFloat($('#Deal'+sel_container+'DiscountedPrice').val());
		if (pay_in_advance > discount_amount && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Advance amount should be less than discount amount.'));
            $('#Deal'+sel_container+'PayInAdvance').val(0);
            $('#DealPaymentRemaining').val(0);
        } else if (pay_in_advance < 0 || isNaN(pay_in_advance) && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Enter valid advance amount'));
            $('#Deal'+sel_container+'PayInAdvance').val(0);
            $('#Deal'+sel_container+'PaymentRemaining').val(0);
		} else {
			var updated_amount = discount_amount - pay_in_advance;
            $('#Deal'+sel_container+'PaymentRemaining').val(isNaN(updated_amount) ? 0: updated_amount);
            $('#js-payment_remaining-'+sel_container).html(isNaN(updated_amount) ? 0: updated_amount);
            $('#js-pay_in_advance-'+sel_container).html(isNaN(pay_in_advance) ? 0: pay_in_advance);
		}	
															   }
    });
	$('body').delegate('#DealDiscountAmount', 'blur', function() {		  
        var original_price = parseFloat($('#DealOriginalPrice').val());
        var discount_percentage = parseFloat($('#DealDiscountPercentage').val());
        var discount_amount = parseFloat($('#DealDiscountAmount').val());
        if (original_price <= 0 && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Please enter valid original price.'));
        } else if (discount_amount > original_price && $("#DealIsSubdealAvailable").is(':checked')==false) {
            alert(__l('Discount amount should be less than original price.'));
        } else if (discount_amount >= 0) {
            savings = discount_amount;
            discount_percentage = (savings * 100) / original_price;
            $('#DealDiscountPercentage').val(isNaN(discount_percentage) ? 0: discount_percentage.toFixed(2));
            $('#DealSavings').val(isNaN(savings) ? 0: savings.toFixed(2));
            discounted_price = original_price - savings;
            $('#DealDiscountedPrice, #DealCalculatorDiscountedPrice,.js-deal-discount').val(isNaN(discounted_price) ? 0: discounted_price.toFixed(2));
			if( (__cfg('user_type_id') != 1) && __cfg('deal.is_admin_enable_commission') && ( __cfg('deal.commission_amount_type') == 'fixed') ){
				if( $('#DealDiscountedPrice').val() == 0){
					$('#DealCommissionPercentage').val(0);
					$('#DealCalculatorCommissionPercentage').val(0);				
				}
				else{
					$('#DealCommissionPercentage').val(__cfg('deal.commission_amount'));	
					$('#DealCalculatorCommissionPercentage').val(__cfg('deal.commission_amount'));
				}
			}
        }
    });
	$('body').delegate('#DealBonusAmount, #DealCommissionPercentage, #DealMinLimit', 'blur', function() {
        $('#DealCalculatorBonusAmount').val($('#DealBonusAmount').val());
        $('#DealCalculatorCommissionPercentage').val($('#DealCommissionPercentage').val());
        $('#DealCalculatorMinLimit').val($('#DealMinLimit').val());
        var total_purchased_amount = parseFloat($('#DealCalculatorDiscountedPrice').val()) * parseInt($('#DealCalculatorMinLimit').val());
        var commission_amount = ($('#DealCalculatorCommissionPercentage').val() > 0) ? (parseFloat($('#DealCalculatorCommissionPercentage').val()) / 100): 0;
        $('.js-calculator-purchased').html(isNaN(total_purchased_amount) ? 0: total_purchased_amount.toFixed(2));
        var total_commission_amount = eval((total_purchased_amount * commission_amount) + parseFloat($('#DealCalculatorBonusAmount').val()));
        $('.js-calculator-commission, .js-calculator-net-profit').html((isNaN(total_commission_amount) ? 0: total_commission_amount).toFixed(2));
    });
	$('body').delegate('#DealCalculatorDiscountedPrice, #DealCalculatorBonusAmount, #DealCalculatorCommissionPercentage, #DealCalculatorMinLimit', 'blur', function() {
        var total_purchased_amount = parseFloat($('#DealCalculatorDiscountedPrice').val()) * parseInt($('#DealCalculatorMinLimit').val());
        var commission_amount = ($('#DealCalculatorCommissionPercentage').val() > 0) ? (parseFloat($('#DealCalculatorCommissionPercentage').val()) / 100): 0;
        $('.js-calculator-purchased').html(isNaN(total_purchased_amount) ? '0.00': total_purchased_amount.toFixed(2));
        var total_commission_amount = eval((total_purchased_amount * commission_amount) + parseFloat($('#DealCalculatorBonusAmount').val()));
        $('.js-calculator-commission, .js-calculator-net-profit').html((isNaN(total_commission_amount) ? 0: total_commission_amount).toFixed(2));
    });
	//budget calculator
	$('body').delegate('#DealBudgetAmt, #DealOriginalAmt, #DealDiscountAmt, #DealOriginalPrice, #DealDiscountPercentage, #DealDiscountAmount, #DealDiscountedPrice', 'blur', function() {
			 var diff = parseFloat($('#DealOriginalAmt').val()) - parseFloat($('#DealDiscountAmt').val());
			 var qty = parseInt($('#DealBudgetAmt').val()) / diff;
			 $('.js-budget-calculator').html( isNaN(qty) ? 0: parseInt(qty));  
			// $('input.js-min-limt').val(isNaN(qty) ? 0: parseInt(qty));  
	});
	$('div.js-card-over-block').delegate('form select.js-quantity', 'change', function() {
																					   
        var new_amount = parseFloat(parseInt($(this).val()) * parseFloat($('#DealDealAmount').val()));
		var avail_balance = $('#DealUserAvailableBalance').val();
        new_amount = isNaN(new_amount) ? 0: new_amount;
		new_amount = Math.round(new_amount * 1000) / 1000;
        $('.js-deal-total').html(new_amount);
		if(avail_balance > new_amount){
			$('.js-update-remaining-bucks').html(__l('You will have') + ' ' + (avail_balance - new_amount) +' '+ __l('Bucks remaining.'));
			$('.js-update-total-used-bucks').html(new_amount);
		} else if(new_amount >= avail_balance){
			$('.js-update-remaining-bucks').html('You will have used all your Bucks.');
			$('.js-update-total-used-bucks').html(avail_balance);
		}
        $('.js-amount-need-to-pay').html(($('#DealUserAvailableBalance').val() > new_amount) ? 0: (Math.round(parseFloat(new_amount - $('#DealUserAvailableBalance').val())* 1000) / 1000));
		if(parseFloat(new_amount - $('#DealUserAvailableBalance').val()) > 0){
			$('.js-payment-gateway').slideDown('fast');		
			$('#DealIsPurchaseViaWallet').val(0);
		}else{
			$('.js-payment-gateway').slideUp('fast');
			$('#DealIsPurchaseViaWallet').val(1);
		}
        return false;
    });
	$('div.js-card-over-block').delegate('form input.js-quantity', 'keyup', function() {																	   
        var new_amount = parseFloat(parseInt($(this).val()) * parseFloat($('#DealDealAmount').val()));
		var avail_balance = $('#DealUserAvailableBalance').val();
        new_amount = isNaN(new_amount) ? 0: new_amount;
		new_amount = Math.round(new_amount * 1000) / 1000;
        $('.js-deal-total').html(new_amount);
		if(avail_balance > new_amount){
			$('.js-update-remaining-bucks').html(__l('You will have') + ' ' + (avail_balance - new_amount) +' '+ __l('Bucks remaining.'));
			$('.js-update-total-used-bucks').html(new_amount);
		} else if(new_amount >= avail_balance){
			$('.js-update-remaining-bucks').html('You will have used all your Bucks.');
			$('.js-update-total-used-bucks').html(avail_balance);
		}
        $('.js-amount-need-to-pay').html(($('#DealUserAvailableBalance').val() > new_amount) ? 0: (Math.round(parseFloat(new_amount - $('#DealUserAvailableBalance').val())* 1000) / 1000));
		if(parseFloat(new_amount - $('#DealUserAvailableBalance').val()) > 0){
			$('.js-payment-gateway').slideDown('fast');		
			$('#DealIsPurchaseViaWallet').val(0);
		}else{
			$('.js-payment-gateway').slideUp('fast');
			$('#DealIsPurchaseViaWallet').val(1);
		}
        return false;
    });
	// For Gift card //	
	$('div#gift_users-add').delegate('form input#GiftUserAmount', 'keyup', function() {
        var new_amount = parseFloat($('#GiftUserAmount').val());
		var avail_balance = $('#GiftUserUserAvailableBalance').val();
        new_amount = isNaN(new_amount) ? 0: new_amount;
		new_amount = Math.round(new_amount * 1000) / 1000;
       $('.js-amount-need-to-pay').html(($('#GiftUserUserAvailableBalance').val() > new_amount) ? 0: (Math.round(parseFloat(new_amount - $('#GiftUserUserAvailableBalance').val())* 1000) / 1000));
	   if(avail_balance > new_amount){
			$('.js-update-remaining-bucks').html(__l('You will have') + ' ' + (avail_balance - new_amount) +' '+ __l('Bucks remaining.'));
			$('.js-update-total-used-bucks').html(new_amount);
		} else if(new_amount >= avail_balance){
			$('.js-update-remaining-bucks').html('You will have used all your Bucks.');
			$('.js-update-total-used-bucks').html(0);
		}
	   if($('#GiftUserGroupWallet').val() == 1){
			if(new_amount > $('#GiftUserUserAvailableBalance').val()){
				$('.js-payment-gateway').slideDown('fast');		
				$('#GiftUserIsPurchaseViaWallet').val(0);
			}else{
				$('.js-payment-gateway').slideUp('fast');
				$('#GiftUserIsPurchaseViaWallet').val(1);
			}
	   }
	   
        return false;
    });
	if($('#GiftUserGroupWallet', 'body').is('#GiftUserGroupWallet')){
        $this = $('#GiftUserGroupWallet');
		var new_amount = parseFloat($('#GiftUserAmount').val());
        new_amount = isNaN(new_amount) ? 0: new_amount;
		new_amount = Math.round(new_amount * 1000) / 1000;
		if($this.val() == 1){
			if(new_amount > $('#GiftUserUserAvailableBalance').val()){
				$('.js-payment-gateway').slideDown('fast');		
				$('#GiftUserIsPurchaseViaWallet').val(0);
			}else{
				$('.js-payment-gateway').slideUp('fast');
				$('#GiftUserIsPurchaseViaWallet').val(1);
			}			
		}
		else{
			if($('#GiftUserPaymentGatewayId4').attr('checked') == true ){
				$('.js-credit-payment').show();
			}
			else if($('#GiftUserPaymentGatewayId2').attr('checked') == true ){
				$('.js-credit-payment').show();
			}
			else{
				$('.js-credit-payment').hide();	
			}
		}
	}
	$('div.js-card-over-block').delegate('form input.js-buy-confirm', 'click', function() {
		var user_balance;
		user_balance = $('#DealUserAvailableBalance').val();
		if($('#DealPaymentTypeId1:checked').val() && user_balance != '' && user_balance != '0.00'){
			return window.confirm(__l('By clicking this button you are confirming your purchase. Once you confirmed amount will be deducted from your wallet and you can not undo this process. Are you sure you want to confirm this purchase?'));
		}else if((!user_balance || user_balance == '0.00') && ($('#DealPaymentTypeId1:checked').val() != '' && typeof($('#DealPaymentTypeId1:checked').val())  != 'undefined')){
			return window.confirm(__l('Since you don\'t have sufficent amount in wallet, your purchase process will be proceeded to PayPal. Are you sure you want to confirm this purchase?'));
		}else{
			return true;
		}
    });
	$('div#gift_users-add').delegate('#GiftUserFriendName, #GiftUserAmount, #GiftUserMessage, #GiftUserFrom', 'keyup', function() {
        var value = ($(this).val() != '') ? $(this).val(): $(this).metadata().default_value;
		value = stripHTML(value);
		if(value != $(this).metadata().default_value){
			$(this).val(value);
		}
        $('#' + $(this).metadata().update).html(value.replace(/\n/g, "<br />"));
    });
	$('div.js-company-add-edit-over-block').delegate('form.js_company_profile', 'click', function() {
        $('.js-company_profile_show').toggle();
    });
	$('div.js-import-over-block').delegate('form select.js-invite-all', 'change', function() {
        $('.invite-select').val($(this).val());
    });
	if($('div.js-truncate', 'body').is('div.js-truncate')){
        var $this = $('div.js-truncate');
        $this.truncate(100, {
            chars: /\s/,
            trail: ["<a href='#' class='truncate_show'>" + __l(' more', 'en_us') + "</a> ... ", " ...<a href='#' class='truncate_hide'>" + __l('less', 'en_us') + "</a>"]
        });
	}
	if($('form input.js-payment-type', 'body').is('form input.js-payment-type')){
		if ($('.js-payment-type:checked').val() == 2) {
            $('.js-hide-for-credit, .js-show-payment-profile').slideUp();
            $('.js-credit-payment').slideDown();
            $('.js-right-block').removeClass('wallet-login-block');
        } else if ($('.js-payment-type:checked').val() == 3) {
            $('.js-hide-for-credit, .js-credit-payment, .js-show-payment-profile').slideUp();
            $('.js-right-block').removeClass('wallet-login-block');
        } else if ($('.js-payment-type:checked').val() == 4) {
            $('.js-hide-for-credit').slideUp();
            $('.js-show-payment-profile').slideDown();
			if ($('#UserIsShowNewCard').val() == 1) {
				$('.js-credit-payment').slideDown();
			} else {
				$('.js-credit-payment').slideUp();
			}
            $('.js-right-block').removeClass('wallet-login-block');
        } else {
            $('.js-credit-payment, .js-show-payment-profile').slideUp();
            $('.js-hide-for-credit').slideDown();
        }
        
	}
	$('div.js-card-over-block').delegate('form input.js-payment-type', 'click', function() {
        if ($(this).val() == 2) {
            $('.js-hide-for-credit, .js-show-payment-profile').slideUp();
            $('.js-credit-payment, #currency-changing-info').slideDown();
            $('.js-right-block').removeClass('wallet-login-block');
        } else if ($(this).val() == 3) {
            $('.js-hide-for-credit, .js-credit-payment, .js-show-payment-profile').slideUp();
			$('#currency-changing-info').slideDown();
            $('.js-right-block').removeClass('wallet-login-block');
        } else if ($(this).val() == 4) {
            $('.js-hide-for-credit').slideUp();
            $('.js-show-payment-profile, #currency-changing-info').slideDown();
			if ($('#UserIsShowNewCard').val() == 1) {
				$('.js-credit-payment,').slideDown();
			} else {
				$('.js-credit-payment').slideUp();
			}			
            $('.js-right-block').removeClass('wallet-login-block');
        } else {
            $('.js-credit-payment, .js-show-payment-profile, #currency-changing-info').slideUp();
            $('.js-hide-for-credit').slideDown();
        }
    });
	if($('a.js-add-new-card', 'div.js-card-over-block').is('a.js-add-new-card')){
		if( $('.js-credit-payment').css('display') == 'none'){
			$('a.js-add-new-card').text('Add new card');	
		}
		else{
			$('a.js-add-new-card').text('Close new card');	
		}
	}
	$('div.js-card-over-block').delegate('a.js-add-new-card', 'click', function() {
		if( $('.js-credit-payment').css('display') == 'none'){
			$(this).text('Close new card');
			$('.js-credit-payment').slideDown('fast');
			$('#UserIsShowNewCard').val(1);
		}
		else{
			$(this).text('Add new card');
			$('.js-credit-payment').slideUp('fast');
			$('#UserIsShowNewCard').val(0);			
		}
		return false;
	});
   
	$('div#deals-live').delegate('.js-now-deal-more', 'click', function(e) {		
		if(!$(e.target).hasClass("button") && !$(e.target).hasClass("js-map-data")){
			desc_div = $(this).metadata().container;
			$('.' + desc_div).slideToggle("slow");
	        return false;
		}
    });
//For slide validation error
if($('#homeSubscriptionFrom', 'body').is('#homeSubscriptionFrom')){
	currentStep = $('#homeSubscriptionFrom').metadata().Currentstep;
}
//End error
    $('div.js-accordion').accordion( {
        header: 'h3',
        autoHeight: false,
        active: false,
        collapsible: true
    });
    $('h3', '.js-accordion').click(function(e) {
        var contentDiv = $(this).next('div');
        if ( ! contentDiv.html().length) {
            $this = $(this);
            $this.block();
            $.get($(this).find('a').attr('href'), function(data) {
                contentDiv.html(data);
				aftersubmitdeal(false);
                $this.unblock();
            });
        }
    });
	$('div.js-company-add-edit-over-block').delegate('form input.js_company_profile_enable', 'click', function() {
        if ($('.js_company_profile_enable:checked').length) {
            $('.js-company_profile_show').show();
        } else {
            $('.js-company_profile_show').hide();
        }
    });
	$('div#user_friends-import').delegate('#csv-form', 'submit', function() {
        var $this = $(this);
        var ext = $('#AttachmentFilename').val().split('.').pop().toLowerCase();
        var allow = new Array('csv', 'txt');
        if (jQuery.inArray(ext, allow) == -1) {
            $('div.error-message').remove();
            $('#AttachmentFilename').parent().append('<div class="error-message">'+ __l('Invalid extension, Only csv, txt are allowed')+'</div>');
            return false;
        }
    });
	$('div.js-company-address-delete-over-block').delegate('a.js-on-the-fly-delete', 'click', function() {
        var $this = $(this);
        if (window.confirm('Are you sure you want to ' + this.innerHTML.toLowerCase() + '?')) {
            $this.parents('li').block();
            $.get($this.attr('href'), function(data) {
                if (data == 'deleted') {
                    $this.parents('li').remove();
                    $.fn.setflashMsg('Company branch address has been deleted ', 'success');
                }
                $this.parents('li').unblock();
            });
        }
        return false;
    });
	//IsAnytimeDeal click event
	$('div.js-deal-subdeal-available-over-block').delegate('#DealIsAnytimeDeal', 'click', function() {
        if($('#DealIsAnytimeDeal:checked').val()){
			$('.js-anytime-deal').hide();
		}
		else{
			$('.js-anytime-deal').show();
		}
    });
	//IsAnytimeDeal page onload event
	if($('#DealIsAnytimeDeal', 'body').is('#DealIsAnytimeDeal')){
        if($('#DealIsAnytimeDeal:checked').val()){
			$('.js-anytime-deal').hide();
		}
		else{
			$('.js-anytime-deal').show();
		}
	}
	$('[id^="js-gallery-"]').each(function() {
		var $this = $(this);
		$this.showcase({
			animation: { autoCycle: true},
			css: {  width: __cfg('medium_big_thumb.width'), height: __cfg('medium_big_thumb.height') },   
			navigator: { 
							 css: { padding: "0px 300px" },   
							 position:"bottom-left", 
							 item: {
								 css: {width:"7px", height:"7px", backgroundColor: "#75797C", borderColor: "#696868"},
								 cssHover: { backgroundColor: "#00B5C8", borderColor: "#00B5C8" },
								 cssSelected: { backgroundColor: "#00B5C8", borderColor: "#00B5C8"}
							 }
						},
			titleBar: { enabled: false }
		});	
		$this.css("width", __cfg('medium_big_thumb.width')).css("height", __cfg('medium_big_thumb.height'));
	});
	
	if($('#js-gallery', 'body').is('#js-gallery')){
		$("#js-gallery").showcase({
			animation: { autoCycle: true},
			css: {  width: __cfg('medium_big_thumb.width'), height: __cfg('medium_big_thumb.height') },   
			navigator: { 
							 css: { padding: "0px 300px" },   
							 position:"bottom-left", 
							 item: {
								 css: {width:"7px", height:"7px", backgroundColor: "#DFDFDF", borderColor: "#696868"},
								 cssHover: { backgroundColor: "#186FA5", borderColor: "#696868" },
								 cssSelected: { backgroundColor: "#186FA5", borderColor: "#696868"}
							 }
						},
			titleBar: { enabled: false }
		});	
		// deal image sliding 
		$("#js-gallery").css("width", __cfg('medium_big_thumb.width')).css("height", __cfg('medium_big_thumb.height'));
	}
	$('.show-case-image').css("width", __cfg('medium_big_thumb.width')).css("height", __cfg('medium_big_thumb.height'));
	if($('#js-mobile-gallery', 'body').is('#js-mobile-gallery')){
		$("#js-mobile-gallery").showcase({
			animation: { autoCycle: true},
			css: {  width: __cfg('small_big_thumb.width'), height: __cfg('small_big_thumb.height')},   
			navigator: { 
							 css: { padding: "0px 100px" },   
							 position:"bottom-left", 
							 item: {
								 css: {width:"7px", height:"7px", backgroundColor: "#DFDFDF", borderColor: "#696868"},
								 cssHover: { backgroundColor: "#186FA5", borderColor: "#696868" },
								 cssSelected: { backgroundColor: "#186FA5", borderColor: "#696868"}
							 }
						},
			titleBar: { enabled: false }
		});
		// deal image sliding 
		$("#js-mobile-gallery").css("width", __cfg('small_big_thumb.width')).css("height", __cfg('small_big_thumb.height'));
	}
	$('div.js-deal-subdeal-available-over-block').delegate('#DealCompanyId', 'change', function() {
		if ($(this).val() >= 1) {																	
			$.get(__cfg('path_absolute') + 'companies/get_company_address/' + $(this).val(), function(data) {
				$('.js-show-branch-addresses').html(data);
				$('div.js-show-deal-company-main-address deal-company-main-address').html($('div.js-deal-company-main-address').html());
				if(data.indexOf('No Branch') > -1 ){
					$('.js-show-branch-addresses').html('<span class="info">' + __l('You don\'t have any branch address.') + '</span>');
					$('#js-redeem-all-branch').attr('disabled', true);
				}
				else{					
					$("input[name='data[CompanyAddressesDeal][company_address_id][]']").attr("checked", "checked");
					$('#js-redeem-all-branch').attr('disabled', false);					
				}
				return false;
			});
		}
		
    });
	$('div.js-deal-subdeal-available-over-block').delegate('#DealIsSubdealAvailable', 'click', function() {
		if ($(this).is(':checked')) {
			$('.js-subdeal-not-need').hide();
			$('.js-subdeal-need').show();
		}else{
			$('.js-subdeal-not-need').show();
			$('.js-subdeal-need').hide();
		}
	});
	if($('#DealIsSubdealAvailable', 'body').is('#DealIsSubdealAvailable')){
		$this = $('#DealIsSubdealAvailable');
		if ($this.is(':checked')) {
			$('.js-subdeal-not-need').hide();
		}else{
			$('.js-subdeal-not-need').show();
		}
	}
	$('div.js-subdeal-delete-over-block').delegate('.js-sub-deal-price', 'blur', function() {
		$this = $(this);
		//$this.metadata().container
        var original_price = parseFloat($('#' + $this.metadata().DealOriginalPrice).val());
        var discount_percentage = parseFloat($('#'+ $this.metadata().DealDiscountPercentage).val());
        var discount_amount = parseFloat($('#'+$this.metadata().DealDiscountAmount).val());
        if (original_price <= 0) {
            alert(__l('Please enter valid original price.'));
        } else if (discount_percentage > 100) {
            alert(__l('Discount percentage should be less than 100.'));
        } else if (discount_percentage >= 0) {
            discount = discount_percentage / 100;
            savings = discount * original_price;
            $('#'+ $this.metadata().DealDiscountAmount +', #'+$this.metadata().DealSavings).val((isNaN(savings) ? 0: savings).toFixed(2));
            discounted_price = original_price - savings;
            $('#'+ $this.metadata().DealDiscountedPrice +', #'+ $this.metadata().DealCalculatorDiscountedPrice).val((isNaN(discounted_price) ? 0: discounted_price).toFixed(2));
        } else {
            $('#'+ $this.metadata().DealDiscountedPrice +', #'+ $this.metadata().DealCalculatorDiscountedPrice).val(isNaN(original_price) ? 0: original_price);
        }
		if( (__cfg('user_type_id') != 1) && __cfg('deal.is_admin_enable_commission') && ( __cfg('deal.commission_amount_type') == 'fixed') ){
			if( $('#'+ $this.metadata().DealDiscountedPrice).val() == 0){
				$('#'+ $this.metadata().DealCommissionPercentage).val(0);
				$('#'+ $this.metadata().DealCalculatorCommissionPercentage).val(0);				
			}
			else{
				$('#'+ $this.metadata().DealCommissionPercentage).val(__cfg('deal.commission_amount'));	
				$('#'+ $this.metadata().DealCalculatorCommissionPercentage).val(__cfg('deal.commission_amount'));
			}
		}
	});
	$('div.js-subdeal-delete-over-block').delegate('.js-sub-deal-amount', 'blur', function() {
        var original_price = parseFloat($('#' + $this.metadata().DealOriginalPrice).val());
        var discount_percentage = parseFloat($('#'+ $this.metadata().DealDiscountPercentage).val());
        var discount_amount = parseFloat($('#'+$this.metadata().DealDiscountAmount).val());
        if (original_price <= 0) {
            alert(__l('Please enter valid original price.'));
        } else if (discount_amount > original_price) {
            alert(__l('Discount amount should be less than original price.'));
        } else if (discount_amount >= 0) {
            savings = discount_amount;
            discount_percentage = (savings * 100) / original_price;
            $('#'+ $this.metadata().DealDiscountPercentage).val(isNaN(discount_percentage) ? 0: discount_percentage.toFixed(2));
           $('#'+ $this.metadata().DealSavings).val(isNaN(savings) ? 0: savings.toFixed(2));
            discounted_price = original_price - savings;
            $('#'+ $this.metadata().DealDiscountedPrice +', #'+ $this.metadata().DealCalculatorDiscountedPrice + ',.js-deal-discount').val(isNaN(discounted_price) ? 0: discounted_price.toFixed(2));
			if( (__cfg('user_type_id') != 1) && __cfg('deal.is_admin_enable_commission') && ( __cfg('deal.commission_amount_type') == 'fixed') ){
				if( $('#'+ $this.metadata().DealDiscountedPrice).val() == 0){
					$('#'+ $this.metadata().DealCommissionPercentage).val(0);
					$('#'+ $this.metadata().DealCalculatorCommissionPercentage).val(0);				
				}
				else{
					$('#'+ $this.metadata().DealCommissionPercentage).val(__cfg('deal.commission_amount'));	
					$('#'+ $this.metadata().DealCalculatorCommissionPercentage).val(__cfg('deal.commission_amount'));
				}
			}
        }
    });
	$('div.js-subdeal-delete-over-block').delegate('.js-sub-deal-bonus-amount', 'blur', function() {
		$this = $(this);
        $('#'+ $this.metadata().DealCalculatorBonusAmount).val($('#'+ $this.metadata().DealBonusAmount).val());
        $('#'+ $this.metadata().DealCalculatorCommissionPercentage).val($('#'+ $this.metadata().DealCommissionPercentage).val());
        $('#'+ $this.metadata().DealCalculatorMinLimit).val($('#'+ $this.metadata().DealMinLimit).val());
        var total_purchased_amount = parseFloat($('#'+ $this.metadata().DealCalculatorDiscountedPrice).val()) * parseInt($('#'+ $this.metadata().DealCalculatorMinLimit).val());
        var commission_amount = ($('#'+ $this.metadata().DealCalculatorCommissionPercentage).val() > 0) ? (parseFloat($('#'+ $this.metadata().DealCalculatorCommissionPercentage).val()) / 100): 0;
        $('.js-calculator-purchased'+$this.metadata().ivalue).html(isNaN(total_purchased_amount) ? 0: total_purchased_amount.toFixed(2));
        var total_commission_amount = eval((total_purchased_amount * commission_amount) + parseFloat($('#DealCalculatorBonusAmount').val()));
        $('.js-calculator-commission'+ $this.metadata().ivalue +', .js-calculator-net-profit' +$this.metadata().ivalue).html((isNaN(total_commission_amount) ? 0: total_commission_amount).toFixed(2));
    });
	$('div.js-subdeal-delete-over-block').delegate('.js-sub-deal-calculator', 'blur', function() {	
		$this = $(this);
        var total_purchased_amount = parseFloat($('#'+$this.metadata().DealCalculatorDiscountedPrice).val()) * parseInt($('#'+$this.metadata().DealCalculatorMinLimit).val());
        var commission_amount = ($('#'+$this.metadata().DealCalculatorCommissionPercentage).val() > 0) ? (parseFloat($('#'+$this.metadata().DealCalculatorCommissionPercentage).val()) / 100): 0;
        $('.js-calculator-purchased' + $this.metadata().ivalue).html(isNaN(total_purchased_amount) ? 0: total_purchased_amount.toFixed(2));
        var total_commission_amount = eval((total_purchased_amount * commission_amount) + parseFloat($('#'+$this.metadata().DealCalculatorBonusAmount).val()));
        $('.js-calculator-commission'+$this.metadata().ivalue +', .js-calculator-net-profit'+$this.metadata().ivalue).html((isNaN(total_commission_amount) ? 0: total_commission_amount).toFixed(2));
    });
	$('div.js-subdeal-delete-over-block').delegate('span.js-subdeal-add', 'click', function() {	
		$this = $(this);
		$('.sub-deal-more-info').block();
		$.get(__cfg('path_absolute') + 'deals/subdeal_more/' +$this.metadata().id, function(data) {
			$('.js-subdeal-addmore-deal').append(data);
			$this.metadata().id = parseInt($this.metadata().id) + 1;
			$('.js-subdeal-delete').metadata().id = parseInt($('.js-subdeal-delete').metadata().id) + 1;
			$('.sub-deal-more-info').unblock();
			return false;
		});		
		return false;
	});
	$('div.js-subdeal-delete-over-block').delegate('span.js-subdeal-delete', 'click', function() {	
		$this = $(this);
		subdeal = parseInt($this.metadata().id) - 1;
		if (window.confirm('Are you sure you want to delete SubDeal #' + parseInt($this.metadata().id) + '?')) {
			if(subdeal > 1){
				status = '';
				if($('.js-subdeal-' + subdeal).hasClass('available')){
					status = $('.js-subdeal-' + subdeal).metadata().available;
				}
				if(status == 'Notpresent' || status == ''){
					$('#js-subdeal-' + subdeal).remove();	
					$('.js-subdeal-add').metadata().id = parseInt($this.metadata().id) - 1;
					$this.metadata().id = parseInt($('.js-subdeal-delete').metadata().id) - 1;
				}
				else{
					delete_deal_id = $('.js-subdeal-' + subdeal).metadata().available;
					main_deal_id = $('.js-subdeal-' + subdeal).metadata().main_deal_id;
					$.get(__cfg('path_absolute') + 'deals/subdeal_delete/' + delete_deal_id + '/' + main_deal_id, function(data) {
						if(data == 'Success'){
							$('#js-subdeal-' + subdeal).remove();	
							$.fn.setflashMsg('Sub Deal deleted', 'success');
							$('.js-subdeal-add').metadata().id = parseInt($this.metadata().id) - 1;
							$this.metadata().id = parseInt($('.js-subdeal-delete').metadata().id) - 1;
							
						}
						else if(data == 'Need Two Deals'){
							$.fn.setflashMsg('Two Sub deal Should be Must', 'error');	
						}
						else if(data == 'Fail'){
							$.fn.setflashMsg('Sub deal delete could not complete. Please Try Again ', 'error');	
						}
						else{
							$.fn.setflashMsg('Sub deal delete could not complete. Please Try Again ', 'error');	
						}
						return false;
					});	
				}
			}
			else{	
				$.fn.setflashMsg('Two Sub deal Should be Must', 'error');
			}
		}
		return false;
	});
	$('div.js-dialog-over-block').delegate('a.js-multiple-sub-deal', 'click', function() {	
		$this = $(this);
		$( "#" + $this.metadata().opendialog).dialog( "open" );
		return false;
	});
	$('ol.js-subdeal-on-fly-over-block').delegate('a.js-on-the-fly-sub-deal-delete', 'click', function() {	
        var $this = $(this);
        if (window.confirm('Are you sure you want to ' + this.innerHTML.toLowerCase() + '?')) {
            $this.parents('li').block();
            $.get($this.attr('href'), function(data) {
				if(data == 'Success'){
					$.fn.setflashMsg('Sub Deal deleted', 'success');
					$this.parents('li').remove();
				}
				else if(data == 'Need Two Deals'){
					$.fn.setflashMsg('Two Sub deal Should be Must', 'error');	
				}
				else if(data == 'Fail'){
					$.fn.setflashMsg('Sub deal delete could not complete. Please Try Again ', 'error');	
				}
				else{
					$.fn.setflashMsg('Sub deal delete could not complete. Please Try Again ', 'error');	
				}
                $this.parents('li').unblock();
            });
        }
        return false;
    });
	// For Two/Three Step Subscriptions //
	
	$("body").delegate(".js-grouponpro_sub_form", "submit", function() {
		updateStepSub();	
	});
	
	$("body").delegate("#PropertyAddressSearch", "blur", function() {
			$('#js-geo-fail-address-fill-block').show();
			loadSideMap();
	});
	
	$("body").delegate("form.js-geo-submit", "submit", function() {
		if($('#PropertyAddressSearch').val() == '' || ($('#js-street_id').val() == '' || $('#CityName').val() == '' || $('#js-country_id').val() == '' )){
			$('#js-geo-fail-address-fill-block').show();
			return false;		
		}			
		return true;
	});	
	
	$("body").delegate("#js-street_id, #CityName, #StateName, #js-country_id", "blur", function() {
        if ($('#js-street_id').val() != '' || $('#CityName').val() != '') {
			var address = '';
			address = __cfg('result_geo_format');
			address_list = address.split('##');
			for(i=0; i<address_list.length; i++){	
				switch(address_list[i]){
					case 'AREA':
								address = address.replace("##AREA##", $('#js-street_id').val());
								break;
					case 'CITY':
								address = address.replace("##CITY##", $('#CityName').val());
								break;
					case 'STATE':
								address = address.replace("##STATE##", $('#StateName').val());
								break;
					case 'COUNTRY':
								var name = $('#js-country_id option:selected').val();
								if(name =='') {
									address = address.replace("##COUNTRY##", '');
								} else {
									address = address.replace("##COUNTRY##", $('#js-country_id option:selected').text());
								}
								break;
					case 'ZIPCODE':
								address = address.replace("##ZIPCODE##", $('#PropertyPostalCode').val());
								break;
								
				}
			}
			address = $.trim(address);
			var intIndexOfMatch = address.indexOf("  ");
			while (intIndexOfMatch != -1){
			  address = address.replace("  ", " ");
			  intIndexOfMatch = address.indexOf("  ");
			}
			var intIndexOfMatch = address.indexOf(", ,");
			while (intIndexOfMatch != -1){
			  address = address.replace(", ,", ",");
			  intIndexOfMatch = address.indexOf(", ,");
			}
			if (address.substring(0, 1) == ",") {
				address = address.substring(1);
			}
			address = $.trim(address);
			size = address.length;
			
			if (address.substring(size-1, size) == ",") {
				address = address.substring(0, size-1);
			}
			
			if($('#PropertyAddressSearch', 'body').is('#PropertyAddressSearch')){
				$('#PropertyAddressSearch').val(address);
			}
			geocoder1.geocode( {
				'address': address
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					marker1.setMap(null);
					map1.setCenter(results[0].geometry.location);
					marker1 = new google.maps.Marker( {
						draggable: true,
						map: map1,
						position: results[0].geometry.location
					});					
					$('#latitude').val(marker1.getPosition().lat());
					$('#longitude').val(marker1.getPosition().lng());					
					google.maps.event.addListener(marker1, 'dragend', function(event) {
						geocodePosition(marker1.getPosition());
					});
					google.maps.event.addListener(map1, 'mouseout', function(event) {
						$('#zoomlevel').val(map1.getZoom());
					});    
				}
			});   
        }
    });
	$("body").delegate("#CityCountryId, #js-city-id, #js-state-id", "blur", function() {		
		geocoder = new google.maps.Geocoder();
		if ($('#CityCountryId').val() != '' || $('#js-city-id').val() != '' || $('#js-state-id').val() != '') {
			if ($('#js-city-id').val() != '' && $('#CityCountryId option:selected').text() != '') {
                var address = $('#js-city-id').val() + ', ' + $('#CityCountryId option:selected').text();
            } else {
                if ($('#js-city-id').val() != '') {
                    var address = $('#js-city-id').val()
                    } else if ($('#CityCountryId option:selected').text() != '') {
                    var address = $('#CityCountryId option:selected').text();
                }
            }
			geocoder.geocode( {
				'address': address
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					marker1.setMap(null);
					map1.setCenter(results[0].geometry.location);
					marker1 = new google.maps.Marker( {
						draggable: true,
						map: map1,
						position: results[0].geometry.location
					});					
					$('#latitude').val(marker1.getPosition().lat());
					$('#longitude').val(marker1.getPosition().lng());					
					google.maps.event.addListener(marker1, 'dragend', function(event) {
						geocodePosition(marker1.getPosition());
					});
					google.maps.event.addListener(map1, 'mouseout', function(event) {
						$('#zoomlevel').val(map1.getZoom());
					});    
					loadCityMap();
				}
			});  
		}
	});	
	$('div.js-responses').delegate('.js-check-invalid', 'click', function() {
			$('div.cities-checkbox-block').find('.error-message').remove();
	});
	
	
    // js code to do automatic validation on input fields blur
    $('div.input').each(function() {
        var m = /validation:{([\*]*|.*|[\/]*)}$/.exec($(this).attr('class'));
        if (m && m[1]) {
            $(this).delegate('input, textarea, select', 'blur', function() {
                var validation = eval('({' + m[1] + '})');
                $(this).parent().removeClass('error');
                $(this).siblings('div.error-message').remove();
                error_message = 0;
				if(!$(this).parents('div').hasClass('js-clone')){
                for (var i in validation) {
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'notempty' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'notempty' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !$(this).val()) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'alphaNumeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'alphaNumeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[0-9A-Za-z]+$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'numeric' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'numeric' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[+-]?[0-9|.]+$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && validation[i]['rule'] == 'email' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'email' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && !(/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)$/.test($(this).val()))) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'equalTo') || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'equalTo' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val() != validation[i]['rule'][1]) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'between' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'between' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && ($(this).val().length < validation[i]['rule'][1] || $(this).val().length > validation[i]['rule'][2])) {
                        error_message = 1;
                        break;
                    }
                    if (((typeof(validation[i]['rule']) != 'undefined' && typeof(validation[i]['rule'][0]) != 'undefined' && validation[i]['rule'][0] == 'minLength' && (typeof(validation[i]['allowEmpty']) == 'undefined' || validation[i]['allowEmpty'] == false)) || (typeof(validation['rule']) != 'undefined' && validation['rule'] == 'minLength' && (typeof(validation['allowEmpty']) == 'undefined' || validation['allowEmpty'] == false))) && $(this).val().length < validation[i]['rule'][1]) {
                        error_message = 1;
                        break;
                    }
                }
				}
                if (error_message) {
                    $(this).parent().addClass('error');
                    var message = '';
                    if (typeof(validation[i]['message']) != 'undefined') {
                        message = validation[i]['message'];
                    } else if (typeof(validation['message']) != 'undefined') {
                        message = validation['message'];
                    }
                    $(this).parent().append('<div class="error-message">' + message + '</div>').fadeIn();
                }
            });
        }
    });
    $('body').delegate('form', 'submit', function() {
		$this = $(this);
        $(this).find('div.input input[type=text], div.input input[type=password], div.input textarea, div.input select').trigger('blur');
        $('input, textarea, select', $('.error', $(this)).filter(':first')).trigger('focus');
		$('.error-message').each(function(i) {
						if($(this).parents('div').hasClass('js-clone')){
							$(this).remove();
						}
		});		
        return ! ($('.error-message', $this).length);
    });

if (getCookie('ice') == '') {
    document.cookie = 'ice=true;path=/';
}
if (getCookie('ice') == 'true' && (getCookie('city_name') == null || getCookie('city_name') == '') && __cfg('ccity') == false) {	
	if(getCookie('_geo') == '' || getCookie('_geo') == null){
		document.cookie = '_requested_url=' + window.location.href + ';path=/';
		$.ajax( {
			type: 'GET',
			url: '//j.maxmind.com/app/geoip.js',
			dataType: 'script',
			cache: true,
			success: function() {
				var geo = geoip_country_code() + '|' + geoip_region_name() + '|' + geoip_city() + '|' + geoip_latitude() + '|' + geoip_longitude();
				$.cookie('_geo', geo, {expires: 100, path: '/'});
				$.cookie('city_name', geoip_city(), {expires: 100, path: '/'});
				nearbyCityRedirect(geoip_city(), geoip_latitude(), geoip_longitude());
			}
		});
	}else{
		city_name = getCookie('_geo');
		geo_info = city_name.split('|');
		$.cookie('city_name', geo_info[2], {expires: 100, path: '/'});
		nearbyCityRedirect(geo_info[2], geo_info[3], geo_info[4]);
	}
} 

if($('div.js-register-load-geo-data', 'body').is('div.js-register-load-geo-data')){
		city_name = getCookie('_geo');
		geo_info = city_name.split('|');
		city_val = $('#CityName').val();
		if(city_val ==""){
			$('#CityName').val(geo_info[2]);
		}
		state_val = $('#StateName').val();			
		if(state_val ==""){
			$('#StateName').val(geo_info[1]);
		}
		$('#js-country_iso_code').val(geo_info[0]);	
		$('#latitude').val(geo_info[3]);		
		$('#longitude').val(geo_info[4]);		
}

	$('div#settings-admin_edit').delegate('.js-disabled-inputs-active', 'change', function() {
		if ($(this).is(':checked')) {
			$('.js-disabled-inputs').attr('disabled', false);
		}
		else{
			$('.js-disabled-inputs').attr('disabled', 'disabled');			
		}
    });
	$('body').delegate('.mceEditor', 'hover', function() {
        el  = $('.mceFirst iframe');
        el_w  = el.width();
        el.css("width",el_w+1);
        el.css("width",el_w-1);
    });

	if($('.js-jcarousellite', 'body').is('.js-jcarousellite')){		
		// jcarousellite
		$(".js-jcarousellite").jCarouselLite({
			btnNext: ".next",
			btnPrev: ".prev",
			mouseWheel: true
		});
	}

	if($('.js-broadcast-message', 'body').is('.js-broadcast-message')){		
		// Count Character //
		$('.js-broadcast-message').simplyCountable({
			counter: '#js-box-title',
			countable: 'characters',
			maxCount: '256',
			strictMax: true,
			countDirection: 'down',
			safeClass: 'safe',
			overClass: 'over'
		});
	
	}
	//jQuery.noConflict();
	$('.js-timestamp').timeago();
});

function nearbyCityRedirect(cityName, lat, lng){
	$.get(__cfg('path_absolute')+'cities/check_city/latitude:'+ lat +'/longitude:'+ lng,function(data){
		if(data!=''){			
			$.cookie('city_name', data, {expires: 100, path: '/'});
			location.href=__cfg('path_absolute')+'welcome_to_'+__cfg('site_name');
		}
	});	
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + '=');
        if (c_start !=- 1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(';', c_start);
            if (c_end ==- 1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return '';
}

function stripHTML(oldString) { 

   var newString = "";
   var inTag = false;
   for(var i = 0; i < oldString.length; i++) {

        if(oldString.charAt(i) == '<') inTag = true;
        if(oldString.charAt(i) == '>') {
            if(oldString.charAt(i+1)=="<")
            {
              		//dont do anything
			}
			else
			{
				inTag = false;
				i++;
			}
        }   
        if(!inTag) newString += oldString.charAt(i);

   }
   return newString;
}

// For Two/Three Step Subscriptions //

var currentStep = 1;
var animating = false;

function updateStepSub(data, value){
	if(data == 'animate'){
//		evt.preventDefault();
		if (animating == false) {
			animating = true;
			currentStep ++ ;
			styleSteps(value);
		}
	} else {
		if (animating) {
			evt.preventDefault();
		}	
	}
}
$(window).resize(function() {
    resizing = true;
    if (resizeTimer !== null) {
        window.clearTimeout(resizeTimer);
    }
    resizeTimer = window.setTimeout(pageRedraw, 200);
});

var curr_step = 'js-step_one1';
var retsd = getSteps();
styleSteps(false);

var resizing = false;
var resizeTimer = null;

function getSteps() {
    var old_step = (currentStep == 3) ? 'js-step_one1': null;
    var curr_step = (currentStep == 1) ? 'js-step_one1': (currentStep == 2) ? 'js-step_two': 'js-step_three';
    var prev_step = (currentStep == 2) ? 'js-step_one1': (currentStep == 3) ? 'js-step_two': null;
    var next_step = (currentStep == 1) ? 'js-step_two': (currentStep == 2) ? 'js-step_three': null;
    var super_step = (currentStep == 1) ? 'js-step_three': null;
    return {
        old: old_step,
        curr: curr_step,
        prev: prev_step,
        next: next_step,
        superStep: super_step
    };
}

function pageRedraw() {
    resizing = false;
    styleSteps(300);
}

function styleSteps(animSpeed) {
    pos = calculatePositions();
    steps = getSteps();
    if ( ! animSpeed) {
        $('.' + steps.old).css( {
            left: pos.offLeft + 'px',
            opacity: 0
        });
        $('.' + steps.prev).css( {
            left: pos.left + 'px',
            opacity: 0
        });
        $('.' + steps.curr).css( {
            left: pos.center + 'px',
            opacity: 1
        });
        $('.' + steps.next).css( {
            left: pos.right + 'px',
            opacity: 0
        });
        $('.' + steps.superStep).css( {
            left: pos.offRight + 'px',
            opacity: 0
        });
        clearAnimateFlag();
    } else {
        $('.' + steps.old).animate( {
            left: pos.offLeft + 'px',
            opacity: 0
        }, animSpeed);
        $('.' + steps.prev).animate( {
            left: pos.left + 'px',
            opacity: 0
        }, animSpeed);
        $('.' + steps.curr).animate( {
            left: pos.center + 'px',
            opacity: 1
        }, {
            duration: animSpeed,
            complete: clearAnimateFlag
        });
        $('.' + steps.next).animate( {
            left: pos.right + 'px',
            opacity: 0
        }, animSpeed);
        $('.' + steps.superStep).animate( {
            left: pos.offRight + 'px',
            opacity: 0
        }, animSpeed);
    }
}

function clearAnimateFlag() {
    animating = false;
}

if (tout && 1) window._tdump = tout;

function calculatePositions() {
    var offset = 20;
    var step_width = $('.js-form_step').width() / 2;
    var window_width = $(window).width();

    var offLeft = -3 * step_width;
    var leftPos = offset - step_width;
    var centerPos = window_width / 2;
    var rightPos = window_width - offset + step_width;
    var offRight = rightPos + (3 * step_width);
    return {
        offLeft: offLeft,
        left: leftPos,
        center: centerPos,
        right: rightPos,
        offRight: offRight
    };
}

function reSizeMe() {
	$(".js-step_two").hide();
    resizing = true;
    if (resizeTimer !== null) {
        window.clearTimeout(resizeTimer);
    }
    resizeTimer = window.setTimeout(pageRedraw, 200);
}



// EO- Two/Three Step Subscriptions //
function aftersubmitdeal(flash_call){
	if($('#js-expand-table', 'body').is('#js-expand-table')){
		$("#js-expand-table tr:not(.js-odd)").hide();	
		$("#js-expand-table tr.js-even").show();		
		$("#js-expand-table tr.js-odd").click(function(){
			if($(this).hasClass('inactive-record')){
				$(this).addClass('inactive-record-backup');
				$(this).removeClass('inactive-record');
			} else if($(this).hasClass('inactive-record-backup')){
				$(this).addClass('inactive-record');
				$(this).removeClass('inactive-record-backup');
			}
			display = $(this).next("tr").css('display');
			if(display == 'none'){
				$(this).addClass('active-row');
			} else{
				$(this).removeClass('active-row');
			}
			$(this).next("tr").slideToggle('slow');
			$(this).find(".arrow").toggleClass("up");					
		});
	}
		if($('#DealIsSubdealAvailable', 'body').is('#DealIsSubdealAvailable')){
			$this = $('#DealIsSubdealAvailable');
			if ($this.is(':checked')) {
				$('.js-subdeal-not-need').hide();
			}else{
				$('.js-subdeal-not-need').show();
			}
		}
		if($('#DealIsAnytimeDeal', 'body').is('#DealIsAnytimeDeal')){
			if($('#DealIsAnytimeDeal:checked').val()){
				$('.js-anytime-deal').hide();
			}
			else{
				$('.js-anytime-deal').show();
			}
		}
		
		if(!flash_call){
			$('form div.js-time').ftimepicker();	
			// jquery datepicker
			$('form div.js-datetime').fdatepicker();
		}
		// overlabel
		$('form .js-overlabel label').foverlabel();
		// colorbox
		$('a.js-thickbox').fcolorbox();
		// captcha play 
		$('a.js-captcha-play').captchaPlay();
		// colorpicker
		$('.js_colorpick').fcolorpicker();
		
		$('.js-autocomplete').fautocomplete();		
		// tabs 
		$('#users-my_stuff .js-mystuff-tabs, .js-tabs').tabs();	
		// countdown clock
		$('#deals-index .js-deal-end-countdown, #deals-view .js-deal-end-countdown, .js-widget-deal-end-countdown').each(function(){
					var end_date = parseInt($(this).parents().find('.js-time').html());
					$(this).countdown( {
						until: end_date,
						format: 'd H M S'
					});									  
		});
	   	// load geo map 
		$.floadgeomaplisting('#DealCityNameSearch');		
		$.floadGeo('#PropertyAddressSearch');		
		// flash message
		$('#errorMessage,#authMessage,#successMessage,#flashMessage').flashMsg();
	   // jcarousellite
	   if($('.js-jcarousellite', 'body').is('.js-jcarousellite')){
			$(".js-jcarousellite").jCarouselLite({
				btnNext: ".next",
				btnPrev: ".prev",
				mouseWheel: true
			});
	   }
		if($('div.js-truncate', 'body').is('div.js-truncate')){
			var $this = $('div.js-truncate');
			$this.truncate(100, {
				chars: /\s/,
				trail: ["<a href='#' class='truncate_show'>" + __l(' more', 'en_us') + "</a> ... ", " ...<a href='#' class='truncate_hide'>" + __l('less', 'en_us') + "</a>"]
			});
		}
		
		if($('.js-repeat-type-select', 'body').is('.js-repeat-type-select')){
			if($('.js-repeat-type-select').val() == 4) {
				$('.js-repeat-date').show();
				$('.js-repeat_until_block').show();
			} else {
				if($('.js-repeat-type-select').val() == 1) {
					$('.js-repeat_until_block').hide();
				} else {
					$('.js-repeat_until_block').show();
				}
				$('.js-repeat-date').hide();
			}
		}
		if($('.js-repeat-until-select', 'body').is('.js-repeat-until-select')){
			if($('.js-repeat-until-select:checked').val() == 2) {
				$('.js-repeat-until').show();
			} else {
				$('.js-repeat-until').hide();
			}
		}
		if($('form.js-gig-photo-checkbox', 'body').is('form.js-gig-photo-checkbox')){
			var active = $('.js-gig-photo-checkbox:checked').length;
			var total = $('.js-gig-photo-checkbox').length;
			if (active == total)
				$('.js-gig-photo-checkbox').parent('.input').hide();
			return false;
		}
		if($('.js-editor', 'body').is('.js-editor')){
			$('.js-editor').ftinyMce();
		}

}
function buildChart($default_load){
		if($default_load == ''){
			$default_load = 'body';			
		}
		$('.js-load-line-graph', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;
			chart_y_title = $(this).metadata().chart_y_title;
			var table = document.getElementById(data_container);
			options = {
				   chart: {
						renderTo: chart_container,
						defaultSeriesType: 'line'
				   },
				   title: {
					  text: chart_title
				   },
				   xAxis: {
					   labels: {
							rotation: -90
					   }
				   },
				   yAxis: {
					  title: {
						 text: chart_y_title
					  }
				   },
				   tooltip: {
					  formatter: function() {
						 return '<b>'+ this.series.name +'</b><br/>'+
							this.y +' '+ this.x;
					  }
				   }
			};
			// the categories
			options.xAxis.categories = [];
			jQuery('tbody th', table).each( function(i) {
				options.xAxis.categories.push(this.innerHTML);
			});

			// the data series
			options.series = [];
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if (j > 0) { // skip first column
						if (i == 0) { // get the name and init the series
							options.series[j - 1] = {
								name: this.innerHTML,
								data: []
							};
						} else { // add values
							options.series[j - 1].data.push(parseFloat(this.innerHTML));
						}
					}
				});
			});
			var chart = new Highcharts.Chart(options);
		});				
		$('.js-load-pie-chart', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;	
			chart_y_title = $(this).metadata().chart_y_title;	
			var table = document.getElementById(data_container);
			options = {
				chart: {
						renderTo: chart_container,
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: chart_title
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ (this.percentage).toFixed(2) +' %';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: false
							},
							showInLegend: true
						}
					},
				    series: [{
						type: 'pie',
						name: chart_y_title,
						data: []
					}]
			};
			options.series[0].data = [] ;						
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if(j == 0){
						options.series[0].data[i] = [];
						options.series[0].data[i][j] = this.innerHTML													
					} else { // add values
						options.series[0].data[i][j] = parseFloat(this.innerHTML);
					}
				});				
			});				
			var chart = new Highcharts.Chart(options);
		});		
		$('.js-load-column-chart', $default_load).each(function(){
			data_container = $(this).metadata().data_container;
			chart_container = $(this).metadata().chart_container;
			chart_title = $(this).metadata().chart_title;	
			chart_y_title = $(this).metadata().chart_y_title;	
			var table = document.getElementById(data_container);
			seriesType = 'column';
			if($(this).metadata().series_type){
				seriesType = $(this).metadata().series_type;
			}
			options = { 
					chart: {
						renderTo: chart_container,
						defaultSeriesType: seriesType,
						margin: [ 50, 50, 100, 80]
					},
					title: {
						text: chart_title
					},
					xAxis: {
						categories: [							
						],
						labels: {
							rotation: -90,
							align: 'right',
							style: {
								 font: 'normal 13px Verdana, sans-serif'
							}
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: chart_y_title
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.x +'</b><br/>'+
								  Highcharts.numberFormat(this.y, 1);
						}
					},
				    series: [{
						name: 'Data',
						data: [],
						dataLabels: {
							enabled: true,
							rotation: -90,
							color: '#FFFFFF',
							align: 'right',
							x: -3,
							y: 10,
							formatter: function() {
								return '';
							},
							style: {
								font: 'normal 13px Verdana, sans-serif'
							}
						}			
					}]
			};
			// the categories
			options.xAxis.categories = [];
			options.series[0].data = [] ;						
			jQuery('tr', table).each( function(i) {
				var tr = this;
				jQuery('th, td', tr).each( function(j) {
					if(j == 0){
						options.xAxis.categories.push(this.innerHTML);											
					} else { // add values						
						options.series[0].data.push(parseFloat(this.innerHTML));
					}
				});				
			});			
			chart = new Highcharts.Chart(options);
		});		
}
function loadDealPurchaseMap() {
	
	lat = 13.314082;
	lng = 77.695313;
    var zoom = 2;
    latlng = new google.maps.LatLng(lat, lng);
    var myOptions1 = {
        zoom: zoom,
        center: latlng,
        zoomControl: true,
        draggable: true,
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map1 = new google.maps.Map(document.getElementById('js-deal-purchase-location-map'), myOptions1);	    
	var table = document.getElementById('deal_sold_location_data');
	jQuery('tr', table).each( function(i) {
		var tr = this;
		jQuery('th, td', tr).each( function(j) {
			if(j == 0){
				purchase_lat = parseFloat(this.innerHTML);											
			} else {				
				purchase_lng = parseFloat(this.innerHTML);
				var latlng = new google.maps.LatLng(purchase_lat, purchase_lng);
				marker = new google.maps.Marker( {
					draggable: false,
					map: map1,		
					position: latlng
				});

			}
		});		
	});	
		
}
$.fn.fcolorbox1 = function() {
    $(this).colorbox( {
				opacity: 0.30,
				width:'930px',
				height:'650px'
            });
};

$.fn.fcolorbox2 = function() {
    $(this).colorbox( {
				inline:true,
				href:'#test',
				width:'700px',
				height:'500px'
            });
};
$.fn.fcolorbox3 = function() {
    $(this).colorbox( {
				width:'760px',
				height:'330px'
            });
};
$.fn.fcolorbox4 = function() {
    $(this).colorbox( {
				inline:true,
				href:'#time'
            });
};
$.fn.slid = function() {
	// Set starting slide to 1
			var startSlide = 1;
			// Get slide number if it exists
			if (window.location.hash) {
				startSlide = window.location.hash.replace('#','');
			}
			$(this).slides({
			   preload: true,
				//preloadImage: 'img/loading.gif',
				generatePagination: true,
				play: 5000,
				pause: 2500,
				hoverPause: true,
				// Get the starting slide
				start: startSlide,
				animationComplete: function(current){
					// Set the slide number as a hash
					window.location.hash = '#' + current;
				}
			   });
};


$("div.js-lazyload img").lazyload({
		 placeholder : __cfg('path_absolute') + "img/grey.gif" 
	 });
