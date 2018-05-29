// -----------------------------------------------------------------------
// Eros Fratini - eros@recoding.it
// jquery.showcase 2.0.2
//
// 02/11/2010 - Fixed and issue with jQuery 1.4.3, some tests with IE9 beta
// 26/04/2010 - Begin some changes
// 02/02/2010 - Wow, a fix about 10 minute after release....
// 02/02/2010 - Debugging and demos
// 27/12/2009 - Optimized animation, added functions to pause and resume autocycling
//			  - Tested external ease functions		
// 24/12/2009 - Added a lot of settings, redefine css
// 21/12/2009 - Begin to write v2.0
// 27/07/2009 - Added asynchronous loading of images
// 26/06/2009 - some new implementations
// 19/06/2009 - standardization
// 08/06/2009 - Initial sketch
//
// requires jQuery 1.3.x+
//------------------------------------------------------------------------
(function($) {
    $.fn.showcase = function (options) {
        var $container = this;
        
        // Retrieve options
        var opt;
        opt = $.extend({}, $.fn.showcase.defaults, options);

		if (!/images|titles/.test(opt.linksOn)) 
        {
            opt.linksOn = "images";
        }
		if (options && options.css) {  
            opt.css = $.extend({}, $.fn.showcase.defaults.css, options.css); 
        }
        if (options && options.animation) {  
            opt.animation = $.extend({}, $.fn.showcase.defaults.animation, options.animation); 
            if (!/horizontal-slider|vertical-slider|fade/.test(opt.animation.type)) 
            {
                opt.animation.type = "horizontal-slider";
            }
        }
        if (options && options.navigator) { 
            opt.navigator = $.extend({}, $.fn.showcase.defaults.navigator, options.navigator); 
            
            if (!/top-left|top-right|bottom-left|bottom-right/.test(opt.navigator.position)) 
            {
                opt.navigator.position = "top-right";
            }
            
            if (!/horizontal|vertical/.test(opt.navigator.orientation)) 
            { 
                opt.navigator.orientation = "horizontal";
            }
            
			if (options.navigator.css)
			{
				opt.navigator.css = $.extend({}, $.fn.showcase.defaults.navigator.css, options.navigator.css);
			}
			
            if (options.navigator.item) { 
				opt.navigator.item = $.extend({}, $.fn.showcase.defaults.navigator.item, options.navigator.item);
                // Progressive extensions of hover and selected states, inherited by standard css properties
                opt.navigator.item.cssHover = $.extend({}, $.fn.showcase.defaults.navigator.item.css, $.fn.showcase.defaults.navigator.item.cssHover);
                opt.navigator.item.cssSelected = $.extend({}, $.fn.showcase.defaults.navigator.item.css, $.fn.showcase.defaults.navigator.item.cssSelected);
                
				if (options.navigator.item.css) { 
                    opt.navigator.item.css = $.extend({}, $.fn.showcase.defaults.navigator.item.css, options.navigator.item.css);
                    opt.navigator.item.cssHover = $.extend({}, $.fn.showcase.defaults.navigator.item.cssHover, options.navigator.item.css);
                    opt.navigator.item.cssSelected = $.extend({}, $.fn.showcase.defaults.navigator.item.cssSelected, options.navigator.item.css); 
                }  
				if (options.navigator.item.cssHover) { opt.navigator.item.cssHover = $.extend({}, $.fn.showcase.defaults.navigator.item.cssHover, options.navigator.item.cssHover); }
				if (options.navigator.item.cssSelected) { opt.navigator.item.cssSelected = $.extend({}, $.fn.showcase.defaults.navigator.item.cssSelected, options.navigator.item.cssSelected); }
			}
		}
		
        if (options && options.titleBar) { 
            opt.titleBar = $.extend({}, $.fn.showcase.defaults.titleBar, options.titleBar); 
			if (!/bottom|top/.test(opt.titleBar.position)) 
            {
                opt.titleBar.position = "bottom";
            }
			
            if (options.titleBar.css) { opt.titleBar.css = $.extend({}, $.fn.showcase.defaults.titleBar.css, options.titleBar.css);  }
        }
        
        // Setup of container
        $container.css(opt.css);
        
        // Check loading mode.
        // If there's something in opt.images[], I'll load them asynchronously, 
        // it will be nice to have width and height setted, in order to define the $container sizes
        if (opt.images.length != 0) {
            $container.css({ width: opt.css.width, height: opt.css.height, overflow: "hidden" });
            for (var i in opt.images) {
                var img = new Image();
                img.src = opt.images[i].url;
                img.alt = opt.images[i].description || "";
                var $link = $("<a />").attr({ "href": opt.images[i].link || "#", "target": opt.images[i].target || "_self" });
                $link.append(img);
                $container.append($link);
            }
        }
        
        // Check loading state of #1 image
        if ($container.find("img:first")[0].complete) {
            $.fn.showcase.start($container, opt);
        }
        else {
            $container.find("img:first").load( function() {
                $.fn.showcase.start($container, opt);
            });
        }
		
		// functions to control the playback of showcase
		$.fn.extend({
			pause: function() { $container.data("stopped", true); },
			go: function() { $container.data("stopped", false); }
		}) 
    };

	// This will start all showcase's stuffs
    $.fn.showcase.start = function($container, opt) {
        // Define local vars
        var index = 0;                             
        var nImages = $container.find("img").length;
        var $fi = $container.find("img:first");
        var imagesize = { width: 680, height: 272 };
        
		opt.css.width = imagesize.width;
		opt.css.height = imagesize.height;
		
        // Redefine size of container
        $container.css({ width: opt.css.width, height: opt.css.height });
		$container.find("a").css({ position: "absolute", top: "0", left: "0" })
                  .find("img").css("border", "0px");
    
    	// setup navigator
        var $slider = $("<div id='slider' />").css({ position:"absolute" });
		var $divNavigator = $("<div id='navigator' />").css(opt.navigator.css);

        switch (opt.navigator.position)
        {
            case "top-left": $divNavigator.css({ top: "0px", left: "0px" });
                break;
            case "top-right": $divNavigator.css({ top: "0px", right: "0px" });
                break;
            case "bottom-left": $divNavigator.css({ bottom: "0px", left: "0px" });
                break;
            case "bottom-right": $divNavigator.css({ bottom: "0px", right: "0px" });
                break;
        }
        
        $container.append($divNavigator).hover(
            function() { 
                if (opt.titleBar.autoHide && opt.titleBar.enabled) {
                    $($titleBar).stop().animate({ opacity: opt.titleBar.css.opacity, left: 0, right: 0, height: opt.titleBar.css.height }, 250);
                }
                if (opt.navigator.autoHide) { $($divNavigator).stop().animate({ opacity: 1 }, 250); }
                $(this).data("isMouseHover", true);
            },
            function() { 
                if (opt.titleBar.autoHide && opt.titleBar.enabled) {
                    $titleBar.stop().animate({ opacity: 0, height: "0px" }, 400); 
                }
                if (opt.navigator.autoHide) { $divNavigator.stop().animate({ opacity: 0 }, 250); }
                $(this).data("isMouseHover", false);
            }
        );

        $container.find("a").wrapAll($slider).each( function(i) {
            switch (opt.animation.type)
            { 
                case "horizontal-slider":
                    $(this).css("left", i*imagesize.width);
                    break;
                case "vertical-slider":
                    $(this).css("top", i*imagesize.height);
                    break;
                case "fade":
                    $(this).css({ top: "0", left: "0", opacity:1, "z-index": 1000-i });
                    break;
            }
            
			// Create navigation bar item
            var $navElement = $("<a href='#'>" + (opt.navigator.showNumber ? (i + 1) : "") + "</a>")
                                .css({ 	display: "block",
										"text-decoration": "none",
										"-moz-outline-style": "none" })
                                .click( function() {
									if (opt.animation.autoCycle) { clearInterval(opt.animation.intervalID); } // stop the current automatic animation
                                    $.fn.showcase.showImage(i, $container, imagesize, opt);
                                    index = i;
									if (opt.animation.autoCycle) { opt.animation.intervalID = showcaseCycler(index, nImages, $container, imagesize, opt); } // restart the automatic animation
                                    return false;
                                })
                                .hover( 
                                	function() { if (!$(this).data("selected")) {
                                					if (opt.navigator.item.cssClassHover)
                                					{ $(this).addClass(opt.navigator.item.cssClassHover); }
                                					else 
                                					{ $(this).css(opt.navigator.item.cssHover); }
                                				}
                                	},
                                	function() { if (!$(this).data("selected")) {
	                            					if (opt.navigator.item.cssClassHover) 
					                   				{ $(this).removeClass(opt.navigator.item.cssClassHover); }
					                   				else 
					                   				{ $(this).css(opt.navigator.item.css); }
                                				}
                                	}
                                )
                                .appendTo($divNavigator);

            if (opt.navigator.item.cssClass) { $navElement.attr("class", opt.navigator.item.cssClass); }
            else {
            	$.extend({}, $navElement.css, opt.navigator.item);
                $navElement.css(opt.navigator.item.css);
            }
			
			switch (opt.navigator.orientation) 
            {
                case "horizontal":
                    $navElement.css("float", "left");
                    break;
                case "vertical":
                    $navElement.css("float", "none");
                    break;    
            }
            
            if (opt.navigator.showMiniature) {
                $("<img />").attr({ src: $("img", this).attr("src"), width: $navElement.innerWidth(), height: $navElement.innerHeight(), border: "0px" }).appendTo($navElement);
            }
        });
        
        if (opt.navigator.autoHide) {
            $divNavigator.css("opacity", 0);
        }

        // Create titleBar
		if (opt.titleBar.enabled) {
			if (opt.linksOn == "images")
			{
				var $titleBar = $("<div id='subBar' />").html( $("<span />").html($container.find("a:first img").attr("alt")) );
			}
			else 
			{
				var $a = $("<a />").attr("href", $container.find("a:first").attr("href")).html("<span>" + $container.find("a:first img").attr("alt") + "</span>");
				var $titleBar = $("<div id='subBar' />").html($a);

				$container.find("#slider a").each( function() {
					$(this).attr("rel", $(this).attr("href"));
				});
				$container.find("#slider a").removeAttr("href");
			}
			
            $titleBar.css({
                opacity: 0.50,
                width: "100%",
			 	overflow: "hidden",
    	   	   	"z-index": 10002,
    	   	   	position: "absolute"
            });
            
			if(opt.titleBar.position == "top") { $titleBar.css("top", "0"); }
            else { $titleBar.css("bottom", "0"); }
			
	        if (opt.titleBar.cssClass) { $titleBar.attr("class", opt.titleBar.cssClass); }
	        else { 
                $titleBar.css(opt.titleBar.css); 
                $("a", $titleBar).css("color", opt.titleBar.css.color);
            }
            
	        if (opt.titleBar.autoHide) { $titleBar.css({
				"height": "0px",
				"opacity": 0
			}); }
	        $titleBar.appendTo($container);
		}
			
		// set first image as selected
		$.fn.showcase.setNavigationItem(0, $container, opt);
        
		// startup cycling
        if (opt.animation.autoCycle) {
            opt.animation.intervalID = showcaseCycler(index, nImages, $container, imagesize, opt);
        }
    };
    
	var showcaseCycler = function(index, nImages, $container, imagesize, opt) {
		return setInterval( function() { 
				if (!$container.data("stopped")){
					if (!$container.data("isMouseHover") || !opt.animation.stopOnHover) 
                    	$.fn.showcase.showImage(++index % nImages, $container, imagesize, opt);	
				}
            }, opt.animation.interval);
	};
	
    $.fn.showcase.showImage = function(i, $container, imagesize, opt) {
        var $a = $container.find("a");
		var $a_this = $container.find("a").eq(i);

        switch (opt.animation.type)
        { 
            case "horizontal-slider": $container.find("#slider").stop().animate({ left: - (i*imagesize.width) }, opt.animation.speed, opt.animation.easefunction);
                break;
            case "vertical-slider": $container.find("#slider").stop().animate({ top: - (i*imagesize.height) }, opt.animation.speed, opt.animation.easefunction);
                break;
            case "fade":
                $container.css({ "z-index": "1001" });
                if ($a_this.css("z-index") != "1000") 
                {
                    $a_this.css({ "z-index": "1000", opacity: 0 });
					
                    $a.not($a_this).each( function() {
						if ($(this).css("z-index") != "auto")
							$(this).css("z-index", parseInt($(this).css("z-index"), 10) - 1);
                    });
                    
                    $a_this.stop().animate({ opacity: 1 }, opt.animation.speed, opt.animation.easefunction);
                }
                break;
        }

		if (opt.titleBar.enabled) {
			if (opt.linksOn == "titles") {
				$("#subBar a", $container).attr({
					"href": $a_this.attr("rel"), "target": $a_this.attr("target")
				});
			}
		}
		$("#subBar span", $container).html($a_this.find("img").attr("alt"));
        // Setting selected navigationItem
		$.fn.showcase.setNavigationItem(i, $container, opt);
	};
    
	// Highlight the navigationItem related to image
	$.fn.showcase.setNavigationItem = function(i, $container, opt) {
        if (opt.navigator.item.cssClassSelected) {
            $container.find("#navigator a").removeClass(opt.navigator.item.cssClassSelected).data("selected", false);
			$container.find("#navigator a").eq(i).addClass(opt.navigator.item.cssClassSelected).data("selected", true);
        }
        else {
			if (opt.navigator.item.cssClass) {
				//$container.find("#navigator a").removeAttr("style").data("selected", false);
				$container.find("#navigator a").eq(i).css(opt.navigator.item.cssSelected).data("selected", true);
			}
			else {
				$container.find("#navigator a").css(opt.navigator.item.css).data("selected", false);
				$container.find("#navigator a").eq(i).css(opt.navigator.item.cssSelected).data("selected", true);	
			}
        }
	};
	
    $.fn.showcase.defaults = {
        images: [],
		linksOn: "images",
		css: {	position: "relative", 
				overflow: "hidden",
				border: "none",
				width: "",
				height: ""
		},
        animation: { autoCycle: true,
                     stopOnHover: true,
                     interval: 4000,
                     speed: 500,
                     easefunction: "swing",
                     type: "horizontal-slider" },
		
		navigator: { css: {	border: "none",
					        padding: "5px",
							margin: "0px",
							position: "absolute", 
			            	"z-index": 1000
					},
					position: "top-right",
					orientation: "horizontal",
					autoHide: false,
					showNumber: false,
					showMiniature: false,
					item: { cssClass: null,
					 		cssClassHover: null,
					     	cssClassSelected: null,
							css: {	
								color: "#000000",
								"text-decoration": "none",
                                "text-align": "center",
								"-moz-outline-style": "none",
								width: "12px", 
								height: "12px",
								lineHeight: "12px",
								verticalAlign: "middle",
								backgroundColor: "#878787",
								margin: "0px 3px 3px 0px",
								border: "solid 1px #acacac",
                                "border-radius": "4px",
								"-moz-border-radius": "4px",
								"-webkit-border-radius": "4px" },
							cssHover: {
								backgroundColor: "#767676",
								border: "solid 1px #676767" },
							cssSelected: {	
								backgroundColor: "#00FF00",
								border: "solid 1px #acacac" }
							}
                     },
		titleBar: { enabled: true,
					autoHide: true,
					position: "bottom",
		            cssClass: null,
		            css: { 	opacity: 0.50,
		        	   	   	color: "#ffffff",
		                   	backgroundColor: "#000000",
		                   	height: "40px",
						   	padding: "4px",
		                   	fontColor: "#444444",
		                   	fontStyle: "italic",
		                   	fontWeight: "bold",
		                   	fontSize: "1em" } }
	};
})(jQuery);