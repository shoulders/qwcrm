/* expense_other.js */
   
$(document).ready(function() {

    // Get the initially selected VAT Tax Code
    var selected_vat_tax_code = $('#vat_tax_code').find('option:selected');

    // Set the initial VAT rate based on the selected VAT Tax Code 
    var tcVatRate = selected_vat_tax_code.data('rate');            
    $('#unit_tax_rate').val(tcVatRate);

    // Set VAT and Net readonly based on VAT TAX code (i.e. T9)
    if(selected_vat_tax_code.data('only_gross')) {                       
        $('#unit_net').attr('readonly', true);
        $('#unit_tax').attr('readonly', true);            
    }

    // Recalculate totals
    calculateTotals('vat_tax_code');        

    // Bind an action to the VAT Tax Code dropdown - Update the totals on change and alter the readonly status of Net and Tax rate where required
    $('#vat_tax_code').change(function() {
        
        // Get values
        var selected = $(this).find('option:selected');
        var tcVatRate = selected.data('rate');   
        var tcVatOnlyGross = selected.data('only_gross'); 
        
        // Set Tax rate
        $('#unit_tax_rate').val(tcVatRate);
        
        // Handle 'Only Gross' logic (i.e. on/off selection)
        if(tcVatOnlyGross) {                       
            $("#unit_net").val(parseFloat(0.00).toFixed(2));            
            $('#unit_net').attr('readonly', true);                 
            $('#unit_tax').attr('readonly', true);      
        } else {
            if($('#unit_net').attr('readonly')) {
                $('#unit_net').val('');
            }
            $('#unit_net').attr('readonly', false);
            $('#unit_tax').attr('readonly', false);              
        }
        calculateTotals('vat_tax_code');
    } );

    // If not a VAT Tax system
    {if !'/^vat_/'|preg_match:$qw_tax_system}

        // Non-VAT Auto Calculations - Automatically populate Net with the Gross figure
        $('.amend_values').submit(function( event ) {                   

            // Get input field values
            var unit_gross  = Number(document.getElementById('unit_gross').value);

            // Set the new unit_gross input value
            document.getElementById('unit_net').value = unit_gross.toFixed(2);

        } );

    {/if}

} );  

// VAT Auto Calculations - Automatically calculate totals
function calculateTotals(fieldName) {
    
    // Get input field values
    var unit_net        = Number(document.getElementById('unit_net').value);
    var unit_tax_rate   = Number(document.getElementById('unit_tax_rate').value);
    var unit_tax        = Number(document.getElementById('unit_tax').value);

    // Calculations        
    var auto_unit_tax = (unit_net * (unit_tax_rate/100));        
    if(fieldName !== 'unit_tax') {
        var auto_unit_gross = (unit_net + auto_unit_tax);
    } else {            
        var auto_unit_gross = (unit_net + unit_tax);
    }

    // Set the new unit_tax input value if not editing the unit_tax input field
    if(fieldName !== 'unit_tax') {
        document.getElementById('unit_tax').value = auto_unit_tax.toFixed(2);
    }

    {if '/^vat_/'|preg_match:$qw_tax_system}
        // Set the new unit_gross input value
        document.getElementById('unit_gross').value = auto_unit_gross.toFixed(2);
    {/if}

}