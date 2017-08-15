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



        // qty Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // qty Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'labour_qty['+iteration+']');
        el.setAttribute('name', 'labour_qty['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown', 'return onlyNumbers(event)');
        buildRow.appendChild(el);



        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_description['+iteration+']');
        el.setAttribute('name', 'labour_description['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');  // only required of 'input'
        //el.setAttribute('maxlength', '50');
        el.required = true;
        //el.onkeydown = 'return onlyAlphaNumeric(event)';
        buildRow.appendChild(el);
        
            // Other key press examples - utested, unused
            //el.setAttribute('onkeypress', 'return onlyAlphaNumeric(event)');
            //el.setAttribute('onkeydown', 'return onlyAlphaNumeric(event)');
            //el.onkeypress = function(event) { return onlyAlphaNumeric(event); } ;        
            //el.onkeydown = 'return onlyAlphaNumeric(event)';


        // Description Cell - Populate the Select Options
        {section loop=$labour_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_prefill_items[i].description}', '{$labour_prefill_items[i].description}');
        {/section}


        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('labour_description['+iteration+']');

        // Description Cell - Set Combobox settings
        combo.setSize(400);    
        combo.DOMelem_input.maxLength = 50;    
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Description Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyAlphaNumeric(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );



        // Amount Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Amount Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_amount['+iteration+']');
        el.setAttribute('name', 'labour_amount['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '6');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');  // only required of 'input'
        //el.setAttribute('maxlength', '6');
        //el.required = true;
        //el.setAttribute('onkeydown', 'return onlyNumbersPeriod(event)');
        buildRow.appendChild(el);


        // Amount Cell - Populate the Select Options
        {section loop=$labour_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_prefill_items[i].amount}', '{$labour_prefill_items[i].amount}');
        {/section}

        // Amount Cell - Add some HTML to add the Currency Symbol to the left of the Rate Box      
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';


        // Amount Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('labour_amount['+iteration+']');         

        // Amount Cell - Set Combobox settings
        combo.setSize(90);  // This sets the width of the combo box and drop down options width  
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Amount Cell - Apply Key restriction to the virtual combobox
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



        // qty Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // qty Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'parts_qty['+iteration+']');
        el.setAttribute('name', 'parts_qty['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown', 'return onlyNumbers(event)');
        buildRow.appendChild(el);



        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'parts_description['+iteration+']');
        el.setAttribute('name', 'parts_description['+iteration+']');    
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');
        //el.setAttribute('maxlength', '50');
        el.required = true;       
        //el.onkeydown = 'return onlyAlphaNumeric(event)';
        buildRow.appendChild(el);
        
        
        // Description Cell - Populate the Select Options
        {section loop=$parts_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$parts_prefill_items[i].description}', '{$parts_prefill_items[i].description}');
        {/section}


        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('parts_description['+iteration+']');

        // Description Cell - Set Combobox settings
        combo.setSize(400);    
        combo.DOMelem_input.maxLength = 50;    
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Description Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyAlphaNumeric(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );        



        // Amount Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Amount Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'parts_amount['+iteration+']');
        el.setAttribute('name', 'parts_amount['+iteration+']');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '10');
        //el.setAttribute('value', '1');
        //el.setAttribute('maxlength', '10');    
        //el.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');        
        //el.required = true;
        //el.setAttribute('onkeydown', 'return onlyNumbersPeriod(event)');
        buildRow.appendChild(el);
        
        
        // Amount Cell - Populate the Select Options
        {section loop=$parts_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$parts_prefill_items[i].amount}', '{$parts_prefill_items[i].amount}');
        {/section}

        // Amount Cell - Add some HTML to add the Currency Symbol to the left of the Rate Box      
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';
        
        // Amount Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('parts_amount['+iteration+']');         

        // Amount Cell - Set Combobox settings
        combo.setSize(90);  // This sets the width of the combo box and drop down options width  
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Amount Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyNumbersPeriod(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );

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
                    <td class="menuhead2" width="80%">&nbsp;{t}Invoice For Workorder ID{/t} {$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">                                        
                                    <form action="index.php?page=invoice:edit&invoice_id={$invoice_id}" method="post" name="new_invoice" id="new_invoice">

                                        <!-- Invoice Information -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{t}Invoice ID{/t}</b></td>
                                                <td class="row2"><b>{t}Work Order{/t}</b></td>
                                                <td class="row2"><b>{t}Technician{/t}</b></td> 
                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                <td class="row2"><b>{t}Due Date{/t}</b></td>                                                                                                                                 
                                                <td class="row2"><b>{t}Total{/t}</b></td>
                                                <td class="row2"><b>{t}Amount Paid{/t}</b></td>
                                                <td class="row2"><b>{t}Balance{/t}</b></td>
                                                {*<td class="row2"><b>{t}Date Paid{/t}</b></td>*}
                                            </tr>
                                            <tr class="olotd4">
                                                <td>{$invoice_id}</td>
                                                <td>
                                                    {if $workorder_id > 0}
                                                        <a href="index.php?page=workorder:details&workorder_id={$invoice_details.workorder_id}">{$invoice_details.workorder_id}</a>
                                                    {else}
                                                        {t}n/a{/t}
                                                    {/if}
                                                </td>
                                                <td><a href="index.php?page=user:details&user_id={$invoice_details.employee_id}">{$employee_display_name}</a></td> 
                                                <td>
                                                    <input id="date" name="date" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
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
                                                    <input id="due_date" name="due_date" class="olotd4" size="10"  value="{$invoice_details.due_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                    <input id="due_date_button" value="+" type="button">                                                    
                                                    <script>                                                        
                                                       Calendar.setup({
                                                           trigger     : "due_date_button",
                                                           inputField  : "due_date",
                                                           dateFormat  : "{$date_format}"                                                                                            
                                                       });                                                         
                                                    </script>                                                   
                                                </td>
                                                <td>{$currency_sym}{$invoice_details.total|string_format:"%.2f"}</td>
                                                <td>{$currency_sym}{$invoice_details.paid_amount|string_format:"%.2f"}</td>
                                                <td><font color="#cc0000">{$currency_sym}{$invoice_details.balance|string_format:"%.2f"}</font></td>
                                                {*<td>{$invoice_details.paid_date|date_format:$date_format}</td>*}
                                            </tr>
                                            
                                            <!-- Scope -->
                                            <tr class="olotd4">
                                                <td colspan="2"><b>{t}Work Order Scope{/t}:</b></td>
                                                <td>{$workorder_details.scope}</td>
                                            </tr>

                                            <tr>

                                                <!-- Customer Details -->
                                                <td colspan="5" valign="top" align="left">
                                                    <b>{t}Bill{/t}</b>                                                        
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td valign="top">
                                                                <a href="index.php?page=customer:details&customer_id={$customer_details.customer_id}">{$customer_details.display_name}</a><br>
                                                                {$customer_details.address|nl2br}<br>
                                                                {$customer_details.city}<br>
                                                                {$customer_details.state}<br>
                                                                {$customer_details.zip}<br>
                                                                {$customer_details.country}<br>
                                                                {$customer_details.phone}<br>
                                                                {$customer_details.email}                                                                        
                                                            </td>
                                                        </tr>
                                                    </table>                                                        
                                                </td>

                                                <!-- Company Details -->
                                                <td colspan="3" valign="top" >
                                                    <b>{t}Pay{/t}</b>
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top">                                                                    
                                                                {$company_details.display_name} <br>
                                                                {$company_details.address}<br>
                                                                {$company_details.city}<br>
                                                                {$company_details.state}<br>
                                                                {$company_details.zip}<br>
                                                                {$company_details.country}<br>
                                                                {$company_details.phone}<br>
                                                                {$company_details.email}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>

                                            <!-- Terms and Discount -->
                                            <tr>
                                                <td colspan="7" valign="top" align="left">                                                        
                                                    {t}TERMS{/t}: <font color="red" size="+1">{$customer_details.credit_terms}</font><br>
                                                    {t}Current customer discount rate is{/t}: 
                                                    <input type="text" class="olotd4" size="4" name="discount_rate" value="{$invoice_details.discount_rate|string_format:"%.2f"}"> %<br>
                                                    <b>** {t}Change this if you want to temporarily override the discount rate for this invoice ONLY{/t} **</b>                                                       
                                                </td>
                                            </tr>

                                        </table>                                                         
                                        <br>                                            

                                        <!-- Function Buttons -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Function Buttons{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">

                                                    <!-- if invoice has an amount -->
                                                    {if $invoice_details.total > 0 }

                                                        <!-- Print Buttons -->   
                                                        <button type="button" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=invoice&theme=print');">{t}Print HTML{/t}</button>
                                                        <button type="button" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.invoice_id}&print_type=print_pdf&print_content=invoice&theme=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                        <button type="button" onClick="$.ajax( { url:'index.php?page=invoice:print&invoice_id={$invoice_details.invoice_id}&print_type=email_pdf&print_content=invoice&theme=print', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                        <button type="button" onClick="window.open('index.php?page=invoice:print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=customer_envelope&theme=print');">{t}Print Customer Envelope{/t}</button>                                            

                                                        {if $invoice_details.balance > 0}
                                                            <!-- Receive Payment Button -->
                                                            <button type="button" onClick="location.href='index.php?page=payment:new&invoice_id={$invoice_details.invoice_id}';">{t}Receive Payment{/t}</button>
                                                        {/if}

                                                    {else}

                                                        <!-- Delete Button -->
                                                        <button type="button" name="{t}Delete{/t}" onClick="location.href='index.php?page=invoice:delete&invoice_id={$invoice_details.invoice_id}';">{t}Delete{/t}</button>

                                                        {if $workorder_details.status == '9' && $workorder_id != '0'}
                                                            <!-- Close Button -->
                                                            <button type="button" name="Close Work Order" onClick="location.href='index.php?page=workorder:details_edit_resolution&workorder_id={$invoice_details.workorder_id}';">{t}Close Work Order{/t}</button>
                                                        {/if}

                                                        <!-- Work Order must be closed before payment can be received. -->
                                                        {t}You can only delete an invoice when there is no transactions or amounts on the invoice.{/t}

                                                    {/if} 

                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Transaction Log -->
                                        {if $transactions != null}                                            
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" id="transaction_log">
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Transaction Log{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"><b>{t}Transaction ID{/t}</b></td>
                                                                <td class="row2"><b>{t}Date{/t}</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}</b></td>
                                                                <td class="row2"><b>{t}Type{/t}</b></td>
                                                            </tr>                                                            
                                                            {section name=t loop=$transactions}
                                                                <tr class="olotd4">
                                                                    <td>{$transactions[t].transaction_id}</td>
                                                                    <td>{$transactions[t].date|date_format:$date_format}</td>
                                                                    <td><b>{$currency_symbol}</b>{$transactions[t].amount|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        {if $transactions[t].type == 1}{t}Credit Card{/t}
                                                                        {elseif $transactions[t].type == 2}{t}Cheque{/t}
                                                                        {elseif $transactions[t].type == 3}{t}Cash{/t}
                                                                        {elseif $transactions[t].type == 4}{t}Gift Certificate{/t}
                                                                        {elseif $transactions[t].type == 5}{t}PayPal{/t}
                                                                        {/if}
                                                                    </td>
                                                                </tr>
                                                                <tr class="olotd4">
                                                                    <td><b>{t}Note{/t}</b></td>
                                                                    <td colspan="3">{$transactions[t].note}</td>
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
                                                <td class="menuhead2">&nbsp;{t}Labour{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $labour_items != '0'}
                                                        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                            <tr  class="olotd4">
                                                                <td class="row2"><b>{t}No{/t}</b></td>
                                                                <td class="row2" width="12"><b>{t}Qty{/t}</b></td>
                                                                <td class="row2"><b>{t}Description{/t}</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}</b></td>
                                                                <td class="row2"><b>{t}Total{/t}</b></td>
                                                                <td class="row2"><b>{t}Actions{/t}</b></td>
                                                            </tr>
                                                            {section name=l loop=$labour_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.l.index+1}</td>
                                                                    <td>{$labour_items[l].qty}</td>
                                                                    <td>{$labour_items[l].description}</td>
                                                                    <td>{$currency_sym}{$labour_items[l].amount|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$labour_items[l].sub_total|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        <a href="index.php?page=invoice:delete_labour&labour_id={$labour_items[l].invoice_labour_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Labour Record? This will permanently remove the record from the database.{/t}');">
                                                                            <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Labour Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            {/section}
                                                            <tr>
                                                                <td colspan="5" style="text-align:right;"><b>{t}Labour Total{/t}</b></td>
                                                                <td style="text-align:left;">{$currency_sym}{$labour_sub_total|string_format:"%.2f"}</td>
                                                            </tr>
                                                        </table>
                                                    {/if}
                                                    <br>
                                                    <!-- Additional Javascript Labour Table -->
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labour_items">
                                                        <tr class="olotd4">
                                                            <td class="row2" style="width: 15px;"><b>{t}No{/t}</b></td>
                                                            <td class="row2" style="width: 66px;"><b>{t}Qty{/t}</b></td>
                                                            <td class="row2" style="width: 453px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" style="width: 110px;"><b>{t}Amount{/t}</b></td> 
                                                        </tr>

                                                        <!-- Additional Rows are added here -->

                                                    </table>
                                                    <p>
                                                        <input type="button" value="{t}Add{/t}" onclick="addRowToTableLabour();" />
                                                        <input type="button" value="{t}Remove{/t}" onclick="removeRowFromTableLabour();" />
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Parts Items -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Parts{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $parts_items != '0'}
                                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                            <tr class="olotd4">
                                                                <td class="row2"><b>{t}No{/t}</b></td>
                                                                <td class="row2"><b>{t}Qty{/t}</b></td>
                                                                <td class="row2"><b>{t}Description{/t}</b></td>
                                                                <td class="row2"><b>{t}Amount{/t}</b></td>
                                                                <td class="row2"><b>{t}Total{/t}</b></td>
                                                                <td class="row2"><b>{t}Actions{/t}</b></td>
                                                            </tr>
                                                            {section name=p loop=$parts_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.p.index+1}</td>
                                                                    <td>{$parts_items[p].qty}</td>
                                                                    <td>{$parts_items[p].description}</td>
                                                                    <td>{$currency_sym}{$parts_items[p].amount|string_format:"%.2f"}</td>
                                                                    <td>{$currency_sym}{$parts_items[p].sub_total|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        <a href="index.php?page=invoice:delete_parts&parts_id={$parts_items[p].invoice_parts_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Parts Record? This will permanently remove the record from the database.{/t}');">
                                                                            <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Parts Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                             {/section}
                                                            <tr>
                                                                <td colspan="5" style="text-align:right;"><b>{t}Parts Total{/t}</b></td>
                                                                <td style="text-align:left;">{$currency_sym}{$parts_sub_total|string_format:"%.2f"}</td>
                                                            </tr>
                                                        </table>
                                                    {/if}
                                                    <br>
                                                    <!-- Additional Javascript Parts Table -->
                                                    <table id="parts_items" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2" style="width: 15px;"><b>{t}No{/t}</b></td>
                                                            <td class="row2" style="width: 66px;"><b>{t}Qty{/t}</b></td>
                                                            <td class="row2" style="width: 453px;"><b>{t}Description{/t}</b></td>
                                                            <td class="row2" style="width: 110px;"><b>{t}Amount{/t}</b></td>                                                            
                                                        </tr>

                                                        <!-- Additional Rows are added here -->

                                                    </table>
                                                    <p>
                                                        <input type="button" value="{t}Add{/t}" onclick="addRowToTableParts();" />
                                                        <input type="button" value="{t}Remove{/t}" onclick="removeRowFromTableParts();" />
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>

                                        <!-- Totals Section -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Total{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table width="100%" border="1" cellpadding="3" cellspacing="0" class="olotable">
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Sub Total{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.sub_total|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ {$invoice_details.discount_rate|string_format:"%.2f"}%)</b></td>
                                                            <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice_details.discount_amount|string_format:"%.2f"}</td>
                                                        </tr>                                                        
                                                        <tr>                                                            
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Tax{/t} (@ {$invoice_details.tax_rate}%)</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.tax_amount|string_format:"%.2f"}</td>                                                            
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{t}Total{/t}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.total|string_format:"%.2f"}</td>
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
                                                    <input type="hidden" name="invoice_id" value="{$invoice_details.invoice_id}">
                                                    <input type="hidden" name="sub_total" value="{$invoice_details.sub_total|string_format:"%.2f"}">
                                                    <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
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