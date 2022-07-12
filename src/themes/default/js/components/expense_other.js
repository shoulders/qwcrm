/* expense_other.js */
   
$(document).ready(function() {

    {if !'/^vat_/'|preg_match:$qw_tax_system}

        // Non-VAT Auto Calculations - Automatically populate Net with the Gross figure before submission
        $('.amend_values').submit(function( event ) {                   

            // Get input field values
            let unit_gross  =  +$('#unit_gross').val();

            // Set the unit_net to match the unit_gross input value
            $('#unit_net').val(parseFloat(unit_gross).toFixed(2));

        } );

    {/if}

} );  

// VAT Auto Calculations - Automatically calculate totals
function calculateTotals(fieldName) {
    
    {if '/^vat_/'|preg_match:$qw_tax_system}
    
        // Get input values
        let unit_net        = $('#unit_net').val();    
        let unit_tax        = $('#unit_tax').val();       

        // Prevent NaN
        unit_net = !unit_net ? '0' : unit_net;
        unit_tax = !unit_tax ? '0' : unit_tax;

        // Calculations    
        let unit_gross = parseFloat(unit_net) + parseFloat(unit_tax);

        // Update values onscreen
        if(fieldName !== 'unit_gross') {
            $('#unit_gross').val(parseFloat(unit_gross).toFixed(2));
        }
        
    {else}
        return;
    {/if}
}