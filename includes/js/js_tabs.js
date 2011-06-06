
Browser = new function () {
	
	this.isSupported = function(){
		return typeof document.getElementsByTagName != "undefined"
			&& typeof document.getElementById != "undefined";
	};
	
		var ua = navigator.userAgent;
		var OMNI = ua.indexOf("Omni") > 0;

		this.OP5 = /Opera [56]/.test(ua);
		this.OP7 = /Opera [7]/.test(ua);
		this.MAC = /Mac/.test(ua);

		if(!this.OP5 && !OMNI){
			this.IE5 = /MSIE 5/.test(ua);
			this.IE5_0 = /MSIE 5.0/.test(ua);
			this.MOZ =/Gecko/.test(ua);
			this.MAC_IE5 = this.MAC && this.IE5;
			this.IE6 = /MSIE 6/.test(ua);
			this.KONQUEROR = /Konqueror/.test(ua);
		}		
};
var px = "px";
TokenizedExps = {};
function getTokenizedExp(token, flag){
	var x = TokenizedExps[token];
	if(!x)
		x = TokenizedExps[token] = new RegExp("(^|\\s)"+token+"($|\\s)", flag);
	return x;
}

function hasToken(s, token){
	return getTokenizedExp(token,"").test(s);
};
	

function getChildNodesWithClass(parent, klass){
		
	var collection = parent.childNodes;
	var returnedCollection = [];
	var exp = getTokenizedExp(klass,"");

	for(var i = 0, counter = 0; i < collection.length; i++)
		if(exp.test(collection[i].className))
			returnedCollection[counter++] = collection[i];

	return returnedCollection;
}


function getElementsWithClass(parent, tagName, klass){
	var returnedCollection = [];
	try{
	var exp = getTokenizedExp(klass,"");
	var collection = (tagName == "*" && parent.all) ?
		parent.all : parent.getElementsByTagName(tagName);
	
	for(var i = 0, counter = 0; i < collection.length; i++){
		
		if(exp.test(collection[i].className))
			returnedCollection[counter++] = collection[i];
	}
	return returnedCollection;
	}
	catch(x){	alert("parent = "+ parent  +" tagName = "+ tagName+" klass = "+klass);throw x;
}
}



function get_elements_with_class_from_classList(el, tagName, classList){

    var returnedCollection = new Array(0);
    
    var collection = (tagName == "*" && el.all) ?
    	el.all : el.getElementsByTagName(tagName);
    var exps = [];
	for(var i = 0; i < classList.length; i++)
		exps[i] = getTokenizedExp(classList[i],"");
	for(var j = 0, coLen = collection.length; j < coLen; j++){
		kloop: for(var k = 0; k < classList.length; k++){
			if(exps[k].test(collection[j].className)){
				returnedCollection[returnedCollection.length] = collection[j];
				break kloop;
			}
		}
	}
    return returnedCollection;
}

function findAncestorWithClass(el, klass) {
	
	if(el == null)
		return null;
	var exp = getTokenizedExp(klass,"");
	for(var parent = el.parentNode;parent != null;){
	
		if( exp.test(parent.className) )
			return parent;
			
		parent = parent.parentNode;
	}
	return null;
}


function getDescendantById(parent, id){
	var childNodes = parent.all ? parent.all : parent.getElementsByTagName("*");
	for(var i = 0, len = childNodes.length; i < len; i++)
		if(childNodes[i].id == id)
			return childNodes[i];
	return null;
}


function getViewportHeight() {
if(window.innerHeight)
return window.innerHeight;
if(typeof window.document.documentElement.clientHeight=="number")
return window.document.documentElement.clientHeight;
return window.document.body.clientHeight;
}
function getViewportWidth() {
if(window.innerWidth)
return window.innerWidth-16;
if(typeof window.document.documentElement.clientWidth=="number")
return window.document.documentElement.clientWidth;
return window.document.body.clientWidth;
}
function getScrollLeft(){
if(typeof window.pageXOffset=="number")
return window.pageXOffset;
if(document.documentElement.scrollLeft)
return Math.max(document.documentElement.scrollLeft,document.body.scrollLeft);
else if(document.body.scrollLeft!=null)
return document.body.scrollLeft;
return 0;
}
function getScrollTop(){
if(typeof window.pageYOffset=="number")
return window.pageYOffset;
if(document.documentElement.scrollTop)
return Math.max(document.documentElement.scrollTop,document.body.scrollTop);
else if(document.body.scrollTop!=null)
return document.body.scrollTop;
return 0;
}


Function.prototype.extend=function(souper){this.prototype=new souper;this.prototype.constructor=this;this.souper=souper;this.prototype.souper=souper;};ElementWrapper=function ElementWrapper(el){if(arguments.length==0) return;this.el=el;this.id=el.id;if(!ElementWrapper.list[this.id])
ElementWrapper.list[this.id]=this;};ElementWrapper.list=new function(){};ElementWrapper.getWrapper=function(id){return ElementWrapper.list[id];};EventQueue=function EventQueue(eventObj){if(arguments.length==0) return;this.souper=EventQueue.souper;this.souper(eventObj);this.addToPool();};EventQueue.extend(ElementWrapper);EventQueue.prototype.addEventListener=function(etype,pointer){var list=this.eventHandlerList(etype.toLowerCase());return list[list.length++]=pointer;};EventQueue.prototype.eventHandlerList=function(etype){if(!this[etype])
this[etype]=new EventQueue.EventHandler(this,etype);return this[etype];};EventQueue.prototype.removeEventListener=function(etype,pointer){etype=etype.toLowerCase();var list=this[etype];var len=list.length;if(len==0) return null;var newList=new Array(len-1);var rtn=null;for(var i=0;i<len;i++)
if(list[i]!=pointer)newList[i]=list[i];else rtn=pointer;this[etype]=newList;return rtn;};EventQueue.prototype.handleEvent=function(e){var rtn=true;for(var i=0,len=this[e].length;i<len;i++){this.tempFunction=this[e][i];if(rtn!=false)
rtn=this.tempFunction();}return rtn;};EventQueue.prototype.addToPool=function(){if(!EventQueue.list[this.id])
EventQueue.list[this.id]=this;};EventQueue.EventHandler=function EventHandler(wrapper,etype){etype=etype.toLowerCase();this.etype=etype;this.length=0;this.id=wrapper.id;wrapper.el[etype]=new Function("return EventQueue.fireEvent('"+wrapper.id+"','"+etype+"')");};EventQueue.fireEvent=function(id,e){var wrapper=EventQueue.list[id];if(!wrapper) return false;var r=wrapper.handleEvent(e);return r;};EventQueue.EventHandler.prototype.toString=function toString(){return this.id+"."+this.etype;};EventQueue.list=new Object;


function setPageCookie(name, value){
document.cookie=name+"="+escape(value)+"; path="+getPath();
}
function getCookie(name){
var dc=document.cookie;
var prefix=name+"=";
var begin=dc.lastIndexOf(prefix);
if(begin==-1) return null;
var end=dc.indexOf(";", begin);
if(end==-1) end=dc.length;
return unescape(dc.substring(begin+prefix.length, end));
}
function deletePageCookie(name, path){
var value=getCookie(name);
if(value!=null)
document.cookie=name+"="+"; path="+getPath()+"; expires=Thu, 01-Jan-70 00:00:01 GMT";
return value;
}
function getFilename(){
var href=window.location.href;
var file=href.substring(href.lastIndexOf("/")+1);
return file;
}
function getPath(){
var path = location.pathname;
return path.substring(0, path.lastIndexOf("/")+1);
}




if(!window.TabParams)
	window.TabParams={
	useClone:false,
	alwaysShowClone:false,
	eventType:"click",
	tabTagName:"*",
	imgOverExt:"",
	imgActiveExt:""
};
TabSystem=function TabSystem(el,tabsDiv){if(arguments.length==0)return;this.souper=TabSystem.souper;this.souper(el);if(typeof tabsDiv.onselectstart!="undefined")
tabsDiv.onselectstart=function(){return false;};this.el.onchange=function(){};this.el.onbeforechange=function(){};this.defaultActiveTab=null;this.activeTab=null;this.relatedTab=null;this.nextTab=null;this.tabsDiv=tabsDiv;this.tabParams=this.getTabParams();this.tabArray=get_elements_with_class_from_classList(this.tabsDiv,this.tabParams.tabTagName,["tab","tabActive"]);this.tabsClone=null;this.tabs=new Array(0);if(!TabSystem.list[this.id])
TabSystem.list[this.id]=this;};TabSystem.list=new Object;TabSystem.extend(EventQueue);TabSystem.prototype.parentSystem=function(){var root=TabSystem.list["body"];if(root=this)return null;var parent=findAncestorWithClass(this.el,"content");if(parent!=null)
return TabSystem.list[parent.id];return root;};TabSystem.prototype.getTabParams=function(){if(!this.tabParams){this.tabParams=new Object;var parentSystem=this.parentSystem();parentTp=(parentSystem==null)?TabParams:parentSystem.getTabParams();for(var param in parentTp)
this.tabParams[param]=parentTp[param];}return this.tabParams;};TabSystem.prototype.setEventType=function(eventType){var params=this.getTabParams();if(params.eventType==eventType)return;for(var i=0,len=this.tabArray.length;i<len;i++){var tab=Tab.list[this.tabArray[i].id];tab.removeEventListener("on"+params.eventType,tab.depressTab);tab.addEventListener("on"+eventType,tab.depressTab);}params.eventType=eventType;};function removeTabs(ts){ts.tabsDiv.style.display="none";if(ts.tabsClone)
ts.tabsClone.style.display="none";var cs=getElementsWithClass(ts.el,"div","content");for(var i=0;i<cs.length;i++){cs[i].style.visibility='visible';cs[i].style.display='block';}}function undoRemoveTabs(ts){ts.tabsDiv.style.display="block";if(ts.tabsClone)
ts.tabsClone.style.display="block";isTabLayout=true;for(var i=0;i<ts.tabs.length;i++)
if(ts.tabs[i]!=ts.activeTab){ts.tabs[i].content.style.display="none";ts.tabs[i].content.style.visibility="hidden";}}TabSystem.prototype.setAlwaysShowClone=function(flag){this.getTabParams().alwaysShowClone=flag;this.showTabsCloneIfNecessary();};TabSystem.prototype.addClone=function(){if(!this.tabsDiv.cloneNode)return;this.getTabParams().useClone=true;this.tabsClone=this.tabsDiv.cloneNode(true);if(!this.tabsClone)return;this.tabsClone.className="tabs tabsClone";this.el.appendChild(this.tabsClone);for(var i=0;i<this.tabArray.length;i++){var cont=Tab.list[this.tabArray[i].id];var bt=getDescendantById(this.tabsClone,cont.id);bt.id="Bottom"+bt.id;cont.bottomTab=new BottomTab(bt,cont);}this.addEventListener("onchange",updateTabsClonePosition);if(Browser.MAC_IE5)
window.setInterval("updateTabsClonePosition()",300);contentPane.addEventListener("onresize",updateTabsClonePosition);this.showTabsCloneIfNecessary();};tabInit=function tabInit(){if(!Browser.isSupported())
return;var tabsDivs=getElementsWithClass(document.body,"div","tabs");if(tabsDivs.length==0){var tabsDiv0=document.getElementById("tabs");if(tabsDiv0)
tabsDivs=[tabsDiv0];else return;}var tabToDepress;for(var i=0;i<tabsDivs.length;i++){var cnt=findAncestorWithClass(tabsDivs[i],"content")||document.body;if(!cnt.id)
cnt.id="body";var ts=new TabSystem(cnt,tabsDivs[i]);var len=ts.tabArray.length;for(var j=0;j<len;new ControllerTab(ts.tabArray[j++],ts));}
var activeTabs=getCookie("activeTabs"+escape(getFilename()))
if(activeTabs!=null){var activeTabArray=activeTabs.split(",");deletePageCookie("activeTabs",getPath());for(var i=0,len=activeTabArray.length;i<len;i++){var tab=Tab.list[activeTabArray[i]];if(tab)
tab.depressTab();}}if(Browser.MAC_IE5){fixDocHeight=function(){document.documentElement.style.height=document.body.style.height=document.body.clientHeight+"px";};contentPane.addEventListener("onresize",fixDocHeight);setTimeout("fixDocHeight()",500);}handleHashNavigation();deletePageCookie("activeTabs"+escape(getFilename()));for(id in TabSystem.list){var ts=TabSystem.list[id];if(ts.tabParams.useClone)
ts.addClone();if(ts.activeTab==null&&ts.defaultActiveTab!=null)
ts.defaultActiveTab.depressTab();}if(Browser.MOZ) 
try{ repaintFix(document.body); } catch (ex){}};window.id="window";contentPane=new EventQueue(window);function handleHashNavigation(){var id=window.location.hash;if(id){var el=document.getElementById(id.substring(1));if(el){var contentEl;if(hasToken(el.className,"content"))
contentEl=el;else contentEl=findAncestorWithClass(el,"content");if(contentEl)
switchTabs("tab"+contentEl.id.substring("content".length),null,false);}}}Tab=function Tab(el,ts){if(arguments.length==0)return;this.souper=Tab.souper;this.souper(el);this.content=null;this.tabSystem=ts;this.properties=new Object;this.el.onActivate=function(){};this.addEventListener("onmouseover",this.hoverTab);this.addEventListener("onmouseout",this.hoverOff);this.addEventListener("on"+this.tabSystem.getTabParams().eventType,this.depressTab);if(Browser.IE5_0)
positionTabEl(this);if(!Tab.list[this.id])
Tab.list[this.id]=this;};Tab.extend(EventQueue);
Tab.list=new Object;
Tab.prepare=function(){	return true; };
Tab.prepare();
Tab.prototype.setProperty=function(name,value){this.properties[name]=value;};Tab.prototype.getContent=function(){if(this.content==null){var id=this.id.substring(3);this.content=document.getElementById("content"+id);if(!this.content){alert("tab.id="+this.id+"\n"+"content"+id+" does not exist!");}}return this.content;};Tab.prototype.getTabSystem=function(){return this.tabSystem;};hoverTab=function hoverTab(){var tab=Tab.list[this.id];var activeTab=tab.tabSystem.activeTab;if(activeTab&&activeTab.id==tab.id)return;tab.setClassName("tabHover tab");if(tab.hoversrc)
tab.el.src=tab.hoversrc;};hoverOff=function hoverOff(){var tab=Tab.list[this.id];var activeTab=tab.tabSystem.activeTab;if(activeTab&&activeTab.id==tab.id)return;tab.setClassName("tab");if(tab.normalsrc)
tab.el.src=tab.normalsrc;};Tab.prototype.toString=function(){return this.id;};function resetTab(tab){tab.setClassName("tab");if(tab.normalsrc)
tab.el.src=tab.normalsrc;tab.getContent().style.display="none";tab.getContent().style.visibility="hidden";}ControllerTab=function ControllerTab(el,ts){if(arguments.length==0)return;this.souper(el,ts);if(el.tagName.toLowerCase()=="img"){this.normalsrc=el.src;this.hoversrc=el.src.replace(extExp,TabParams.imgOverExt+"$1");this.activesrc=el.src.replace(extExp,TabParams.imgActiveExt+"$1");}if(hasToken(el.className,"tabActive")){this.depressTab();this.tabSystem.defaultActiveTab=this;}else{this.getContent().style.display="none";this.getContent().style.visibility="hidden";}this.tabSystem.tabs[this.tabSystem.tabs.length]=this;};ControllerTab.extend(Tab);ControllerTab.prototype.setClassName=function(klass){this.el.className=klass;if(this.bottomTab)
this.bottomTab.el.className=klass;};ControllerTab.prototype.hoverTab=hoverTab;ControllerTab.prototype.hoverOff=hoverOff;ControllerTab.prototype.depressTab=function depressTab(e){var tab=Tab.list[this.id];var tabSystem=tab.tabSystem;tabSystem.nextTab=tab;if(tabSystem.activeTab==tab)return;tabSystem.relatedTab=tabSystem.activeTab;if(false==tabSystem.el.onbeforechange())return;tab.el.onActivate();tab.setClassName("tab tabActive");if(tab.activesrc)
tab.el.src=tab.activesrc;if(tabSystem.activeTab)
resetTab(tabSystem.activeTab);tabSystem.activeTab=tab;tabSystem.el.onchange();if(tabSystem.relatedTab)
tabSystem.relatedTab.getContent().style.display="none";tab.getContent().style.display="block";tab.getContent().style.visibility="inherit";tabSystem.nextTab=null;if(tabSystem.tabsClone)
tabSystem.showTabsCloneIfNecessary();if(Browser.MOZ)
updateTabsClonePosition(1);};BottomTab=function BottomTab(el,controllerTab){if(arguments.length==0)return;this.souper(el,controllerTab.tabSystem);this.controllerTab=controllerTab;};BottomTab.extend(Tab);BottomTab.prototype.hoverTab=function(){this.controllerTab.hoverTab();};BottomTab.prototype.hoverOff=function(){this.controllerTab.hoverOff();};BottomTab.prototype.depressTab=function depressClonedTab(e){var tabSystem=this.tabSystem;if(tabSystem.activeTab==this.controllerTab)return;this.controllerTab.depressTab(e);this.controllerTab.setClassName("tab tabActive");window.scrollTo(0,(tabSystem.tabsClone.offsetTop+this.el.offsetHeight)-getViewportHeight());};function switchTabs(id,e,bReturn){if(!Browser.isSupported())
return true;try{var tab=Tab.list[id];tab.depressTab(e);}catch(ex){}if(!bReturn)
window.scrollTo(0,0);return bReturn;}updateTabsClonePosition=function updateTabsClonePosition(delay){for(var id in TabSystem.list)
if(TabSystem.list[id].tabParams.useClone)
setTimeout("TabSystem.list."+id+".setTabsClonePosition();",delay||500);};TabSystem.prototype.setTabsClonePosition=function(){if(!this.activeTab)return;var adjustment=0;var contentEl=this.activeTab.content;if(Browser.IE5_0||Browser.MAC_IE5)
adjustment=0;else
adjustment=2;this.tabsClone.style.top=(contentEl.offsetHeight+contentEl.offsetTop+adjustment)+px;};TabSystem.prototype.showTabsCloneIfNecessary=function(){if(!this.activeTab)return;var contentEl=this.activeTab.content;var contentBottom=contentEl.offsetTop+contentEl.offsetHeight;var visibility=(contentBottom>getViewportHeight()||this.getTabParams().alwaysShowClone)?"inherit":"hidden";this.tabsClone.style.visibility=visibility;this.setTabsClonePosition();if(Browser.MOZ){window.scrollBy(0,1);window.scrollBy(0,-1);}};function saveTabSystemState(){var activeTabList=getElementsWithClass(document.body,TabParams.tabTagName,"tabActive");for(var i=0;i<activeTabList.length;i++){if(!activeTabList[i].id)continue;activeTabList[i]=activeTabList[i].id;setPageCookie("activeTabs"+escape(getFilename()),activeTabList);}};contentPane.addEventListener("onunload",saveTabSystemState);function positionTabEl(tab){var tabs=tab.el.parentNode;if(tab.tagName=="IMG"||tab.id.indexOf("Bottomtab")==0)
return;if(!tabs.tabOffset)
tabs.tabOffset=0;var tabWidth=Math.round(tab.el.offsetWidth*1.1)+15;var sty=tab.el.style;sty.left=tabs.tabOffset+px;sty.width=tabWidth+px;sty.textAlign="center";sty.display="block";sty.position="absolute";tabs.tabOffset+=parseInt(tab.el.offsetWidth)+4;}