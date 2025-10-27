/* supplier_autosuggest.js */

// Assign functions to be debounced
const debounceSupplierAutosuggestNameLookup = debounce((inputtedStringToBeSearched) => supplierAutosuggestNameLookup(inputtedStringToBeSearched));

// Supplier Autosuggest - Name
function supplierAutosuggestNameLookup(inputtedStringToBeSearched) {

    // if input string is less than 3 do nothing
    if(inputtedStringToBeSearched.length < 3) {
        $('#supplierAutosuggestName').hide();
        return;
    }

    /* If input string is greater than 10, hide the suggestion box. - if people keep typing it will dissapear
    if(inputtedStringToBeSearched.length > 10) {
        $('#supplierAutosuggestName').hide();
        return;
    }*/

    // Lookup Records and return list - returns suggests in a list <li onclick="fill(value)" >(value)</li>
    $.post('index.php?component=supplier&page_tpl=autosuggest_name', {

        // Build and submit the POST
        supplier_autosuggest_name : inputtedStringToBeSearched

    // Use the response
    }, function(data) {
          if(data.length > 0) {
              $('#supplierAutosuggestName').show();
              $('#supplierAutosuggestNameList').html(data);
          } else {
              $('#supplierAutosuggestName').hide();
          return;
        }
    });

}

// Fill the payee name and populate the supplier_id
function supplierAutosuggestNameIdFill(display_name, supplier_id) {
    $('#supplierAutosuggestNameDummy').val(display_name);       // set the Payee name, this will be discarded and is just for the user
    $('#qform\\[supplier_id\\]').val(supplier_id);              // Set the supplier_id used for real addressing.
    $('#supplierIdLink').html(supplier_id);                     // Set the supplier_id to show users onscreen
    supplierAutosuggestNameClose();
}

// Close Suggestion Box - if someone clicks elsewhere in the page, this closes and resets the suggestions
function supplierAutosuggestNameClose() {
    $('#supplierAutosuggestNameInput').val('');   // empty the autosuggest dummy
    setTimeout("$('#supplierAutosuggestName').hide();", 200);
}

// Monitor the `Assign to Supplier` checkbox
$(document).ready(function() {
  $('#assignToSupplier:checkbox').change(function() {
    if (this.checked) {
      $('#qform\\[payee\\]').val('');
      $('#qform\\[payee\\]').prop('hidden', true);
      $('#qform\\[payee\\]').prop('required', false);
      $('#supplierAutosuggestNameDummy').prop('hidden', false);
      $('#supplierAutosuggestBlock').prop('hidden', false);
    } else {
      $('#supplierIdLink').html('');
      //$('#qform\\[payee\\]').val('');
      $('#qform\\[payee\\]').prop('hidden', false);
      $('#qform\\[payee\\]').prop('required', true);
      $('#supplierAutosuggestNameDummy').prop('hidden', true);
      $('#supplierAutosuggestNameDummy').val('');
      $('#supplierAutosuggestBlock').prop('hidden', true);
    }
  });
});
