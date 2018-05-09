/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

// Tabs
$(document).ready(function() {

    // Set up a listener so that when anything with a class of 'tab'
    // is clicked, this function is run.
    $(".tab").click(function() {

        // Remove the 'active' class from the active tab.
        $("#tabs_container > .tabs > li.active").removeClass("active");

        // Add the 'active' class to the clicked tab.
        $(this).parent().addClass("active");

        // Remove the 'tab_contents_active' class from the visible tab contents.
        $("#tabs_container > .tab_contents_container > div.tab_contents_active").removeClass("tab_contents_active");

        // Add the 'tab_contents_active' class to the associated tab contents.
        $(this.rel).addClass("tab_contents_active");

    } );
} );

/* - not used globally yet - use this to add later like below
 * 
// Performs a redirect to the new Search Page Number - takes the inputted value and adds it to the end
function GotoPageNumber() {
    document.page_select.action='index.php?page=supplier:view&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value;
    return true;

*/

// Change page when user selects with a dropdown menu - must be put in the <select>
function changePage() { 
    
    //var e = document.getElementById('changeThisPage');
    //var value = e.options[e.selectedIndex].value;
    //var text = changething.options[changething.selectedIndex].text;
    // location.href = value;   or 
    
    location.href = document.getElementById('changeThisPage').value;
}

/** Key Input Restrictors - Using event.key **/ // back slash and double quotes need to be escaped

/* Allows Only Visible ASCII characters - reference only
function onlyAllVisible(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!@#$%^&*()-_=+[]/\\{}|;':\",.<>?", true);
}*/

// Letters
function onlyAlpha(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", true);
}

// Letters and Numbers
function onlyAlphaNumeric(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", true);
}

// Letters and Numbers with Punctuation
function onlyAlphaNumericPunctuation(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/.,'-()", true);
}

// Numbers
function onlyNumber(e) {
    return keyRestriction(e, "0123456789", false);
}

// Numbers and Period
function onlyNumberPeriod(e) {
    return keyRestriction(e, "0123456789.", false);
}

// Sortcode
function onlySortcode(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-", true);
}

// Search
function onlySearch(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/.,'-", true);
}

// Names
function onlyName(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.,'-", true);
}

// Phone Numbers
function onlyPhoneNumber(e) {
    return keyRestriction(e, "0123456789.()-+", true);    
}

// URL
function onlyURL(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._~:/?#[]@!$&'()*+,;=`%", false);
}

// Email
function onlyEmail(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._@", false);
}

// Addresses
function onlyAddress(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,/-'", true);
}

// Gift Certificate Code
function onlyGiftCertCode(e) {
    return keyRestriction(e, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", false);
}

// Currency Symbols
function onlyCurrencySymbol(e) {
    return; 
    //return keyRestriction(e, "", false);
}

// Usernames
function onlyUsername(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-._", false);
}

// Passwords
function onlyPassword(e) {    
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!@#$%^&*_+-=(){}/\\[]<>:;|?\"',.", false);  
}

// Dates
function onlyDate(e) {   
   
   //return keyRestriction(e, "0123456789/", false, false);
   
   // Allows only the date picker to fill in date boxes, no keys or copy and paste (keyboard)
   return false;
}

// MySQL
function onlyMysqlDatabaseName(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$_", false);
}

// FilePath - covers unix/linux/windows
function onlyFilePath(e) {
    return keyRestriction(e, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\:_.", false);
}

/* Escape Strings for parsing - is this needed?
function escapeRegExp(string){
  return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}
*/

// Common Function for Key Input Restriction
function keyRestriction(e, allowedCharacters, spacesAllowed = false, copyAndPasteAllowed = true) {    
    
    // Grab the character from the pressed key
    var key = e.key;
    
    // Is copy and paste allowed (Ctrl + x,c,v) - Windows and Mac (allows for Caps Lock being on)
    if ((e.ctrlKey === true || e.metaKey === true) && (key === 'x' || key === 'X' || key === 'c' || key === 'C' || key === 'v' || key === 'V') && copyAndPasteAllowed === true)
        return true;        
    
    // Are Spaces Allowed
    if (key === ' ' && spacesAllowed === true)
        return true;
    
    // Are Spaces Allowed (IE fix)
    if (key === 'Spacebar' && spacesAllowed === true)
        return true;
    
    // Control Keys (Backspace, Enter and Return, End, Home, Left Arrow, Up Arrow, Right Arrow, Down Arrow, Delete)
    if (key === 'Backspace' || key === 'Enter' || key === 'End' || key === 'Home' || key === 'ArrowLeft' || key === 'ArrowUp' || key === 'ArrowRight' || key === 'ArrowDown' || key === 'Delete' || key === 'Tab')       
        return true;

    // Control Keys (IE and Edge fix)
    if (key === 'Left' || key === 'Up' || key === 'Right' || key === 'Down' || key === 'Del')       
        return true;
    
    // Allowed Characters
    else if (allowedCharacters.indexOf(key) > -1)
        return true;
    
    // Input not allowed
    return false;
   
}

// Confirm Passwords Match - Form Submmision Control - No Visual message (not used anywhere)
function confirmPasswordsMatch() {
    
    // Store the password field objects into variables ...
    var password        = document.getElementById('password');
    var confirmPassword = document.getElementById('confirmPassword');
    
    // Validate the passwords match
    if (password.value === confirmPassword.value) {
        return true;
    } else {
        return false; 
    }
}

// Check Passwords Match - With Visual Message
function checkPasswordsMatch(passwordsMatchMSG, passwordsDoNotMatchMSG) {
    
    // Store the password field objects into variables ...
    var password        = document.getElementById('password');
    var confirmPassword = document.getElementById('confirmPassword');
    
    // Set Confimation Message Location ...
    var message = document.getElementById('passwordMessage');
    
    // Set the input background colors we will be using ...
    var goodColor = "#66cc66";
    var badColor  = "#ff6666";
    
    // Compare the values in the password field and the confirmation field
    if (password.value === confirmPassword.value) {
        // The passwords match - Set the color to the good color and inform the user that they have entered the correct password 
        password.style.backgroundColor = goodColor;
        confirmPassword.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = passwordsMatchMSG;
        return true;
    } else {
        // If the passwords do not match - Set the color to the bad color and notify the user.
        password.style.backgroundColor = badColor;
        confirmPassword.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = passwordsDoNotMatchMSG;
        return false;
    }
    
}

// This function allows me to grab systems messages created during page rendering and display
function processSystemMessages(information_msg, warning_msg) {    
           
    if(information_msg) {
        var information = document.getElementById('information_msg');
        information.style.display = 'block';
        information.innerHTML = information_msg;       
    }
    
    if(warning_msg) {
        var warning = document.getElementById('warning_msg'); 
        warning.style.display = 'block';
        warning.innerHTML = warning_msg;
    }
    
}

// Clear any onscreen system messages
function clearSystemMessages() {
    
    // Wipe information messages
    var information = document.getElementById('information_msg');
    information.style.display = 'none';
    information.innerHTML = '';

    // wipe warning messages
    var warning = document.getElementById('warning_msg'); 
    warning.style.display = 'none';
    warning.innerHTML = '';
    
    // empty the system_message_functions div
    //var system_message_functions = document.getElementById('system_message_functions');
    //system_message_functions.innerHTML = '';
    
}

// A standard confirmation dialogue - e.g. return confirmChoice(msg);
function confirmChoice(msg) {
    
    var choice = confirm(msg);
    if (choice)
        return true;
    else
        return false;
    
}