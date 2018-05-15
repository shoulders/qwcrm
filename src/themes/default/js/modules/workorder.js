/* workorder.js */

// New Workorder Scope Autosuggest
function lookupSuggestions(scope_input_string) {
    
    // if scope input string is less than 3 do nothing
    if(scope_input_string.length <= 3) {        
        $('#suggestions').hide();
        return;
    }
    
    // If scope input string is greater than 10, hide the suggestion box. - if people keep typing it will dissapear
    if(scope_input_string.length > 10) {        
        $('#suggestions').hide();
        return;
    }
    
    // Lookup Records and return list - returns suggests in a list <li onclick="fill(value)" >(value)</li>
    $.post('index.php?component=workorder&page_tpl=autosuggest_scope&theme=print', {             
        posted_scope_string : scope_input_string }, function(data) {
            if(data.length > 0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            } else {
                $('#suggestions').hide();
                return;
            }
        }
    );
    
}

// Fill the selection into the Scope Input Box
function fill(clickedSuggestion) {
    $('#scope').val(clickedSuggestion);
    setTimeout("$('#suggestions').hide();", 200);
}

// Close Suggestion Box - if someone clicks elsewhere in the page this closes the suggestions
function closeSuggestions() {
    setTimeout("$('#suggestions').hide();", 200);
}