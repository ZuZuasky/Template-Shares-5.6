var TSBoxes =
{
	options:
	{
		buttons: 5,
		className : 'default',
		color: false,
		duration: 0.6,
		effect:
		{
			mouseover: false,
			mouseout: (window.Effect && Effect.Morph)
		},
		hoverColor: false,
		hoverClass: 'hover',
		ghostColor: false,
		ghosting: false,
		identity: false,
		indicator: false,
		inverse: false,
		locked: false,
		max: 5,
		onRate: Prototype.emptyFunction,
		rated: false,
		ratedClass: 'rated',
		rerate: false,
		overlay: 'default.png',
		overlayImages: '../images/',
		stars: 5,
		total: 0
	}
};

Object.extend(TSBoxes,{REQUIRED_Prototype:"1.6.0.2",REQUIRED_Scriptaculous:"1.8.1",load:function(){this.require("Prototype");this.identify.counter=1;var A=/tsbox(?:-[\w\d.]+)?\.js(.*)/;this.imageSource=(($$("head script[src]").find(function(B){return B.src.match(A)})||{}).src||"").replace(A,"")+this.options.overlayImages},require:function(A){if((typeof window[A]=="undefined")||(this.convertVersionString(window[A].Version)<this.convertVersionString(this["REQUIRED_"+A]))){throw("Lightview requires "+A+" >= "+this["REQUIRED_"+A]);}},convertVersionString:function(A){var B=A.replace(/_.*|\./g,"");B=parseInt(B+"0".times(4-B.length));return A.indexOf("_")>-1?B-1:B},fixIE:(function(B){var A=new RegExp("MSIE ([\\d.]+)").exec(B);return A?(parseFloat(A[1])<7):false})(navigator.userAgent),identify:function(B){B=$(B);var C=B.readAttribute("id"),A=arguments.callee;if(C){return C}do{C="tsbox_"+A.counter++}while($(C));B.writeAttribute("id",C);return C},imagecache:[],cacheImage:function(A){if(!this.getCachedImage(A.src)){this.imagecache.push(A)}return A},getCachedImage:function(A){return this.imagecache.find(function(B){return B.src==A})},buildQueue:[],queueBuild:function(A){this.buildQueue.push(A)},processBuildQueue:function(){if(!this.buildQueue[0]){this.batchLoading=true;return}this.cacheBuildBatch(this.buildQueue[0])},cacheBuildBatch:function(C){var E=[],B=C.options.overlay,A=this.getCachedImage(B);this.buildQueue.each(function(F){if(F.options.overlay==B){E.push(F);this.buildQueue=this.buildQueue.without(F)}}.bind(this));if(!A){var D=new Image();D.onload=function(){this.buildBatch(E,{src:B,height:D.height,width:D.width,fullsrc:D.src})}.bind(this);D.src=TSBoxes.imageSource+B}else{this.buildBatch(E,A)}},buildBatch:function(B,A){B.each(function(C){C.imageInfo=A;C.build()});this.processBuildQueue()},useEvent:(function(A){return{click:"click",mouseover:"mouseover",mouseout:(A?"mouseleave":"mouseout")}})(Prototype.Browser.IE),capture:function(A){if(!Prototype.Browser.IE){A=A.wrap(function(E,D){var C=Object.isElement(this)?this:this.element,B=D.relatedTarget;if(B!=C&&!$A(C.select("*")).member(B)){E(D)}})}return A}});TSBoxes.load();document.observe("dom:loaded",TSBoxes.processBuildQueue.bind(TSBoxes));var TSBox=Class.create({initialize:function(A,B){this.element=$(A);this.average=B;this.options=Object.extend(Object.clone(TSBoxes.options),arguments[2]||{});$w("identity rated max total").each(function(C){this[C]=this.options[C]}.bind(this));this.locked=this.options.locked||(this.rated&&!this.options.rerate);if(!this.identity){this.identity=TSBoxes.identify(this.element)}if(this.options.effect&&(this.options.effect.mouseover||this.options.effect.mouseout)){TSBoxes.require("Scriptaculous")}TSBoxes.queueBuild(this);if(TSBoxes.batchLoading){TSBoxes.processBuildQueue()}},enable:function(){$w("mouseout mouseover click").each(function(C){var B=C.capitalize(),A=this["on"+B].bindAsEventListener(this);this["on"+B+"_cached"]=(C=="mouseout"&&!Prototype.Browser.IE)?TSBoxes.capture(A):A;this.starbar.observe(TSBoxes.useEvent[C],this["on"+B+"_cached"])}.bind(this));this.buttons.invoke("setStyle",{cursor:"pointer"})},disable:function(){$w("mouseover mouseout click").each(function(A){this.starbar.stopObserving(TSBoxes.useEvent[A],this["on"+A.capitalize()+"_cached"])}.bind(this));this.buttons.invoke("setStyle",{cursor:"auto"})},build:function(){this.starWidth=this.imageInfo.width;this.starHeight=this.imageInfo.height;this.starSrc=this.imageInfo.fullsrc;this.boxWidth=this.starWidth*this.options.stars;this.buttonWidth=this.boxWidth/this.options.buttons;this.buttonRating=this.options.max/this.options.buttons;if(this.options.effect){this.zeroPosition=this.getBarPosition(0);this.maxPosition=this.getBarPosition(this.options.max)}var A={absolute:{position:"absolute",top:0,left:0,width:this.boxWidth+"px",height:this.starHeight+"px"},base:{position:"relative",width:this.boxWidth+"px",height:this.starHeight+"px"},star:{position:"absolute",top:0,left:0,width:this.starWidth+"px",height:this.starHeight+"px"}};this.element.addClassName("tsbox");this.container=new Element("div",{className:this.options.className||""}).setStyle({position:"relative"}).insert(this.status=new Element("div").insert(this.hover=new Element("div").insert(this.wrapper=new Element("div",{className:"stars"}).setStyle(Object.extend({overflow:"hidden"},A.base)))));if(this.rated){this.status.addClassName("rated")}if(this.locked){this.status.addClassName("locked")}if(this.options.ghosting){this.wrapper.insert(this.ghost=new Element("div",{className:"ghost"}).setStyle(A.absolute));if(this.options.ghostColor){this.ghost.setStyle({background:this.options.ghostColor})}if(this.options.effect){this.ghost.scope=this.ghost.identify()}this.setBarPosition(this.ghost,this.average,(window.Effect&&Effect.Morph))}this.wrapper.insert(this.colorbar=new Element("div",{className:"colorbar"}).setStyle(A.absolute)).insert(new Element("div").setStyle(A.absolute).insert(this.starbar=new Element("div").setStyle(A.base)));if(this.options.color){this.colorbar.setStyle({background:this.options.color})}if(this.options.effect){this.colorbar.scope=this.colorbar.identify()}this.options.stars.times(function(B){var C;this.starbar.insert(C=new Element("div").setStyle(Object.extend({background:"url("+this.starSrc+") top left no-repeat",left:this.starWidth*B+"px"},A.star)));C.setStyle({left:this.starWidth*B+"px"});if(TSBoxes.fixIE){C.setStyle({background:"none",filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+this.starSrc+"'', sizingMethod='scale')"})}}.bind(this));this.buttons=[];this.options.buttons.times(function(D){var C,B=this.options.inverse?this.boxWidth-this.buttonWidth*(D+1):this.buttonWidth*D;this.starbar.insert(C=new Element("div").setStyle({position:"absolute",top:0,left:B+"px",width:this.buttonWidth+(Prototype.Browser.IE?1:0)+"px",height:this.starHeight+"px"}));C.rating=this.buttonRating*D+this.buttonRating;this.buttons.push(C)}.bind(this));this.setBarPosition(this.colorbar,this.average);this.element.update(this.container);this.inputs={};$w("average max rated rerated total").each(function(B){this.element.insert(this.inputs[B]=new Element("input",{type:"hidden",name:this.identity+"_"+B,value:""+(B=="rerated"?!!this[B]:this[B])}))}.bind(this));if(this.options.indicator){this.hover.insert(this.indicator=new Element("div",{className:"indicator"}));this.updateIndicator()}if(!this.locked){this.enable()}},updateAverage:function(A){if(this.rated&&this.options.rerate){this.average=(this.total*this.average-this.rated)/(this.total-1||1)}var B=this.rated?this.total:this.total++;this.average=(this.average==0)?A:(this.average*(this.rated?B-1:B)+A)/(this.rated?B:B+1)},updateIndicator:function(){this.indicator.update(new Template(this.options.indicator).evaluate({max:this.options.max,total:this.total,average:(this.average*10).round()/10}))},getBarPosition:function(B){var A=(this.boxWidth-(B/this.buttonRating)*this.buttonWidth);return parseInt(this.options.inverse?A.ceil():-1*A.floor())},setBarPosition:function(A,B){if(this.options.effect&&this["activeEffect_"+A.scope]){Effect.Queues.get(A.scope).remove(this["activeEffect_"+A.scope])}var D=this.getBarPosition(B);if(arguments[2]){var C=parseInt(A.getStyle("left")),F=this.getBarPosition(B);if(C==F){return}var E=((this.maxPosition-(C-F).abs()).abs()/this.zeroPosition.abs()).toFixed(2);this["activeEffect_"+A.scope]=new Effect.Morph(A,{style:{left:D+"px"},queue:{position:"end",limit:1,scope:A.scope},duration:(this.options.duration*E)})}else{A.setStyle({left:D+"px"})}},onClick:function(C){var B=C.element();if(!B.rating){return}this.updateAverage(B.rating);if(this.options.indicator){this.updateIndicator()}if(this.options.ghosting){this.setBarPosition(this.ghost,this.average,(window.Effect&&Effect.Morph))}if(!this.rated){this.status.addClassName("rated")}this.rerated=!!this.rated;this.rated=B.rating;if(!this.options.rerate){this.disable();this.status.addClassName("locked");this.onMouseout(C)}var A={};$w("average identity max rated rerated total").each(function(D){if(D!="identity"){this.inputs[D].value=this[D]}A[D]=this[D]}.bind(this));this.options.onRate(this.element,A);this.element.fire("tsbox:rated",A)},onMouseout:function(A){this.setBarPosition(this.colorbar,this.average,(this.options.effect&&this.options.effect.mouseout));this.hovered=false;if(this.options.hoverClass){this.hover.removeClassName(this.options.hoverClass)}if(this.options.hoverColor){this.colorbar.setStyle({background:this.options.color})}this.element.fire("tsbox:left")},onMouseover:function(B){var A=B.element();if(!A.rating){return}this.setBarPosition(this.colorbar,A.rating,(this.options.effect&&this.options.effect.mouseover));if(!this.hovered&&this.options.hoverClass){this.hover.addClassName(this.options.hoverClass)}this.hovered=true;if(this.options.hoverColor){this.colorbar.setStyle({background:this.options.hoverColor})}this.element.fire("tsbox:changed",{identify:this.options.identity,max:this.options.max,rating:A.rating,total:this.total})}});