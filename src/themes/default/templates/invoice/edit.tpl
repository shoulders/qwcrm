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
     
    // Invoice items JSON (items from the database)
    var invoiceItems = {$invoice_items_json};
    
    // Run these functions when the DOM is ready
    $(document).ready(function() {
        
        // Prepare the Data
        modifyDummyRowsForTaxSystem();
        processInvoiceItemsFromDatabase(invoiceItems);   
                    
        // Page Building has now completed
        pageBuilding = false;
        
        // Intialialise the correct values on page
        refreshTotals();
        
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

    // Create and populate item rows with sorted data from the database
    function processInvoiceItemsFromDatabase(invoiceItems) {
    
        // Form Fields that are submitted
        fieldNames = [
            //"invoice_" + "_id",
            //"invoice_id",
            //"tax_system",            
            "description",
            "unit_qty",            
            "unit_net",
            "unit_discount",
            "sales_tax_exempt",
            "vat_tax_code",
            "unit_tax_rate",
            "unit_tax",
            "unit_gross",
            "subtotal_net",
            "subtotal_tax",
            "subtotal_gross"];

        // Loop through invoice items from the database
        $.each(invoiceItems, function(itemIndex, invoiceItem) {

            // Create a new row to be populated and get the row identifier
            iteration = createNewTableRow();
            
            // Loop through the various fields and populate with their data
            $.each(fieldNames, function(fieldIndex, fieldName) {
                
                // If it is a checkbox
                if(fieldName == "sales_tax_exempt") {
                    if(invoiceItem[fieldName] === '1') {
                        $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').prop('checked', true);
                    }
                
                // If it is a Combobox
                } else if(fieldName === "description") {
                    
                    // Build the Combobox identifier
                    let comboboxInputName = fieldName.replace("_", "")+'Combobox';
                    
                    // Update Combobox text value using it's API to prevent a change trigger                    
                    // If you change a combobox <option> with Javascript after the comobobox is initiated the the "onChange" and "onSelectionChange" are fired on the first mouse click on <body> (see _doOnBodyMouseDown())
                    window[iteration+comboboxInputName].setComboText(invoiceItem[fieldName]);
                                    
                // Standard Input Value
                } else {
                    // Update field value
                    $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').val(invoiceItem[fieldName]);
                    //$('#qform\\[invoice_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').attr('value', invoiceItem[fieldName]);
                    //document.getElementById('qform[invoice_items]['+iteration+']['+fieldName+']').value = invoiceItem[fieldName]; (this does not work for some reason)                
                }
            });

        });        

    }
    
    // Dynamically Copy, Process and add an new invoice item row to the relevant table
    function createNewTableRow() {
        
        // Get Table
        var tbl = document.getElementById('invoice_items');
        
        // Get Next Row Number
        var iteration = tbl.rows.length - 1; 
        
        // Clone Dummy Row        
        var clonedRow = $('#dummy_invoice_items_row_iteration').clone();
                
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
        window[iteration+'descriptionCombobox'] = dhtmlXComboFromSelect('qform[invoice_items]['+iteration+'][description]');
        
        // Set Combobox Options - https://docs.dhtmlx.com/api__refs__dhtmlxcombo.html    
        window[iteration+'descriptionCombobox'].DOMelem_input.id = 'qform[invoice_items]['+iteration+'][description_combobox]';        
        //window[iteration+'descriptionCombobox'].setSize(400);    
        window[iteration+'descriptionCombobox'].DOMelem_input.maxLength = 100;    
        window[iteration+'descriptionCombobox'].DOMelem_input.required = true;
        window[iteration+'descriptionCombobox'].setComboText('');
        window[iteration+'descriptionCombobox'].setFontSize("10px","10px");        
        dhtmlxEvent(window[iteration+'descriptionCombobox'].DOMelem_input, "keypress", function(e) {
            // This uses Suite API - https://docs.dhtmlx.com/event__index.html
            keyPressed = true;    
            if(onlyAlphaNumericPunctuation(e)) { return true; }
            e.cancelBubble = true;
            if (e.preventDefault) e.preventDefault();
            return false;
        } );
        //window[iteration+'descriptionCombobox'].attachEvent("keypress", function(e) { /* Does not Work with keypress */ } ); 
        //window[iteration+'descriptionCombobox'].attachEvent("onSelectionChange", function() { /* this does not really do what I want */ } );
        window[iteration+'descriptionCombobox'].attachEvent("onChange", function(value, text) {
            
            // Set Unit Net default with the prefill's value (pulled from the Dummy row because dhtmlxcombo does not support data-values)
            if(keyPressed != true) {
                let matchingOption = $('#qform\\[invoice_items\\]\\[iteration\\]\\[description\\]').find('option[value="'+window[iteration+'descriptionCombobox'].getComboText()+'"]');
                let unitNet = matchingOption.data('unit-net');
                if(unitNet != null) { $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\[unit_net\\]').val(parseFloat(unitNet).toFixed(2)); }
            }
                    
            // Reset the keyPessed Boolean as it has now been called and used
            keyPressed = false;
        
            refreshPage();
            
        } );        
        
        // Set Vat Tax Code default value
        $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').val('{$default_vat_tax_code}');
        
        // Update the intial Tax Rate to match the intial VAT Tax Code (Only if the Tax system is VAT based)        
        if(invoiceTaxSystem.startsWith("vat_")) {
            let selected = $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $('#qform\\[invoice_items\\]\\['+iteration+'\\]\\[unit_tax_rate\\]').val(parseFloat(newTaxRate).toFixed(2));
        }
        
        /* Event Binding - Refresh all rows when triggered */
           
        // Monitor for change in VAT Tax Code/Rate selectbox and update tax rate accordingly   
        $(".invoice_item_row select[id$='\\[vat_tax_code\\]']").off("change").on("change", function() {
            let selected = $(this).find('option:selected');
            let newTaxRate = selected.data('tax-rate'); 
            $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(newTaxRate).toFixed(2));            
            refreshPage();            
        });
        
        // Monitor Sales Tax Exempt Checkboxes for click
        $(".invoice_item_row input[id$='\\[sales_tax_exempt\\]']").click(function () {

            // Toggle the value between 0.00 and configured Sales Tax Rate
            if ($(this).is(":checked")) {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val('0.00');                    
            } else {                
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(invoiceSalesTaxRate).toFixed(2));
            }
            refreshPage();            
        });
                
        /* Monitor all row input boxes for changes
        $(".invoice_item_row input[type='text']").off("change").on("change", function() {
            refreshPage();            
        });*/
        
        // Monitor all row input boxes for keyup
        $(".invoice_item_row input[type='text']").off("keyup").on("keyup", function() {
            refreshPage();            
        });
        
        // Item Delete button action
        $(".invoice_item_row .confirmDelete").off("click").on("click", function() {
            hideddrivetip();
            //if(!confirm('Are you Sure you want to delete this item?')) { return; }
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
    function refreshPage(applyDiscountRate = false) {
                    
        // Disable all buttons on page refresh unless on initial page build, if there is a change
        if(pageBuilding === false) {            
            $(".userButton").prop('disabled', true).attr('title', '{t}This button is disabled until you have saved your changes.{/t}');
        }
        
        // Only allow 'Refresh Invoice Totals' after the page has completely loaded
        if(pageBuilding === false) {            
            refreshTotals(applyDiscountRate);
        }
     
    }
    
    // Apply a discount rate to all items via the 'Apply Discount' button
    function applyDiscountRate() {
    
        let clientDiscountRate = +$("#client_discount_rate").val();
        
        if (clientDiscountRate < 0 || clientDiscountRate > 99.99) {
            alert("{t}The discount rate must be within the range of 0 and 99.99{/t}");
        } else {
            refreshPage(true);
        }
    }

    // Recalculate and then refresh all onscreen invoice totals
    function refreshTotals(applyDiscountRate = false) {
        
        // Get the client discount rate
        var clientDiscountRate = +$("#client_discount_rate").val();        
        
        /* Invoice Item Rows */
        
        // Variable stores for Items Sums
        invoiceItemsSubTotalDiscount     = 0.00;
        invoiceItemsSubTotalNet          = 0.00;
        invoiceItemsSubTotalTax          = 0.00;
        invoiceItemsSubTotalGross        = 0.00;
        
        // Loop through item rows, calculate and refresh new values onscreen (Tax System Aware)
        $('.invoice_item_row').each(function() {
            
            // Unit Values (not used onscreen)
            rowUnitQty                  = +$(this).find("input[id$='\\[unit_qty\\]']").val();
            rowUnitNet                  = +$(this).find("input[id$='\\[unit_net\\]']").val();            
            rowUnitDiscount             = applyDiscountRate ? rowUnitNet * (clientDiscountRate / 100) : +$(this).find("input[id$='\\[unit_discount\\]']").val();
            rowUnitTaxRate              = +$(this).find("input[id$='\\[unit_tax_rate\\]']").val();
                 
            // Row Totals
            rowSubTotalNet              = (rowUnitNet - rowUnitDiscount) * rowUnitQty;
            rowSubTotalTax              = rowSubTotalNet * (rowUnitTaxRate / 100);
            rowSubTotalGross            = rowSubTotalNet + rowSubTotalTax;
            
            // Update Row Totals onscreen
            if(applyDiscountRate) { $(this).find("input[id$='\\[unit_discount\\]']").val(parseFloat(rowUnitDiscount).toFixed(2)); }            
            $(this).find("input[id$='\\[subtotal_net\\]']").val(parseFloat(rowSubTotalNet).toFixed(2));
            $(this).find("input[id$='\\[subtotal_tax\\]']").val(parseFloat(rowSubTotalTax).toFixed(2));
            $(this).find("input[id$='\\[subtotal_gross\\]']").val(parseFloat(rowSubTotalGross).toFixed(2));
            
            // Update Invoice Items SubTotals            
            invoiceItemsSubTotalDiscount     += rowUnitDiscount * rowUnitQty;
            invoiceItemsSubTotalNet          += rowSubTotalNet;
            invoiceItemsSubTotalTax          += rowSubTotalTax;
            invoiceItemsSubTotalGross        += rowSubTotalGross;            

        });
        
        /* Invoice Items SubTotals */
        
        // Update Items SubTotals onscreen
        $("#invoice_items_subtotal_discount").text(parseFloat(invoiceItemsSubTotalDiscount).toFixed(2));
        $("#invoice_items_subtotal_net").text(parseFloat(invoiceItemsSubTotalNet).toFixed(2));
        $("#invoice_items_subtotal_tax").text(parseFloat(invoiceItemsSubTotalTax).toFixed(2));
        $("#invoice_items_subtotal_gross").text(parseFloat(invoiceItemsSubTotalGross).toFixed(2));
        
        /* Voucher SubTotals */
                
        // These will return 0 if not present        
        var vouchersTotalNet    = +$("#vouchersTotalNet").text(); 
        var vouchersTotalTax    = +$("#vouchersTotalTax").text();
        //var vouchersTotalGross  = +$("#vouchersTotalGross").text();
               
        /* Invoice Totals */

        // Calculations
        var invoiceTotalDiscount    = invoiceItemsSubTotalDiscount;   
        var invoiceTotalNet         = invoiceItemsSubTotalNet + vouchersTotalNet;   // Vouchers are not discounted on purpose
        var invoiceTotalTax         = invoiceItemsSubTotalTax + vouchersTotalTax;
        var invoiceTotalGross       = invoiceTotalNet + invoiceTotalTax;
        
        // Update values onscreen + Convert Value to 0.00 format                
        $("#invoiceTotalDiscountText").text(parseFloat(invoiceTotalDiscount).toFixed(2));
        $("#invoiceTotalDiscount").val(parseFloat(invoiceTotalDiscount).toFixed(2));              
        $("#invoiceTotalNetText").text(parseFloat(invoiceTotalNet).toFixed(2));
        $("#invoiceTotalNet").val(parseFloat(invoiceTotalNet).toFixed(2));
        $("#invoiceTotalTaxText").text(parseFloat(invoiceTotalTax).toFixed(2));
        $("#invoiceTotalTax").val(parseFloat(invoiceTotalTax).toFixed(2));
        $("#invoiceTotalGrossText").text(parseFloat(invoiceTotalGross).toFixed(2));
        $("#invoiceTotalGross").val(parseFloat(invoiceTotalGross).toFixed(2));
        $("#invoiceTotalGrossTop").text(parseFloat(invoiceTotalGross).toFixed(2));

    }
    
</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form action="index.php?component=invoice&page_tpl=edit&invoice_id={$invoice_id}" method="post" name="new_invoice" id="new_invoice">
                <table width="1024" cellpadding="4" cellspacing="0" border="0" >

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
                                                    {if $invoice_details.workorder_id}
                                                        <a href="index.php?component=workorder&page_tpl=details&workorder_id={$invoice_details.workorder_id}">{$invoice_details.workorder_id}</a>
                                                    {else}
                                                        {t}n/a{/t}
                                                    {/if}
                                                </td>
                                                <td><a href="index.php?component=user&page_tpl=details&user_id={$invoice_details.employee_id}">{$employee_display_name}</a></td> 
                                                <td>
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
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
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
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
                                                <td colspan="5">{if $invoice_details.workorder_id}{$workorder_details.scope}{else}{t}n/a{/t}{/if}</td>

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
                                                    <p><b>{t}Credit Terms{/t}: </b>{if $client_details.credit_terms}{$client_details.credit_terms}{else}{t}n/a{/t}{/if}</p>
                                                    <b>{t}Discount{/t}:</b><br>
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
                                                        <input type="number" class="olotd4" size="6" id="client_discount_rate" value="{$client_details.discount_rate|string_format:"%.2f"}"> %<br>
                                                        <button type="button" onclick="applyDiscountRate();">{t}Apply Discount{/t}</button>
                                                        <br>
                                                        ** {t}The default value shown is the client's standard discount rate, but can be changed for this invoice.{/t} **<br>
                                                        ** {t}This will alter all items.{/t} **
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
                                                    {if $invoice_details.unit_gross > 0}                                                             
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=htmlBrowser');">{t}Print HTML{/t}</button>
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfBrowser');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfDownload');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Download PDF{/t}</button>
                                                        <button type="button" class="userButton" onclick="confirm('Are you sure you want to email this invoice to the client?') && $.ajax( { url:'index.php?component=invoice&page_tpl=email&invoice_id={$invoice_details.invoice_id}&commContent=invoice&commType=pdfEmail', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&commContent=client_envelope&commType=htmlBrowser');">{t}Print Client Envelope{/t}</button>                                            
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
                                                    
                                                    <!-- Credit Note Button -->
                                                    {*if $invoice_details.balance > 0}                                                        
                                                        <button type="button" class="userButton" onclick="window.open('index.php?component=creditnote&page_tpl=new&invoice_id={$invoice_details.invoice_id}', '_self');">{t}Add a Sales Credit Note{/t}</button>
                                                    {/if*}

                                                </td>
                                            </tr>
                                        </table>                                                
                                    </td>
                                </tr>

                                <!-- Invoice Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">                                                    
                                                    <table id="invoice_items" width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" align="left" style="width: 200px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Qty{/t}</b></td>
                                                            <td class="row2" align="left" style="width: 75px;"><b>{if $invoice_details.tax_system != 'no_tax'}{t}Unit Net{/t}{else}Unit Gross{/if} ({$currency_sym})</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Discount{/t} ({$currency_sym})</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="left" hidden><b>{t}Net{/t} ({$currency_sym})</b></td>                                                            
                                                            <td class="vatTaxSystem row2" align="right" hidden><b>{t}VAT Tax Code{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t} (%)</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} ({$currency_sym})</b></td>
                                                            <td class="salesTaxSystem row2"  align="right" hidden><b>{t}Sales Tax{/t} {t}Exempt{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Gross{/t} ({$currency_sym})</b></td>
                                                            <td class="row2" align="right"><b>{t}Actions{/t}</b></td>                                                                
                                                        </tr>

                                                        <!-- Invoice Items Dummy Row -->                                                            
                                                        <tr id="dummy_invoice_items_row_iteration" class="dummy_invoice_item_row olotd4" style="display: none;">
                                                            <td align="left">
                                                                <select id="qform[invoice_items][iteration][description]" name="qform[invoice_items][iteration][description]" value="" style="width: 100%" disabled>
                                                                    {section loop=$invoice_prefill_items name=i}
                                                                        <option value="{$invoice_prefill_items[i].description}" data-unit-net="{$invoice_prefill_items[i].unit_net|string_format:"%.2f"}">{$invoice_prefill_items[i].description}</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>
                                                            <td align="left"><input id="qform[invoice_items][iteration][unit_qty]" name="qform[invoice_items][iteration][unit_qty]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="left"><input id="qform[invoice_items][iteration][unit_net]" name="qform[invoice_items][iteration][unit_net]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td align="left"><input id="qform[invoice_items][iteration][unit_discount]" name="qform[invoice_items][iteration][unit_discount]" style="width: 50px;" size="6" value="0.00" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="left" hidden><input id="qform[invoice_items][iteration][subtotal_net]" name="qform[invoice_items][iteration][subtotal_net]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="right" hidden>
                                                                <select id="qform[invoice_items][iteration][vat_tax_code]" name="qform[invoice_items][iteration][vat_tax_code]" value="" style="width: 100%; font-size: 10px;" required disabled>                                                                            
                                                                    {section loop=$vat_tax_codes name=i}
                                                                        <option value="{$vat_tax_codes[i].tax_key}" data-tax-rate="{$vat_tax_codes[i].rate|string_format:"%.2f"}">{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                    {/section}                                                                            
                                                                </select>
                                                            </td>                                                                               
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden>
                                                                <input id="qform[invoice_items][iteration][unit_tax_rate]" name="qform[invoice_items][iteration][unit_tax_rate]" style="width: 50px;" size="6" value="{if $invoice_details.tax_system == 'sales_tax_cash'}{$invoice_details.sales_tax_rate|string_format:"%.2f"}{else}0.00{/if}" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[invoice_items][iteration][subtotal_tax]" name="qform[invoice_items][iteration][subtotal_tax]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>                                                                                                                                      
                                                            <td class="salesTaxSystem" align="right" hidden><input id="qform[invoice_items][iteration][sales_tax_exempt]" name="qform[invoice_items][iteration][sales_tax_exempt]" type="checkbox" disabled></td>
                                                            <td align="right">
                                                                <input id="qform[invoice_items][iteration][subtotal_gross]" name="qform[invoice_items][iteration][subtotal_gross]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <!-- Hidden but needed -->
                                                                <input id="qform[invoice_items][iteration][unit_tax]" name="qform[invoice_items][iteration][unit_tax]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <input id="qform[invoice_items][iteration][unit_gross]" name="qform[invoice_items][iteration][unit_gross]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                            </td>
                                                            <td align="right">
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Item</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>
                                                        
                                                        <!-- Invoice Items Table Record Rows are added here -->
                                                        
                                                    </table>                                                    
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
                                                        <p>                                                                
                                                            <button type="button" onclick="createNewTableRow();">{t}Add{/t}</button>                                                                
                                                        </p>
                                                    {/if}
                                                </td>
                                            </tr>                                        
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Invoice Items Sub Totals -->
                                <tr>
                                    <td>
                                        <table class="olotable" style="margin-top: 10px;" width="500" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                            <tr style="background-color: #c3d9ea;">
                                                <td style="text-align:right;"><b>{t}Invoice Items{/t} {t}Totals{/t} </b></td>
                                                <td width="140" align="left">{t}Discount{/t}: {$currency_sym}<span id="invoice_items_subtotal_discount">0.00</span></td>
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{t}Net{/t}: {$currency_sym}<span id="invoice_items_subtotal_net">0.00</span></td>                                                
                                                <td width="80" align="left" class="vatTaxSystem salesTaxSystem" hidden>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}<span id="invoice_items_subtotal_tax">0.00</sapn></td>
                                                <td width="80" align="left">{t}Gross{/t}: {$currency_sym}<span id="invoice_items_subtotal_gross">0.00</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>                                    
                                
                                <!-- Vouchers -->
                                {*if $display_vouchers.total_results*}
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
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="invoiceTotalDiscountText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="invoiceTotalDiscount" name="qform[unit_discount]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="invoiceTotalNetText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="invoiceTotalNet" name="qform[unit_net]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>                                                                    
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>                                                           
                                                                        <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="invoiceTotalTaxText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="invoiceTotalTax" name="qform[unit_tax]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>                                                                                                                                       
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="invoiceTotalGrossText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="invoiceTotalGross" name="qform[unit_gross]" value="0.00" readonly hidden>
                                                                        </td>
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
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}
                                                        <input type="hidden" name="qform[invoice_id]" value="{$invoice_details.invoice_id}">
                                                        <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                        <button type="button" class="olotd4" onclick="window.location.href='index.php?component=invoice&page_tpl=details&invoice_id={$invoice_details.invoice_id}';">{t}Cancel{/t}</button>
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