/* workorder_autosuggest.js */

// Assign functions to be debounced
const debounceWorkorderAutosuggestScopeLookup = debounce((inputtedStringToBeSearched) => workorderAutosuggestScopeLookup(inputtedStringToBeSearched));

// Workorder Autosuggest - Scope
function workorderAutosuggestScopeLookup(inputtedStringToBeSearched) {

    // if input string is less than 3 do nothing
    if(inputtedStringToBeSearched.length < 3) {
        $('#workorderAutosuggestScope').hide();
        return;
    }

    /* If input string is greater than 10, hide the suggestion box. - if people keep typing it will dissapear
    if(inputtedStringToBeSearched.length > 10) {
        $('#workorderAutosuggestScope').hide();
        return;
    }*/

    // Lookup Records and return list - returns suggests in a list <li onclick="fill(value)" >(value)</li>
    $.post('index.php?component=workorder&page_tpl=autosuggest_scope', {

        // Build and submit the POST
        workorder_autosuggest_scope: inputtedStringToBeSearched

    // Use the response
    }, function(data) {
        if (data.length > 0) {
            $('#workorderAutosuggestScope').show();
            $('#workorderAutosuggestScopeList').html(data);
        } else {
            $('#workorderAutosuggestScope').hide();
            return;
        }
    });

}

// Fill the selection into the Scope Input Box
function workorderAutosuggestScopeFill(clickedSuggestion) {
    $('#scope').val(clickedSuggestion);
    setTimeout("$('#workorderAutosuggestScope').hide();", 200);
}

// Close Suggestion Box - if someone clicks elsewhere in the page this closes the suggestions
function workorderAutosuggestScopeClose() {
    setTimeout("$('#workorderAutosuggestScope').hide();", 200);
}
