// Tabs
$(document).ready(function(){

    // Set up a listener so that when anything with a class of 'tab'
    // is clicked, this function is run.
    $(".tab").click(function(){

        // Remove the 'active' class from the active tab.
        $("#tabs_container > .tabs > li.active")
            .removeClass("active");

        // Add the 'active' class to the clicked tab.
        $(this).parent().addClass("active");

        // Remove the 'tab_contents_active' class from the visible tab contents.
        $("#tabs_container > .tab_contents_container > div.tab_contents_active")
            .removeClass("tab_contents_active");

        // Add the 'tab_contents_active' class to the associated tab contents.
        $(this.rel).addClass("tab_contents_active");

    });
});


// Change page on select
function changePage() {
    box = document.forms[0].page_no;
    destination = box.options[box.selectedIndex].value;
    if (destination) {location.href = destination;}
}
        
/* customer.js /employee main.tpl - investigate why these have a different number, possible fault
function changePage() {
    box = document.forms[1].page_no;
    destination = box.options[box.selectedIndex].value;
    if (destination) location.href = destination;*/

/** Key Input Restrictions - Using event.key **/

// Allows Only Letters
function onlyAlpha(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", true);
}

// Allow Only Numbers and Letters
function onlyAlphaNumeric(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", true);
}

// Allow Only Numbers and Letters - Including Comma, Backslash, Minus, Single Quote (for addresses?)
function onlyAddresses(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,/-'", true);
}

// Allows Only Numbers
function onlyNumbers(e) {
    return keyRestriction(e, "0123456789", false);
}

// Allows Only Numbers and Period
function onlyNumbersPeriod(e) {
    return keyRestriction(e, "0123456789.", false);
}

// Allow Only Phone Numbers - Including Period, Brackets, Plus, Minus
function onlyPhoneNumber(e) {
    return keyRestriction(e, "0123456789.()-+", true);
}

// Allow Only valid characters for URL
function onlyURL(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~:/?#[]@!$&'()*+,;=`%", false);
}

// Allow Only valid characters for Email
function onlyEmail(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._@", false);
}

// Common Function for Key Input Restriction
function keyRestriction(e, allowedCharacters, spacesAllowed) {    
    
    // Grab the character from the pressed key
    var key = e.key;   
    
    // Are Spaces Allowed
    if (key === ' ' && spacesAllowed === true)
        return true;
    
    // Are Spaces Allowed (IE fix)
    if (key === 'Spacebar' && spacesAllowed === true)
        return true;
    
    // Control Keys (Backspace, Enter and Return, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
    if (key === 'Backspace' || key === 'Enter' || key === 'End' || key === 'Home' || key === 'ArrowLeft' || key === 'ArrowUp' || key === 'ArrowRight' || key === 'ArrowDown' || key === 'Delete')       
        return true;

    // Control Keys (IE and Edge fix)
    if (key === 'Left' || key === 'Up' || key === 'Right' || key === 'Down' || key === 'Del')       
        return true;
    
    // Allowed Characters
    else if (allowedCharacters.indexOf(key) > -1)
        return true;
    else
        return false;
    
}