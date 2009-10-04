// Load this script when page loads
$(document).ready(function(){

 // Set up a listener so that when anything with a class of 'tab'
 // is clicked, this function is run.
 $('.tab').click(function () {

  // Remove the 'active' class from the active tab.
  $('#tabs_container > .tabs > li.active')
	  .removeClass('active');

  // Add the 'active' class to the clicked tab.
  $(this).parent().addClass('active');

  // Remove the 'tab_contents_active' class from the visible tab contents.
  $('#tabs_container > .tab_contents_container > div.tab_contents_active')
	  .removeClass('tab_contents_active');

  // Add the 'tab_contents_active' class to the associated tab contents.
  $(this.rel).addClass('tab_contents_active');

 });
});

