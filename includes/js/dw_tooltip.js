/*************************************************************************
  
  dw_tooltip.js
  version date: Nov 2003
  requires: dw_event.js and dw_viewport.js
  
  This code is from Dynamic Web Coding at http://www.dyn-web.com/
  Copyright 2003 by Sharon Paine 
  See Terms of Use at http://www.dyn-web.com/bus/terms.html
  regarding conditions under which you may use this code.
  This notice must be retained in the code as is!

*************************************************************************/

var Tooltip = {
  followMouse: true,
  offX: 8,
  offY: 12,
  
  ready: false,
  t1: null,
  t2: null,
  tipID: "tipDiv",
  tip: null,
  
  init: function() {
    if ( document.createElement && document.body && typeof document.body.appendChild != "undefined" ) {
      var el = document.createElement("DIV");
      el.className = "tooltip";
      el.id = this.tipID;
      document.body.appendChild(el);
      this.ready = true;
    }
  },
  
  show: function(e, msg) {
    if (this.t1) clearTimeout(this.t1);	
  	if (this.t2) clearTimeout(this.t2); 
    this.tip = document.getElementById( this.tipID );
  	// set up mousemove 
  	if (this.followMouse) 
      dw_event.add( document, "mousemove", this.trackMouse, true );
    this.writeTip("");  // for mac ie
    this.writeTip(msg);
    viewport.getAll();
    this.positionTip(e);
  	this.t1 = setTimeout("document.getElementById('" + Tooltip.tipID + "').style.visibility = 'visible'",200);	
    },
    
    writeTip: function(msg) {
      if ( this.tip && typeof this.tip.innerHTML != "undefined" ) this.tip.innerHTML = msg;
    },
    
    positionTip: function(e) {
      var x = e.pageX? e.pageX: e.clientX + viewport.scrollX;
      var y = e.pageY? e.pageY: e.clientY + viewport.scrollY;

      if ( x + this.tip.offsetWidth + this.offX > viewport.width + viewport.scrollX )
        x = x - this.tip.offsetWidth - this.offX;
      else x = x + this.offX;
    
      if ( y + this.tip.offsetHeight + this.offY > viewport.height + viewport.scrollY )
        y = ( y - this.tip.offsetHeight - this.offY > viewport.scrollY )? y - this.tip.offsetHeight - this.offY : viewport.height + viewport.scrollY - this.tip.offsetHeight;
      else y = y + this.offY;
  
      this.tip.style.left = x + "px"; this.tip.style.top = y + "px";
    },
    
    hide: function() {
      if (this.t1) clearTimeout(this.t1);	
    	if (this.t2) clearTimeout(this.t2); 
      this.t2 = setTimeout("document.getElementById('" + this.tipID + "').style.visibility = 'hidden'",200);
    	// release mousemove
    	if (this.followMouse) 
    		dw_event.remove( document, "mousemove", this.trackMouse, true );
      this.tip = null;
    },
    
    trackMouse: function(e) {
    	e = dw_event.DOMit(e);
     	Tooltip.positionTip(e);	
    }

}

Tooltip.init();