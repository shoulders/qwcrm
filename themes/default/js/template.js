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






/** Key Input Restrictions - case insensitive**/

// Allows Only Letters - Including (Backspace, Space, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyAlpha(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz", true);
}

// Allow Only Numbers and Letters - Including (Backspace, Space, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyAlphaNumeric(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789", true);
}

// Allow Only Numbers and Letters - Including (Backspace, Space, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete && Comma, Backslash, Minus, Single Quote)
function onlyAlphaNumericExtra(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789,/-'", true); 
}

// Allows Only Numbers - Including (Backspace, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyNumbers(e) {
    return keyRestriction(e, "0123456789", false);
}

// Allows Only Numbers and Periods - Including (Backspace, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyNumbersPeriods(e) {
    return keyRestriction(e, "0123456789.", false);
}

// Allow Only Phone Numbers - Including (Backspace, Space, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete && Period, Brackets, Plus, Minus)
function onlyPhoneNumber(e) {
    return keyRestriction(e, "0123456789.()-+", true);
}

// Allow Only valid characters for URL - Including (Backspace, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyURL(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789-._~:/?#[]@!$&'()*+,;=`.%", false);
}

// Allow Only valid characters for Email - Including (Backspace, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
function onlyEmail(e) {
   return keyRestriction(e, "abcdefghijklmnopqrstuvwxyz0123456789-._@", false);
}

// Common Function for Key Input Restriction
function keyRestriction(e, allowedCharacters, spacesAllowed) {
    
    //if (!e.key){window.alert(key);}
    
    var key;
    //var keychar;
    
    // Grab the character from the keypress
    key = e.key;
    
    var keytest = e.which;
    window.alert(key + ' - ' + keytest);
    
    //if (key == ''){window.alert(key);}
    /*if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
       return true;*/   
   
    // Make the script case insensitive (onkeypress)
    //keychar = String.fromCharCode(key);
    //key = key.toLowerCase();
    
    
    // This works - ish -- http://stackoverflow.com/questions/1435885/converting-keystrokes-gathered-by-onkeydown-into-characters-in-javascript
    
    // see these notes - http://www.w3schools.com/jsref/event_key_keycode.asp
    
    //The keydown and keyup events are traditionally associated with detecting any key, not just those which produce a character value.  - https://w3c.github.io/uievents/#widl-KeyboardEvent-key
    
    //var key = e.which || e.keyCode; // Use either which or keyCode, depending on browser support
    //var keychar = String.fromCharCode(key);
    //window.alert(keychar);
    
    // Make the script case insensitive (onkeydown)
    //keychar = String.fromCharCode(key);
    //keychar = keychar.toLowerCase();
    
    //var textBox = getObject('txtChar');
    //var charCode = (e.which) ? e.which : e.keyCode;
    //var keychar = String.fromCharCode(charCode);
    
    // Are Spaces Allowed
    //if (key === 32 && spacesAllowed === true)
        //return true;
    if (key === ' ' && spacesAllowed === true)
        return true;
    
//window.alert(key.value);

    // do a if e.key is empty check for the charcode of the thing and then compare - this needs to be done until full support in chrome


    // onkeydown key is corretly alerted but script fail to validate - Control Keys (Backspace, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
    if (key === 8 || key === 35 || key === 36 || key === 37 || key === 38 || key === 39 || key === 40 || key === 46){          
        return true;}
    if (key === 'Backspace' || key === 'End' || key === 'Home' || key === 'ArrowLeft' || key === 'ArrowUp' || key === 'RightArrow' || key === 'ArrowDown' || key === 'Delete'){        
        return true;}
    
    // onkeypress - original exceptions
    // Control keys (-, -, Backspace, Tab, Enter, Escape, Space)
    /*if ((key===null) || (key===0) || (key===8) || (key===9) || (key===13) || (key===27) || (key===32))
        return true;

    // Allowed Characters
    else if ((allowedCharacters.indexOf(keychar) > -1))
       return true;
    else
       return false;
       */
      else if (allowedCharacters.indexOf(key) > -1)
       return true;
    else
       return false;
      
}