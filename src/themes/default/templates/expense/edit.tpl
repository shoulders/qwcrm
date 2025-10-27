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
<script>{include file="`$theme_js_dir_finc`components/supplier_autosuggest.js"}</script>
<script>

    // Page Building Flag
    var pageBuilding = true;

    // Key pressed Boolean - Allow me to determine if action was started by a mouse click or typing
    var keyPressed = false;

    // Expense Tax System
    var expenseTaxSystem = '{$expense_details.tax_system}';

    // Expense items JSON (items from the database)
    var expenseItems = {$expense_items_json};

    // Run these functions when the DOM is ready
    $(document).ready(function() {

        // Prepare the Data
        modifyDummyRowsForTaxSystem();
        processExpenseItemsFromDatabase(expenseItems);

        // Page Building has now completed
        pageBuilding = false;

        // Intialialise the correct values on page
        refreshTotals();

    });

    // Change the Dummy records so the visible fields match the Tax System
    function modifyDummyRowsForTaxSystem() {

        // If the Tax system is No Tax
        if(expenseTaxSystem.startsWith("no_tax")) {
        }

        // If the Tax system is VAT based
        if(expenseTaxSystem.startsWith("vat_")) {
            $(".vatTaxSystem").show();
        }

        // If the Tax system Sales Tax based
        if(expenseTaxSystem.startsWith("sales_tax_cash")) {
            $(".salesTaxSystem").show();
        }

    }

    // Create and populate item rows with sorted data from the database
    function processExpenseItemsFromDatabase(expenseItems) {

        // Form Fields that are submitted
        fieldNames = [
            //"expense_" + "_id",
            //"expense_id",
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

        // Loop through expense items from the database
        $.each(expenseItems, function(itemIndex, expenseItem) {

            // Create a new row to be populated and get the row identifier
            iteration = createNewTableRow();

            // Loop through the various fields and populate with their data
            $.each(fieldNames, function(fieldIndex, fieldName) {

                // If it is sales_tax_exempt and should be checked, do it
                if(fieldName == "sales_tax_exempt") {
                    if(expenseItem[fieldName] === '1') {
                        $('#qform\\[expense_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').prop('checked', true);
                    }

                // If it is a Combobox
                } else if(fieldName === "description") {

                    // Build the Combobox identifier
                    let comboboxInputName = fieldName.replace("_", "")+'Combobox';

                    // Update Combobox text value using it's API to prevent a change trigger
                    // If you change a combobox <option> with Javascript after the comobobox is initiated the the "onChange" and "onSelectionChange" are fired on the first mouse click on <body> (see _doOnBodyMouseDown())
                    window[iteration+comboboxInputName].setComboText(expenseItem[fieldName]);

                // Standard Input Value
                } else {
                    // Update field value
                    $('#qform\\[expense_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').val(expenseItem[fieldName]);
                    //$('#qform\\[expense_items\\]\\['+iteration+'\\]\\['+fieldName+'\\]').attr('value', expenseItem[fieldName]);
                    //document.getElementById('qform[expense_items]['+iteration+']['+fieldName+']').value = expenseItem[fieldName]; (this does not work for some reason)
                }
            });

        });

    }

    // Dynamically Copy, Process and add an new expense item row to the relevant table
    function createNewTableRow() {

        // Get Table
        var tbl = document.getElementById('expense_items');

        // Get Next Row Number
        var iteration = tbl.rows.length - 1;

        // Clone Dummy Row
        var clonedRow = $('#dummy_expense_items_row_iteration').clone();

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
        window[iteration+'descriptionCombobox'] = dhtmlXComboFromSelect('qform[expense_items]['+iteration+'][description]');

        // Set Combobox Options - https://docs.dhtmlx.com/api__refs__dhtmlxcombo.html
        window[iteration+'descriptionCombobox'].DOMelem_input.id = 'qform[expense_items]['+iteration+'][description_combobox]';
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
                let matchingOption = $('#qform\\[expense_items\\]\\[iteration\\]\\[description\\]').find('option[value="'+window[iteration+'descriptionCombobox'].getComboText()+'"]');
                let unitNet = matchingOption.data('unit-net');
                if(unitNet != null) { $('#qform\\[expense_items\\]\\['+iteration+'\\]\\[unit_net\\]').val(parseFloat(unitNet).toFixed(2)); }
            }

            // Reset the keyPessed Boolean as it has now been called and used
            keyPressed = false;

            refreshPage();

        } );

        // Set Vat Tax Code default value
        $('#qform\\[expense_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').val('{$default_vat_tax_code}');

        // Update the intial Tax Rate to match the intial VAT Tax Code (Only if the Tax system is VAT based)
        if(expenseTaxSystem.startsWith("vat_")) {
            let selected = $('#qform\\[expense_items\\]\\['+iteration+'\\]\\[vat_tax_code\\]').find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $('#qform\\[expense_items\\]\\['+iteration+'\\]\\[unit_tax_rate\\]').val(parseFloat(newTaxRate).toFixed(2));
        }

        /* Event Binding - Refresh all rows when triggered */

        // Monitor for change in VAT Tax Code/Rate selectbox and update tax rate accordingly
        $(".expense_item_row select[id$='\\[vat_tax_code\\]']").off("change").on("change", function() {
            let selected = $(this).find('option:selected');
            let newTaxRate = selected.data('tax-rate');
            $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(newTaxRate).toFixed(2));
            refreshPage();
        });

        // Monitor Sales Tax Exempt Checkboxes for click - Toggle the value between 0.00 and configured Sales Tax Rate
        $(".expense_item_row input[id$='\\[sales_tax_exempt\\]']").click(function () {
            if ($(this).is(":checked")) {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val('0.00');
            } else {
                $(this).closest('tr').find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat($("#expenseSalesTaxRate").val()).toFixed(2));  // what about +$ -  should this be a a varible rel;oad on each page refresh
            }
            refreshPage();
        });


        /* Monitor all row input boxes for changes
        $(".expense_item_row input[type='text']").off("change").on("change", function() {
            refreshPage();
        });*/

        // Monitor all row input boxes for keyup
        $(".expense_item_row input[type='text']").off("keyup").on("keyup", function() {
            refreshPage();
        });

        // Item Delete button action
        $(".expense_item_row .confirmDelete").off("click").on("click", function() {
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

        // Disable all buttons on page refresh unless on initial page build, if there is a change
        if(pageBuilding === false) {

            // Disable some function buttons because there is a change
            $(".userButton").prop('disabled', true).attr('title', '{t}This button is disabled until you have saved your changes.{/t}');

            // Refresh Record Totals
            refreshTotals();
        }

    }

    // Recalculate and then refresh all onscreen expense totals
    function refreshTotals() {

        // Refresh the Sales Tax Rate
        let expenseSalesTaxRate = $("#expenseSalesTaxRate").val();

        /* Expense Item Rows */

        // Variable stores for Items Sums
        expenseItemsSubTotalDiscount     = 0.00;
        expenseItemsSubTotalNet          = 0.00;
        expenseItemsSubTotalTax          = 0.00;
        expenseItemsSubTotalGross        = 0.00;

        // Loop through item rows, calculate and refresh new values onscreen (Tax System Aware)
        $('.expense_item_row').each(function() {

            // Update Sales Tax Rate if on sales_tax system and not exempt
            if(expenseTaxSystem === 'sales_tax_cash' && !$(this).find("input[id$='\\[sales_tax_exempt\\]']").is(":checked")) {
                $(this).find("input[id$='\\[unit_tax_rate\\]']").val(parseFloat(expenseSalesTaxRate).toFixed(2));
            }

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
            expenseItemsSubTotalDiscount     += rowUnitDiscount * rowUnitQty;
            expenseItemsSubTotalNet          += rowSubTotalNet;
            expenseItemsSubTotalTax          += rowSubTotalTax;
            expenseItemsSubTotalGross        += rowSubTotalGross;

        });

        /* Expense Totals */

        // These var declarationsa re just kept for now for comparrision with expense:edit
        var expenseTotalDiscount    = expenseItemsSubTotalDiscount;
        var expenseTotalNet         = expenseItemsSubTotalNet;
        var expenseTotalTax         = expenseItemsSubTotalTax;
        var expenseTotalGross       = expenseItemsSubTotalGross;

        // Update values onscreen + Convert Value to 0.00 format
        $("#expenseTotalDiscountText").text(parseFloat(expenseTotalDiscount).toFixed(2));
        $("#expenseTotalDiscount").val(parseFloat(expenseTotalDiscount).toFixed(2));
        $("#expenseTotalNetText").text(parseFloat(expenseTotalNet).toFixed(2));
        $("#expenseTotalNet").val(parseFloat(expenseTotalNet).toFixed(2));
        $("#expenseTotalTaxText").text(parseFloat(expenseTotalTax).toFixed(2));
        $("#expenseTotalTax").val(parseFloat(expenseTotalTax).toFixed(2));
        $("#expenseTotalGrossText").text(parseFloat(expenseTotalGross).toFixed(2));
        $("#expenseTotalGross").val(parseFloat(expenseTotalGross).toFixed(2));
        $("#expenseTotalGrossTop").text(parseFloat(expenseTotalGross).toFixed(2));

    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form action="index.php?component=expense&page_tpl=edit&expense_id={$expense_id}" method="post" name="new_expense" id="new_expense">
                <table width="1024" cellpadding="4" cellspacing="0" border="0" >

                    <!-- Title -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Edit{/t} {t}Expense ID{/t} {$expense_details.expense_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}EXPENSE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}EXPENSE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                            </a>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">

                                <!-- Expense Details Block -->
                                <tr>
                                    <td class="menutd">

                                        <!-- Expense Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">

                                            <tr class="olotd4">
                                                <td class="row2"><b>{t}Expense ID{/t}</b></td>
                                                <td class="row2"><b>{t}Supplier ID{/t}</b></td>
                                                <td class="row2"><b>{t}Employee{/t}</b></td>
                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                <td class="row2"><b>{t}Due Date{/t}</b></td>
                                                <td class="row2"><b>{t}Status{/t}</b></td>
                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                            </tr>
                                            <tr class="olotd4">
                                                <td>{$expense_id}</td>
                                                <td id="supplierIdLink">
                                                    {if $expense_details.supplier_id}
                                                        <a href="index.php?component=supplier&page_tpl=details&supplier_id={$expense_details.supplier_id}">{$expense_details.supplier_id}</a><br>
                                                    {/if}
                                                </td>
                                                <td>
                                                    <a href="index.php?component=user&page_tpl=details&user_id={$expense_details.employee_id}">{$employee_display_name}</a>
                                                </td>
                                                <td>
                                                    <input id="date" name="qform[date]" class="olotd4" size="10" value="{$expense_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                    <button type="button" id="date_button">+</button>
                                                    <script>
                                                        Calendar.setup( {
                                                            trigger     : "date_button",
                                                            inputField  : "date",
                                                            dateFormat  : "{$date_format}",
                                                            onChange    : function() { refreshPage(); }
                                                        } );
                                                    </script>
                                                </td>
                                                <td>
                                                    <input id="due_date" name="qform[due_date]" class="olotd4" size="10" value="{$expense_details.due_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                    <button type="button" id="due_date_button">+</button>
                                                    <script>
                                                        Calendar.setup( {
                                                            trigger     : "due_date_button",
                                                            inputField  : "due_date",
                                                            dateFormat  : "{$date_format}",
                                                            onChange    : function() { refreshPage(); }
                                                        } );
                                                    </script>
                                                </td>
                                                <td>
                                                    {section name=s loop=$expense_statuses}
                                                        {if $expense_details.status == $expense_statuses[s].status_key}{t}{$expense_statuses[s].display_name}{/t}{/if}
                                                    {/section}
                                                </td>
                                                <td>{$currency_sym}<span id="expenseTotalGrossTop">0.00</span></td>

                                            </tr>

                                            <!-- Expense Details -->
                                            <tr>
                                                <td colspan="7">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                                        <tr>
                                                            <td><strong>{t}Payee{/t}:</strong></td>
                                                            <td>
                                                                <!-- Payee Input -->
                                                                <input id="qform[payee]" name="qform[payee]" class="olotd5" value="{$expense_details.payee}" size="25" type="text" maxlength="50" {if $expense_details.supplier_id}hidden{else}required{/if} onkeydown="return onlyName(event);">
                                                                <input id="supplierAutosuggestNameDummy" class="olotd5" value="{$expense_details.display_name}" size="25" type="text" maxlength="50" readonly {if !$expense_details.supplier_id}hidden{/if}>
                                                                <input id="assignToSupplier" name="assignToSupplier" type="checkbox" {if $expense_details.supplier_id}checked{/if}>{t}Assign to Supplier{/t}

                                                                <!-- Autosuggest -->
                                                                <div id="supplierAutosuggestBlock" {if !$expense_details.supplier_id}hidden{/if}>
                                                                    <input id="supplierAutosuggestNameInput"class="" value="" size="25" type="text" maxlength="50" placeholder="{t}Start typing a Supplier's name{/t}" onkeydown="return onlyAlphaNumericPunctuation(event);" onkeyup="debounceSupplierAutosuggestNameLookup(this.value);" onblur="supplierAutosuggestNameClose();">
                                                                    <div class="suggestionsBoxWrapper">
                                                                        <div id="supplierAutosuggestName" class="suggestionsBox">
                                                                            <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                                            <div id="supplierAutosuggestNameList" class="suggestionList">&nbsp;</div>
                                                                        </div>
                                                                    </div>
                                                                <div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>{t}Reference{/t}:</strong></td>
                                                            <td>
                                                                <input name="qform[reference]" class="olotd5" value="{$expense_details.reference}" size="25" type="text" maxlength="50" onkeydown="return onlyAlphaNumericPunctuation(event);">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>{t}Type{/t}:</strong></td>
                                                            <td>
                                                                <select id="type" name="qform[type]" class="olotd5" required>
                                                                <option value=""{if !$expense_details.type} selected{/if} disabled>&nbsp;</option>
                                                                <option disabled>----------</option>
                                                                {section name=s loop=$expense_types}
                                                                    <option value="{$expense_types[s].type_key}"{if $expense_details.type == $expense_types[s].type_key} selected{/if}>{t}{$expense_types[s].display_name}{/t}</option>
                                                                {/section}
                                                            </select>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>

                                <!-- Expense Items -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Items{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table id="expense_items" width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" align="left" style="width: 200px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Qty{/t}</b></td>
                                                            <td class="row2" align="left" style="width: 75px;"><b>{if $expense_details.tax_system != 'no_tax'}{t}Unit Net{/t}{else}Unit Gross{/if} ({$currency_sym})</b></td>
                                                            <td class="row2" align="left"><b>{t}Unit Discount{/t} ({$currency_sym})</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="left" hidden><b>{t}Net{/t} ({$currency_sym})</b></td>
                                                            <td class="vatTaxSystem row2" align="right" hidden><b>{t}VAT Tax Code{/t}</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$expense_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t} (%)</b></td>
                                                            <td class="vatTaxSystem salesTaxSystem row2" align="right" hidden><b>{if '/^vat_/'|preg_match:$expense_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} ({$currency_sym})</b></td>
                                                            <td class="salesTaxSystem row2"  align="right" hidden><b>{t}Sales Tax{/t} {t}Exempt{/t}</b></td>
                                                            <td class="row2" align="right"><b>{t}Gross{/t} ({$currency_sym})</b></td>
                                                            <td class="row2" align="right"><b>{t}Actions{/t}</b></td>
                                                        </tr>

                                                        <!-- Expense Items Dummy Row -->
                                                        <tr id="dummy_expense_items_row_iteration" class="dummy_expense_item_row olotd4" style="display: none;">
                                                            <td align="left">
                                                                <select id="qform[expense_items][iteration][description]" name="qform[expense_items][iteration][description]" value="" style="width: 100%" disabled>
                                                                    {section loop=$expense_prefill_items name=i}
                                                                        <option value="{$expense_prefill_items[i].description}" data-unit-net="{$expense_prefill_items[i].unit_net|string_format:"%.2f"}">{$expense_prefill_items[i].description}</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                            <td align="left"><input id="qform[expense_items][iteration][unit_qty]" name="qform[expense_items][iteration][unit_qty]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="left"><input id="qform[expense_items][iteration][unit_net]" name="qform[expense_items][iteration][unit_net]" style="width: 50px;" size="6" value="" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td align="left"><input id="qform[expense_items][iteration][unit_discount]" name="qform[expense_items][iteration][unit_discount]" style="width: 50px;" size="6" value="0.00" type="text" maxlength="10" required disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="left" hidden><input id="qform[expense_items][iteration][subtotal_net]" name="qform[expense_items][iteration][subtotal_net]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem" align="right" hidden>
                                                                <select id="qform[expense_items][iteration][vat_tax_code]" name="qform[expense_items][iteration][vat_tax_code]" value="" style="width: 100%; font-size: 10px;" required disabled>
                                                                    {section loop=$vat_tax_codes name=i}
                                                                        <option value="{$vat_tax_codes[i].tax_key}" data-tax-rate="{$vat_tax_codes[i].rate|string_format:"%.2f"}">{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden>
                                                                <input id="qform[expense_items][iteration][unit_tax_rate]" name="qform[expense_items][iteration][unit_tax_rate]" style="width: 50px;" size="6" value="{if $expense_details.tax_system == 'sales_tax_cash'}{$expense_details.sales_tax_rate|string_format:"%.2f"}{else}0.00{/if}" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="vatTaxSystem salesTaxSystem" align="right" hidden><input id="qform[expense_items][iteration][subtotal_tax]" name="qform[expense_items][iteration][subtotal_tax]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="salesTaxSystem" align="right" hidden><input id="qform[expense_items][iteration][sales_tax_exempt]" name="qform[expense_items][iteration][sales_tax_exempt]" type="checkbox" value="1" disabled></td>
                                                            <td align="right">
                                                                <input id="qform[expense_items][iteration][subtotal_gross]" name="qform[expense_items][iteration][subtotal_gross]" size="6" value="0.00" type="text" maxlength="10" required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <!-- Hidden but needed -->
                                                                <input id="qform[expense_items][iteration][unit_tax]" name="qform[expense_items][iteration][unit_tax]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                                <input id="qform[expense_items][iteration][unit_gross]" name="qform[expense_items][iteration][unit_gross]" size="6" value="0.00" type="text" maxlength="10" hidden required readonly disabled onkeydown="return onlyNumberPeriod(event);">
                                                            </td>
                                                            <td align="right">
                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" class="confirmDelete" onmouseover="ddrivetip('<b>Delete Item</b>');" onmouseout="hideddrivetip();">
                                                            </td>
                                                        </tr>

                                                        <!-- Expense Items Table Record Rows are added here -->

                                                    </table>

                                                    <p><button type="button" onclick="createNewTableRow();">{t}Add{/t}</button></p>

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
                                                <td class="menuhead2">&nbsp;{t}Totals{/t}</td>
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
                                                                            {$currency_sym}<span id="expenseTotalDiscountText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="expenseTotalDiscount" name="qform[unit_discount]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="expenseTotalNetText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="expenseTotalNet" name="qform[unit_net]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="vatTaxSystem salesTaxSystem" hidden>
                                                                        <td class="olotd4" width="80%" align="right">
                                                                            {if '/^vat_/'|preg_match:$expense_details.tax_system}
                                                                                <b>{t}VAT{/t}<b>
                                                                                <input name="qform[sales_tax_rate]" value="{$expense_details.sales_tax_rate}" hidden>
                                                                            {else}
                                                                                <b>{t}Sales Tax{/t} (@
                                                                                <input id="expenseSalesTaxRate" name="qform[sales_tax_rate]" class="olotd5" style="width: 40px; text-align: right;" value="{$expense_details.sales_tax_rate|string_format:"%.2f"}" type="text" maxlength="10" required onkeydown="return onlyNumberPeriod(event);" onkeyup="refreshPage();">
                                                                                %)</b>
                                                                            {/if}
                                                                        </td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="expenseTotalTaxText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="expenseTotalTax" name="qform[unit_tax]" value="0.00" readonly hidden>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">
                                                                            {$currency_sym}<span id="expenseTotalGrossText">0.00</span>
                                                                            <input type="text" class="olotd4" size="4" id="expenseTotalGross" name="qform[unit_gross]" value="0.00" readonly hidden>
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

                                <!-- Expense Note -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Note{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <textarea name="qform[note]" class="olotd5 mceNoEditor" cols="50" rows="3" maxlength="300"  onkeydown="return onlyAddress(event);"/>{$expense_details.note}</textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Buttons and Hidden Section -->
                                <tr>
                                    <td>
                                        <table width="100%"  cellpadding="3" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" valign="top" width="100%">
                                                    <button type="submit" name="submit" value="submit" {*onclick="return confirm('{t}Are you sure you want to continue without payment?{/t}');"*}>{t}Submit{/t}</button>
                                                    <button type="submit" name="submit" value="submitandnew" onclick="return confirm('{t}Are you sure you want to continue without payment?{/t}');">{t}Submit and New{/t}</button>
                                                    <button type="submit" name="submit" value="submitandpayment">{t}Submit and Payment{/t}</button>
                                                    <button type="button" class="olotd4" onclick="window.location.href='index.php?component=expense&page_tpl=details&expense_id={$expense_details.expense_id}';">{t}Cancel{/t}</button>
                                                    <input type="hidden" name="qform[expense_id]" value="{$expense_details.expense_id}">
                                                    <input id="qform[supplier_id]" type="hidden" name="qform[supplier_id]" value="{$expense_details.supplier_id}">
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
