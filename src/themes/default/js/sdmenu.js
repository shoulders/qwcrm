// Create the SDMenu Object
function SDMenu(id) {
  if (!document.getElementById || !document.getElementsByTagName)
		return false;
	this.menu = document.getElementById(id);
	this.submenus = this.menu.getElementsByTagName("div");
	this.remember = true;
	this.speed = 3;
	this.markCurrent = true;
	this.oneSmOnly = false;
}

// Prepare the Sub Menu Items
SDMenu.prototype.init = function() {

	var mainInstance = this;

	// Add a toggle event on to each of the Sub Menu Items
	for (var i = 0; i < this.submenus.length; i++)
		this.submenus[i].getElementsByTagName("span")[0].onclick = function() {
  			mainInstance.toggleMenu(this.parentNode);
		};

  // Set the current Sub Menu item
	if (this.markCurrent) {
		var links = this.menu.getElementsByTagName("a");
		for (var i = 0; i < links.length; i++)
			if (links[i].href == document.location.href) {
				links[i].className = "current";
				break;
			}
	}

  // Load Sub Menu Expanded States from Cookie (if set, else read from the HTML) and set their Expansion status
	if (this.remember) {

    // Build the cookie name for this menu
		var regex = new RegExp("" + encodeURIComponent(this.menu.id) + "=([01]+)");

    // If a matching cookie found, apply expansion states (via CSS) to the Sub Menu Items
    var match = regex.exec(document.cookie);
		if (match) {
			var states = match[1].split("");
			for (var i = 0; i < states.length; i++)
        states[i] == 1 ? this.submenus[i].classList.add("collapsed") : this.submenus[i].classList.remove("collapsed");
    }
	}
};

// Save the Sub Menu Expanded States to a cookie
SDMenu.prototype.memorize = function() {
	if (this.remember) {
		var states = new Array();
		for (var i = 0; i < this.submenus.length; i++)
			states.push(this.submenus[i].classList.contains("collapsed") ? 1 : 0);
		var d = new Date();
		d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
		document.cookie = "sdmenu_" + encodeURIComponent(this.menu.id) + "=" + states.join("") + "; expires=" + d.toGMTString() + "; path=/;SameSite=Lax";
	}
};

// Toggle Sub Menu Collapse Status
SDMenu.prototype.toggleMenu = function(submenu) {
	if (submenu.classList.contains("collapsed"))
		this.expandMenu(submenu);
	else
		this.collapseMenu(submenu);
};

// Collapse Sub Menu
SDMenu.prototype.collapseMenu = function(submenu) {
	var minHeight = submenu.getElementsByTagName("span")[0].offsetHeight;
	var moveBy = Math.round(this.speed * submenu.getElementsByTagName("a").length);
	var mainInstance = this;
	var intId = setInterval(function() {
		var curHeight = submenu.offsetHeight;
		var newHeight = curHeight - moveBy;
		if (newHeight > minHeight)
			submenu.style.height = newHeight + "px";
		else {
			clearInterval(intId);
			submenu.style.height = "";
      if (!submenu.getAttribute('style')) {
        submenu.removeAttribute('style');
      }
			submenu.classList.add("collapsed");
			mainInstance.memorize();
		}
	}, 30);
};

// Collapse All Sub Menus
SDMenu.prototype.collapseAll = function() {
	for (var i = 0; i < this.submenus.length; i++)
		if (!this.submenus[i].classList.contains("collapsed"))
			this.collapseMenu(this.submenus[i]);
};

// Collapse All Other Sub Menus (but this one?)
SDMenu.prototype.collapseOthers = function(submenu) {
	if (this.oneSmOnly) {
		for (var i = 0; i < this.submenus.length; i++)
			if (this.submenus[i] != submenu && !this.submenus[i].classList.contains("collapsed"))
				this.collapseMenu(this.submenus[i]);
	}
};

// Expand Sub Menu
SDMenu.prototype.expandMenu = function(submenu) {
	var fullHeight = submenu.getElementsByTagName("span")[0].offsetHeight;
	var links = submenu.getElementsByTagName("a");
	for (var i = 0; i < links.length; i++)
		fullHeight += links[i].offsetHeight;
	var moveBy = Math.round(this.speed * links.length);
	var mainInstance = this;
	var intId = setInterval(function() {
		var curHeight = submenu.offsetHeight;
		var newHeight = curHeight + moveBy;
		if (newHeight < fullHeight)
			submenu.style.height = newHeight + "px";
		else {
			clearInterval(intId);
			submenu.style.height = "";
      if (!submenu.getAttribute('style')) {
        submenu.removeAttribute('style');
      }
			submenu.classList.remove("collapsed");
      if (!submenu.getAttribute('class')) {
        submenu.removeAttribute('class');
      }
			mainInstance.memorize();
		}
	}, 30);
	this.collapseOthers(submenu);
};

// Expand All Sub Menus
SDMenu.prototype.expandAll = function() {
	var oldOneSmOnly = this.oneSmOnly;
	this.oneSmOnly = false;
	for (var i = 0; i < this.submenus.length; i++)
		if (this.submenus[i].classList.contains("collapsed"))
			this.expandMenu(this.submenus[i]);
	this.oneSmOnly = oldOneSmOnly;
};
