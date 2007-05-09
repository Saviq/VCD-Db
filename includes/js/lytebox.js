//******************************************************************************************************************************/
//	LyteBox v2.02
//
//	 Author: Markus F. Hay
//  Website: http://www.dolem.com/
//	   Date: November 13, 2006
//	License: Creative Commons Attribution 2.5 License (http://creativecommons.org/licenses/by/2.5/)
// Browsers: Tested successfully on WinXP with the following browsers (using no DOCTYPE, Strict DOCTYPE, and Transitional DOCTYPE):
//				* Firefox: 2.0, 1.5
//				* Internet Explorer: 7.0, 6.0 SP2, 5.5 SP2
//				* Opera: 9.10
//
//     NOTE: LyteBox was written from the Lightbox class that Lokesh Dhakar (http://www.huddletogether.com)
//			 originally wrote. The purpose was to write a self-contained object that eliminates the dependency
//			 of prototype.js, effects.js, and scriptaculous.js. Most of the core functionality and code was
//			 left in tact, and several functions were added to mimic the effects provided by sciptaculous. These
//			 newly added functions include: appear(), fade(), resizeW(), resizeH(), and toggleSelects(). Functions
//			 that were not needed were removed, as were global variables. Additionally, support has been added for
//			 iFrame environments without any code modifications needed by the end user (auto-detect).
//
//			 Other changes include:
//				- new close, next, and previous images
//				- prev/next images are always displayed (as opposed to mousing over a part of the DIV to see them)
//
//			 That being said, the original comments by Lokesh are below:
//
//				* Lightbox v2.02
//				* by Lokesh Dhakar - http://www.huddletogether.com
//				* 3/31/06
//
//				* For more information on this script, visit: http://huddletogether.com/projects/lightbox2/
//				* Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
//	
//				* Credit also due to those who have helped, inspired, and made their code available to the public.
//				* Including: Scott Upton(uptonic.com), Peter-Paul Koch(quirksmode.org), Thomas Fuchs(mir.aculo.us), and others.
//
//******************************************************************************************************************************/
//  Extended Built-in Objects
//  - Array.prototype.removeDuplicates()
//  - Array.prototype.empty()
//
//	LyteBox Class Definition
//	- LyteBox()
//  - initialize()
//  - start()
//  - changeImage(imageNum)
//  - resizeImageContainer(imgWidth, imgHeight)
//  - showImage()
//  - updateDetails()
//  - updateNav()
//  - enableKeyboardNav()
//  - disableKeyboardNav()
//  - keyboardAction()
//  - preloadNeighborImages()
//  - end()
//  - appear(id, opacity)
//  - fade(id, opacity)
//  - resizeW(id, curW, maxW, timer)
//  - resizeH(id, curH, maxH, timer)
//  - getPageScroll()
//  - getPageSize()
//  - toggleSelects(state)
//  - pause(numberMillis)
//
//  - initLytebox()
//**************************************************************************************************************/
Array.prototype.removeDuplicates = function () { for (var i = 1; i < this.length; i++) { if (this[i][0] == this[i-1][0]) { this.splice(i,1); } } }
Array.prototype.empty = function () { for (var i = 0; i <= this.length; i++) { this.shift(); } }

//*************************/
// LyteBox constructor
//*************************/
function LyteBox() {
	/*** Start Configuration ***/	
	this.fileLoadingImage	= "images/lytebox/loading.gif";
	this.fileCloseImage 	= "images/lytebox/close.gif";
	
	this.maxWidth			= 0.8;	// maximum image width (of the available screen) set null to disable
	this.maxHeight			= 0.8;	// maximum image height (same as above)
	
	this.resizeSpeed		= 10;	// controls the speed of the image resizing (1=slowest and 10=fastest)
	this.borderSize			= 12;	//if you adjust the padding in the CSS, you will need to update this variable
	/*** End Configuration ***/
	
	
	if(this.resizeSpeed > 10) { this.resizeSpeed = 10; }
	if(this.resizeSpeed < 1) { resizeSpeed = 1; }
	this.resizeDuration = (11 - this.resizeSpeed) * 0.15;
	
	/* MEMBER VARIABLES USED TO CLEAR SETTIMEOUTS */
	this.resizeWTimerArray		= new Array();
	this.resizeWTimerCount		= 0;
	this.resizeHTimerArray		= new Array();
	this.resizeHTimerCount		= 0;
	this.showImageTimerArray	= new Array();
	this.showImageTimerCount	= 0;
	this.overlayTimerArray		= new Array();
	this.overlayTimerCount		= 0;
	this.imageTimerArray		= new Array();
	this.imageTimerCount		= 0;
	this.timerIDArray			= new Array();
	this.timerIDCount			= 0;
	
	/* GLOBAL */
	this.imageArray	 = new Array();
	this.activeImage = null;
	
	/* Check for iFrame environment (will set this.isFrame and this.doc member variables) */
	this.checkFrame();
	
	// We need to know the certain browser versions (or if it's IE) since IE is "special" and requires spoon feeding.
	/*@cc_on
		/*@if (@_jscript)
			this.ie = (document.all) ? true : false;
		/*@else @*/
			this.ie = false;
		/*@end
	@*/
	this.ie7 = (this.ie && window.XMLHttpRequest);
	
	/* INITIALIZE */
	this.initialize();
}

//********************************************************************************************/
// initialize()
// Constructor runs on completion of the DOM loading. Loops through anchor tags looking for 
// 'lytebox' references and applies onclick events to appropriate links. The 2nd section of
// the function inserts html at the bottom of the page which is used to display the shadow 
// overlay and the image container.
//********************************************************************************************/
LyteBox.prototype.initialize = function() {
	if (!document.getElementsByTagName) { return; }
	
	// populate array of anchors from the appropriate window (could be the parent or iframe document)
	var anchors = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');

	// loop through all anchor tags
	for (var i = 0; i < anchors.length; i++) {
		var anchor = anchors[i];		
		var relAttribute = String(anchor.getAttribute('rel'));
		
		// use the string.match() method to catch 'lytebox' references in the rel attribute
		if (anchor.getAttribute('href') && (relAttribute.toLowerCase().match('lytebox'))) {
			anchor.onclick = function () { myLytebox.start(this); return false; }
		}
	}
	
	// The rest of this code inserts html at the bottom of the page that looks similar to this:
	//
	//	<div id="overlay"></div>
	//	<div id="lytebox">
	//		<div id="outerImageContainer">
	//			<div id="imageContainer">
	//				<img id="lyteboxImage">
	//				<div style="" id="hoverNav">
	//					<a href="#" id="prevLink"></a>
	//					<a href="#" id="nextLink"></a>
	//				</div>
	//				<div id="loading">
	//					<a href="#" id="loadingLink">
	//						<img src="images/loading.gif">
	//					</a>
	//				</div>
	//			</div>
	//		</div>
	//		<div id="imageDataContainer">
	//			<div id="imageData">
	//				<div id="imageDetails">
	//					<span id="caption"></span>
	//					<span id="numberDisplay"></span>
	//				</div>
	//				<div id="bottomNav">
	//					<a href="#" id="bottomNavClose"><img src="images/close.gif"></a>
	//				</div>
	//			</div>
	//		</div>
	//	</div>


	var objBody = this.doc.getElementsByTagName("body").item(0);

	var objOverlay = this.doc.createElement("div");
		objOverlay.setAttribute('id','overlay');
		objOverlay.style.display = 'none';
		if (this.isFrame) {
			objOverlay.onclick = function() { window.parent[window.name].myLytebox.end(); return false; }
		} else {
			objOverlay.onclick = function() { myLytebox.end(); return false; }
		}
		objBody.appendChild(objOverlay);
	
	var objLytebox = this.doc.createElement("div");
		objLytebox.setAttribute('id','lytebox');
		objLytebox.style.display = 'none';
		objBody.appendChild(objLytebox);
	
	var objOuterImageContainer = this.doc.createElement("div");
		objOuterImageContainer.setAttribute('id','outerImageContainer');
		objLytebox.appendChild(objOuterImageContainer);

	var objImageContainer = this.doc.createElement("div");
		objImageContainer.setAttribute('id','imageContainer');
		objOuterImageContainer.appendChild(objImageContainer);

	var objLyteboxImage = this.doc.createElement("img");
		objLyteboxImage.setAttribute('id','lyteboxImage');
		objImageContainer.appendChild(objLyteboxImage);
		
	var objLoading = this.doc.createElement("div");
		objLoading.setAttribute('id','loading');
		objImageContainer.appendChild(objLoading);

	var objLoadingLink = this.doc.createElement("a");
		objLoadingLink.setAttribute('id','loadingLink');
		objLoadingLink.setAttribute('href','#');
		if (this.isFrame) {
			objLoadingLink.onclick = function() { window.parent[window.name].myLytebox.end(); return false; }
		} else {
			objLoadingLink.onclick = function() { myLytebox.end(); return false; }
		}
		objLoading.appendChild(objLoadingLink);
	
	var objLoadingImage = this.doc.createElement("img");
		objLoadingImage.setAttribute('src', this.fileLoadingImage);
		objLoadingLink.appendChild(objLoadingImage);
		
	var objImageDataContainer = this.doc.createElement("div");
		objImageDataContainer.setAttribute('id','imageDataContainer');
		objImageDataContainer.className = 'clearfix';
		objLytebox.appendChild(objImageDataContainer);

	var objImageData =this.doc.createElement("div");
		objImageData.setAttribute('id','imageData');
		objImageDataContainer.appendChild(objImageData);
	
	var objImageDetails = this.doc.createElement("div");
		objImageDetails.setAttribute('id','imageDetails');
		objImageData.appendChild(objImageDetails);

	var objCaption = this.doc.createElement("span");
		objCaption.setAttribute('id','caption');
		objImageDetails.appendChild(objCaption);
		
	var objHoverNav = this.doc.createElement("div");
		objHoverNav.setAttribute('id','hoverNav');
		objImageContainer.appendChild(objHoverNav);
	
	var objBottomNav = this.doc.createElement("div");
		objBottomNav.setAttribute('id','bottomNav');
		objImageData.appendChild(objBottomNav);
	
	var objPrevLink = this.doc.createElement("a");
		objPrevLink.setAttribute('id','prevLink');
		objPrevLink.setAttribute('href','#');
		objHoverNav.appendChild(objPrevLink);
	
	var objNextLink = this.doc.createElement("a");
		objNextLink.setAttribute('id','nextLink');
		objNextLink.setAttribute('href','#');
		objHoverNav.appendChild(objNextLink);
	
	var objNumberDisplay = this.doc.createElement("span");
		objNumberDisplay.setAttribute('id','numberDisplay');
		objImageDetails.appendChild(objNumberDisplay);

	var objBottomNavCloseLink = this.doc.createElement("a");
		objBottomNavCloseLink.setAttribute('id','bottomNavClose');
		objBottomNavCloseLink.setAttribute('href','#');
		if (this.isFrame) {
			objBottomNavCloseLink.onclick = function() { window.parent[window.name].myLytebox.end(); return false; }
		} else {
			objBottomNavCloseLink.onclick = function() { myLytebox.end(); return false; }
		}
		objBottomNav.appendChild(objBottomNavCloseLink);

	var objBottomNavCloseImage = this.doc.createElement("img");
		objBottomNavCloseImage.setAttribute('src', this.fileCloseImage);
		objBottomNavCloseLink.appendChild(objBottomNavCloseImage);
};

//********************************************************************************/
// start()
// Display overlay and Lytebox. If image is part of a set, add siblings to imageArray.
//********************************************************************************/
LyteBox.prototype.start = function(imageLink) {	
	// Hide select boxes for IE6 and below
	if (this.ie && !this.ie7) {	this.toggleSelects('hide');	}

	// stretch overlay to fill page and fade in
	var pageSize	= this.getPageSize();
	var objOverlay	= this.doc.getElementById('overlay');
	var objBody		= this.doc.getElementsByTagName("body").item(0);
	
	objOverlay.style.height = pageSize[1] + "px";
	objOverlay.style.display = '';
	this.appear('overlay', 0);
	
	// initialize
	this.imageArray = [];
	this.imageNum = 0;

	if (!document.getElementsByTagName){ return; }

	var anchors = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');

	// if image is NOT part of a set..
	if((imageLink.getAttribute('rel') == 'lytebox')) {	// add single image to imageArray
		this.imageArray.push(new Array(imageLink.getAttribute('href'), imageLink.getAttribute('title')));			
	} else { // if image is part of a set..
		// loop through anchors, find other images in set, and add them to imageArray
		for (var i = 0; i < anchors.length; i++){
			var anchor = anchors[i];
			if (anchor.getAttribute('href') && (anchor.getAttribute('rel') == imageLink.getAttribute('rel'))) {
				this.imageArray.push(new Array(anchor.getAttribute('href'), anchor.getAttribute('title')));
			}
		}
		this.imageArray.removeDuplicates();
		while(this.imageArray[this.imageNum][0] != imageLink.getAttribute('href')) { this.imageNum++; }
	}

	// calculate top offset for the lytebox and display
	var object = this.doc.getElementById('lytebox');
		object.style.top = (this.getPageScroll() + (pageSize[3] / 15)) + "px";
		object.style.display = '';
	
	this.changeImage(this.imageNum);
};

//******************************************************************************/
// changeImage()
// Hide most elements and preload image in preparation for resizing image container.
//******************************************************************************/
LyteBox.prototype.changeImage = function(imageNum) {		
	this.activeImage = imageNum;	// update global var
	var pageSize = this.getPageSize();

	// hide elements during transition
	this.doc.getElementById('loading').style.display = '';
	this.doc.getElementById('lyteboxImage').style.display = 'none';
	this.doc.getElementById('prevLink').style.display = 'none';
	this.doc.getElementById('nextLink').style.display = 'none';
	this.doc.getElementById('imageDataContainer').style.display = 'none';
	this.doc.getElementById('numberDisplay').style.display = 'none';	
	
	imgPreloader = new Image();
	// once image is preloaded, resize image container
	imgPreloader.onload = function() {
		var lyteboxImage = myLytebox.doc.getElementById('lyteboxImage');
		lyteboxImage.src = myLytebox.imageArray[myLytebox.activeImage][0];
		// resize the image to maxWidth and maxHeight of the browser window.
		imgPreloader.aspect = imgPreloader.width / imgPreloader.height;
		if(myLytebox.maxWidth && (imgPreloader.width > (pageSize[2] * myLytebox.maxWidth))) {
			imgPreloader.width = pageSize[2] * myLytebox.maxWidth;
			imgPreloader.height = imgPreloader.width / imgPreloader.aspect; 
		}
		if(myLytebox.maxHeight && (imgPreloader.height > (pageSize[3] * myLytebox.maxHeight))) {
			imgPreloader.height = pageSize[3] * myLytebox.maxHeight;
			imgPreloader.width = imgPreloader.height * imgPreloader.aspect; 
		}
		lyteboxImage.width = imgPreloader.width;
		lyteboxImage.height = imgPreloader.height;
		myLytebox.resizeImageContainer(imgPreloader.width, imgPreloader.height);
	}
	imgPreloader.src = this.imageArray[this.activeImage][0];
};

//******************************************************************************/
// resizeImageContainer()
//******************************************************************************/
LyteBox.prototype.resizeImageContainer = function(imgWidth, imgHeight) {
	// get current height and width
	this.wCur = this.doc.getElementById('outerImageContainer').offsetWidth;
	this.hCur = this.doc.getElementById('outerImageContainer').offsetHeight;

	// scalars based on change from old to new
	this.xScale = ((imgWidth  + (this.borderSize * 2)) / this.wCur) * 100;
	this.yScale = ((imgHeight  + (this.borderSize * 2)) / this.hCur) * 100;

	// calculate size difference between new and old image, and resize if necessary
	var wDiff = (this.wCur - this.borderSize * 2) - imgWidth;
	var hDiff = (this.hCur - this.borderSize * 2) - imgHeight;
	
	if (!(hDiff == 0)) {
		this.hDone = false;
		this.resizeH('outerImageContainer', this.hCur, imgHeight + this.borderSize*2, this.getPixelRate(this.hCur, imgHeight));
	} else {
		this.hDone = true;
	}
	if (!(wDiff == 0)) {
		this.wDone = false;
		this.resizeW('outerImageContainer', this.wCur, imgWidth + this.borderSize*2, this.getPixelRate(this.wCur, imgWidth));
	} else {
		this.wDone = true;
	}

	// if new and old image are same size and no scaling transition is necessary, do a quick pause to prevent image flicker.
	if ((hDiff == 0) && (wDiff == 0)) {
		if (this.ie){ this.pause(250); } else { this.pause(100); } 
	}
	
	this.doc.getElementById('prevLink').style.height = imgHeight + "px";
	this.doc.getElementById('nextLink').style.height = imgHeight + "px";
	this.doc.getElementById('imageDataContainer').style.width = (imgWidth + (this.borderSize * 2)) + "px";

	this.showImage();
};

//******************************************************************************/
// showImage() - Display image and begin preloading neighbors.
//******************************************************************************/
LyteBox.prototype.showImage = function() {
	if (this.wDone && this.hDone) {
		// Clear the timer for showImage...
		for (var i = 0; i < this.showImageTimerCount; i++) { window.clearTimeout(this.showImageTimerArray[i]); }
		
		this.doc.getElementById('loading').style.display = 'none';
		this.doc.getElementById('lyteboxImage').style.display = '';
		this.appear('lyteboxImage', 0);
		this.preloadNeighborImages();
	} else {
		this.showImageTimerArray[this.showImageTimerCount++] = setTimeout("myLytebox.showImage()", 200);
	}
};

//******************************************************************************/
// updateDetails() - Display caption, and bottom nav.
//******************************************************************************/
LyteBox.prototype.updateDetails = function() {
	var object = this.doc.getElementById('caption');
	object.style.display = '';
	object.innerHTML = this.imageArray[this.activeImage][1];
	this.updateNav()
	
	object = this.doc.getElementById('imageDataContainer');
	object.style.display = '';
	
	// if image is part of set display 'Image x of x' 
	if(this.imageArray.length > 1){
		object = this.doc.getElementById('numberDisplay');
		object.style.display = '';
		object.innerHTML = "Image " + eval(this.activeImage + 1) + " of " + this.imageArray.length;
	}

	this.appear('imageDataContainer', 0);
};

//******************************************************************************/
// updateNav() - Display appropriate previous and next hover navigation.
//******************************************************************************/
LyteBox.prototype.updateNav = function() {
	// if not first image in set, display prev image button
	if(this.activeImage != 0){
		var object = this.doc.getElementById('prevLink');
			object.style.display = '';
			object.onclick = function() {
				myLytebox.changeImage(myLytebox.activeImage - 1); return false;
			}
	}
	// if not last image in set, display next image button
	if(this.activeImage != (this.imageArray.length - 1)){
		var object = this.doc.getElementById('nextLink');
			object.style.display = '';
			object.onclick = function() {
				myLytebox.changeImage(myLytebox.activeImage + 1); return false;
			}
	}	
	this.enableKeyboardNav();
};

//********************************************************************************/
// enableKeyboardNav(), disableKeyboardNav(), keyboardAction() -- COMBINED COMMENT
//********************************************************************************/
LyteBox.prototype.enableKeyboardNav = function() { document.onkeydown = this.keyboardAction; };
LyteBox.prototype.disableKeyboardNav = function() { document.onkeydown = ''; };
LyteBox.prototype.keyboardAction = function(e) {
	var keycode = key = null;
	keycode	= (e == null) ? event.keyCode : e.which;
	key		= String.fromCharCode(keycode).toLowerCase();
	
	if((key == 'x') || (key == 'c')){	// close lytebox
		myLytebox.end();
	} else if(key == 'f'){	// display previous image (front)
		if(this.activeImage != 0){
			myLytebox.disableKeyboardNav();
			myLytebox.changeImage(this.activeImage - 1);
		}
	} else if(key == 'b'){	// display next image (back)
		if(this.activeImage != (this.imageArray.length - 1)) {
			myLytebox.disableKeyboardNav();
			myLytebox.changeImage(this.activeImage + 1);
		}
	}
};

//********************************************************************************/
// preloadNeighborImages() - Preload previous and next images.
//********************************************************************************/
LyteBox.prototype.preloadNeighborImages = function() {
	if ((this.imageArray.length - 1) > this.activeImage) {
		preloadNextImage = new Image();
		preloadNextImage.src = this.imageArray[this.activeImage + 1][0];
	}
	if(this.activeImage > 0) {
		preloadPrevImage = new Image();
		preloadPrevImage.src = this.imageArray[this.activeImage - 1][0];
	}
};

//********************************************************************************/
// end()
//********************************************************************************/
LyteBox.prototype.end = function() {
	this.disableKeyboardNav();
	this.doc.getElementById('lytebox').style.display = 'none';
	this.fade('overlay', 80);
	this.toggleSelects('visible');
};

//***********************************************************************************/
// checkFrame() - Determines if we are in an iFrame or not so we can display properly
//***********************************************************************************/
LyteBox.prototype.checkFrame = function() {
	// If we are an iFrame ONLY (framesets are excluded because we can't overlay a frameset). Note that there are situations
	// where "this" will not refer to LyteBox, such as when buttons are clicked, therefor we have to set this.dialog appropriately.
	if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
		this.isFrame = true;
		this.lytebox = "window.parent." + window.name + ".myLytebox";
		this.doc = parent.document;
	} else {
		this.isFrame = false;
		this.lytebox = "myLytebox";
		this.doc = document;
	}
};

//*******************************************************************************************/
// getPixelRate() - Determines the rate (number of pixels) that we want to scale PER call
//				    to a setTimeout() function. "cur" represents the current width or height,
//					and img represents the image (new) width or height.
//*******************************************************************************************/
LyteBox.prototype.getPixelRate = function(cur, img) {
	var diff = (img > cur) ? img - cur : cur - img;
	
	if (diff > 0 && diff <= 100) { return 4; }
	if (diff > 100 && diff <= 200) { return 8; }
	if (diff > 200 && diff <= 300) { return 12; }
	if (diff > 300 && diff <= 400) { return 16; }
	if (diff > 400 && diff <= 500) { return 20; }
	if (diff > 500 && diff <= 600) { return 24; }
	if (diff > 600 && diff <= 700) { return 28; }
	if (diff > 700) { return 32; }
};

//********************************************************************************/
// appear() - Makes an element fade in (appear).
//********************************************************************************/
LyteBox.prototype.appear = function(id, opacity) {
	var object = this.doc.getElementById(id).style;
	object.opacity = (opacity/100);
	object.MozOpacity = (opacity/100);
	object.KhtmlOpacity = (opacity/100);
	object.filter = "alpha(opacity=" + (opacity+10) + ")";
	
	if (opacity == 100 && id == 'lyteboxImage') {
		this.updateDetails();
	} else if (opacity == 80 && id == 'overlay') {
		// Clear the overlay timer...
		for (var i = 0; i < this.overlayTimerCount; i++) { window.clearTimeout(this.overlayTimerArray[i]); }
		return;
	} else if (opacity == 100 && id == 'imageDataContainer') {
		// Clear all the image timers...
		for (var i = 0; i < this.imageTimerCount; i++) { window.clearTimeout(this.imageTimerArray[i]); }
	} else {
		if (id == 'overlay') {
			this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.appear('" + id + "', " + (opacity+20) + ")", 1);
		} else {
			this.imageTimerArray[this.imageTimerCount++] = setTimeout("myLytebox.appear('" + id + "', " + (opacity+10) + ")", 1);
		}
	}
};

//********************************************************************************/
// fade() - Makes an element fade out (disappear).
//********************************************************************************/
LyteBox.prototype.fade = function(id, opacity) {
	var object = this.doc.getElementById(id).style;
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + opacity + ")";
	
	if (opacity == 0) {
		try {
			object.display = 'none';
		} catch(err) { }
	} else if (id == 'overlay') {
		this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.fade('" + id + "', " + (opacity-20) + ")", 1);
	} else {
		this.timerIDArray[this.timerIDCount++] = setTimeout("myLytebox.fade('" + id + "', " + (opacity-10) + ")", 1);
	}
};

//********************************************************************************/
// resizeW() - Resize the width of an element (smooth animation)...
//********************************************************************************/
LyteBox.prototype.resizeW = function(id, curW, maxW, pixelrate, speed) {
	if (!this.hDone) {
		this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + id + "', " + curW + ", " + maxW + ", " + pixelrate + ")", 100);
		return;
	}
	
	var object = this.doc.getElementById(id);
	var timer = speed ? speed : (this.resizeDuration/2);
	
	object.style.width = (curW) + "px";
	
	if (curW < maxW) {
		curW += (curW + pixelrate >= maxW) ? (maxW - curW) : pixelrate;	// increase size
	} else if (curW > maxW) {
		curW -= (curW - pixelrate <= maxW) ? (curW - maxW) : pixelrate;	// decrease size
	}
	this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + id + "', " + curW + ", " + maxW + ", " + pixelrate + ", " + (timer+0.02) + ")", timer+0.02);
	
	if (parseInt(object.style.width) == maxW) {
		this.wDone = true;
		// Clear all the timers for resizing...
		for (var i = 0; i < this.resizeWTimerCount; i++) { window.clearTimeout(this.resizeWTimerArray[i]); }
	}
};

//********************************************************************************/
// resizeH() - Resize the height of an element (smooth animation)...
//********************************************************************************/
LyteBox.prototype.resizeH = function(id, curH, maxH, pixelrate, speed) {
	var timer = speed ? speed : (this.resizeDuration/2);
	var object = this.doc.getElementById(id);
	
	object.style.height = (curH) + "px";
	
	if (curH < maxH) {
		curH += (curH + pixelrate >= maxH) ? (maxH - curH) : pixelrate;	// increase size
	} else if (curH > maxH) {
		curH -= (curH - pixelrate <= maxH) ? (curH - maxH) : pixelrate;	// decrease size
	}
	this.resizeHTimerArray[this.resizeHTimerCount++] = setTimeout("myLytebox.resizeH('" + id + "', " + curH + ", " + maxH + ", " + pixelrate + ", " + (timer+.02) + ")", timer+.02);
	
	if (parseInt(object.style.height) == maxH) {
		this.hDone = true;
		// Clear all the timers for resizing...
		for (var i = 0; i < this.resizeHTimerCount; i++) { window.clearTimeout(this.resizeHTimerArray[i]); }
	}
};

//**************************************************/
// getPageScroll() - returns the y page scroll value
//**************************************************/
LyteBox.prototype.getPageScroll = function() {
	if (self.pageYOffset) {
		return this.isFrame ? parent.pageYOffset : self.pageYOffset;
	} else if (this.doc.documentElement && this.doc.documentElement.scrollTop){	 // Explorer 6 Strict
		return this.doc.documentElement.scrollTop;
	} else if (document.body) {// all other Explorers
		return this.doc.body.scrollTop;
	}
};

//*******************************************************************************/
// getPageSize() - Returns array with page width, height and window width, height
// Core code from - quirksmode.org, Edit for Firefox by pHaez
//*******************************************************************************/
LyteBox.prototype.getPageSize = function() {	
	var xScroll, yScroll, windowWidth, windowHeight;
	
	if (window.innerHeight && window.scrollMaxY) {
		xScroll = this.doc.scrollWidth;
		yScroll = (this.isFrame ? parent.innerHeight : self.innerHeight) + (this.isFrame ? parent.scrollMaxY : self.scrollMaxY);
	} else if (this.doc.body.scrollHeight > this.doc.body.offsetHeight){ // all but Explorer Mac
		xScroll = this.doc.body.scrollWidth;
		yScroll = this.doc.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = this.doc.getElementsByTagName("html").item(0).offsetWidth;
		yScroll = this.doc.getElementsByTagName("html").item(0).offsetHeight;
		
		// Strict mode fixes
		xScroll = (xScroll < this.doc.body.offsetWidth) ? this.doc.body.offsetWidth : xScroll;
		yScroll = (yScroll < this.doc.body.offsetHeight) ? this.doc.body.offsetHeight : yScroll;
	}
	
	if (self.innerHeight) {	// all except Explorer
		windowWidth = (this.isFrame) ? parent.innerWidth : self.innerWidth;
		windowHeight = (this.isFrame) ? parent.innerHeight : self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = this.doc.documentElement.clientWidth;
		windowHeight = this.doc.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = this.doc.getElementsByTagName("html").item(0).clientWidth;
		windowHeight = this.doc.getElementsByTagName("html").item(0).clientHeight;
		
		// Strict mode fixes...
		windowWidth = (windowWidth == 0) ? this.doc.body.clientWidth : windowWidth;
		windowHeight = (windowHeight == 0) ? this.doc.body.clientHeight : windowHeight;
	}
	
	// for small pages with total height/width less then height/width of the viewport
	var pageHeight = (yScroll < windowHeight) ? windowHeight : yScroll;
	var pageWidth = (xScroll < windowWidth) ? windowWidth : xScroll;
	
	return new Array(pageWidth, pageHeight, windowWidth, windowHeight);
};

//**********************************************************************************************************/
// toggleSelects(state) - Toggles select boxes between hidden and visible states, including those in iFrames
//**********************************************************************************************************/
LyteBox.prototype.toggleSelects = function(state) {
	// hide in the parent frame, then in child frames
	var selects = this.doc.getElementsByTagName("select");
	for (i = 0; i < selects.length; i++ ) {
		selects[i].style.visibility = (state == "hide") ? 'hidden' : 'visible';
	}

	if (this.isFrame) {
		for (i = 0; i < parent.frames.length; i++) {
			selects = parent.frames[i].window.document.getElementsByTagName("select");
			for (var j = 0; j < selects.length; j++) {
				selects[j].style.visibility = (state == "hide") ? 'hidden' : 'visible';
			}
		}
	}
};

//********************************************************************************/
// pause(numberMillis)
// Pauses code execution for specified time. Uses busy code, not good.
// Code from http://www.faqts.com/knowledge_base/view.phtml/aid/1602
//********************************************************************************/
LyteBox.prototype.pause = function(numberMillis) {
	var now = new Date();
	var exitTime = now.getTime() + numberMillis;
	while (true) {
		now = new Date();
		if (now.getTime() > exitTime) { return; }
	}
};

//***************/
// add listeners
//***************/
if (window.addEventListener) {		// W3C
	window.addEventListener("load",initLytebox,false);
} else if (window.attachEvent) {	// Exploder
	window.attachEvent("onload",initLytebox);
} else {							// Old skool
	window.onload = function() {initLytebox();}
}

/* START IT UP! */
function initLytebox() { myLytebox = new LyteBox(); }