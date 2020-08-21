<!-- edit.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}dhtmlxcommon.js"></script>
<script src="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.css">
<script>
    
    // Page Building Flag
    var pageBuilding = true;
    
    // Key pressed Boolean - Allow me to determine if action was started by a mouse click or typing
    var keyPressed = false;
    
    // Default Sales Tax Rate
    var invoiceSalesTaxRate = {$invoice_details.sales_tax_rate|string_format:"%.2f"};
    
    // Invoice Tax System
    var invoiceTaxSystem = '{$invoice_details.tax_system}';    
     
    // Labour and Parts items JSON (items from the database)
    var labourItems = {$labour_items_json};
    var partsItems  = {$parts_items_json};    
    
    // Run these functions when the DOM is ready
    $(document).ready(function() {
        
        modifyDummyRowsForTaxSystem();
        processInvoiceItemsFromDatabase('labour', labourItems);
        processInvoiceItemsFromDatabase('parts', partsItems);       
        refreshPage();
            
        // Page Building has now completed
        pageBuilding = false;
        
    });
 
    // Change the Dummy rcords so the visible fields match the Tax System
    function modifyDummyRowsForTaxSystem() {
        
        // If the Tax system is No Tax
        if(invoiceTaxSystem.startsWith("no_tax")) {            
        }
        
        // If the Tax system is VAT based
        if(invoiceTaxSystem.startsWith("vat_")) {
            $(".vatTaxSystem").show();
        }
        
        // If the Tax system Sales Tax based
        if(invoiceTaxSystem.startsWith("sales_tax_cash")) {
            $(".salesTaxSystem").show();
        }
        
    }

    // Create and populate Labour and Parts item rows with sotered data from the database
    function processInvoiceItemsFromDatabase(section, items) {
    
        // Form Fields that are submitted, not all item fields submitted are currently used in the backend
        fieldNames = [
            //"invoice_labour_id",
            //"invoice_id",
            //"tax_system",            
            "description",
            "unit_qty",            
            "unit_net",
            "sales_tax_exempt",
            "vat_tax_code",
            "unit_tax_rate",
            //"unit_tax",
            //"unit_gross",
            "subtotal_net",
            "subtotal_tax",
            "subtotal_gross"];

        // Loop through section items from the database
        $.each(items, function(itemIndex, item) {

            // Create a new row to be populated and get the row identifier
            iteration = createNewTableRow(section);
            
            // Loop through the various fields and populate with their data
            $.each(fieldNames, function(fieldIndex, fieldName) {
                
                // If it is a checkbox
                if(fieldName == "sales_tax_exempt") {
                    if(item[fieldName] === '1') {
                        $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').prop('checked', true);
                    }
                
                // If it is a Combobox
                } else if(fieldName === "description") {
                    
                    // Build the Combobox identifier
                    let comboboxInputName = fieldName.replace("_", "")+'Combobox';
                    
                    // Update Combobox text value using it's API to prevent a change trigger                    
                    // If you change a combobox <option> with Javascript after the comobobox is initiated the the "onChange" and "onSelectionChange" are fired on the first mouse click on <body> (see _doOnBodyMouseDown())
                    window[section+iteration+comboboxInputName].setComboText(item[fieldName]);
                                    
                // Standard Input Value
                } else {
                    // Update field value
                    $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').val(item[fieldName]);
                    //$('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').attr('value', item[fieldName]);
                    //document.getElementById('qform['+section+'_items]['+iteration+']['+fieldName+']').value = item[fieldName]; (this does not work for some reason)                
                }
            });

        });        

    }
    
    // Dynamically Copy, Process and add an new item row to the relevant table
    function createNewTableRow(section) {
        
        // Get Table
        var tbl = document.getElementById(section+'_items');
        
        // Get Next Row Number
        var iteration = tbl.rows.length - 1; 
        
        // Clone Dummy Row        
        var clonedRow = $('#dummy_'+section+'_items_row_iteration').clone();
                
        // Get the outerHTML
        var clonedRowStr = clonedRow.prop("outerHTML");
                
        // Refactor variables
        clonedRowStr = clonedRowStr.replace(/style="display: none;"/, "");
        clonedRowStr = clonedRowStr.replace(/ disabled/g, "");
        clonedRowStr = clonedRowStr.replace(/dummy_/g, "");
        clonedRowStr = clonedRowStr.replace(/iteration/g, iteration);        
        
        // Append the row to the end of the table
        $(tbl).append(clonedRowStr);
        
        // Convert Description cell into a combobox
        window[section+iteration+'descriptionCombobox'] = dhtmlXComboFromSelect('qform['+section+'_items]['+iteration+'][description]');
        
        // Set Combobox Options - https://docs.dhtmlx.com/api__refs__dhtmlxcombo.html    
        window[section+iteration+'descriptionCombobox'].DOMelem_input.id = 'qform['+section+'_items]['+iteration+'][description_combobox]';        
        //window[section+iteration+'descriptionCombobox'].setSize(400);    
        window[section+iteration+'descriptionCombobox'].DOMelem_input.maxLength = 100;    
        window[section+iteration+'descriptionCombobox'].DOMelem_input.required = true;
        window[section+iteration+'descriptionCombobox'].setComboText('');
        window[section+iteration+'descriptionCombobox'].setFontSize("10px","10px");        
        dhtmlxEvent(window[section+iteration+'descriptionCombobox'].DOMelem_input, "keypress", function(e) {
            // This uses Suite API - https://docs.dhtmlx.com/event__index.html
            keyPressed = true;    
            if(onlyAlphaNumericPunctuation(e)) { return true; }
            e.cancelBubble = true;
            if (e.preventDefault) e.preventDefault();
            return false;
        } );
        //window[section+iteration+'descriptionCombobox'].attachEvent("keypress", function(e) { /* Does not Work with keypress */ } ); 
        //window[section+iteration+'descriptionCombobox'].attachEvent("onSelectionChange", function() { /* this does not really do what I want */ } );
        window[section+iteration+'descriptionCombobox'].attachEvent("onChange", function(value, text) {
            
            // Set Unit Net default with the prefill's value (pulled from the Dummy row because dhtmlxcombo does not support data-values)
            if(keyPressed != true) {
                let matchingOption = $('#qform\\['+section+'_items\\]\\[iteration\\]\\[description\\]').find('option[value="'+window[section+iteration+'descriptionCombobox'].getComboText()+'"]');
                let unitNet = matchingOption.data('unit-net');
                if(unitNet != null) { $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\[unit_net\\]').val(parseFloat(unitNet).toFixed(2)); }
            }
                    
            // Reset the keyPessed Boolean as it has now been called and used
            keyPressed = false;
        
            refreshPage();
            
        } );        
        
        // Set Vat Tax Code default value
        $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').val('{$default_vat_tax_code}');
        
        // Update the intial Tax Rate to match the intial VAT Tax Code (Only if the Tax system is VAT based)        
        if(invoiceTaxSystem.startsWith("vat_")) {
            let selected = $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $('#qform\\['+section+'_items\\]\\['+iteration+'\\]\\[unit_tax_rate\\]').val(parseFloat(newTaxRate).toFixed(2));
        }
        
        /* Event Binding - Refreshes All Rows */
           
        // Monitor for change in VAT Tax Code/Rate selectbox and update tax rate accordingly   
        $(".item_row select[id$='\\[vat_tax_code\\]']").off("change").on("change", function() {
            let selected = $(this).find('option:selected');
            let newTaxRate = selected.data('tax-rate'); 
            $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(newTaxRate).toFixed(2));            
            refreshPage();            
        });
        
        // Monitor Sales Tax Exempt Checkboxes for click
        $(".item_row input[id$='\\[sales_tax_exempt\\]']").click(function () {

            // Toggle the value between 0.00 and configured Sales Tax Rate
            if ($(this).is(":checked")) {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val('0.00');                    
            } else {                
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(invoiceSalesTaxRate).toFixed(2));
            }
            refreshPage();            
        });
                
        /* Monitor all input boxes for changes
        $("input[type='text']").off("change").on("change", function() {
            refreshPage();            
        });*/
        
        // Monitor all input boxes for keyup
        $("input[type='text']").off("keyup").on("keyup", function() {
            refreshPage();            
        });
        
        // Item Delete button action
        $(".item_row .confirmDelete").off("click").on("click", function() {
            hideddrivetip();
            if(!confirm('Are you Sure you want to delete this item?')) { return; }
            $(this).closest('tr').remove();
            refreshPage();                       
        });   
        
        /* Cleaning Up */
        
        // Refresh the page
        refreshPage();
            
        // Return the current row index number
        return iteration;
                 
    }

    // Refresh all dynamic items onscreen
    function refreshPage() {
        
        // Refresh Invoice Totals
        refreshTotals();
        
        // Disable all buttons on page refresh unless on initial page build, if there is a change
        if(pageBuilding === false) {            
            $(".userButton").prop('disabled', true).attr('title', '{t}This button is disabled until you have saved your changes.{/t}');
        }
        
    }

    // Recalculate and then refresh all onscreen invoice totals
    function refreshTotals() {
        
        /* Individual Labour and Parts Items */
        
        // Loop through item rows, calculate and refresh new values onscreen (Tax System Aware)
        $('.item_row').each(function() {
            
            // Get User inputed data
            rowUnitQty      = $(this).find("input[id$='\\[unit_qty\\]']").val();
            rowUnitNet      = $(this).find("input[id$='\\[unit_net\\]']").val();
            rowUnitTaxRate  = $(this).find("input[id$='\\[unit_tax_rate\\]']").val();
                        
            // Calculate new values
            rowUnitTax          = rowUnitNet * (rowUnitTaxRate / 100);
            rowUnitGross        = rowUnitNet + rowUnitTax;
            rowSubTotalNet      = rowUnitNet * rowUnitQty;
            rowSubTotalTax      = rowSubTotalNet * (rowUnitTaxRate / 100);
            rowSubTotalGross    = rowSubTotalNet + rowSubTotalTax;
            
            // Update values onscreen + Convert Value to 0.00 format
            rowSubTotalNet      = $(this).find("input[id$='\\[subtotal_net\\]']").val(parseFloat(rowSubTotalNet).toFixed(2));
            rowSubTotalTax      = $(this).find("input[id$='\\[subtotal_tax\\]']").val(parseFloat(rowSubTotalTax).toFixed(2));
            rowSubTotalGross    = $(this).find("input[id$='\\[subtotal_gross\\]']").val(parseFloat(rowSubTotalGross).toFixed(2));
            
        });

        /* Labour and Parts SubTotals */
        
        // Variables stores for Labour and Parts Sum
        labourItemsSubTotalNet      = 0.00;
        labourItemsSubTotalTax      = 0.00;
        labourItemsSubTotalGross    = 0.00;
        partsItemsSubTotalNet       = 0.00;
        partsItemsSubTotalTax       = 0.00;
        partsItemsSubTotalGross     = 0.00;   
        
        // Identifier of values to be Totaled / Target identfier for new value / Variable store to sum values in
        totalsLabourParts = [
            ["#labour_items .item_row input[id$='\\[subtotal_net\\]']","#labour_items_subtotal_net","labourItemsSubTotalNet"],
            ["#labour_items .item_row input[id$='\\[subtotal_tax\\]']","#labour_items_subtotal_tax","labourItemsSubTotalTax"],
            ["#labour_items .item_row input[id$='\\[subtotal_gross\\]']","#labour_items_subtotal_gross","labourItemsSubTotalGross"],
            ["#parts_items .item_row input[id$='\\[subtotal_net\\]']","#parts_items_subtotal_net","partsItemsSubTotalNet"],
            ["#parts_items .item_row input[id$='\\[subtotal_tax\\]']","#parts_items_subtotal_tax","partsItemsSubTotalTax"],
            ["#parts_items .item_row input[id$='\\[subtotal_gross\\]']","#parts_items_subtotal_gross","partsItemsSubTotalGross"]
        ];
        
        // Sum the row values into their relevant variable store
        totalsLabourParts.forEach(function (item, index) {            
            $(item[0]).each(function() {                             
                window[item[2]] += +$(this).val();  //+$() is actually two operations, where first $() runs to grab your input and then + coerces whatever the value of the input is into a number.              
            });                        
        });
        
        // Update values onscreen + Convert Value to 0.00 format
        totalsLabourParts.forEach(function (item, index) {
            $(item[1]).text(parseFloat(window[item[2]]).toFixed(2));
        });
        
        /* Invoice Totals */
        
        // General Invoice Variables
        invoiceDiscountRate = +$("#qform\\[unit_discount_rate\\]").val();
        vouchersTotalNet    = +$("#vouchersTotalNet").text();
        vouchersTotalTax    = +$("#vouchersTotalTax").text();
        vouchersTotalGross  = +$("#vouchersTotalGross").text();
                
        // The actual calculations
        invoiceTotalDiscount    = (labourItemsSubTotalNet + partsItemsSubTotalNet) * (invoiceDiscountRate / 100);               // Divide by 100; turns 17.5 in to 0.17575
        invoiceTotalNet         = (labourItemsSubTotalNet + partsItemsSubTotalNet + vouchersTotalNet) - invoiceTotalDiscount;   // Vouchers are not discounted on purpose
        invoiceTotalTax         = labourItemsSubTotalTax + partsItemsSubTotalTax + vouchersTotalTax;
        invoiceTotalGross       = invoiceTotalNet + invoiceTotalTax;
                
        // Update values onscreen + Convert Value to 0.00 format
        $("#invoiceTotalLabourItemsSubTotalNet").text(parseFloat(labourItemsSubTotalNet).toFixed(2));
        $("#invoiceTotalPartsItemsSubTotalNet").text(parseFloat(partsItemsSubTotalNet).toFixed(2));   
        $("#invoiceTotalDiscountRate").text(parseFloat(invoiceDiscountRate).toFixed(2));
        $("#invoiceTotalDiscount").text(parseFloat(invoiceTotalDiscount).toFixed(2));
        $("#invoiceTotalVouchersTotalNet").text(parseFloat(vouchersTotalNet).toFixed(2));
        $("#invoiceTotalNet").text(parseFloat(invoiceTotalNet).toFixed(2)); 
        $("#invoiceTotalTax").text(parseFloat(invoiceTotalTax).toFixed(2)); 
        $("#invoiceTotalGross").text(parseFloat(invoiceTotalGross).toFixed(2));
        $("#invoiceTotalGrossTop").text(parseFloat(invoiceTotalGross).toFixed(2));

    }
    
</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form action="index.php?component=invoice&page_tpl=edit&invoice_id={$invoice_id}" method="post" name="new_invoice" id="new_invoice">
                <table width="700" cellpadding="4" cellspacing="0" border="0" >

                    <!-- Title -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Edit{/t} {t}Invoice ID{/t} {$invoice_details.invoice_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}INVOICE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}INVOICE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                            </a>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                                <!-- Invoice Details Block -->
                                <tr>
                                    <td class="menutd">                                    

                                        <!-- Invoice Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            
                                            <tr class="olotd4">
                                                <td class="row2"><b>{t}Invoice ID{/t}</b></td>
                                                <td class="row2"><b>{t}Work Order{/t}</b></td>
                                                <td class="row2"><b>{t}Employee{/t}</b></td> 
                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                <td class="row2"><b>{t}Due Date{/t}</b></td>                                                                                                                                 
                                                <td class="row2"><b>{t}Status{/t}</b></td>
                                                <td class="row2"><b>{t}Gross{/t}</b></td>                                                                                              
                                            </tr>
                                            <tr class="olotd4">

                                                <td>{$invoice_id}</td>
                                                <td>
                                                    {if {$invoice_details.workorder_id} > 0}
                                                        <a href="index.php?component=workorder&page_tpl=details&workorder_id={$invoice_details.workorder_id}">{$invoice_details.workorder_id}</a>
                                                    {else}
                                                        {t}n/a{/t}
                                                    {/if}
                                                </td>
                                                <td><a href="index.php?component=user&page_tpl=details&user_id={$invoice_details.employee_id}">{$employee_display_name}</a></td> 
                                                <td>
                                                    {if !$display_payments}
                                                        <input id="date" name="qform[date]" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                        <button type="button" id="date_button">+</button>
                                                        <script>                                                        
                                                            Calendar.setup( {
                                                                trigger     : "date_button",
                                                                inputField  : "date",
                                                                dateFormat  : "{$date_format}",
                                                                onChange    : function() { refreshPage(); }
                                                            } );                                                        
                                                        </script>
                                                    {else}
                                                        {$invoice_details.date|date_format:$date_format}
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if !$display_payments}
                                                        <input id="due_date" name="qform[due_date]" class="olotd4" size="10" value="{$invoice_details.due_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                        <button type="button" id="due_date_button">+</button>
                                                        <script>                                                        
                                                           Calendar.setup({
                                                               trigger     : "due_date_button",
                                                               inputField  : "due_date",
                                                               dateFormat  : "{$date_format}",
                                                               onChange    : function() { refreshPage(); }
                                                           });                                                         
                                                        </script>
                                                    {else}
                                                        {$invoice_details.due_date|date_format:$date_format}
                                                    {/if}
                                                </td>                                                
                                                <td>
                                                    {if $invoice_details.status == 'refunded'}<a href="index.php?component=refund&page_tpl=details&refund_id={$invoice_details.refund_id}">{/if}
                                                    {section name=s loop=$invoice_statuses}    
                                                        {if $invoice_details.status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}        
                                                    {/section}
                                                    {if $invoice_details.status == 'refunded'}</a>{/if}                                                    
                                            <td>{$currency_sym}<span id="invoiceTotalGrossTop">0.00</span></td>                                                                                            

                                            </tr>                                        
                                            <tr class="olotd4">

                                                <!-- Scope -->
                                                <td colspan="2"><b>{t}Work Order Scope{/t}:</b></td>
                                                <td colspan="5">{if $workorder_details.scope}{$workorder_details.scope}{else}{t}n/a{/t}{/if}</td>

                                            </tr>
                                            <tr>

                                                <!-- Client Details -->
                                                <td colspan="5" valign="top" align="left">
                                                    <b>{t}Bill{/t}</b>                                                        
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td valign="top">
                                                                <a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.display_name}</a><br>
                                                                {$client_details.address|nl2br}<br>
                                                                {$client_details.city}<br>
                                                                {$client_details.state}<br>
                                                                {$client_details.zip}<br>
                                                                {$client_details.country}<br>
                                                                {$client_details.primary_phone}<br>
                                                                {$client_details.email}                                                                        
                                                            </td>
                                                        </tr>
                                                    </table>                                                        
                                                </td>

                                                <!-- Company Details -->
                                                <td colspan="2" valign="top" >
                                                    <b>{t}Pay{/t}</b>
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top">                                                                    
                                                                {$company_details.company_name} <br>
                                                                {$company_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                                                {$company_details.city}<br>
                                                                {$company_details.state}<br>
                                                                {$company_details.zip}<br>
                                                                {$company_details.country}<br>
                                                                {$company_details.primary_phone}<br>
                                                                {$company_details.email}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>                                        
                                            <tr>

                                                <!-- Terms and Discount -->
                                                <td colspan="7" valign="top" align="left">                                                        
                                                    <b>{t}TERMS{/t}:</b> {$client_details.credit_terms}<br>
                                                    <b>{t}Client Discount Rate{/t}:</b>
                                                    {if !$display_payments}
                                                        <input type="text" class="olotd4" size="4" id="qform[unit_discount_rate]" name="qform[unit_discount_rate]" value="{$invoice_details.unit_discount_rate|string_format:"%.2f"}"> %<br>
                                                        <b>** {t}Change this if you want to temporarily override the discount rate for this invoice ONLY{/t} **</b>
                                                    {else}                                                        
                                                        {$invoice_details.unit_discount_rate|string_format:"%.2f"} % 
                                                    {/if}                                           
                                                </td>

                                            </tr>
                                        </table>                                                
                                    </td>
                                </tr>

                                <!-- Function Buttons -->                                 
                                <tr>
                                    <td>                                    
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="payments_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Function Buttons{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- Print Buttons -->  
                                                    {if $invoice_details.unit_gross > 0 }                                                             
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=invoice&themeVar=print');">{t}Print HTML{/t}</button>
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_pdf&print_content=invoice&themeVar=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                        <button type="button" class="userButton" onclick="confirm('Are you sure you want to email this invoice to the client?') && $.ajax( { url:'index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=email_pdf&print_content=invoice&themeVar=print', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=download_pdf&print_content=invoice&themeVar=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Download PDF{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=client_envelope&themeVar=print');">{t}Print Client Envelope{/t}</button>                                            
                                                        <br>
                                                        <br>
                                                    {/if}

                                                    <!-- Add Voucher Button -->
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}  
                                                        <button type="button" class="userButton" onclick="location.href='index.php?component=voucher&page_tpl=new&invoice_id={$invoice_details.invoice_id}';">{t}Add Voucher{/t}</button>
                                                    {/if}

                                                    <!-- Receive Payment Button -->
                                                    {if $invoice_details.status == 'unpaid' || $invoice_details.status == 'partially_paid'}                                                            
                                                        <button type="button" class="userButton" onclick="location.href='index.php?component=payment&page_tpl=new&type=invoice&invoice_id={$invoice_details.invoice_id}';">{t}Receive Payment{/t}</button>
                                                    {/if}

                                                </td>
                                            </tr>
                                        </table>                                                
                                    </td>
                                </tr>
                                

                                <!-- Payments -->                                
                                {if $display_payments}
                                    <tr>
                                        <td>                                                
                                            {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=_gettext("Payments")}
                                        </td>
                                    </tr>
                                {/if}

                                <!-- Labour Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Labour{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">                                                    
                                                    <table id="labour_items" width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" align="left" style="width: 200px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Qty{/t}</b></td>                                                            
                                                            <td class="row2" align="left" style="width: 75px;"><b>{if $invoice_details.tax_system != 'no_tax'}{t}Unit Net{/t}{else}Unit Gross{/if}</b></td>                                                                
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="left" hidden><b>{t}Net{/t}</b></td>
                                                            <td class="vatTaxSystem row2" align="right" hidden><b>{t}VAT Tax Code{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                            <td class="salesTaxSystem row2"  align="right" hidden><b>{t}Sales Tax{/t} {t}Exempt{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Gross{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Actions{/t}</b></td>                                                                
                                                        </tr>

                                                        <!-- Labour Items Dummy Row -->                                                            
                                                        <tr id="dummy_labour_items_row_iteration" class="dummy_item_row olotd4" style="display: none;">
                                                            <td align="left">
                                                                <select id="qform[labour_items][iteration][description]" name="qform[labour_items][iteration][description]" value="" style="width: 100%" disabled>
                                                                    {section loop=$labour_prefill_items name=i}
                                                                        <option value="{$labour_prefill_items[i].description}" data-unit-net="{$labour_prefill_items[i].unit_net|string_format:"%.2f"}">{$labour_prefill_items[i].description}</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>
                                                            <td align="left"><input id="qform[labour_items][iteration][unit_qty]" name="qform[labour_items][iteration][unit_qty]" style="width: 50px;" size="6" value="" type="text" maxlength="6" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="left"><input id="qform[labour_items][iteration][unit_net]" name="qform[labour_items][iteration][unit_net]" style="width: 100%;" size="6" value="1.00" type="text" maxlength="6" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="left" hidden><input id="qform[labour_items][iteration][subtotal_net]" name="qform[labour_items][iteration][subtotal_net]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="right" hidden>
                                                                <select id="qform[labour_items][iteration][vat_tax_code]" name="qform[labour_items][iteration][vat_tax_code]" value="TNA" style="width: 100%; font-size: 10px;" required disabled>                                                                            
                                                                    <option value="TNA" data-tax-rate="0.00" hidden>TNA - Not Applicable @ 0.00%</option>
                                                                    {section loop=$vat_tax_codes name=i}
                                                                        <option value="{$vat_tax_codes[i].tax_key}" data-tax-rate="{$vat_tax_codes[i].rate|string_format:"%.2f"}">{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>                                                                               
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[labour_items][iteration][unit_tax_rate]" name="qform[labour_items][iteration][unit_tax_rate]" style="width: 50px;" size="6" value="{if $invoice_details.tax_system == 'sales_tax_cash'}{$invoice_details.sales_tax_rate|string_format:"%.2f"}{else}0.00{/if}" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);">%</td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[labour_items][iteration][subtotal_tax]" name="qform[labour_items][iteration][subtotal_tax]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>                                                                                                                                      
                                                            <td class="salesTaxSystem" align="right" hidden><input id="qform[labour_items][iteration][sales_tax_exempt]" name="qform[labour_items][iteration][sales_tax_exempt]" type="checkbox" disabled></td>
                                                            <td align="right"><input id="qform[labour_items][iteration][subtotal_gross]" name="qform[labour_items][iteration][subtotal_gross]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td align="right">
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Labour Record</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- Labour Table Record Rows are added here -->
                                                        
                                                    </table>                                                    
                                                    {if !$display_payments}
                                                        <p>                                                                
                                                            <button type="button" onclick="createNewTableRow('labour');">{t}Add{/t}</button>                                                                
                                                        </p>
                                                    {/if}
                                                </td>
                                            </tr>                                        
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Labour Sub Totals -->
                                <tr>
                                    <td>
                                        <table class="olotable" style="margin-top: 10px;" width="400" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                            <tr style="background-color: #c3d9ea;">
                                                <td style="text-align:right;"><b>{t}Labour{/t} {t}Totals{/t}</b></td>
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{t}Net{/t}: {$currency_sym}<span id="labour_items_subtotal_net">0.00</span></td>                                                                            
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}<span id="labour_items_subtotal_tax">0.00</sapn></td>
                                                <td width="80" align="left">{t}Gross{/t}: {$currency_sym}<span id="labour_items_subtotal_gross">0.00</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>                                    

                                <!-- Parts Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Parts{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">                                                    
                                                    <table id="parts_items" width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" align="left" style="width: 200px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Qty{/t}</b></td>                                                            
                                                            <td class="row2" align="left" style="width: 75px;"><b>{if $invoice_details.tax_system != 'no_tax'}{t}Unit Net{/t}{else}Unit Gross{/if}</b></td>                                                                
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="left" hidden><b>{t}Net{/t}</b></td>
                                                            <td class="vatTaxSystem row2" align="right" hidden><b>{t}VAT Tax Code{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                            <td class="salesTaxSystem row2"  align="right" hidden><b>{t}Sales Tax{/t} {t}Exempt{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Gross{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Actions{/t}</b></td>                                                                
                                                        </tr>

                                                        <!-- Parts Items Dummy Row -->                                                            
                                                        <tr id="dummy_parts_items_row_iteration" class="dummy_item_row olotd4" style="display: none;">
                                                            <td align="left">
                                                                <select id="qform[parts_items][iteration][description]" name="qform[parts_items][iteration][description]" value="" style="width: 100%" disabled>
                                                                    {section loop=$parts_prefill_items name=i}
                                                                        <option value="{$parts_prefill_items[i].description}" data-unit-net="{$parts_prefill_items[i].unit_net|string_format:"%.2f"}">{$parts_prefill_items[i].description}</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>
                                                            <td align="left"><input id="qform[parts_items][iteration][unit_qty]" name="qform[parts_items][iteration][unit_qty]" style="width: 50px;" size="6" value="" type="text" maxlength="6" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="left"><input id="qform[parts_items][iteration][unit_net]" name="qform[parts_items][iteration][unit_net]" style="width: 100%;" size="6" value="1.00" type="text" maxlength="6" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="left" hidden><input id="qform[parts_items][iteration][subtotal_net]" name="qform[parts_items][iteration][subtotal_net]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="right" hidden>
                                                                <select id="qform[parts_items][iteration][vat_tax_code]" name="qform[parts_items][iteration][vat_tax_code]" value="TNA" style="width: 100%; font-size: 10px;" required disabled>                                                                            
                                                                    <option value="TNA" data-tax-rate="0.00" hidden>TNA - Not Applicable @ 0.00%</option>
                                                                    {section loop=$vat_tax_codes name=i}
                                                                        <option value="{$vat_tax_codes[i].tax_key}" data-tax-rate="{$vat_tax_codes[i].rate|string_format:"%.2f"}">{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>                                                                               
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[parts_items][iteration][unit_tax_rate]" name="qform[parts_items][iteration][unit_tax_rate]" style="width: 50px;" size="6" value="{if $invoice_details.tax_system == 'sales_tax_cash'}{$invoice_details.sales_tax_rate|string_format:"%.2f"}{else}0.00{/if}" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);">%</td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[parts_items][iteration][subtotal_tax]" name="qform[parts_items][iteration][subtotal_tax]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>                                                                                                                                      
                                                            <td class="salesTaxSystem" align="right" hidden><input id="qform[parts_items][iteration][sales_tax_exempt]" name="qform[parts_items][iteration][sales_tax_exempt]" type="checkbox" disabled></td>
                                                            <td align="right"><input id="qform[parts_items][iteration][subtotal_gross]" name="qform[parts_items][iteration][subtotal_gross]" size="6" value="0.00" type="text" maxlength="6" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td align="right">
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Parts Record</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- Parts Table Record Rows are added here -->
                                                        
                                                    </table>                                                    
                                                    {if !$display_payments}
                                                        <p>                                                                
                                                            <button type="button" onclick="createNewTableRow('parts');">{t}Add{/t}</button>                                                                
                                                        </p>
                                                    {/if}
                                                </td>
                                            </tr>                                        
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Parts Sub Totals -->
                                <tr>
                                    <td>
                                        <table class="olotable" style="margin-top: 10px;" width="400" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                            <tr style="background-color: #c3d9ea;">
                                                <td style="text-align:right;"><b>{t}Parts{/t} {t}Totals{/t}</b></td>
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{t}Net{/t}: {$currency_sym}<span id="parts_items_subtotal_net">0.00</span></td>                                                                            
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}<span id="parts_items_subtotal_tax">0.00</sapn></td>
                                                <td width="80" align="left">{t}Gross{/t}: {$currency_sym}<span id="parts_items_subtotal_gross">0.00</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Vouchers -->
                                {*if $display_vouchers*}
                                    <tr>
                                        <td>                                                
                                            {include file='voucher/blocks/display_vouchers_block.tpl' display_vouchers=$display_vouchers block_title=_gettext("Vouchers")}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="olotable" style="margin-top: 10px;" width="400" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                                <tr style="background-color: #c3d9ea;">
                                                    <td style="text-align:right;"><b>{t}Voucher{/t} {t}Totals{/t}</b></td>
                                                    <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{t}Net{/t}: {$currency_sym}<span id="vouchersTotalNet">{$voucher_items_subtotals.subtotal_net|string_format:"%.2f"}</span></td>                                                    
                                                    <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}<span id="vouchersTotalTax">{$voucher_items_subtotals.subtotal_tax|string_format:"%.2f"}</span></td>
                                                    <td width="80" align="left">{t}Gross{/t}: {$currency_sym}<span id="vouchersTotalGross">{$voucher_items_subtotals.subtotal_gross|string_format:"%.2f"}</span></td>
                                                </tr>
                                            </table>  
                                        </td>
                                    </tr>
                                {*/if*}

                                <!-- Totals Section -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Invoice Total{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                        <tr>
                                                            <td class="menutd2">
                                                                <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Labour{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalLabourItemsSubTotalNet">0.00</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Parts{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalPartsItemsSubTotalNet">0.00</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ <span id="invoiceTotalDiscountRate">0.00</span>%)</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalDiscount">0.00</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Vouchers{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalVouchersTotalNet">0.00</span></td>
                                                                    </tr>                                                                   
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalNet">0.00</span></td>
                                                                    </tr>                                                                    
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>                                                           
                                                                        <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalTax">0.00</span></td>                                                            
                                                                    </tr>                                                                                                                                       
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}<span id="invoiceTotalGross">0.00</span></td>
                                                                    </tr> 
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Button and Hidden Section -->
                                <tr>
                                    <td>
                                        <table width="100%"  cellpadding="3" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" valign="top" width="25%">                                                    
                                                    {if !$display_payments}
                                                        <input type="hidden" name="qform[invoice_id]" value="{$invoice_details.invoice_id}">
                                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                        <button type="button" class="olotd4" onclick="window.location.href='index.php?component=invoice&page_tpl=search';">{t}Cancel{/t}</button>
                                                    {/if}
                                                </td>
                                                <td align="right" width="75%"></td>
                                            </tr>
                                        </table>                                                
                                    </td>
                                </tr> 
                                
                            </table>                      
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>