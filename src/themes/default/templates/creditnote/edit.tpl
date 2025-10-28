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
    var creditnoteSalesTaxRate = {$creditnote_details.sales_tax_rate|string_format:"%.2f"};

    // Credit Note Tax System
    var creditnoteTaxSystem = '{$creditnote_details.tax_system}';

    // Credit note items JSON (items from the database)
    var creditnoteItems = {$creditnote_items_json};

    // Run these functions when the DOM is ready
    $(document).ready(function() {

        // Prepare the Data
        modifyDummyRowsForTaxSystem();
        processCreditnoteItemsFromDatabase(creditnoteItems);

        // Page Building has now completed
        pageBuilding = false;

        // Intialialise the correct values on page
        refreshTotals();

    });

    // Change the Dummy records so the visible fields match the Tax System
    function modifyDummyRowsForTaxSystem() {

        // If the Tax system is No Tax
        if(creditnoteTaxSystem.startsWith("no_tax")) {
        }

        // If the Tax system is VAT based
        if(creditnoteTaxSystem.startsWith("vat_")) {
            $(".vatTaxSystem").show();
        }

        // If the Tax system Sales Tax based
        if(creditnoteTaxSystem.startsWith("sales_tax_cash")) {
            $(".salesTaxSystem").show();
        }

    }

    // Create and populate item rows with sorted data from the database
    function processCreditnoteItemsFromDatabase(creditnoteItems) {

        // Form Fields that are submitted
        fieldNames = [
            //"creditnote_" + "_id",
            //"creditnote_id",
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

        // Loop through credit note items from the database
        $.each(creditnoteItems, function(itemIndex, creditnoteItem) {

            // Create a new row to be populated and get the row identifier
            iteration = createNewTableRow();

            // Loop through the various fields and populate with their data
            $.each(fieldNames, function(fieldIndex, fieldName) {

                // If it is sales_tax_exempt and should be checked, do it
                if(fieldName == "sales_tax_exempt") {
                    if(creditnoteItem[fieldName] === '1') {
                        $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').prop('checked', true);
                    }

                // If it is a Combobox
                } else if(fieldName === "description") {

                    // Build the Combobox identifier
                    let comboboxInputName = fieldName.replace("_", "")+'Combobox';

                    // Update Combobox text value using it's API to prevent a change trigger
                    // If you change a combobox <option> with Javascript after the comobobox is initiated the the "onChange" and "onSelectionChange" are fired on the first mouse click on <body> (see _doOnBodyMouseDown())
                    window[iteration+comboboxInputName].setComboText(creditnoteItem[fieldName]);

                // Standard Input Value
                } else {
                    // Update field value
                    $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').val(creditnoteItem[fieldName]);
                    //$('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').attr('value', creditnoteItem[fieldName]);
                    //document.getElementById('qform[creditnote_items]['+iteration+']['+fieldName+']').value = creditnoteItem[fieldName]; (this does not work for some reason)
                }
            });

        });

    }

    // Dynamically Copy, Process and add an new credit note item row to the relevant table
    function createNewTableRow() {

        // Get Table
        var tbl = document.getElementById('creditnote_items');

        // Get Next Row Number
        var iteration = tbl.rows.length - 1;

        // Clone Dummy Row
        var clonedRow = $('#dummy_creditnote_items_row_iteration').clone();

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
        window[iteration+'descriptionCombobox'] = dhtmlXComboFromSelect('qform[creditnote_items]['+iteration+'][description]');

        // Set Combobox Options - https://docs.dhtmlx.com/api__refs__dhtmlxcombo.html
        window[iteration+'descriptionCombobox'].DOMelem_input.id = 'qform[creditnote_items]['+iteration+'][description_combobox]';
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
                let matchingOption = $('#qform\\[creditnote_items\\]\\[iteration\\]\\[description\\]').find('option[value="'+window[iteration+'descriptionCombobox'].getComboText()+'"]');
                let unitNet = matchingOption.data('unit-net');
                if(unitNet != null) { $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\[unit_net\\]').val(parseFloat(unitNet).toFixed(2)); }
            }

            // Reset the keyPessed Boolean as it has now been called and used
            keyPressed = false;

            refreshPage();

        } );

        // Set Vat Tax Code default value
        $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').val('{$default_vat_tax_code}');

        // Update the intial Tax Rate to match the intial VAT Tax Code (Only if the Tax system is VAT based)
        if(creditnoteTaxSystem.startsWith("vat_")) {
            let selected = $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $('#qform\\[creditnote_items\\]\\['+iteration+'\\]\\[unit_tax_rate\\]').val(parseFloat(newTaxRate).toFixed(2));
        }

        /* Event Binding - Refresh all rows when triggered */

        // Monitor for change in VAT Tax Code/Rate selectbox and update tax rate accordingly
        $(".creditnote_item_row select[id$='\\[vat_tax_code\\]']").off("change").on("change", function() {
            let selected = $(this).find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(newTaxRate).toFixed(2));
            refreshPage();
        });

        // Monitor Sales Tax Exempt Checkboxes for click
        $(".creditnote_item_row input[id$='\\[sales_tax_exempt\\]']").click(function () {

            // Toggle the value between 0.00 and configured Sales Tax Rate
            if ($(this).is(":checked")) {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val('0.00');
            } else {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(creditnoteSalesTaxRate).toFixed(2));
            }
            refreshPage();
        });

        /* Monitor all row input boxes for changes
        $(".creditnote_item_row input[type='text']").off("change").on("change", function() {
            refreshPage();
        });*/

        // Monitor all row input boxes for keyup
        $(".creditnote_item_row input[type='text']").off("keyup").on("keyup", function() {
            refreshPage();
        });

        // Item Delete button action
        $(".creditnote_item_row .confirmDelete").off("click").on("click", function() {
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
    function refreshPage() {

        // Only run once the page is fully loaded
        if(pageBuilding) { return; }

        // Disable some function buttons because there is a change
        $(".userButton").prop('disabled', true).attr('title', '{t}This button is disabled until you have saved your changes.{/t}');

        // Refresh Invoice Totals
        refreshTotals();

    }

    // Recalculate and then refresh all onscreen credit note totals
    function refreshTotals() {

        /* Credit Note Item Rows */

        // Variable stores for Items Sums
        creditnoteItemsSubTotalDiscount     = 0.00;
        creditnoteItemsSubTotalNet          = 0.00;
        creditnoteItemsSubTotalTax          = 0.00;
        creditnoteItemsSubTotalGross        = 0.00;

        // Loop through item rows, calculate and refresh new values onscreen (Tax System Aware)
        $('.creditnote_item_row').each(function() {

            // Unit Values (not used onscreen)
            rowUnitQty                  = +$(this).find("input[id$='\\[unit_qty\\]']").val();
            rowUnitNet                  = +$(this).find("input[id$='\\[unit_net\\]']").val();
            rowUnitDiscount             = +$(this).find("input[id$='\\[unit_discount\\]']").val();
            rowUnitTaxRate              = +$(this).find("input[id$='\\[unit_tax_rate\\]']").val();

            // Row Totals
            rowSubTotalNet              = (rowUnitNet - rowUnitDiscount) * rowUnitQty;
            rowSubTotalTax              = rowSubTotalNet * (rowUnitTaxRate / 100);
            rowSubTotalGross            = rowSubTotalNet + rowSubTotalTax;

            // Update Row Totals onscreen
            $(this).find("input[id$='\\[subtotal_net\\]']").val(parseFloat(rowSubTotalNet).toFixed(2));
            $(this).find("input[id$='\\[subtotal_tax\\]']").val(parseFloat(rowSubTotalTax).toFixed(2));
            $(this).find("input[id$='\\[subtotal_gross\\]']").val(parseFloat(rowSubTotalGross).toFixed(2));

            // Update credit Note Items SubTotals
            creditnoteItemsSubTotalDiscount     += rowUnitDiscount * rowUnitQty;
            creditnoteItemsSubTotalNet          += rowSubTotalNet;
            creditnoteItemsSubTotalTax          += rowSubTotalTax;
            creditnoteItemsSubTotalGross        += rowSubTotalGross;

        });

        /* Credit Note Totals */

        // These var declarationsa re just kept for now for comparrision with creditnote:edit
        var creditnoteTotalDiscount    = creditnoteItemsSubTotalDiscount;
        var creditnoteTotalNet         = creditnoteItemsSubTotalNet;
        var creditnoteTotalTax         = creditnoteItemsSubTotalTax;
        var creditnoteTotalGross       = creditnoteItemsSubTotalGross;

        // Update values onscreen + Convert Value to 0.00 format
        $("#creditnoteTotalDiscountText").text(parseFloat(creditnoteTotalDiscount).toFixed(2));
        $("#creditnoteTotalDiscount").val(parseFloat(creditnoteTotalDiscount).toFixed(2));
        $("#creditnoteTotalNetText").text(parseFloat(creditnoteTotalNet).toFixed(2));
        $("#creditnoteTotalNet").val(parseFloat(creditnoteTotalNet).toFixed(2));
        $("#creditnoteTotalTaxText").text(parseFloat(creditnoteTotalTax).toFixed(2));
        $("#creditnoteTotalTax").val(parseFloat(creditnoteTotalTax).toFixed(2));
        $("#creditnoteTotalGrossText").text(parseFloat(creditnoteTotalGross).toFixed(2));
        $("#creditnoteTotalGross").val(parseFloat(creditnoteTotalGross).toFixed(2));
        $("#creditnoteTotalGrossTop").text(parseFloat(creditnoteTotalGross).toFixed(2));

    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form action="index.php?component=creditnote&page_tpl=edit&creditnote_id={$creditnote_id}" method="post" name="new_creditnote" id="new_creditnote">
                <table width="1024" cellpadding="4" cellspacing="0" border="0" >

                    <!-- Title -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Edit{/t} {t}Credit Note ID{/t} {$creditnote_details.creditnote_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}CREDITNOTE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}CREDITNOTE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                            </a>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                                <!-- Credit Note Details Block -->
                                <tr>
                                    <td class="menutd">

                                        <!-- Credit Note Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">

                                            <tr class="olotd4">
                                                <td class="row2"><b>{t}Credit Note ID{/t}</b></td>
                                                <td class="row2"><b>{t}Type{/t}</b></td>
                                                <td class="row2">
                                                    {if $creditnote_details.type == 'sales'}
                                                        <b>{t}Client ID{/t}<br>{t}(created from){/t}</b>
                                                    {else}
                                                        <b>{t}Supplier ID{/t}<br>{t}(created from){/t}</b>
                                                    {/if}
                                                </td>
                                                <td class="row2">
                                                    {if $creditnote_details.type == 'sales'}
                                                        <b>{t}Invoice ID{/t}<br>{t}(created from){/t}</b></b>
                                                    {else}
                                                        <b>{t}Expense ID{/t}<br>{t}(created from){/t}</b></b>
                                                    {/if}
                                                </td>
                                                <td class="row2"><b>{t}Employee{/t}</b></td>
                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                <td class="row2"><b>{t}Expiry Date{/t}</b></td>
                                                <td class="row2"><b>{t}Status{/t}</b></td>
                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                            </tr>
                                            <tr class="olotd4">
                                                <td>{$creditnote_id}</td>
                                                <td>
                                                    {section name=t loop=$creditnote_types}
                                                        {if $creditnote_details.type == $creditnote_types[t].type_key}{t}{$creditnote_types[t].display_name}{/t}{/if}
                                                    {/section}
                                                </td>
                                                <td>
                                                    {if '/^sales/'|preg_match:$creditnote_details.type}
                                                        <a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.client_id}</a><br>
                                                    {else}
                                                        <a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_details.supplier_id}">{$supplier_details.supplier_id}</a><br>
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if $creditnote_details.invoice_id}
                                                        <a href="index.php?component=invoice&page_tpl=details&invoice_id={$creditnote_details.invoice_id}">{$creditnote_details.invoice_id}</a>
                                                    {elseif $creditnote_details.expense_id}
                                                        <a href="index.php?component=expense&page_tpl=details&expense_id={$creditnote_details.expense_id}">{$creditnote_details.expense_id}</a>
                                                    {/if}
                                                </td>
                                                <td>
                                                    <a href="index.php?component=user&page_tpl=details&user_id={$creditnote_details.employee_id}">{$employee_display_name}</a>
                                                </td>
                                                <td>
                                                    {if $creditnote_details.status == 'pending' || $creditnote_details.status == 'unused'}
                                                        <input id="date" name="qform[date]" class="olotd4" size="10" value="{$creditnote_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                        {$creditnote_details.date|date_format:$date_format}
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if $creditnote_details.status == 'pending' || $creditnote_details.status == 'unused'}
                                                        <input id="expiry_date" name="qform[expiry_date]" class="olotd4" size="10" value="{$creditnote_details.expiry_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                        <button type="button" id="expiry_date_button">+</button>
                                                        <script>
                                                            Calendar.setup( {
                                                                trigger     : "expiry_date_button",
                                                                inputField  : "expiry_date",
                                                                dateFormat  : "{$date_format}",
                                                                onChange    : function() { refreshPage(); }
                                                            } );
                                                        </script>
                                                    {else}
                                                        {$creditnote_details.expiry_date|date_format:$date_format}
                                                    {/if}
                                                </td>
                                                <td>
                                                    {section name=s loop=$creditnote_statuses}
                                                        {if $creditnote_details.status == $creditnote_statuses[s].status_key}{t}{$creditnote_statuses[s].display_name}{/t}{/if}
                                                    {/section}
                                                </td>
                                                <td>{$currency_sym}<span id="creditnoteTotalGrossTop">0.00</span></td>

                                            </tr>

                                            <tr>

                                                {if $client_details}
                                                    <!-- Client Details -->
                                                    <td colspan="5" valign="top" align="left">
                                                        <b>{t}Client Details{/t}</b>
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
                                                {else}
                                                    <!-- Supplier Details -->
                                                    <td colspan="5" valign="top" align="left">
                                                        <b>{t}Supplier Details{/t}</b>
                                                        <table cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td valign="top">
                                                                    <a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_details.supplier_id}">{$supplier_details.display_name}</a><br>
                                                                    {$supplier_details.address|nl2br}<br>
                                                                    {$supplier_details.city}<br>
                                                                    {$supplier_details.state}<br>
                                                                    {$supplier_details.zip}<br>
                                                                    {$supplier_details.country}<br>
                                                                    {$supplier_details.primary_phone}<br>
                                                                    {$supplier_details.email}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                {/if}

                                                <!-- Company Details -->
                                                <td colspan="2" valign="top">
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

                                            <!-- Reference -->
                                            <tr>
                                                <td><strong>{t}Reference{/t}:</strong></td>
                                                <td>
                                                    <input name="qform[reference]" class="olotd5" value="{$creditnote_details.reference}" size="25" type="text" maxlength="50" onkeydown="return onlyAlphaNumericPunctuation(event);">
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>

                                <!-- Credit Note Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table id="creditnote_items" width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" align="left" style="width: 200px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Qty{/t}</b></td>
                                                            <td class="row2" align="left" style="width: 75px;"><b>{if $creditnote_details.tax_system != 'no_tax'}{t}Unit Net{/t}{else}Unit Gross{/if} ({$currency_sym})</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Discount{/t} ({$currency_sym})</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="left" hidden><b>{t}Net{/t} ({$currency_sym})</b></td>
                                                            <td class="vatTaxSystem row2" align="right" hidden><b>{t}VAT Tax Code{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t} (%)</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} ({$currency_sym})</b></td>
                                                            <td class="salesTaxSystem row2"  align="right" hidden><b>{t}Sales Tax{/t} {t}Exempt{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Gross{/t} ({$currency_sym})</b></td>
                                                            <td class="row2" align="right"><b>{t}Actions{/t}</b></td>
                                                        </tr>

                                                        <!-- Credit Note Items Dummy Row -->
                                                        <tr id="dummy_creditnote_items_row_iteration" class="dummy_creditnote_item_row olotd4" style="display: none;">
                                                            <td align="left">
                                                                <select id="qform[creditnote_items][iteration][description]" name="qform[creditnote_items][iteration][description]" value="" style="width: 100%" disabled>
                                                                    {section loop=$creditnote_prefill_items name=i}
                                                                        <option value="{$creditnote_prefill_items[i].description}" data-unit-net="{$creditnote_prefill_items[i].unit_net|string_format:"%.2f"}">{$creditnote_prefill_items[i].description}</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                            <td align="left"><input id="qform[creditnote_items][iteration][unit_qty]" name="qform[creditnote_items][iteration][unit_qty]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="left"><input id="qform[creditnote_items][iteration][unit_net]" name="qform[creditnote_items][iteration][unit_net]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td align="left"><input id="qform[creditnote_items][iteration][unit_discount]" name="qform[creditnote_items][iteration][unit_discount]" style="width: 50px;" size="6" value="0.00" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="left" hidden><input id="qform[creditnote_items][iteration][subtotal_net]" name="qform[creditnote_items][iteration][subtotal_net]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="right" hidden>
                                                                <select id="qform[creditnote_items][iteration][vat_tax_code]" name="qform[creditnote_items][iteration][vat_tax_code]" value="" style="width: 100%; font-size: 10px;" required disabled>
                                                                    {section loop=$vat_tax_codes name=i}
                                                                        <option value="{$vat_tax_codes[i].tax_key}" data-tax-rate="{$vat_tax_codes[i].rate|string_format:"%.2f"}">{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden>
                                                                <input id="qform[creditnote_items][iteration][unit_tax_rate]" name="qform[creditnote_items][iteration][unit_tax_rate]" style="width: 50px;" size="6" value="{if $creditnote_details.tax_system == 'sales_tax_cash'}{$creditnote_details.sales_tax_rate|string_format:"%.2f"}{else}0.00{/if}" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[creditnote_items][iteration][subtotal_tax]" name="qform[creditnote_items][iteration][subtotal_tax]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="salesTaxSystem" align="right" hidden><input id="qform[creditnote_items][iteration][sales_tax_exempt]" name="qform[creditnote_items][iteration][sales_tax_exempt]" type="checkbox" value="1" disabled></td>
                                                            <td align="right">
                                                                <input id="qform[creditnote_items][iteration][subtotal_gross]" name="qform[creditnote_items][iteration][subtotal_gross]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <!-- Hidden but needed -->
                                                                <input id="qform[creditnote_items][iteration][unit_tax]" name="qform[creditnote_items][iteration][unit_tax]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <input id="qform[creditnote_items][iteration][unit_gross]" name="qform[creditnote_items][iteration][unit_gross]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                            </td>
                                                            <td align="right">
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Item</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>

                                                        <!-- Credit Note Items Table Record Rows are added here -->

                                                    </table>
                                                    {if $creditnote_details.status == 'pending' || $creditnote_details.status == 'unused'}
                                                        <p>
                                                            <button type="button" onclick="createNewTableRow();">{t}Add{/t}</button>
                                                        </p>
                                                    {/if}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Totals Section -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Credit Note Total{/t}</td>
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
                                                                            {$currency_sym}<span id="creditnoteTotalDiscountText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="creditnoteTotalDiscount" name="qform[unit_discount]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="creditnoteTotalNetText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="creditnoteTotalNet" name="qform[unit_net]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$creditnote_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="creditnoteTotalTaxText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="creditnoteTotalTax" name="qform[unit_tax]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="creditnoteTotalGrossText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="creditnoteTotalGross" name="qform[unit_gross]" value="0.00" readonly hidden>
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

                                <!-- Reason for Credit Note -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Note{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <textarea name="qform[note]" class="olotd5 mceNoEditor" cols="50" rows="3" maxlength="300" placeholder="{t}You must leave a reason for this Credit Note{/t}" onkeydown="return onlyAddress(event);" required/>{$creditnote_details.note}</textarea>
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
                                                <td align="left" valign="top" width="100%">
                                                    <input type="hidden" name="qform[creditnote_id]" value="{$creditnote_details.creditnote_id}">
                                                    <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                    <button type="button" class="olotd4" onclick="window.location.href='index.php?component=creditnote&page_tpl=details&creditnote_id={$creditnote_details.creditnote_id}';">{t}Cancel{/t}</button>
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
