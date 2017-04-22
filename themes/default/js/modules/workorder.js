
/* workorder.js */

// New Workorder Scope Autosuggest
function lookupSuggestions(scope) {
    
    // If Scope term is greater than 5, hide the suggestion box. - if people keep typing it will dissapear
    if(scope.length > 5) {        
        $('#suggestions').hide();
    
    // Lookup Records and return list - workorder_new_scope_autosuggest.php returns <li></li> suggestions with onclick="fill(value)" added
    } else {        
        $.post("{$includes_dir}autosuggest/workorder_new_scope_autosuggest.php", {queryString: ""+scope+""}, function(data) {
            if(data.length > 0) {
                $('#suggestions').show();
                $('#autoSuggestionsList').html(data);
            }
        } );
    }
}

// Fill the selection into the Scope Input Box
function fill(clickedSuggestion) {
    $('#workorder_scope').val(clickedSuggestion);
    setTimeout("$('#suggestions').hide();", 200);
}

// Close Suggestion Box - if someone clicks elsewhere in the page this closes the suggestions
function closeSuggestions() {
    setTimeout("$('#suggestions').hide();", 200);
}
