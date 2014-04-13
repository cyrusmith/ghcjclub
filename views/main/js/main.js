if(!Array.prototype.indexOf)
	Array.prototype.indexOf = function(searchElement, fromIndex){
		for(var i = fromIndex||0, length = this.length; i<length; i++)
			if(this[i] === searchElement) return i;
		return -1
	};

$.browser = {
	msie: /msie/.test(navigator.userAgent.toLowerCase())
};

/*!
 * jScrollPane - v2.0.14 - 2013-05-01
 * http://jscrollpane.kelvinluck.com/
 */
(function(b,a,c){b.fn.jScrollPane=function(e){function d(D,O){var ay,Q=this,Y,aj,v,al,T,Z,y,q,az,aE,au,i,I,h,j,aa,U,ap,X,t,A,aq,af,am,G,l,at,ax,x,av,aH,f,L,ai=true,P=true,aG=false,k=false,ao=D.clone(false,false).empty(),ac=b.fn.mwheelIntent?"mwheelIntent.jsp":"mousewheel.jsp";aH=D.css("paddingTop")+" "+D.css("paddingRight")+" "+D.css("paddingBottom")+" "+D.css("paddingLeft");f=(parseInt(D.css("paddingLeft"),10)||0)+(parseInt(D.css("paddingRight"),10)||0);function ar(aQ){var aL,aN,aM,aJ,aI,aP,aO=false,aK=false;ay=aQ;if(Y===c){aI=D.scrollTop();aP=D.scrollLeft();D.css({overflow:"hidden",padding:0});aj=D.innerWidth()+f;v=D.innerHeight();D.width(aj);Y=b('<div class="jspPane" />').css("padding",aH).append(D.children());al=b('<div class="jspContainer" />').css({width:aj+"px",height:v+"px"}).append(Y).appendTo(D)}else{D.css("width","");aO=ay.stickToBottom&&K();aK=ay.stickToRight&&B();aJ=D.innerWidth()+f!=aj||D.outerHeight()!=v;if(aJ){aj=D.innerWidth()+f;v=D.innerHeight();al.css({width:aj+"px",height:v+"px"})}if(!aJ&&L==T&&Y.outerHeight()==Z){D.width(aj);return}L=T;Y.css("width","");D.width(aj);al.find(">.jspVerticalBar,>.jspHorizontalBar").remove().end()}Y.css("overflow","auto");if(aQ.contentWidth){T=aQ.contentWidth}else{T=Y[0].scrollWidth}Z=Y[0].scrollHeight;Y.css("overflow","");y=T/aj;q=Z/v;az=q>1;aE=y>1;if(!(aE||az)){D.removeClass("jspScrollable");Y.css({top:0,width:al.width()-f});n();E();R();w()}else{D.addClass("jspScrollable");aL=ay.maintainPosition&&(I||aa);if(aL){aN=aC();aM=aA()}aF();z();F();if(aL){N(aK?(T-aj):aN,false);M(aO?(Z-v):aM,false)}J();ag();an();if(ay.enableKeyboardNavigation){S()}if(ay.clickOnTrack){p()}C();if(ay.hijackInternalLinks){m()}}if(ay.autoReinitialise&&!av){av=setInterval(function(){ar(ay)},ay.autoReinitialiseDelay)}else{if(!ay.autoReinitialise&&av){clearInterval(av)}}aI&&D.scrollTop(0)&&M(aI,false);aP&&D.scrollLeft(0)&&N(aP,false);D.trigger("jsp-initialised",[aE||az])}function aF(){if(az){al.append(b('<div class="jspVerticalBar" />').append(b('<div class="jspCap jspCapTop" />'),b('<div class="jspTrack" />').append(b('<div class="jspDrag" />').append(b('<div class="jspDragTop" />'),b('<div class="jspDragBottom" />'))),b('<div class="jspCap jspCapBottom" />')));U=al.find(">.jspVerticalBar");ap=U.find(">.jspTrack");au=ap.find(">.jspDrag");if(ay.showArrows){aq=b('<a class="jspArrow jspArrowUp" />').bind("mousedown.jsp",aD(0,-1)).bind("click.jsp",aB);af=b('<a class="jspArrow jspArrowDown" />').bind("mousedown.jsp",aD(0,1)).bind("click.jsp",aB);if(ay.arrowScrollOnHover){aq.bind("mouseover.jsp",aD(0,-1,aq));af.bind("mouseover.jsp",aD(0,1,af))}ak(ap,ay.verticalArrowPositions,aq,af)}t=v;al.find(">.jspVerticalBar>.jspCap:visible,>.jspVerticalBar>.jspArrow").each(function(){t-=b(this).outerHeight()});au.hover(function(){au.addClass("jspHover")},function(){au.removeClass("jspHover")}).bind("mousedown.jsp",function(aI){b("html").bind("dragstart.jsp selectstart.jsp",aB);au.addClass("jspActive");var s=aI.pageY-au.position().top;b("html").bind("mousemove.jsp",function(aJ){V(aJ.pageY-s,false)}).bind("mouseup.jsp mouseleave.jsp",aw);return false});o()}}function o(){ap.height(t+"px");I=0;X=ay.verticalGutter+ap.outerWidth();Y.width(aj-X-f);try{if(U.position().left===0){Y.css("margin-left",X+"px")}}catch(s){}}function z(){if(aE){al.append(b('<div class="jspHorizontalBar" />').append(b('<div class="jspCap jspCapLeft" />'),b('<div class="jspTrack" />').append(b('<div class="jspDrag" />').append(b('<div class="jspDragLeft" />'),b('<div class="jspDragRight" />'))),b('<div class="jspCap jspCapRight" />')));am=al.find(">.jspHorizontalBar");G=am.find(">.jspTrack");h=G.find(">.jspDrag");if(ay.showArrows){ax=b('<a class="jspArrow jspArrowLeft" />').bind("mousedown.jsp",aD(-1,0)).bind("click.jsp",aB);x=b('<a class="jspArrow jspArrowRight" />').bind("mousedown.jsp",aD(1,0)).bind("click.jsp",aB);if(ay.arrowScrollOnHover){ax.bind("mouseover.jsp",aD(-1,0,ax));x.bind("mouseover.jsp",aD(1,0,x))}ak(G,ay.horizontalArrowPositions,ax,x)}h.hover(function(){h.addClass("jspHover")},function(){h.removeClass("jspHover")}).bind("mousedown.jsp",function(aI){b("html").bind("dragstart.jsp selectstart.jsp",aB);h.addClass("jspActive");var s=aI.pageX-h.position().left;b("html").bind("mousemove.jsp",function(aJ){W(aJ.pageX-s,false)}).bind("mouseup.jsp mouseleave.jsp",aw);return false});l=al.innerWidth();ah()}}function ah(){al.find(">.jspHorizontalBar>.jspCap:visible,>.jspHorizontalBar>.jspArrow").each(function(){l-=b(this).outerWidth()});G.width(l+"px");aa=0}function F(){if(aE&&az){var aI=G.outerHeight(),s=ap.outerWidth();t-=aI;b(am).find(">.jspCap:visible,>.jspArrow").each(function(){l+=b(this).outerWidth()});l-=s;v-=s;aj-=aI;G.parent().append(b('<div class="jspCorner" />').css("width",aI+"px"));o();ah()}if(aE){Y.width((al.outerWidth()-f)+"px")}Z=Y.outerHeight();q=Z/v;if(aE){at=Math.ceil(1/y*l);if(at>ay.horizontalDragMaxWidth){at=ay.horizontalDragMaxWidth}else{if(at<ay.horizontalDragMinWidth){at=ay.horizontalDragMinWidth}}h.width(at+"px");j=l-at;ae(aa)}if(az){A=Math.ceil(1/q*t);if(A>ay.verticalDragMaxHeight){A=ay.verticalDragMaxHeight}else{if(A<ay.verticalDragMinHeight){A=ay.verticalDragMinHeight}}au.height(A+"px");i=t-A;ad(I)}}function ak(aJ,aL,aI,s){var aN="before",aK="after",aM;if(aL=="os"){aL=/Mac/.test(navigator.platform)?"after":"split"}if(aL==aN){aK=aL}else{if(aL==aK){aN=aL;aM=aI;aI=s;s=aM}}aJ[aN](aI)[aK](s)}function aD(aI,s,aJ){return function(){H(aI,s,this,aJ);this.blur();return false}}function H(aL,aK,aO,aN){aO=b(aO).addClass("jspActive");var aM,aJ,aI=true,s=function(){if(aL!==0){Q.scrollByX(aL*ay.arrowButtonSpeed)}if(aK!==0){Q.scrollByY(aK*ay.arrowButtonSpeed)}aJ=setTimeout(s,aI?ay.initialDelay:ay.arrowRepeatFreq);aI=false};s();aM=aN?"mouseout.jsp":"mouseup.jsp";aN=aN||b("html");aN.bind(aM,function(){aO.removeClass("jspActive");aJ&&clearTimeout(aJ);aJ=null;aN.unbind(aM)})}function p(){w();if(az){ap.bind("mousedown.jsp",function(aN){if(aN.originalTarget===c||aN.originalTarget==aN.currentTarget){var aL=b(this),aO=aL.offset(),aM=aN.pageY-aO.top-I,aJ,aI=true,s=function(){var aR=aL.offset(),aS=aN.pageY-aR.top-A/2,aP=v*ay.scrollPagePercent,aQ=i*aP/(Z-v);if(aM<0){if(I-aQ>aS){Q.scrollByY(-aP)}else{V(aS)}}else{if(aM>0){if(I+aQ<aS){Q.scrollByY(aP)}else{V(aS)}}else{aK();return}}aJ=setTimeout(s,aI?ay.initialDelay:ay.trackClickRepeatFreq);aI=false},aK=function(){aJ&&clearTimeout(aJ);aJ=null;b(document).unbind("mouseup.jsp",aK)};s();b(document).bind("mouseup.jsp",aK);return false}})}if(aE){G.bind("mousedown.jsp",function(aN){if(aN.originalTarget===c||aN.originalTarget==aN.currentTarget){var aL=b(this),aO=aL.offset(),aM=aN.pageX-aO.left-aa,aJ,aI=true,s=function(){var aR=aL.offset(),aS=aN.pageX-aR.left-at/2,aP=aj*ay.scrollPagePercent,aQ=j*aP/(T-aj);if(aM<0){if(aa-aQ>aS){Q.scrollByX(-aP)}else{W(aS)}}else{if(aM>0){if(aa+aQ<aS){Q.scrollByX(aP)}else{W(aS)}}else{aK();return}}aJ=setTimeout(s,aI?ay.initialDelay:ay.trackClickRepeatFreq);aI=false},aK=function(){aJ&&clearTimeout(aJ);aJ=null;b(document).unbind("mouseup.jsp",aK)};s();b(document).bind("mouseup.jsp",aK);return false}})}}function w(){if(G){G.unbind("mousedown.jsp")}if(ap){ap.unbind("mousedown.jsp")}}function aw(){b("html").unbind("dragstart.jsp selectstart.jsp mousemove.jsp mouseup.jsp mouseleave.jsp");if(au){au.removeClass("jspActive")}if(h){h.removeClass("jspActive")}}function V(s,aI){if(!az){return}if(s<0){s=0}else{if(s>i){s=i}}if(aI===c){aI=ay.animateScroll}if(aI){Q.animate(au,"top",s,ad)}else{au.css("top",s);ad(s)}}function ad(aI){if(aI===c){aI=au.position().top}al.scrollTop(0);I=aI;var aL=I===0,aJ=I==i,aK=aI/i,s=-aK*(Z-v);if(ai!=aL||aG!=aJ){ai=aL;aG=aJ;D.trigger("jsp-arrow-change",[ai,aG,P,k])}u(aL,aJ);Y.css("top",s);D.trigger("jsp-scroll-y",[-s,aL,aJ]).trigger("scroll")}function W(aI,s){if(!aE){return}if(aI<0){aI=0}else{if(aI>j){aI=j}}if(s===c){s=ay.animateScroll}if(s){Q.animate(h,"left",aI,ae)
}else{h.css("left",aI);ae(aI)}}function ae(aI){if(aI===c){aI=h.position().left}al.scrollTop(0);aa=aI;var aL=aa===0,aK=aa==j,aJ=aI/j,s=-aJ*(T-aj);if(P!=aL||k!=aK){P=aL;k=aK;D.trigger("jsp-arrow-change",[ai,aG,P,k])}r(aL,aK);Y.css("left",s);D.trigger("jsp-scroll-x",[-s,aL,aK]).trigger("scroll")}function u(aI,s){if(ay.showArrows){aq[aI?"addClass":"removeClass"]("jspDisabled");af[s?"addClass":"removeClass"]("jspDisabled")}}function r(aI,s){if(ay.showArrows){ax[aI?"addClass":"removeClass"]("jspDisabled");x[s?"addClass":"removeClass"]("jspDisabled")}}function M(s,aI){var aJ=s/(Z-v);V(aJ*i,aI)}function N(aI,s){var aJ=aI/(T-aj);W(aJ*j,s)}function ab(aV,aQ,aJ){var aN,aK,aL,s=0,aU=0,aI,aP,aO,aS,aR,aT;try{aN=b(aV)}catch(aM){return}aK=aN.outerHeight();aL=aN.outerWidth();al.scrollTop(0);al.scrollLeft(0);while(!aN.is(".jspPane")){s+=aN.position().top;aU+=aN.position().left;aN=aN.offsetParent();if(/^body|html$/i.test(aN[0].nodeName)){return}}aI=aA();aO=aI+v;if(s<aI||aQ){aR=s-ay.verticalGutter}else{if(s+aK>aO){aR=s-v+aK+ay.verticalGutter}}if(aR){M(aR,aJ)}aP=aC();aS=aP+aj;if(aU<aP||aQ){aT=aU-ay.horizontalGutter}else{if(aU+aL>aS){aT=aU-aj+aL+ay.horizontalGutter}}if(aT){N(aT,aJ)}}function aC(){return -Y.position().left}function aA(){return -Y.position().top}function K(){var s=Z-v;return(s>20)&&(s-aA()<10)}function B(){var s=T-aj;return(s>20)&&(s-aC()<10)}function ag(){al.unbind(ac).bind(ac,function(aL,aM,aK,aI){var aJ=aa,s=I;Q.scrollBy(aK*ay.mouseWheelSpeed,-aI*ay.mouseWheelSpeed,false);return aJ==aa&&s==I})}function n(){al.unbind(ac)}function aB(){return false}function J(){Y.find(":input,a").unbind("focus.jsp").bind("focus.jsp",function(s){ab(s.target,false)})}function E(){Y.find(":input,a").unbind("focus.jsp")}function S(){var s,aI,aK=[];aE&&aK.push(am[0]);az&&aK.push(U[0]);Y.focus(function(){D.focus()});D.attr("tabindex",0).unbind("keydown.jsp keypress.jsp").bind("keydown.jsp",function(aN){if(aN.target!==this&&!(aK.length&&b(aN.target).closest(aK).length)){return}var aM=aa,aL=I;switch(aN.keyCode){case 40:case 38:case 34:case 32:case 33:case 39:case 37:s=aN.keyCode;aJ();break;case 35:M(Z-v);s=null;break;case 36:M(0);s=null;break}aI=aN.keyCode==s&&aM!=aa||aL!=I;return !aI}).bind("keypress.jsp",function(aL){if(aL.keyCode==s){aJ()}return !aI});if(ay.hideFocus){D.css("outline","none");if("hideFocus" in al[0]){D.attr("hideFocus",true)}}else{D.css("outline","");if("hideFocus" in al[0]){D.attr("hideFocus",false)}}function aJ(){var aM=aa,aL=I;switch(s){case 40:Q.scrollByY(ay.keyboardSpeed,false);break;case 38:Q.scrollByY(-ay.keyboardSpeed,false);break;case 34:case 32:Q.scrollByY(v*ay.scrollPagePercent,false);break;case 33:Q.scrollByY(-v*ay.scrollPagePercent,false);break;case 39:Q.scrollByX(ay.keyboardSpeed,false);break;case 37:Q.scrollByX(-ay.keyboardSpeed,false);break}aI=aM!=aa||aL!=I;return aI}}function R(){D.attr("tabindex","-1").removeAttr("tabindex").unbind("keydown.jsp keypress.jsp")}function C(){if(location.hash&&location.hash.length>1){var aK,aI,aJ=escape(location.hash.substr(1));try{aK=b("#"+aJ+', a[name="'+aJ+'"]')}catch(s){return}if(aK.length&&Y.find(aJ)){if(al.scrollTop()===0){aI=setInterval(function(){if(al.scrollTop()>0){ab(aK,true);b(document).scrollTop(al.position().top);clearInterval(aI)}},50)}else{ab(aK,true);b(document).scrollTop(al.position().top)}}}}function m(){if(b(document.body).data("jspHijack")){return}b(document.body).data("jspHijack",true);b(document.body).delegate("a[href*=#]","click",function(s){var aI=this.href.substr(0,this.href.indexOf("#")),aK=location.href,aO,aP,aJ,aM,aL,aN;if(location.href.indexOf("#")!==-1){aK=location.href.substr(0,location.href.indexOf("#"))}if(aI!==aK){return}aO=escape(this.href.substr(this.href.indexOf("#")+1));aP;try{aP=b("#"+aO+', a[name="'+aO+'"]')}catch(aQ){return}if(!aP.length){return}aJ=aP.closest(".jspScrollable");aM=aJ.data("jsp");aM.scrollToElement(aP,true);if(aJ[0].scrollIntoView){aL=b(a).scrollTop();aN=aP.offset().top;if(aN<aL||aN>aL+b(a).height()){aJ[0].scrollIntoView()}}s.preventDefault()
})}function an(){var aJ,aI,aL,aK,aM,s=false;al.unbind("touchstart.jsp touchmove.jsp touchend.jsp click.jsp-touchclick").bind("touchstart.jsp",function(aN){var aO=aN.originalEvent.touches[0];aJ=aC();aI=aA();aL=aO.pageX;aK=aO.pageY;aM=false;s=true}).bind("touchmove.jsp",function(aQ){if(!s){return}var aP=aQ.originalEvent.touches[0],aO=aa,aN=I;Q.scrollTo(aJ+aL-aP.pageX,aI+aK-aP.pageY);aM=aM||Math.abs(aL-aP.pageX)>5||Math.abs(aK-aP.pageY)>5;return aO==aa&&aN==I}).bind("touchend.jsp",function(aN){s=false}).bind("click.jsp-touchclick",function(aN){if(aM){aM=false;return false}})}function g(){var s=aA(),aI=aC();D.removeClass("jspScrollable").unbind(".jsp");D.replaceWith(ao.append(Y.children()));ao.scrollTop(s);ao.scrollLeft(aI);if(av){clearInterval(av)}}b.extend(Q,{reinitialise:function(aI){aI=b.extend({},ay,aI);ar(aI)},scrollToElement:function(aJ,aI,s){ab(aJ,aI,s)},scrollTo:function(aJ,s,aI){N(aJ,aI);M(s,aI)},scrollToX:function(aI,s){N(aI,s)},scrollToY:function(s,aI){M(s,aI)},scrollToPercentX:function(aI,s){N(aI*(T-aj),s)},scrollToPercentY:function(aI,s){M(aI*(Z-v),s)},scrollBy:function(aI,s,aJ){Q.scrollByX(aI,aJ);Q.scrollByY(s,aJ)},scrollByX:function(s,aJ){var aI=aC()+Math[s<0?"floor":"ceil"](s),aK=aI/(T-aj);W(aK*j,aJ)},scrollByY:function(s,aJ){var aI=aA()+Math[s<0?"floor":"ceil"](s),aK=aI/(Z-v);V(aK*i,aJ)},positionDragX:function(s,aI){W(s,aI)},positionDragY:function(aI,s){V(aI,s)},animate:function(aI,aL,s,aK){var aJ={};aJ[aL]=s;aI.animate(aJ,{duration:ay.animateDuration,easing:ay.animateEase,queue:false,step:aK})},getContentPositionX:function(){return aC()},getContentPositionY:function(){return aA()},getContentWidth:function(){return T},getContentHeight:function(){return Z},getPercentScrolledX:function(){return aC()/(T-aj)},getPercentScrolledY:function(){return aA()/(Z-v)},getIsScrollableH:function(){return aE},getIsScrollableV:function(){return az},getContentPane:function(){return Y},scrollToBottom:function(s){V(i,s)},hijackInternalLinks:b.noop,destroy:function(){g()}});ar(O)}e=b.extend({},b.fn.jScrollPane.defaults,e);b.each(["arrowButtonSpeed","trackClickSpeed","keyboardSpeed"],function(){e[this]=e[this]||e.speed});return this.each(function(){var f=b(this),g=f.data("jsp");if(g){g.reinitialise(e)}else{b("script",f).filter('[type="text/javascript"],:not([type])').remove();g=new d(f,e);f.data("jsp",g)}})};b.fn.jScrollPane.defaults={showArrows:false,maintainPosition:true,stickToBottom:false,stickToRight:false,clickOnTrack:true,autoReinitialise:false,autoReinitialiseDelay:500,verticalDragMinHeight:0,verticalDragMaxHeight:99999,horizontalDragMinWidth:0,horizontalDragMaxWidth:99999,contentWidth:c,animateScroll:true,animateDuration:300,animateEase:"ease",hijackInternalLinks:false,verticalGutter:0,horizontalGutter:0,mouseWheelSpeed:20,arrowButtonSpeed:0,arrowRepeatFreq:50,arrowScrollOnHover:false,trackClickSpeed:0,trackClickRepeatFreq:70,verticalArrowPositions:"split",horizontalArrowPositions:"split",enableKeyboardNavigation:true,hideFocus:false,keyboardSpeed:0,initialDelay:300,speed:30,scrollPagePercent:0.8}})(jQuery,this);

/*
 * Placeholder plugin for jQuery
 */
(function(b){function d(a){this.input=a;a.attr("type")=="password"&&this.handlePassword();b(a[0].form).submit(function(){if(a.hasClass("placeholder")&&a[0].value==a.attr("placeholder"))a[0].value=""})}d.prototype={show:function(a){if(this.input[0].value===""||a&&this.valueIsPlaceholder()){if(this.isPassword)try{this.input[0].setAttribute("type","text")}catch(b){this.input.before(this.fakePassword.show()).hide()}this.input.addClass("placeholder");this.input[0].value=this.input.attr("placeholder")}},
	hide:function(){if(this.valueIsPlaceholder()&&this.input.hasClass("placeholder")&&(this.input.removeClass("placeholder"),this.input[0].value="",this.isPassword)){try{this.input[0].setAttribute("type","password")}catch(a){}this.input.show();this.input[0].focus()}},valueIsPlaceholder:function(){return this.input[0].value==this.input.attr("placeholder")},handlePassword:function(){var a=this.input;a.attr("realType","password");this.isPassword=!0;if(b.browser.msie&&a[0].outerHTML){var c=b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
		"type=$1text$1"));this.fakePassword=c.val(a.attr("placeholder")).addClass("placeholder").focus(function(){a.trigger("focus");b(this).hide()});b(a[0].form).submit(function(){c.remove();a.show()})}}};var e=!!("placeholder"in document.createElement("input"));b.fn.placeholder=function(){return e?this:this.each(function(){var a=b(this),c=new d(a);c.show(!0);a.focus(function(){c.hide()});a.blur(function(){c.show(!1)});b.browser.msie&&(b(window).load(function(){a.val()&&a.removeClass("placeholder");c.show(!0)}),
	a.focus(function(){if(this.value==""){var a=this.createTextRange();a.collapse(!0);a.moveStart("character",0);a.select()}}))})}})(jQuery);

/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Version: 3.0.6
 */
(function($){var c=['DOMMouseScroll','mousewheel'];if($.event.fixHooks){for(var i=c.length;i;){$.event.fixHooks[c[--i]]=$.event.mouseHooks}}$.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var i=c.length;i;){this.addEventListener(c[--i],handler,false)}}else{this.onmousewheel=handler}},teardown:function(){if(this.removeEventListener){for(var i=c.length;i;){this.removeEventListener(c[--i],handler,false)}}else{this.onmousewheel=null}}};$.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}});function handler(a){var b=a||window.event,args=[].slice.call(arguments,1),delta=0,returnValue=true,deltaX=0,deltaY=0;a=$.event.fix(b);a.type="mousewheel";if(b.wheelDelta){delta=b.wheelDelta/120}if(b.detail){delta=-b.detail/3}deltaY=delta;if(b.axis!==undefined&&b.axis===b.HORIZONTAL_AXIS){deltaY=0;deltaX=-1*delta}if(b.wheelDeltaY!==undefined){deltaY=b.wheelDeltaY/120}if(b.wheelDeltaX!==undefined){deltaX=-1*b.wheelDeltaX/120}args.unshift(a,delta,deltaX,deltaY);return($.event.dispatch||$.event.handle).apply(this,args)}})(jQuery);

/*
 * @author trixta
 * @version 1.2
 */
(function($){var b={pos:[-260,-260]},minDif=3,doc=document,root=doc.documentElement,body=doc.body,longDelay,shortDelay;function unsetPos(){if(this===b.elem){b.pos=[-260,-260];b.elem=false;minDif=3}}$.event.special.mwheelIntent={setup:function(){var a=$(this).bind('mousewheel',$.event.special.mwheelIntent.handler);if(this!==doc&&this!==root&&this!==body){a.bind('mouseleave',unsetPos)}a=null;return true},teardown:function(){$(this).unbind('mousewheel',$.event.special.mwheelIntent.handler).unbind('mouseleave',unsetPos);return true},handler:function(e,d){var a=[e.clientX,e.clientY];if(this===b.elem||Math.abs(b.pos[0]-a[0])>minDif||Math.abs(b.pos[1]-a[1])>minDif){b.elem=this;b.pos=a;minDif=250;clearTimeout(shortDelay);shortDelay=setTimeout(function(){minDif=10},200);clearTimeout(longDelay);longDelay=setTimeout(function(){minDif=3},1500);e=$.extend({},e,{type:'mwheelIntent'});return($.event.dispatch||$.event.handle).apply(this,arguments)}}};$.fn.extend({mwheelIntent:function(a){return a?this.bind("mwheelIntent",a):this.trigger("mwheelIntent")},unmwheelIntent:function(a){return this.unbind("mwheelIntent",a)}});$(function(){body=doc.body;$(doc).bind('mwheelIntent.mwheelIntentDefault',$.noop)})})(jQuery);

/*
 * Tiny Carousel 1.9
 */
(function($){"use strict";$.tiny=$.tiny||{};$.tiny.carousel={options:{start:1,display:1,axis:'x',controls:true,pager:false,interval:false,intervaltime:3000,rewind:true,animation:true,duration:300,callback:null}};$.fn.tinycarousel_start=function(){$(this).data('tcl').start()};$.fn.tinycarousel_stop=function(){$(this).data('tcl').stop()};$.fn.tinycarousel_move=function(a){$(this).data('tcl').move(a-1,true)};function Carousel(d,e){var f=this,oViewport=$('.viewport:first',d),oContent=$('.overview:first',d),oPages=oContent.children(),oBtnNext=$('.next:first',d),oBtnPrev=$('.prev:first',d),oPager=$('.pager:first',d),iPageSize=0,iSteps=0,iCurrent=0,oTimer=undefined,bPause=false,bForward=true,bAxis=e.axis==='x';function setButtons(){if(e.controls){oBtnPrev.toggleClass('disable',iCurrent<=0);oBtnNext.toggleClass('disable',!(iCurrent+1<iSteps))}if(e.pager){var a=$('.pagenum',oPager);a.removeClass('active');$(a[iCurrent]).addClass('active')}}function setPager(a){if($(this).hasClass("pagenum")){f.move(parseInt(this.rel,10),true)}return false}function calcStep(){iCurrent=iCurrent+1===iSteps?-1:iCurrent;bForward=iCurrent+1===iSteps?false:iCurrent===0?true:bForward;return bForward?1:-1}function setTimer(){if(e.interval&&!bPause){clearTimeout(oTimer);oTimer=setTimeout(function(){f.move(calcStep())},e.intervaltime)}}function setEvents(){if(e.controls&&oBtnPrev.length>0&&oBtnNext.length>0){oBtnPrev.click(function(){if(!$(this).hasClass('disable')){f.move(-1)}else if(e.rewind){f.move(iSteps-1)}return false});oBtnNext.click(function(){if(!$(this).hasClass('disable')){f.move(1)}else if(e.rewind){f.move(calcStep())}return false})}if(e.interval){d.hover(f.stop,f.start)}if(e.pager&&oPager.length>0){$('a',oPager).click(setPager)}}this.stop=function(){clearTimeout(oTimer);bPause=true};this.start=function(){bPause=false;setTimer()};this.move=function(a,b){iCurrent=b?a:iCurrent+=a;if(iCurrent>-1){iCurrent=iCurrent>=iSteps?iSteps-1:iCurrent;var c={};c[bAxis?'scrollLeft':'scrollTop']=iCurrent*(iPageSize*e.display);oViewport.animate(c,{queue:false,duration:e.animation?e.duration:0,complete:function(){if(typeof e.callback==='function'){e.callback.call(this,oPages[iCurrent],iCurrent)}}});setButtons();setTimer()}};function initialize(){iPageSize=bAxis?$(oPages[0]).outerWidth(true):$(oPages[0]).outerHeight(true);var a=Math.max(0,Math.floor(((bAxis?oViewport.outerWidth():oViewport.outerHeight())/(iPageSize*e.display))-1));iSteps=Math.max(1,Math.round(oPages.length/e.display)-a);iCurrent=Math.min(iSteps,Math.max(1,e.start))-2;oContent.css(bAxis?'width':'height',(iPageSize*oPages.length));f.move(1);setEvents();return f}return initialize()}$.fn.tinycarousel=function(a){var b=$.extend({},$.tiny.carousel.options,a);this.each(function(){$(this).data('tcl',new Carousel($(this),b))});return this}}(jQuery));

// markItUp! Universal MarkUp Engine, JQuery plugin
// v 1.1.x
// http://markitup.jaysalvat.com/
(function($){$.fn.markItUp=function(p,q){var r,ctrlKey,shiftKey,altKey;ctrlKey=shiftKey=altKey=false;r={id:'',nameSpace:'',root:'',previewInWindow:'',previewAutoRefresh:true,previewPosition:'after',previewTemplatePath:'~/templates/preview.html',previewParser:false,previewParserPath:'',previewParserVar:'data',resizeHandle:true,beforeInsert:'',afterInsert:'',onEnter:{},onShiftEnter:{},onCtrlEnter:{},onTab:{},markupSet:[{}],buildFlag:1};$.extend(r,p,q);if(!r.root){$('script').each(function(a,b){miuScript=$(b).get(0).src.match(/(.*)jquery\.markitup(\.pack)?\.js$/);if(miuScript!==null){r.root=miuScript[1]}})}return this.each(function(){var o,textarea,levels,scrollPosition,caretPosition,caretOffset,clicked,hash,header,footer,previewWindow,template,iFrame,abort;o=$(this);textarea=this;levels=[];abort=false;scrollPosition=caretPosition=0;caretOffset=-1;r.previewParserPath=localize(r.previewParserPath);r.previewTemplatePath=localize(r.previewTemplatePath);function localize(a,b){if(b){return a.replace(/("|')~\//g,"$1"+r.root)}return a.replace(/^~\//,r.root)}function init(){id='';nameSpace='';if(r.id){id='id="'+r.id+'"'}else if(o.attr("id")){id='id="markItUp'+(o.attr("id").substr(0,1).toUpperCase())+(o.attr("id").substr(1))+'"'}if(r.nameSpace){nameSpace='class="'+r.nameSpace+'"'}o.wrap('<div '+nameSpace+'></div>');o.wrap('<div '+id+' class="markItUp"></div>');o.wrap('<div class="markItUpContainer"></div>');o.addClass("markItUpEditor");header=$('<div class="bbcode_top markItUpHeader"></div>').insertBefore(o);$(dropMenus(r.markupSet)).appendTo(header);footer=$('<div class="markItUpFooter"></div>').insertAfter(o);if(r.resizeHandle===true&&$.browser.safari!==true){resizeHandle=$('<div class="markItUpResizeHandle"></div>').insertAfter(o).bind("mousedown",function(e){var h=o.height(),y=e.clientY,mouseMove,mouseUp;mouseMove=function(e){o.css("height",Math.max(20,e.clientY+h-y)+"px");return false};mouseUp=function(e){$("html").unbind("mousemove",mouseMove).unbind("mouseup",mouseUp);return false};$("html").bind("mousemove",mouseMove).bind("mouseup",mouseUp)});footer.append(resizeHandle)}o.keydown(keyPressed).keyup(keyPressed);o.bind("insertion",function(e,a){if(a.target!==false){get()}if(textarea===$.markItUp.focused){markup(a)}});o.focus(function(){$.markItUp.focused=this})}function dropMenus(e){var f=$('<div class="ul"></div>'),i=0;$('.li:hover > .ul',f).css('display','block');$('<div class="bbcode_button_left"></div>').appendTo(f);$.each(e,function(a){var b=this,t='',text='',custom='',title,li,j;title=(b.key)?(b.name||'')+' [Ctrl+'+b.key+']':(b.name||'');switch(b.name){case'H1':title='Заголовок 1-го уровня';break;case'H2':title='Заголовок 2-го уровня';break;case'H3':title='Заголовок 3-го уровня';break;case'b':title='Жирный текст';break;case'i':title='Наклонный текст';break;case'U':title='Подчёркнутый текст';break;case'align':title='Сдвиг текста';break;case'picture':title='Вставка картинки';break;case'list_num':title='Элемент числового списка';break;case'list_num_sub':title='Подэлемент числового списка';break;case'list':title='Элемент маркированного списка';break;case'list_sub':title='Подэлемент маркированного списка';break;case'link':title='Ссылка';break;case'smile':title='Смайлы';custom='custom_a';text='Смайлы';c='onclick = "attachObject.controller(\'getAttachForm\', \'smile\')"';break}if(b.preview){title=b.preview;text=b.preview;b.name='custom_a';custom='custom_a'}if(b.nextline){$('<div class="bbcode_button_right"></div>').appendTo(f);$('<div class="bbcode_line"></div>').appendTo(f);$('<div class="bbcode_button_left"></div>').appendTo(f)}if(b.name){var c;switch(b.name){case'box':case'file':title='Загрузка архивов';text='';b.name='box';custom='box';c='onclick = "attachObject.controller(\'getAttachForm\', \'file\')"';break;case'dynamic':case'sample':title='Загрузка семпла';text='';b.name='dynamic';custom='dynamic';c='onclick = "attachObject.controller(\'getAttachForm\', \'sample\')"';break;case'image':title='Загрузка картинки';text='';c='onclick = "attachObject.controller(\'getAttachForm\', \'image\')"';break;case'music':title='Прикрепить трек';text='';c='onclick = "attachObject.controller(\'getAttachForm\', \'track\')"';break;case'video':title='Прикрепить видео';c='onclick = "attachObject.controller(\'getAttachForm\', \'video\')"';break;case'link':title='Вставить ссылку';c='onclick = "attachObject.controller(\'getAttachForm\', \'link\')"';break}}key=(b.key)?'accesskey="'+b.key+'"':'';key=(b.key)?'accesskey="'+b.key+'"':'';if(b.separator){var d=f.children().last();d.prevUntil('.bbcode_button_left').add(d).wrapAll('<div class="bbcode_group"></div>');$('<div class="bbcode_button_right"></div>').appendTo(f);$('<div class="bbcode_button_left"></div>').appendTo(f)}else{i++;for(j=levels.length-1;j>=0;j--){t+=levels[j]+"-"}li=$('<div class=" bbcode_button_center markItUpButton markItUpButton'+t+(i)+' '+(b.className||'')+'"><a href="javascript:void(0)" '+key+' title="'+title+'" class="bbcode_'+b.name+' '+custom+'" '+(c||'')+' style="text-decoration: none;">'+text+'</a></div>').bind("contextmenu",function(){return false}).click(function(){return false}).bind("focusin",function(){o.focus()}).mouseup(function(){if(b.call){eval(b.call)()}setTimeout(function(){markup(b)},1);return false}).hover(function(){$('> .ul',this).show();$(document).one('click',function(){$('.ul .ul',header).hide()})},function(){$('> div',this).hide()}).appendTo(f);if(b.dropMenu){levels.push(i);$(li).addClass('markItUpDropMenu').append(dropMenus(b.dropMenu))}}});text='';custom='';$('<li class="bbcode_button_right"></li>').appendTo(f);var g=f.children().last();g.prevUntil('.bbcode_button_left').add(g).wrapAll('<div class="bbcode_group"></div>');levels.pop();return f}function magicMarkups(c){if(c&&!r.buildFlag){c=c.toString();c=c.replace(/\(\!\(([\s\S]*?)\)\!\)/g,function(x,a){var b=a.split('|!|');if(altKey===true){return(b[1]!==undefined)?b[1]:b[0]}else{return(b[1]===undefined)?"":b[0]}});c=c.replace(/\[\!\[([\s\S]*?)\]\!\]/g,function(x,a){var b=a.split(':!:');if(abort===true){return false}value=prompt(b[0],(b[1])?b[1]:'');if(value===null){abort=true}return value});return c}return""}function prepare(a){if($.isFunction(a)){a=a(hash)}return magicMarkups(a)}function build(a,b){var c=prepare(clicked.openWith);var d=prepare(clicked.placeHolder);var e=prepare(clicked.replaceWith);var f=prepare(clicked.closeWith);var g=prepare(clicked.openBlockWith);var h=prepare(clicked.closeBlockWith);var i=clicked.multiline;var j=a||selection||e||d;var k=get(true);if(b)return[c.length+k[0],j.length];if(e!==""){block=c+e+f}else if(selection===''&&d!==''){block=c+d+f}else{a=a||selection;var m=selection.split(/\r?\n/),blocks=[];for(var l=0;l<m.length;l++){line=m[l];var n;if(n=line.match(/ *$/)){blocks.push(c+line.replace(/ *$/g,'')+f+n)}else{blocks.push(c+line+f)}}block=blocks.join("\n")}block=g+block+h;return{block:block,openWith:c,replaceWith:e,placeHolder:d,closeWith:f}}function markup(a){var b,j,n,i;hash=clicked=a;get();$.extend(hash,{line:"",root:r.root,textarea:textarea,selection:(selection||''),caretPosition:caretPosition,ctrlKey:ctrlKey,shiftKey:shiftKey,altKey:altKey});prepare(r.beforeInsert);prepare(clicked.beforeInsert);if((ctrlKey===true&&shiftKey===true)||a.multiline===true){prepare(clicked.beforeMultiInsert)}$.extend(hash,{line:1});var c;var d=selection;if((ctrlKey===true&&shiftKey===true)){lines=selection.split(/\r?\n/);for(j=0,n=lines.length,i=0;i<n;i++){if($.trim(lines[i])!==''){$.extend(hash,{line:++j,selection:lines[i]});lines[i]=build(lines[i]).block}else{lines[i]=""}}string={block:lines.join('\n')};start=caretPosition;b=string.block.length+(($.browser.opera)?n-1:0)}else if(ctrlKey===true){c=build(selection,true);r.buildFlag=0;string=build(selection);start=caretPosition+string.openWith.length;b=string.block.length-string.openWith.length-string.closeWith.length;b=b-(string.block.match(/ $/)?1:0);b-=fixIeBug(string.block)}else if(shiftKey===true){c=build(selection,true);r.buildFlag=0;string=build(selection);start=caretPosition;b=string.block.length;b-=fixIeBug(string.block)}else{c=build(selection,true);r.buildFlag=0;string=build(selection);start=caretPosition+string.block.length;b=0;start-=fixIeBug(string.block)}if((selection===''&&string.replaceWith==='')){caretOffset+=fixOperaBug(string.block);start=caretPosition+string.openWith.length;b=string.block.length-string.openWith.length-string.closeWith.length;caretOffset=o.val().substring(caretPosition,o.val().length).length;caretOffset-=fixOperaBug(o.val().substring(0,caretPosition))}$.extend(hash,{caretPosition:caretPosition,scrollPosition:scrollPosition});if(string.block!==selection&&abort===false){insert(string.block);set(start,b)}else{caretOffset=-1}get();$.extend(hash,{line:'',selection:selection});if((ctrlKey===true&&shiftKey===true)||a.multiline===true){prepare(clicked.afterMultiInsert)}prepare(clicked.afterInsert);prepare(r.afterInsert);if(previewWindow&&r.previewAutoRefresh){refreshPreview()}if(string.openWith)c[0]+=string.openWith.length;if(!d&&string.placeHolder)c[1]+=string.placeHolder.length;set(c[0],c[1]);shiftKey=altKey=ctrlKey=abort=false;r.buildFlag=1}function fixOperaBug(a){if($.browser.opera){return a.length-a.replace(/\n*/g,'').length}return 0}function fixIeBug(a){if($.browser.msie){return a.length-a.replace(/\r*/g,'').length}return 0}function insert(a){if(document.selection){var b=document.selection.createRange();b.text=a}else{textarea.value=textarea.value.substring(0,caretPosition)+a+textarea.value.substring(caretPosition+selection.length,textarea.value.length)}}function set(a,b){if(textarea.createTextRange){if($.browser.opera&&$.browser.version>=9.5&&b==0){return false}range=textarea.createTextRange();range.collapse(true);range.moveStart('character',a);range.moveEnd('character',b);range.select()}else if(textarea.setSelectionRange){textarea.setSelectionRange(a,a+b)}textarea.scrollTop=scrollPosition;textarea.focus()}function get(a){textarea.focus();if(a){var b=caretPosition}scrollPosition=textarea.scrollTop;if(document.selection){selection=document.selection.createRange().text;if($.browser.msie){var c=document.selection.createRange(),rangeCopy=c.duplicate();rangeCopy.moveToElementText(textarea);caretPosition=-1;while(rangeCopy.inRange(c)){rangeCopy.moveStart('character');caretPosition++}}else{caretPosition=textarea.selectionStart}}else{caretPosition=textarea.selectionStart;selection=textarea.value.substring(caretPosition,textarea.selectionEnd)}if(!a)return selection;else{caretPosition=b;return[caretPosition,textarea.selectionEnd]}}function preview(){refreshPreview()}function refreshPreview(){renderPreview()}function renderPreview(){var c=$.wikiText(localize(o.val(),1));c=c.replace(/<p><br>/g,'<p>');c=c.replace(/<p><br\/>/g,'<p>');if($('.markItUpPreview').length==1)$('.markItUpPreview').html('<br/><div style="padding-bottom: 0;"><h3><strong>Предпросмотр:</strong></h3></div>'+c);else $(textarea).parents('.wiki_editor').find('.markItUpPreview').html('<br/><div style="padding-bottom: 0;"><h3><strong>Предпросмотр:</strong></h3></div>'+c);if($('.sample_player').length){$(".version_time_line").find(".version_line_style").slider({animate:true,max:100,min:1,value:1,stop:function(a,b){CjPlayer.setPosition($(this).slider('value')*$(this).attr('timelength')/100)}});$(".sample_player").find('.version_volume_line').find(".version_line_style").slider({animate:true,max:100,min:1,value:75,change:function(a,b){CjPlayer.setVolume(b.value);$('.version_volume_line').slider({value:b.value});$('iframe#player-frame').contents().find('#slider').slider({value:b.value});$('.trackBar').slider({value:b.value})}});$(".sample_player").find(".ui-slider-handle").addClass("version_line_pointer")}}function keyPressed(e){shiftKey=e.shiftKey;altKey=e.altKey;ctrlKey=(!(e.altKey&&e.ctrlKey))?(e.ctrlKey||e.metaKey):false;if(e.type==='keydown'){if(ctrlKey===true){li=$('a[accesskey="'+String.fromCharCode(e.keyCode)+'"]',header).parent('li');if(li.length!==0){ctrlKey=false;setTimeout(function(){li.triggerHandler('mouseup')},1);return false}}if(e.keyCode===13||e.keyCode===10){if(ctrlKey===true){ctrlKey=false;markup(r.onCtrlEnter);return r.onCtrlEnter.keepDefault}else if(shiftKey===true){shiftKey=false;markup(r.onShiftEnter);return r.onShiftEnter.keepDefault}else{markup(r.onEnter);return r.onEnter.keepDefault}}if(e.keyCode===9){if(shiftKey==true||ctrlKey==true||altKey==true){return false}if(caretOffset!==-1){get();caretOffset=o.val().length-caretOffset;set(caretOffset,0);caretOffset=-1;return false}else{markup(r.onTab);return r.onTab.keepDefault}}}}init()})};$.fn.markItUpRemove=function(){return this.each(function(){var a=$(this).unbind().removeClass('markItUpEditor');a.parent('div').parent('div.markItUp').parent('div').replaceWith(a)})};$.markItUp=function(a){var b={target:false};$.extend(b,a);if(b.target){return $(b.target).each(function(){$(this).focus();$(this).trigger('insertion',[b])})}else{$('textarea').trigger('insertion',[b])}}})(jQuery);

/*
 * Kajabity Wiki Text Plugin for jQuery http://www.kajabity.com/jquery-wikitext/
 * http://www.williams-technologies.co.uk
 */
(function(Z){Z.wikiText=function(k){var l=(k||'').toString();var m='';var n=/([^\r\n]*)(\r\n?|\n)/g;var o;var q=0;var r;var t=/^([ \t]*)$/;var u=/^(={1,6})[ \t]+([^=]+)(={1,6})[ \t]*$/;var v=/^[ \t]{0,}\*[ \t]+(.+)$/;var w=/^[ \t]{0,}\*\*[ \t]+(.+)$/;var x=/^[ \t]{0,}#[ \t]+(.+)$/;var y=/^[ \t]{0,}##[ \t]+(.+)$/;var z=/^\{{3}$/;var A=/^\}{3}$/;var B=/^[ \t]+(.+)$/;var C=/^-{4,}$/;var D;var E=false;var F=false;var G=false;var H=false;var I=false;var J=false;var K=false;var L=[];var M=[];var N={italic:"<em>",italic_end:"</em>",monospace:"<tt>",strikethrough:"<strike>",superscript:"<sup>",subscript:"<sub>"};var O={monospace:"</tt>",strikethrough:"</strike>",superscript:"</sup>",subscript:"</sub>"};var P=function(){var a='';var b;while(L.length>0){b=L.pop();a+=O[b];M.push(b)}return a};var Q=function(){m+=P();if(E){m+="</p>\n";E=false}if(F){m+="</li>\n</ol>\n";F=false}if(G){m+="</li>\n</ol>\n";G=false}if(H){m+="</li>\n</ul>\n";H=false}if(J){m+="</p>\n";J=false}};var R=function(){var a='';while(M.length>0){var b=M.pop();a+=N[b];L.push(b)}return a};var S=function(a){var b='';if(Z.inArray(a,L)>-1){var c;do{c=L.pop();b+=O[c];if(c===a){break}M.push(c)}while(c!==a);b+=R()}else{L.push(a);b=N[a]}return b};var T=function(a,p,b,s){var c;if(typeof $(this).attr('id')=='string'){c=$(this).attr('id').split('id="')[1];c=c.split('"')[0];c=$('#'+c).find('textarea').attr('id')}var d=/<img [^<]+\/>/g;var e=ShowSmiles('#'+c,true,p);if(e.match(d).length>1)return':'+p+':';else return e}; var U=function(a,p,b,s){if(a.match(/\[file\]/))return attachObject.controller('replaceBBCode','file',p);if(a.match(/\[sample\]/))return attachObject.controller('replaceBBCode','sample',p);if(a.match(/\[img\]/))return attachObject.controller('replaceBBCode','image',p);if(a.match(/\[cjplayer\]/))return attachObject.controller('replaceBBCode','track',p);if(a.match(/\[cjclub\]/))return attachObject.controller('replaceBBCode','track',p);if(a.match(/\[utube\]/))return attachObject.controller('replaceBBCode','video',p);return'Ошибка!'}; var V=function(a,b){var c=(a||'').toString();var d='';var e;var f=0;var g;var h;var i;var j,link;if(c.indexOf('![')){c=c.split('![');c=c.join('! [')}c=c.replace(/Цитируемый текст:/g,'');d=c;d=d.replace(/\[b\]/g,'<strong>');d=d.replace(/\[\/b\]/g,'</strong>');d=d.replace(/\[i\]/g,'<em>');d=d.replace(/\[\/i\]/g,'</em>');d=d.replace(/\[u\]/g,'<u>');d=d.replace(/\[\/u\]/g,'</u>');d=d.replace(/====\s(.+)\s====/g,'<h4 style="font-size: 12px;">$1</h4>');d=d.replace(/===\s(.+)\s===/g,'<h3 style="font-size: 17px;">$1</h3>');d=d.replace(/==\s(.+)\s==/g,'<h2 style="font-size: 25px;">$1</h2>');d=d.replace(/\[\[Image:([\w\/\&\+\?\.%=(\[\]);,-:]+)\|(\w+)\]\]/g,'<img src="$1" title="$2" alt="$2" style="max-width: 468px;">');d=d.replace(/\[file\](\d+)\[\/file\]/g,U);d=d.replace(/\[sample\](\d+)\[\/sample\]/g,U);d=d.replace(/\[img\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/img\]/g,U);d=d.replace(/\[cjclub\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjclub\]/g,U);d=d.replace(/\[cjplayer\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/cjplayer\]/g,U);d=d.replace(/\[utube\]([\w\/\&\+\?\.%=(\[\]);<>,-:]+)\[\/utube\]/g,U);d=d.replace(/\[\/url\]/g,'</a>');d=d.replace(/\[url=([\w\s\/\&\+\?\.%=(\[\]);,-:]+)\](.+)/g,'<a href="$1" target="_blank">$2');d=d.replace(/\[url\]([\w\s\/\&\+\?\.%=(\[\]);,-:]+)/g,'<a href="$1" target="_blank">$1');d=d.replace(/((?:\s|^)https?:\/\/(\S+)?)/g,'<a href="$1" target="_blank">$1</a>');d=d.replace(/:([a-zA-Z0-9]{1,}):/g,T);d=d.replace(/\[quote\]/g,'<div class="quote"><i><div style="border: 1px solid #d0d0d0; max-width: 350px; background-color: #efefef; padding: 5px;"><b>Цитата:</b><br/>');d=d.replace(/\[\/quote\]/g,'</div></i></div>');return d};var W=function(){if(q<l.length){o=n.exec(l);if(o!=null){q=n.lastIndex;r=o[1]}else{r=l.substring(q);q=l.length}}else{r=null}return r};while(W()!=null){if(K){if(r.match(A)){K=false;m+="</pre>\n"}else{m+=Z.wikiText.safeText(r)+"\n"}}else if(r.length===0||t.test(r)){Q()}else if((D=r.match(u))!==null){Q();var X=D[1].length;var Y='';switch(X){case 2:Y=" style='font-size: 25px;'";break;case 3:Y=" style='font-size: 17px;'";break;case 4:Y=" style='font-size: 12px;'";break}m+="\n<h"+X+Y+">"+R()+V(D[2])+P()+"</h"+X+">\n\n"}else if((D=r.match(v))!==null){if(I){m+=P()+"</ul>\n";I=false}if(H){m+=P()+"</li>\n"}else{Q();m+="<ul>\n";H=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(w))!==null){if(I){m+=P()+"</li>\n"}else{m+="</li><ul>\n";I=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(x))!==null){if(G){m+=P()+"</ol>\n";G=false}if(F){m+=P()+"</li>\n"}else{Q();m+="<ol>\n";F=true}m+="<li>"+R()+V(D[1])}else if((D=r.match(y))!==null){if(G){m+=P()+"</li>\n"}else{m+="</li><ol>\n";G=true}m+="<li>"+R()+V(D[1])}else if(r.match(z)){Q();m+="<pre>\n";K=true}else if(r.match(C)){Q();m+="<hr/>\n"}else if((D=r.match(B))){if(!(J||F||H)){Q();m+="<p>\n";m+=R();J=true}m+="\n"+V(D[1])}else{if(!E){Q();m+="<p>\n";m+=R();E=true}m+=V(r)+"\n"}}Q();return m};Z.wikiText.safeText=function(a){return(a||'').replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")};Z.wikiText.re_link=/^((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+$/;Z.wikiText.re_mail=/^(mailto:)?([_.\w\-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})$/;Z.wikiText.namedLink=function(a,b){var c;var d;if(!a){return Z.wikiText.safeText(b)}if(Z.wikiText.re_mail.test(a)){a=a.replace(/mailto:/,"");c=encodeURI("mailto:"+a)}else{c=a}if(!b){b=decodeURI(a)}d=Z.wikiText.safeText(b);return d.link(c)};Z.fn.wikiText=function(a){return this.html(Z.wikiText(a))}})(jQuery);


// markItUp!
// http://markitup.jaysalvat.com/
wikiSettings = {
	previewParserPath:	'', // path to your Wiki parser
	onShiftEnter:		{keepDefault:false, replaceWith:'\n\n'},
	markupSet: [
		{name:'b', key:'B', openWith:"[b]", closeWith:"[/b]"},
		{name:'i', key:'I', openWith:"[i]", closeWith:"[/i]"},
		{name:'U', key:'U', openWith:"[u]", closeWith:"[/u]"},
		{name:'S', key:'S', openWith:"[s]", closeWith:"[/s]"},
		{separator:'---------------' },
		{name:'H1', key:'1', openWith:'== ', closeWith:' ==', placeHolder:'Заголовок 1...' },
		{name:'H2', key:'2', openWith:'=== ', closeWith:' ===', placeHolder:'Заголовок 2...' },
		{name:'H3', key:'3', openWith:'==== ', closeWith:' ====', placeHolder:'Заголовок 3...' },
		{separator:'---------------' },
		/*{name:'S', key:'S', openWith:'<s>', closeWith:'</s>'},*/
		//{name:'align', openWith:'(!( :|!|)!)'},
		{name:'list_num', openWith:'(!( # |!| #)!)'},
		{name:'list_num_sub', openWith:'(!( ## |!| ##)!)'},
		{name:'list', openWith:'(!( * |!| *)!)'},
		{name:'list_sub', openWith:'(!( ** |!| **)!)'},
		{separator:'---------------' },
		//{name:'picture', key:"P", replaceWith:'[[Image:[![Url:!:http://]!]|[![name]!]]]'},
		/*{name:'link', key:"L", openWith:"[[![Link]!] ", closeWith:']', placeHolder:'Ссылка...' },*/
		{name:'link'}, /*openWith:"[url=http://] ", closeWith:'[/url]', placeHolder:'Текст...',*/
		/*{name:'Quotes', openWith:'(!(> |!|>)!)', placeHolder:''},*/
		{separator:'---------------' },
		{name:'file', className:'bbcode_box'},
		{name:'sample', className:'bbcode_music'},
		{name:'image', className:'bbcode_image'},
		{name:'music', className:'bbcode_music'},
		{name:'video', className:'bbcode_video'},
		{separator:'---------------' },
		{name:'smile'},
		{separator:'---------------' },
		{name:'video', call:'preview', className:'preview', preview: 'Предпросмотр'}
	]
};

// main.js
window.CJ = window.CJ || {};

// count
CJ = {
  //TODO приделать scrollpane к плейлисту
	slide: function(){
		var scrollContainer = $('#playlist_content'),
			scrollList = $('#playlist_list'),
			scrollSlider = $('#playlist_scroll'),
			delta = scrollList.height() - scrollContainer.height(),
			wheel = true,
			itemHeight = 51,
			dH = 100 / (delta/itemHeight);


		function slideHandle( e, ui ) {
			scrollContainer.stop(true, true).animate({"scrollTop": (1 - ui.value / 100) * delta });
		}

		if ( delta > 0 ) {
			scrollSlider.show().slider({
				animate:true,
				orientation: "vertical",
				range: "min",
				min:0,
				max:100,
				value:100,
				slide: slideHandle
			});

			scrollContainer.mousewheel(function(e, d) {
				if (!wheel) return false;
				wheel = false;

				var value = scrollSlider.slider('option', 'value');

				if (d > 0) { value += dH; }
				else if (d < 0) { value -= dH; }

				value = Math.max(0, Math.min(100, value));
				scrollSlider.slider('value', value);
				scrollContainer.stop(true, false).animate({"scrollTop": (1 - value / 100) * delta }, function(){ wheel = true;});
				e.preventDefault();
			});

		} else {
			scrollSlider.hide();
		}

	},
	showToggle: function(){
		var link = $(this).attr('href');
		$(link).slideToggle();
		return false;
	},
	menuToggle: function(){
		$(this).next('.am_submenu').slideToggle(300);
		return false;
	},
	addTextToggle: function(){
		$(this).parents('.gb').find('.col_text_s_add').slideToggle(300);
		return false;
	},
	toTop: function(e){
		e.preventDefault();
		$("body,html").animate( {scrollTop: 0}, 800);
	},
  // вынес в main-header
//	scroller: function(){
//		var top = document.documentElement.scrollTop || document.body.scrollTop;
//		if (top) {
//			$('#header').addClass('fixed');
//		} else {
//			$('#header').removeClass('fixed');
//		}
//		var offset = $('.promo_list').offset();
//		var offsetTop = offset.top-top;
//		if(offsetTop<150) {
//			$('.promo_what').hide();
//		}
//		else {
//			$('.promo_what').show();
//		}
//        var d = document.body,
//            header = document.getElementById('header'),
//            headerShadow = document.getElementById('header_shadow'),
//            maxScroll = 250;
//
//        headerShadowHeight = top ? ((top > maxScroll) ? 20 : 20 * top/maxScroll ) : 0;
//
//        headerShadow.style.height = headerShadowHeight + "px";
//        d.className = top === 0 ? 'onTop' : '';
//        //header.className = top ? 'fixed' : '';
//	},
	toggleDeletedComment: function(){
		$(this).parents('.com').toggleClass('com_deleted_prev');
		return false;
	},
	toggleAppealComment: function(){
		$(this).parents('.com').toggleClass('com_appeal_prev');
		return false;
	},
	/*
	 * TODO: change into 1 article edit function module
	 */
	aeImages: function(id){
		var $album = $('#' + id),
			$form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			data = $album.data("image");


	},
	aeImageDescription: function(e){
		var th = this,
			$inp = $(th).parents('.ae_album__item').find('.ae_image__desc'),
			inp_val = $inp.val(),
			$form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			$album = $('#ae_album'),
			data = $.data($album, "image");

		if (inp_val) {
			$text.val(inp_val);
		} else {
			$text.val("");
		}

		$('.ae_image__desc_add', $album).removeClass('link_active');
		$(this).addClass('link_active');
		$album.data("image", $inp.attr('name'));  //заносим идентификатор поля в темп

		var l = $(this).offset().left - 190,
			t = $(this).offset().top + 20;

		$form.show().css({top: t, left: l<0?0:l});
		return false;
	},
	aeImageDescriptionText: function(){
		var $form = $('#ae_image__description'),
			$text = $('#ae_image__description_text'),
			$album = $('#ae_album'),
			data = $album.data("image");

		if (data) {
			$('.ae_image__desc[name="' + data + '"]', $album).val($text.val());
		}

		$form.hide();
		$.removeData($album, "image");
		$('.ae_image__desc_add', $album).removeClass('link_active');
	},
	aeImageRemove: function(){
		$(this).parents('.ae_album__item').remove();
		return false;
	},
	baloonOpener: function(e){
		if($(e.target).hasClass('opener')) {
			$(this).toggleClass('open');
		}
	},
	likeTrack: function(){
		$(this).toggleClass('gbt_like_ico_ok');
	},
	favoTrack: function(){
		$(this).toggleClass('gbt_favo_ico_ok');
	},
	playPauseTrack: function(){
		$(this).toggleClass('gbt_pause__ico');
	},
	showBaloonBtns: function(){
		$(this).parents('.baloon').find('.baloon_btns').show();
		return false;
	},
	trackSortable1: function(){
		$(this).parents('.gb_sortable').addClass('gb_sortable_on gb_sortable_on1').removeClass('gb_sortable_on2');
		return false;
	},
	trackSortable2: function(){
		$(this).parents('.gb_sortable').addClass('gb_sortable_on gb_sortable_on2').removeClass('gb_sortable_on1');
		return false;
	},
	trackSortableOff: function(){
		$(this).parents('.gb_sortable').removeClass('gb_sortable_on gb_sortable_on1 gb_sortable_on2');
		return false;
	},
	openMoreFaces: function(){
		var $th = $(this);
		$th.toggleClass('gbt_group__more__open');
		$th.parents('.gb').find('.gbt__more').toggleClass('open');
		return false;
	},
	makeInactive: function(){
		var $th = $(this);
		$th.toggleClass('ico2_eye2');
		$th.parents('.gb').toggleClass('gb_ina');
		return false;
	},
	changeTab: function(){
		var $th = $(this),
			id = $th.data('rel');
		$th
			.addClass('fa_tab__active')
			.siblings()
			.removeClass('fa_tab__active');

		$('#' + id)
			.show()
			.siblings('.tab_cont')
			.hide();
		return false;
	},
	bodyLock: function(){
		var w1 = $(window).width()
			,w2;
		$(document.body).addClass('body_lock');
		w2 = $(window).width();

		$('html').css('margin-right', w2-w1 + "px");
	},
	bodyUnlock: function(){
		$(document.body).removeClass('body_lock');
		$('html').css('margin-right','');
	},
	showGaleryComments: function(){
		CJ.bodyLock();
		$('#galery_overlay').show();
		return false;
	},
	hideGaleryComments: function(){
		CJ.bodyUnlock();
		$('#galery_overlay').hide();
		return false;
	},
	togglePeerRadio: function(){
		$(this)
			.toggleClass('pl_act')
			.toggleClass('rd_act');

		return false;
	},
	togglePlay: function(){
		$(this)
			.toggleClass('ico5_pause');

		return false;
	},
	stop: function(e){
		e.stopPropagation();
		e.preventDefault();
	},
	scrollOpts: {
		autoReinitialise:true,
		initialDelay:500
	},
	toggleMcList: function() {
		var $th = $(this);
		$th.toggleClass('pl_dw');
		$th.parent().next('.mc_clist_out').slideToggle(300);

		return false;
	},
	toggleMcList2: function() {
		var $th = $(this);
		$th.toggleClass('mess_proj_sl_open');
		$th.next('.mess_proj_list').slideToggle(300);
		return false;
	}
	/*
	 * end module
	 */
};

CJ.galery = function(id, galId, galPopupId){
	var $g = $(id),
		$gs = $(galId),
		$gsCarousel,
		$imgBig = $g.find('.gal_img__img'),
		$galCounter = $g.find('.gal_counter'),
		$galText = $g.find('.gal_text'),
		$galPrev = $g.find('.gal_img__prev'),
		$galNext = $g.find('.gal_img__next'),
		$gCcount = $g.find('.ccount'),
		galTinyOpts = {},
		$links = $gs.find(".gal_imgs__link"),
		current = 0,
		count = $links.length,
		$gp = $(galPopupId),
		$imgBiger = $gp.find('.gal_img__i'),
		$galCounterP = $gp.find('.gal_pager'),
		$galTextP = $gp.find('.gal_img__d'),
		$galPrevP = $gp.find('.gal_prev'),
		$galNextP = $gp.find('.gal_next');

	if (!$g.length) return false;

	function setImage(e) {
		var $item = e.target ? $(e.target).parents('.gal_imgs__link') : $links.eq(e),
			index = $links.index($item),
			link = $item.attr('href'),
			linkB = $item.data('big'),
			text = $item.data('text'),
			cCount = $item.data('ccount');

		if ( !$item.is(".active") ) {
			$imgBig.attr('src', link);
			$imgBiger.attr('src', linkB);
			$galText.text(text);
			$galTextP.text(text);
			$gCcount.text(cCount);
			$links.removeClass('active');
			$item.addClass('active');
		}

		current = index;
		setCounter();
		return false;
	}

	function setNext(e) {
		current = current === count-1 ?
			0:
			current + 1;
		setImage(current);
		return false;
	}

	function setPrev(e) {
		current = current === 0 ?
			count - 1:
			current - 1;
		setImage(current);
		return false;
	}

	function setCounter() {
		$galCounter.text(current+1 + "/" + count);
		$galCounterP.text(current+1 + "/" + count);
		$gsCarousel.tinycarousel_move(current+1);
	}

	function initGal() {
		//current = 0;
		$links.eq(current).addClass('active');
		setCounter();
	}

	$gsCarousel = $gs.tinycarousel(galTinyOpts);
	initGal();

	CJ.galeryOverlay('#galery_overlay');
	$gs.on('click', '.gal_imgs__link', setImage);
	$galPrev.on('click', setPrev);
	$galPrevP.on('click', setPrev);
	$galNext.on('click', setNext);
	$galNextP.on('click', setNext);
};


CJ.galeryOverlay = function(id){
	var $overlay = $(id);

	(function init(){
		$overlay.on('click', CJ.hideGaleryComments);
		$overlay.on('click', '.gal_close', CJ.hideGaleryComments);
		$overlay.on('click', '.overlay_in', CJ.stop);
	})();
};


//function()

// DOM ready
$(function(){

	$('input[placeholder], textarea[placeholder]').placeholder();
	$('#slider').tinycarousel();
	$('#tl_slider').tinycarousel();

	CJ.galery("#gal", "#gal_carousel", "#galery_overlay");

	$('.dot1').on('click', function(){
		$('#playlist1').slideToggle(300, function(){
			CJ.slide();
		});
	});
	$('#playlist_opener').on('click', function(){
		$('#playlist').slideToggle(300, function(){
			CJ.slide();
		});
	});




	var $ae_image_text_form = $('#ae_image__description'),
		$ae_image_text_form_btn = $ae_image_text_form.find('.btn_orange');

	$('.com_deleted').on('click', CJ.toggleDeletedComment);
	$('.com_appeal').on('click', CJ.toggleAppealComment);
	$('.show_link').on('click', CJ.showToggle);
	$('.am_link').on('click', CJ.menuToggle);
	$('.gb_show').on('click', CJ.addTextToggle);
	$('#totop').on('click', CJ.toTop);
	$('.ae_image__desc_add', '#ae_album').on('click', CJ.aeImageDescription);
	$('.ae_image__remove', '#ae_album').on('click', CJ.aeImageRemove);
	$ae_image_text_form_btn.on('click',  CJ.aeImageDescriptionText);
	$('.opener').on('click',  CJ.baloonOpener);
	$('.gbt_like_ico').on('click',  CJ.likeTrack);
	$('.gbt_favo_ico').on('click',  CJ.favoTrack);
	$('.gbt_play__ico').on('click',  CJ.playPauseTrack);
	$('.ico2_plus_g').on('click',  CJ.showBaloonBtns);

	/*
	$(".track_list.ui-sortable").sortable({ handle: ".sortable1, .sortable2" });
	$(".proj_albums.ui-sortable").sortable({ handle: ".sortable2" });
	*/
	$(".fa_tabs").on('click', '.fa_tab', CJ.changeTab);

	$(".track_list").on('mouseover','.sortable1', CJ.trackSortable1);
	$(".track_list").on('mouseover','.sortable2', CJ.trackSortable2);
	$(".track_list").on('mouseout','.sortable1, .sortable2', CJ.trackSortableOff);
	$(".gbt_group__more").on('click', CJ.openMoreFaces);
	$(".ico2_eye").on('click', CJ.makeInactive);
	//$(".track_list").on('mouseout','', CJ.trackSortableOff);

	$(".wiki_editor").markItUp( wikiSettings );

	$('.gal_img__show_comment').on('click', CJ.showGaleryComments);
	$('.pleer_changer').on('click', CJ.togglePeerRadio);
	$('.ico5_play').on('click', CJ.togglePlay);

	$('.scroll_small').jScrollPane(CJ.scrollOpts);
	$('.ce_l').find('.sortable').sortable({ handle: ".pl_move" });
	$('.sel_list_scroll').find('.sortable').sortable({ handle: ".pl_move" });


	$('.mc_out').find('.sortable').sortable({ handle: ".pl_move" });
	$('.mc_out').on('click', '.pl_up', CJ.toggleMcList);
	$('#mess_wind').on('click', '.mess_proj_sl', CJ.toggleMcList2);

	

});

	
// вынес в main-header
//window.onload = CJ.scroller;
//window.onscroll = CJ.scroller;

$('.mc_i_checkbox').on('change',function(){
	if($(this).prop('checked')){
		$(this).closest('.mc_list_i').addClass('nbv');$(this).parent($('.mc_list_i')).addClass('bg-change');
	} else{
		$(this).closest('.mc_list_i').removeClass('nbv');$(this).parent($('.mc_list_i')).removeClass('bg-change');
	}
});

$('#sm-mess-wind').hide();


myHeight = document.documentElement.clientHeight;
$('.green').on('click',function(e){e.preventDefault();" "});
$('.btn_orange').on('click',function(e){e.preventDefault();" "});
$('.ico2').on('click',function(e){e.preventDefault();" "});


$('.chat').show();
$('.nm').show();




$('.ce_ch').on("change", function(){
	if($(this).is(':checked')){
		$(this).parent().addClass('checked');
	} else{
		
		$(this).parent().removeClass('checked');
	}
	var mesLength = $('.jspPane').find('.checked').length;
	$('.mes-length').text(mesLength);
	
});
$('.modal').addClass('hs');
$('#chat_btns').hide();
$('#chat').hide();
$('.ico2_dia').click(function(){
	/*if($('#chat').is(':hidden')){
		$('#chat').slideDown('1000', function(){
			$('#openDialong').text('Закрыть диалог');
			$('#chat_btns').show('slow');
			$('.modal').removeClass('hs');
		});	
	}*/
	$('.modal').show();
});

$('#openDialong').click(function(){
	if($('#chat').is(':visible')){
		$('#chat').slideUp('1000', function(){
		$('#openDialong').text('Открыть диалог');	
		$('#chat_btns').hide();
		$('.modal').addClass('hs');
		});

	}else{
		$('#chat').slideDown('1000', function(){
			$('#openDialong').text('Закрыть диалог');
			$('#chat_btns').show('slow');
			$('.modal').removeClass('hs');
		});
	}
});

// Перенес в директиву
//$('.header_closer').on('click', function(){
//		$('#top').slideUp(300);
//		setTimeout($('#topmini').slideDown(), 700);
//		setTimeout($('#header').animate({'height': '45'}, 300), 700);
//		setTimeout($('.header-bg').animate({'height': '45'}, 300), 700);
//		setTimeout($('#c').animate({'margin-top': '82'}, 300), 700);
//		$(this).hide();
//		$('nav.cf').css('position', 'absolute').animate({'top': '45px'},700);
//});
//$('.header_opener').on('click', function(){
//	$('#topmini').slideUp(300);
//	setTimeout($('#top').slideDown(), 700);
//	setTimeout($('#header').animate({'height': '113'}, 300), 700);
//	setTimeout($('.header-bg').animate({'height': '150'}, 300), 700);
//	setTimeout($('#c').animate({'margin-top': '150'}, 300), 700);
//	$('.header_closer').show();
//	$('nav.cf').css('position', 'fixed').animate({'top': '113px'},700);
//});






