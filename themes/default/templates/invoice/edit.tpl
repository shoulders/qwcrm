<!-- edit.tpl -->
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}dhtmlxcombo/fonts/font_roboto/roboto.css"/>
<link rel="stylesheet" href="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.css">
<script>
 
/**--  LABOUR  --**/


    //// Add Row to Labour Table    
    function addRowToTableLabour() {
        var tbl = document.getElementById('labour_items');
        var lastRow = tbl.rows.length;

        // Insert Row - if there's no header row in the table, then iteration = lastRow + 1
        var iteration = lastRow;
        var row = tbl.insertRow(lastRow);
        row.setAttribute('class', 'olotd4');



        // Number Cell - Create Cell
        var buildRow = row.insertCell(0);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 
        var el = document.createTextNode(iteration);        
        buildRow.appendChild(el);



        // QTY Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // QTY Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'labour_hour['+iteration+']');
        el.setAttribute('name', 'labour_hour['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown','return onlyNumbers(event)');
        buildRow.appendChild(el);



        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '300px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_description['+iteration+']');
        el.setAttribute('name', 'labour_description['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '100');
        //el.setAttribute('value', '1');
        //el.setAttribute('maxlength', '50');
        el.required = true;
        //el.onkeypress = function(event) { return onlyAlphaNumeric(event); } ;
        //el.setAttribute("onkeypress", "return isNumberKeyDecimal(event)");
        //el.onkeydown = 'return onlyAlphaNumeric(event)';
        buildRow.appendChild(el);    


        // Description Cell - Populate the Select Options
        {section loop=$labour_rate_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_rate_items[i].LABOUR_RATE_NAME}', '{$labour_rate_items[i].LABOUR_RATE_NAME}');
        {/section}


        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('labour_description['+iteration+']');

        // Description Cell - Set Combobox settings
        combo.setSize(400);    
        combo.DOMelem_input.maxLength = 50;    
        combo.DOMelem_input.required = true;   

        // Description Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyAlphaNumeric(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );



        // Rate Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Rate Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_rate['+iteration+']');
        el.setAttribute('name', 'labour_rate['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '6');
        //el.setAttribute('value', '1');
        //el.setAttribute('maxlength', '6');
        //el.required = true;
        //el.setAttribute('onkeydown','return onlyNumbersPeriod(event)');
        buildRow.appendChild(el);


        // Rate Cell - Populate the Select Options
        {section loop=$labour_rate_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_rate_items[i].LABOUR_RATE_AMOUNT}', '{$labour_rate_items[i].LABOUR_RATE_AMOUNT}');
        {/section}

        // Rate Cell - Add some HTML to add the Currency Symbol to the left of the Rate Box      
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';


        // Rate Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('labour_rate['+iteration+']');         

        // Rate Cell - Set Combobox settings
        combo.setSize(90);  // This sets the width of the combo box and drop down options width  
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;

        // Rate Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyNumbersPeriod(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );

    }

    //// Remove row from Labour table
    function removeRowFromTableLabour() {
        var tbl = document.getElementById('labour_items');
        var lastRow = tbl.rows.length;
        if (lastRow > 1) tbl.deleteRow(lastRow - 1);
    }


    /**--  PARTS  --**/


    //// Add Row to Parts Table
    function addRowToTableParts() {
        var tbl = document.getElementById('parts_items');
        var lastRow = tbl.rows.length;

        // Insert Row - if there's no header row in the table, then iteration = lastRow + 1
        var iteration = lastRow;
        var row = tbl.insertRow(lastRow);
        row.setAttribute('class', 'olotd4');



        // Number Cell - Create Cell
        var buildRow = row.insertCell(0);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 
        var el = document.createTextNode(iteration);        
        buildRow.appendChild(el);



        // QTY Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // QTY Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'parts_qty['+iteration+']');
        el.setAttribute('name', 'parts_qty['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown','return onlyNumbers(event)');
        buildRow.appendChild(el);



        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('input');
        el.setAttribute('id', 'parts_description['+iteration+']');
        el.setAttribute('name', 'parts_description['+iteration+']');    
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '50');
        el.required = true;
        el.setAttribute('onkeydown','return onlyAlphaNumeric(event)');
        buildRow.appendChild(el);



        // Price Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Price Cell - Create Select Input
        var el = document.createElement('input');
        el.setAttribute('id', 'parts_price['+iteration+']');
        el.setAttribute('name', 'parts_price['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '10');
        //el.setAttribute('value', '1');
        el.setAttribute('maxlength', '10');    
        el.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        el.required = true;
        el.setAttribute('onkeydown','return onlyNumbersPeriod(event)');
        buildRow.appendChild(el);


        // Price Cell - Add some HTML to add the Currency Symbol        
        buildRow.innerHTML = '<b>{$currency_sym}&nbsp;</b>' + buildRow.innerHTML;

    }


    //// Remove row from Parts table
    function removeRowFromTableParts() {
        var tbl = document.getElementById('parts_items');
        var lastRow = tbl.rows.length;
        if (lastRow > 1) tbl.deleteRow(lastRow - 1);
    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_invoice_for}{$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_invoice_new_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_invoice_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                        
                                    <form action="index.php?page=invoice:edit" method="POST" name="new_invoice" id="new_invoice" onsubmit="try { var myValidator = validate_new_invoice; } catch(e) { return true; } return myValidator(this);">

                                        <!-- Invoice Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{$translate_invoice_id}</b></td>
                                                <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                <td class="row2"><b>{$translate_invoice_due}</b></td>
                                                <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                <td class="row2"><b>{$translate_invoice_tech}</b></td>
                                                <td class="row2"><b>{$translate_invoice_work_order}</b></td>
                                                <td class="row2"><b>{$translate_invoice_balance}</b></td>
                                            </tr>
                                            <tr class="olotd4">
                                                <td>{$invoice_details.INVOICE_ID}</td>
                                                <td>
                                                    <input id="date" name="date" class="olotd4" size="10" value="{$invoice_details.DATE|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                    <input id="date_button" value="+" type="button">                                                    
                                                    <script>                                                        
                                                        Calendar.setup( {
                                                            trigger     : "date_button",
                                                            inputField  : "date",
                                                            dateFormat  : "{$date_format}"                                                                                            
                                                        } );                                                        
                                                    </script>                                                    
                                                </td>
                                                <td>
                                                    <input id="due_date" name="due_date" class="olotd4" size="10"  value="{$invoice_details.DUE_DATE|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                    <input id="due_date_button" value="+" type="button">                                                    
                                                    <script>                                                        
                                                       Calendar.setup({
                                                           trigger     : "due_date_button",
                                                           inputField  : "due_date",
                                                           dateFormat  : "{$date_format}"                                                                                            
                                                       });                                                         
                                                    </script>                                                   
                                                </td>
                                                <td>{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
                                                <td><a href="index.php?page=employee:details&employee_id={$invoice_details.EMPLOYEE_ID}">{$employee_display_name}</a></td>
                                                <td><a href="index.php?page=workorder:details&workorder_id={$invoice_details.WORKORDER_ID}&page_title={$translate_invoice_workorder_id} {$invoice_details.WORKORDER_ID}">{$invoice_details.WORKORDER_ID}</a></td>
                                                <td><font color="#CC0000">{$currency_sym}{$invoice_details.BALANCE|string_format:"%.2f"}</font></td>
                                            </tr>


                                            <tr>

                                                <!-- Customer Details -->
                                                <td colspan="3" valign="top" align="left">
                                                    <b>{$translate_invoice_bill}</b>                                                        
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td valign="top">
                                                                <a href="index.php?page=customer:details&customer_id={$customer_details.CUSTOMER_ID}&page_title={$customer_details.CUSTOMER_DISPLAY_NAME}">{$customer_details.CUSTOMER_DISPLAY_NAME}</a><br>
                                                                {$customer_details.CUSTOMER_ADDRESS|nl2br}<br>
                                                                {$customer_details.CUSTOMER_CITY}, {$customer_details.CUSTOMER_STATE} {$customer_details.CUSTOMER_ZIP}<br>
                                                                {$customer_details.CUSTOMER_PHONE}<br>
                                                                {$customer_details.CUSTOMER_EMAIL}                                                                        
                                                            </td>
                                                        </tr>
                                                    </table>                                                        
                                                </td>

                                                <!-- Company Details -->
                                                <td colspan="4" valign="top" >
                                                    <b>{$translate_invoice_pay}</b>
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top">                                                                    
                                                                {$company_details.NAME} <br>
                                                                {$company_details.ADDRESS}<br>
                                                                {$company_details.CITY}, {$company_details.STATE} {$company_details.ZIP}<br>
                                                                {$company_details.PHONE}<br>                                                                    
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>

                                            <!-- Terms and Discount -->
                                            <tr>
                                                <td colspan="7" valign="top" align="left">                                                        
                                                    TERMS: <FONT color="red" size="+1">{$customer_details.CREDIT_TERMS}</FONT><br>
                                                    Current customer discount rate is : 
                                                    <input type="text" class="olotd4" size="4" name="discount_rate" value="{$invoice_details.DISCOUNT_RATE|string_format:"%.2f"}"> %<br>
                                                    <b>**Change this if you want to temporarily override the discount rate for this invoice ONLY **</b>                                                       
                                                </td>
                                            </tr>

                                        </table>                                                         
                                        <br>                                            

                                        <!-- Function Buttons -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;Function Buttons</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- if invoice has an amount -->
                                                    {if $invoice_details.TOTAL > 0 }

                                                        <!-- Print Buttons -->   
                                                        <button type="button" name="{$translate_invoice_print}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">{$translate_invoice_print}</button>
                                                        <button type="button" name="{$translate_invoice_pdf}" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_pdf&print_content=invoice&theme=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{$translate_invoice_pdf}</button>
                                                        <button type="button" name="Print Address Only" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.INVOICE_ID}&print_type=print_html&print_content=invoice&theme=print');">Print Address Only</button>                                            

                                                        {if $invoice_details.BALANCE > 0}
                                                            <!-- Receive Payment Button -->
                                                            <button type="button" name="{$translate_invoice_bill_customer}" onClick="location.href='index.php?page=payment:new&invoice_id={$invoice_details.INVOICE_ID}';">{$translate_invoice_bill_customer}</button>
                                                        {/if}

                                                    {else}

                                                        <!-- Delete Button -->
                                                        <button type="button" name="{$translate_invoice_delete}" onClick="location.href='index.php?page=invoice:delete&customer_id={$invoice_details.CUSTOMER_ID}&invoice_id={$invoice_details.INVOICE_ID}&page_title=Deleting&nbsp;Invoice&nbsp;-{$invoice_details.INVOICE_ID}';">{$translate_invoice_delete}</button>

                                                        {if $workorder_status == '9' && $workorder_id != '0'}
                                                            <!-- Close Button -->
                                                            <button type="button" name="Close Work Order" onClick="location.href='index.php?page=workorder:details_edit_resolution&workorder_id={$invoice_details.WORKORDER_ID}&page_title=Closing%20Work%20Order{$invoice_details.WORKORDER_ID}';">{$translate_invoice_close_wo}</button>
                                                        {/if}

                                                        <!-- Work Order must be closed before payment can be received. -->
                                                        {$translate_invoice_edit_invoice_delete_invoice_msg}

                                                    {/if} 

                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Transaction Log -->
                                        {if $transactions != null}                                            
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{$translate_invoice_transaction_log}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"><b>{$translate_invoice_trans_id}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_type}</b></td>
                                                            </tr>                                                            
                                                            {section name=t loop=$transactions}
                                                                <tr class="olotd4">
                                                                    <td>{$transactions[t].TRANSACTION_ID}</td>
                                                                    <td>{$transactions[t].DATE|date_format:$date_format}</td>
                                                                    <td><b>{$currency_symbol}</b>{$transactions[t].AMOUNT|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        {if $transactions[t].TYPE == 1}{$translate_invoice_cc}
                                                                        {elseif $transactions[t].TYPE == 2}{$translate_invoice_check}
                                                                        {elseif $transactions[t].TYPE == 3}{$translate_invoice_cash}
                                                                        {elseif $transactions[t].TYPE == 4}{$translate_invoice_gift}
                                                                        {elseif $transactions[t].TYPE == 5}{$translate_invoice_paypal}
                                                                        {/if}
                                                                    </td>
                                                                </tr>
                                                                <tr class="olotd4">
                                                                    <td><b>{$translate_invoice_note}</b></td>
                                                                    <td colspan="3">{$transactions[t].NOTE}</td>
                                                                </tr>
                                                            {/section}
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br>
                                        {/if}                                            

                                        <!-- Labour Items -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_labour}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $labour_items != '0'}
                                                        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                            <tr  class="olotd4">
                                                                <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                                <td class="row2" width="12"><b>{$translate_invoice_hours}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_rate}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_actions}</b></td>
                                                            </tr>
                                                            {section name=l loop=$labour_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.q.index+1}</td>
                                                                    <td>{$labour_items[l].INVOICE_LABOUR_UNIT}</td>
                                                                    <td>{$labour_items[l].INVOICE_LABOUR_DESCRIPTION}</td>
                                                                    <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_RATE|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$labour_items[l].INVOICE_LABOUR_SUBTOTAL|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        <a href="index.php?page=invoice:delete_labour&labour_id={$labour_items[l].INVOICE_LABOUR_ID}" onclick="return confirmDelete('{$translate_invoice_labour_delete_mes_confirmation}');">
                                                                            <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_invoice_delete_invoice_labour_item|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            {/section}
                                                            <tr>
                                                                <td colspan="5" style="text-align:right;"><b>{$translate_invoice_labour_total}</b></td>
                                                                <td style="text-align:left;">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
                                                            </tr>
                                                        </table>
                                                    {/if}
                                                    <br>
                                                    <!-- Additional Javascript Labour Table -->
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labour_items">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_hours}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>&nbsp;&nbsp;{$translate_invoice_rate}</b></td>
                                                        </tr>

                                                        <!-- Additional Rows are added here -->

                                                    </table>
                                                    <p>
                                                        <input type="button" value="{$translate_invoice_add}" onclick="addRowToTableLabour();" />
                                                        <input type="button" value="{$translate_invoice_remove}" onclick="removeRowFromTableLabour();" />
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Parts Items -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_parts}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $parts_items != '0'}
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_count}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                                <td class="row2"><b>{$translate_invoice_actions}</b></td>
                                                            </tr>
                                                            {section name=p loop=$parts_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.w.index+1}</td>
                                                                    <td>{$parts_items[p].INVOICE_PARTS_COUNT}</td>
                                                                    <td>{$parts_items[p].INVOICE_PARTS_DESCRIPTION}</td>
                                                                    <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$parts_items[p].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        <a href="index.php?page=invoice:delete_parts&parts_id={$parts_items[p].INVOICE_PARTS_ID}" onclick="return confirmDelete('{$translate_invoice_parts_delete_mes_confirmation}');">
                                                                            <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_invoice_delete_parts_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                             {/section}
                                                            <tr>
                                                                <td colspan="5" style="text-align:right;"><b>{$translate_invoice_parts_total}</b></td>
                                                                <td style="text-align:left;">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
                                                            </tr>
                                                        </table>
                                                    {/if}
                                                    <br>
                                                    <!-- Additional Javascript Parts Table -->
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable" id="parts_items">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_count}-QTY</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                        </tr>

                                                        <!-- Additional Rows are added here -->

                                                    </table>
                                                    <p>
                                                        <input type="button" value="{$translate_invoice_add}" onclick="addRowToTableParts();" />
                                                        <input type="button" value="{$translate_invoice_remove}" onclick="removeRowFromTableParts();" />
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Totals Section -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_total}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_sub_total}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.SUB_TOTAL|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_discount} (@ {$invoice_details.DISCOUNT_RATE|string_format:"%.2f"}%)</b></td>
                                                            <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice_details.DISCOUNT|string_format:"%.2f"}</td>
                                                        </tr>                                                        
                                                        <tr>                                                            
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_tax} (@ {$invoice_details.TAX_RATE}%)</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.TAX|string_format:"%.2f"}</td>                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_total}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Button and Hidden Section -->
                                        <table width="100%"  cellpadding="3" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" valign="top" width="25%">                                                        
                                                    <input type="hidden" name="invoice_id" value="{$invoice_details.INVOICE_ID}">
                                                    <input type="hidden" name="sub_total" value="{$invoice_details.SUB_TOTAL|string_format:"%.2f"}">
                                                    <button type="submit" name="submit" value="submit">{$translate_invoice_submit}</button>
                                                </td>
                                                <td align="right" width="75%"></td>
                                            </tr>
                                        </table>                                            

                                    </form>                         
                                 </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>