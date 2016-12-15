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






/** Key Input Restrictions **/

// Allows Only Uppercase and Lowercase Letters to be entered - Including Space
function onlyAlpha(e) {
    
    var charCode = e.which || e.keyCode; 
    
    if ((charCode === 32 || charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122)){
        return true;
    }
    return false;
}

// Allows Only Numbers to be entered - Including Backspace
function onlyNumbers(e) {
    
    var charCode = e.which || e.keyCode;
    
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
        return false;
    }
    return true;
}

// Allows Only Numbers and Periods to be entered  (&& charCode != 44  is comma)
function onlyNumbersPeriods(e) {
    
    var charCode = e.which || e.keyCode;
    
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46){
        return false;
    }
    return true;
}

// Allow Only Phone Numbers - Including (-, -, Backspace, Tab, Enter, Escape, Space && Period, Brackets, Plus, Minus)
function onlyPhoneNumbers(e) {
    
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
        return true;
    
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();

    // Control keys (-, -, Backspace, Tab, Enter, Escape, Space)
    if ((key===null) || (key===0) || (key===8) || (key===9) || (key===13) || (key===27) || (key===32))
        return true;

    // Allowed Characters
    else if ((("0123456789.()-+").indexOf(keychar) > -1))
        return true;
    else
        return false;
}








// Allow Only Numbers and Letters (Uppercase and Lowercase) - Including (Backspace, Space, Left Arrow, Right Arrow, Delete)
function ddonlyAlphaNumeric(e) {
    
    var key;
    var keychar;

    if (window.event)
       key = window.event.keyCode;
    else if (e)
       key = e.which;
    else
        return true;
    
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();

    // Control Keys (Backspace, Space, Left Arrow, Right Arrow, Delete)
    if ((key===8) || (key===32) || (key===37) || (key===39) || (key===46))
        return true;

    // Allowed Characters
    else if ((("abcdefghijklmnopqrstuvwxyz0123456789").indexOf(keychar) > -1))
        return true;
    else
        return false;
}

// Allow Only Numbers and Letters - Including (-, -, Backspace, Tab, Enter, Escape, Space && Comma, Backslash, Minus, Single Quote)
function ddonlyAlphaNumericExtra(e) {
    
    var key;
    var keychar;

    if (window.event)
       key = window.event.keyCode;
    else if (e)
       key = e.which;
    else
        return true;
    
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();

    // Control Keys (-, -, Backspace, Tab, Enter, Escape, Space)
    if ((key===null) || (key===0) || (key===8) || (key===9) || (key===13) || (key===27) || (key===32))
        return true;

    // Allowed Characters
    else if ((("abcdefghijklmnopqrstuvwxyz0123456789,/-'").indexOf(keychar) > -1))
        return true;
    else
        return false;
}

// Allow Only valid characters for URL - Including (-, -, Backspace, Tab, Enter, Escape, Space)
function onlyURL(e) {
    
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
        return true;
    
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();

    // Control Keys (-, -, Backspace, Tab, Enter, Escape, Space)
    if ((key===null) || (key===0) || (key===8) || (key===9) || (key===13) || (key===27) || (key===32))
        return true;

    // Allowed Characters
    else if ((("abcdefghijklmnopqrstuvwxyz0123456789-._~:/?#[]@!$&'()*+,;=`.%").indexOf(keychar) > -1))
        return true;
    else
        return false;
}

// Allow Only valid characters for Email - Including (-, -, Backspace, Tab, Enter, Escape, Space)
function onlyEmail(e) {
    
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
       return true;
   
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();

    // Control Keys (-, -, Backspace, Tab, Enter, Escape, Space)
    if ((key===null) || (key===0) || (key===8) || (key===9) || (key===13) || (key===27) || (key===32))
       return true;

    // Allowed Characters
    else if ((("abcdefghijklmnopqrstuvwxyz0123456789-._@").indexOf(keychar) > -1))
       return true;
    else
       return false;
}









function onlyAlphaNumeric(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789", true);
}

function onlyAlphaNumericExtra(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789,/-'", true); 
}


// Common Function for key restriction routines
function keyRestriction(e, allowedCharacters, spacesAllowed) {
    
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
       return true;
   
    // Make the script case insensitive
    keychar = String.fromCharCode(key);
    keychar = keychar.toLowerCase();
    
    // Are Spaces Allowed
    if (key===32 && spacesAllowed === true)
        return true;

    // Control Keys (Backspace, Left Arrow, Right Arrow, Delete)
    if ((key===8) || (key===37) || (key===39) || (key===46))
        return true;

    // Allowed Characters
    else if ((allowedCharacters.indexOf(keychar) > -1))
       return true;
    else
       return false;
}