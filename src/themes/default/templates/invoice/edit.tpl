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



        // Description Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_items['+iteration+'][description]');
        el.setAttribute('name', 'labour_items['+iteration+'][description]');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');  // only required of 'input'
        //el.setAttribute('maxlength', '100');
        el.required = true;
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';
        buildRow.appendChild(el);
        
        // Other key press examples - utested, unused
        //el.setAttribute('onkeypress', 'return onlyAlphaNumericPunctuation(event)');
        //el.setAttribute('onkeydown', 'return onlyAlphaNumericPunctuation(event)');
        //el.onkeypress = function(event) { return onlyAlphaNumericPunctuation(event); } ;        
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';


        // Description Cell - Populate the Select Options
        {section loop=$labour_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_prefill_items[i].description}', '{$labour_prefill_items[i].description}');
        {/section}


        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('labour_items['+iteration+'][description]');

        // Description Cell - Set Combobox settings
        combo.setSize(400);    
        combo.DOMelem_input.maxLength = 100;    
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Description Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyAlphaNumericPunctuation(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );
        
        
        
        // Unit QTY Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // Unit QTY Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'labour_items['+iteration+'][unit_qty]');
        el.setAttribute('name', 'labour_items['+iteration+'][unit_qty]');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1.00');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown', 'return onlyNumberPeriod(event)');
        buildRow.appendChild(el);



        // Unit Net Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Unit Net Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_items['+iteration+'][unit_net]');
        el.setAttribute('name', 'labour_items['+iteration+'][unit_net]');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '6');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');  // only required of 'input'
        //el.setAttribute('maxlength', '6');
        //el.required = true;
        //el.setAttribute('onkeydown', 'return onlyNumberPeriod(event)');
        buildRow.appendChild(el);


        // Amount Cell - Populate the Select Options
        {section loop=$labour_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$labour_prefill_items[i].net_amount}', '{$labour_prefill_items[i].net_amount}');
        {/section}

        // Amount Cell - Add some HTML to add the Currency Symbol to the left of the Rate Box      
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';


        // Amount Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('labour_items['+iteration+'][unit_net]');         

        // Amount Cell - Set Combobox settings
        combo.setSize(90);  // This sets the width of the combo box and drop down options width  
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Amount Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyNumberPeriod(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );
        
    
    
        // VAT Tax Code Cell - Create Cell
        var buildRow = row.insertCell(4);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // VAT Tax Code Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'labour_items['+iteration+'][vat_tax_code]');
        el.setAttribute('name', 'labour_items['+iteration+'][vat_tax_code]');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');  // only required of 'input'
        //el.setAttribute('maxlength', '100');
        el.required = true;
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';
        buildRow.appendChild(el);

        // Other key press examples - utested, unused
        //el.setAttribute('onkeypress', 'return onlyAlphaNumericPunctuation(event)');
        //el.setAttribute('onkeydown', 'return onlyAlphaNumericPunctuation(event)');
        //el.onkeypress = function(event) { return onlyAlphaNumericPunctuation(event); } ;        
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';


        // VAT Tax Code Cell  - Populate the Select Options
        {section loop=$vat_tax_codes name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%', '{$vat_tax_codes[i].tax_key}');
            {if $default_vat_tax_code == $vat_tax_codes[i].tax_key}
                el.options[{$smarty.section.i.index}].setAttribute('selected', true);
            {/if}
        {/section}
        
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



        // Description Cell - Create Cell
        var buildRow = row.insertCell(1);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // Description Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'parts_items['+iteration+'][description]');
        el.setAttribute('name', 'parts_items['+iteration+'][description]');    
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');
        //el.setAttribute('maxlength', '100');
        el.required = true;       
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';
        buildRow.appendChild(el);
        
        
        // Description Cell - Populate the Select Options
        {section loop=$parts_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$parts_prefill_items[i].description}', '{$parts_prefill_items[i].description}');
        {/section}


        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('parts_items['+iteration+'][description]');

        // Description Cell - Set Combobox settings
        combo.setSize(400);    
        combo.DOMelem_input.maxLength = 100;    
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Description Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyAlphaNumericPunctuation(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );        



        // Unit QTY Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4'); 

        // Unit QTY Cell - Create Input Box
        var el = document.createElement('input');
        el.setAttribute('id', 'parts_items['+iteration+'][unit_qty]');
        el.setAttribute('name', 'parts_items['+iteration+'][unit_qty]');
        //el.setAttribute('class', 'olotd4');
        el.setAttribute('size', '6');        
        el.setAttribute('value', '1');
        el.setAttribute('type', 'text');
        el.setAttribute('maxlength', '6');
        el.required = true;
        el.setAttribute('onkeydown', 'return onlyNumberPeriod(event)');
        buildRow.appendChild(el);
        


        // Unit Net Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');

        // Unit Net Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'parts_items['+iteration+'][unit_net]');
        el.setAttribute('name', 'parts_items['+iteration+'][unit_net]');
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '10');
        //el.setAttribute('value', '1');
        //el.setAttribute('maxlength', '10');    
        //el.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');        
        //el.required = true;
        //el.setAttribute('onkeydown', 'return onlyNumberPeriod(event)');
        buildRow.appendChild(el);
        
        
        // Unit Net Cell - Populate the Select Options
        {section loop=$parts_prefill_items name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$parts_prefill_items[i].net_amount}', '{$parts_prefill_items[i].net_amount}');
        {/section}

        // Unit Net Cell - Add some HTML to add the Currency Symbol to the left of the Rate Box      
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';
        
        // Unit Net Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('parts_items['+iteration+'][unit_net]');         

        // Unit Net Cell - Set Combobox settings
        combo.setSize(90);  // This sets the width of the combo box and drop down options width  
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;
        combo.setComboText('');                 // by default sets comobobox to empty

        // Amount Cell - Apply Key restriction to the virtual combobox
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {

            if(onlyNumberPeriod(e)) { return true; }

            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );
        
        
        
        // VAT Tax Code Cell - Create Cell
        var buildRow = row.insertCell(4);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');

        // VAT Tax Code Cell - Create Select Input
        var el = document.createElement('select');
        el.setAttribute('id', 'parts_items['+iteration+'][vat_tax_code]');
        el.setAttribute('name', 'parts_items['+iteration+'][vat_tax_code]');    
        //el.setAttribute('class', 'olotd4');
        //el.setAttribute('size', '62');
        //el.setAttribute('value', '1');
        //el.setAttribute('type', 'text');
        //el.setAttribute('maxlength', '100');
        el.required = true;       
        //el.onkeydown = 'return onlyAlphaNumericPunctuation(event)';
        buildRow.appendChild(el);
        
        
        // VAT Tax Code Cell - Populate the Select Options
        {section loop=$vat_tax_codes name=i}
            el.options[{$smarty.section.i.index}] = new Option('{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%', '{$vat_tax_codes[i].tax_key}');
        {if $default_vat_tax_code == $vat_tax_codes[i].tax_key}
            el.options[{$smarty.section.i.index}].setAttribute('selected', true);
        {/if}
        {/section}
        

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
            <form action="index.php?component=invoice&page_tpl=edit&invoice_id={$invoice_id}" method="post" name="new_invoice" id="new_invoice">
                <table width="700" cellpadding="4" cellspacing="0" border="0" >

                    <!-- Title -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Details for{/t} {t}Invoice ID{/t} {$invoice_details.invoice_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <td class="row2"><b>{t}Balance{/t}</b></td>                                                    
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
                                                        <input id="date" name="date" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                        <button type="button" id="date_button">+</button>
                                                        <script>                                                        
                                                            Calendar.setup( {
                                                                trigger     : "date_button",
                                                                inputField  : "date",
                                                                dateFormat  : "{$date_format}"                                                                                            
                                                            } );                                                        
                                                        </script>
                                                    {else}
                                                        {$invoice_details.date|date_format:$date_format}
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if !$display_payments}
                                                        <input id="due_date" name="due_date" class="olotd4" size="10" value="{$invoice_details.due_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                        <button type="button" id="due_date_button">+</button>
                                                        <script>                                                        
                                                           Calendar.setup({
                                                               trigger     : "due_date_button",
                                                               inputField  : "due_date",
                                                               dateFormat  : "{$date_format}"                                                                                            
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
                                                <td>{$currency_sym}{$invoice_details.gross_amount|string_format:"%.2f"}</td>
                                                <td><font color="#cc0000">{$currency_sym}{$invoice_details.balance|string_format:"%.2f"}</font></td>                                                

                                            </tr>                                        
                                            <tr class="olotd4">

                                                <!-- Scope -->
                                                <td colspan="2"><b>{t}Work Order Scope{/t}:</b></td>
                                                <td colspan="6">{if $workorder_details.scope}{$workorder_details.scope}{else}{t}n/a{/t}{/if}</td>

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
                                                <td colspan="3" valign="top" >
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
                                                <td colspan="8" valign="top" align="left">                                                        
                                                    <b>{t}TERMS{/t}:</b> {$client_details.credit_terms}<br>
                                                    <b>{t}Client Discount Rate{/t}:</b>
                                                    {if !$display_payments}
                                                        <input type="text" class="olotd4" size="4" name="discount_rate" value="{$invoice_details.discount_rate|string_format:"%.2f"}"> %<br>
                                                        <b>** {t}Change this if you want to temporarily override the discount rate for this invoice ONLY{/t} **</b>
                                                    {else}                                                        
                                                        {$invoice_details.discount_rate|string_format:"%.2f"} % 
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
                                                    {if $invoice_details.gross_amount > 0 }                                                             
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=invoice&theme=print');">{t}Print HTML{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_pdf&print_content=invoice&theme=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                        <button type="button" onclick="confirmChoice('Are you sure you want to email this invoice to the client?') && $.ajax( { url:'index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=email_pdf&print_content=invoice&theme=print', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=client_envelope&theme=print');">{t}Print Client Envelope{/t}</button>                                            
                                                        <br>
                                                        <br>
                                                    {/if}

                                                    <!-- Add Voucher Button -->
                                                    {if $invoice_details.status == 'pending' || $invoice_details.status == 'unpaid'}  
                                                        <button type="button" onclick="location.href='index.php?component=voucher&page_tpl=new&invoice_id={$invoice_details.invoice_id}';">{t}Add Voucher{/t}</button>
                                                    {/if}

                                                    <!-- Receive Payment Button -->
                                                    {if $invoice_details.status == 'unpaid' || $invoice_details.status == 'partially_paid'}                                                            
                                                        <button type="button" onclick="location.href='index.php?component=payment&page_tpl=new&type=invoice&invoice_id={$invoice_details.invoice_id}';">{t}Receive Payment{/t}</button>
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
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{t}Labour{/t}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    {if $labour_items}
                                                        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                            <tr  class="olotd4">
                                                                <td class="row2"><b>{t}No{/t}</b></td>
                                                                <td class="row2"><b>{t}Description{/t}</b></td>
                                                                <td class="row2" width="12"><b>{t}Unit Qty{/t}</b></td>                                                            
                                                                <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                                <td class="row2"><b>{t}Net{/t}</b></td>
                                                                <td class="row2"><b>{t}VAT Tax Code{/t}</b></td>
                                                                <td class="row2"><b>{t}VAT Rate{/t}</b></td>
                                                                <td class="row2"><b>{t}VAT Applied{/t}</b></td>                                                                
                                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                                                <td class="row2"><b>{t}Actions{/t}</b></td>
                                                            </tr>
                                                            {section name=l loop=$labour_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.q.index+1}</td>
                                                                    <td>{$labour_items[l].description}</td>
                                                                    <td>{$labour_items[l].unit_qty|string_format:"%.2f"}</td>                                                                
                                                                    <td>{$currency_sym}{$labour_items[l].unit_net|string_format:"%.2f"}</td>                                                                                                                                  
                                                                    <td>{$currency_sym}{$labour_items[l].sub_total_net|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        {section name=s loop=$vat_tax_codes}
                                                                            {if $labour_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                        {/section}
                                                                    </td>
                                                                    {if $labour_items[l].vat_tax_code == 'T2'}
                                                                        <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                    {else}
                                                                        <td>{$labour_items[l].vat_rate|string_format:"%.2f"}%</td> 
                                                                        <td>{$currency_sym}{$labour_items[l].sub_total_vat|string_format:"%.2f"}</td>
                                                                    {/if}
                                                                    <td>{$currency_sym}{$labour_items[l].sub_total_gross|string_format:"%.2f"}</td>
                                                                    <td>
                                                                        {if !$display_payments}
                                                                            <a href="index.php?component=invoice&page_tpl=delete_labour&labour_id={$labour_items[l].invoice_labour_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Labour Record? This will permanently remove the record from the database.{/t}');">
                                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Labour Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                            </a>
                                                                        {else}
                                                                            -
                                                                        {/if}
                                                                    </td>
                                                                </tr>
                                                            {/section}
                                                            <tr>
                                                                <td colspan="10" style="text-align:right;">
                                                                    <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                                                        <tr>
                                                                            <td style="text-align:right;"><b>{t}Labour{/t} {t}Totals{/t}</b></td>
                                                                            <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                                                                            <td width="80" align="right">{t}VAT{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_vat|string_format:"%.2f"}</td>
                                                                            <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
                                                                        </tr>
                                                                    </table>  
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        {/if}
                                                        <br>

                                                        <!-- Additional Javascript Labour Table -->
                                                        {if !$display_payments}                                                        
                                                            <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labour_items">
                                                                <tr class="olotd4">
                                                                    <td class="row2" style="width: 50px;"><b>{t}No{/t}</b></td>                                                                    
                                                                    <td class="row2" style="width: 453px;"><b>{t}Description{/t}</b></td>                                                                    
                                                                    <td class="row2" style="width: 66px;"><b>{t}Unit Qty{/t}</b></td>
                                                                    <td class="row2" style="width: 110px;"><b>{t}Unit Net{/t}</b></td>
                                                                    <td class="row2" style="width: 66px;"><b>{t}VAT Tax Code{/t}</b></td>
                                                                </tr>
                                                                <!-- Additional Rows are added here -->
                                                            </table>
                                                            <p>
                                                                <button type="button" onclick="addRowToTableLabour();">{t}Add{/t}</button>
                                                                <button type="button" onclick="removeRowFromTableLabour();">{t}Remove{/t}</button>
                                                            </p>
                                                        {/if}

                                                    </td>
                                                </tr>                                        
                                            </table>
                                        </td>
                                    </tr>

                                    <!-- Parts Items -->
                                    <tr>
                                        <td>
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                                <tr>
                                                    <td class="menuhead2">&nbsp;{t}Parts{/t}</td>
                                                </tr>
                                                <tr>
                                                    <td class="menutd2">
                                                        {if $parts_items}
                                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                                <tr class="olotd4">
                                                                    <td class="row2"><b>{t}No{/t}</b></td>
                                                                    <td class="row2"><b>{t}Description{/t}</b></td>
                                                                    <td class="row2" width="12"><b>{t}Unit Qty{/t}</b></td>                                                            
                                                                    <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                                    <td class="row2"><b>{t}Net{/t}</b></td>
                                                                    <td class="row2"><b>{t}VAT Tax Code{/t}</b></td>
                                                                    <td class="row2"><b>{t}VAT Rate{/t}</b></td>
                                                                    <td class="row2"><b>{t}VAT Applied{/t}</b></td>
                                                                    <td class="row2"><b>{t}Gross{/t}</b></td>
                                                                    <td class="row2"><b>{t}Actions{/t}</b></td>
                                                                </tr>
                                                                {section name=p loop=$parts_items}
                                                                    <tr class="olotd4">
                                                                        <td>{$smarty.section.w.index+1}</td>
                                                                        <td>{$parts_items[p].description}</td>
                                                                        <td>{$parts_items[p].unit_qty|string_format:"%.2f"}</td>                                                                
                                                                        <td>{$currency_sym}{$parts_items[p].unit_net|string_format:"%.2f"}</td>                                                                        
                                                                        <td>{$currency_sym}{$parts_items[p].sub_total_net|string_format:"%.2f"}</td>
                                                                        <td>
                                                                            {section name=s loop=$vat_tax_codes}
                                                                                {if $parts_items[p].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                            {/section}
                                                                        </td>
                                                                        {if $parts_items[p].vat_tax_code == 'T2'}
                                                                            <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                        {else}                            
                                                                            <td>{$parts_items[p].vat_rate|string_format:"%.2f"}%</td>                    
                                                                            <td>{$currency_sym}{$parts_items[p].sub_total_vat|string_format:"%.2f"}</td>
                                                                        {/if} 
                                                                        <td>{$currency_sym}{$parts_items[p].sub_total_gross|string_format:"%.2f"}</td>
                                                                        <td>
                                                                            {if !$display_payments}
                                                                            <a href="index.php?component=invoice&page_tpl=delete_parts&parts_id={$parts_items[p].invoice_parts_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Parts Record? This will permanently remove the record from the database.{/t}');">
                                                                                <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Parts Record{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                            </a>
                                                                        {else}
                                                                            -
                                                                        {/if}
                                                                        </td>
                                                                    </tr>
                                                                 {/section}
                                                                <tr>
                                                                    <td colspan="10" style="text-align:right;">
                                                                        <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                                                            <tr>
                                                                                <td style="text-align:right;"><b>{t}Parts{/t} {t}Totals{/t}</b></td>
                                                                                <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                                                                                <td width="80" align="right">{t}VAT{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_vat|string_format:"%.2f"}</td>
                                                                                <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
                                                                            </tr>
                                                                        </table>  
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        {/if}                              
                                                        <br>

                                                        <!-- Additional Javascript Parts Table -->
                                                        {if !$display_payments}                                                    
                                                            <table id="parts_items" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                                <tr class="olotd4">
                                                                    <td class="row2" style="width: 50px;"><b>{t}No{/t}</b></td>                                                                   
                                                                    <td class="row2" style="width: 453px;"><b>{t}Description{/t}</b></td>                                                                    
                                                                    <td class="row2" style="width: 66px;"><b>{t}Unit Qty{/t}</b></td>
                                                                    <td class="row2" style="width: 110px;"><b>{t}Unit Net{/t}</b></td>
                                                                    <td class="row2" style="width: 66px;"><b>{t}VAT Tax Code{/t}</b></td>
                                                                </tr>
                                                                <!-- Additional Rows are added here -->
                                                            </table>
                                                            <p>
                                                                <button type="button" onclick="addRowToTableParts();">{t}Add{/t}</button>
                                                                <button type="button" onclick="removeRowFromTableParts();">{t}Remove{/t}</button>
                                                            </p>
                                                        {/if}

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                
                                    <!-- Vouchers -->                                
                                    <tr>
                                        <td>                                                
                                            {include file='voucher/blocks/display_vouchers_block.tpl' display_vouchers=$display_vouchers block_title=_gettext("Vouchers")}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="text-align:right;"><b>{t}Vouchers{/t} {t}Total{/t}</b> {$currency_sym}{$vouchers_items_sub_total|string_format:"%.2f"}</td>                                    
                                    </tr>

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
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$labour_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Parts{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$parts_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ {$invoice_details.discount_rate|string_format:"%.2f"}%)</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.discount_amount|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Vouchers{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$vouchers_items_sub_total|string_format:"%.2f"}</td>
                                                                    </tr>                                                                    
                                                                    {if $invoice_details.tax_system != 'none'}
                                                                        <tr>
                                                                            <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.net_amount|string_format:"%.2f"}</td>
                                                                        </tr>
                                                                        <tr>                                                            
                                                                            <td class="olotd4" width="80%" align="right"><b>{if $invoice_details.tax_system == 'vat_standard' || $invoice_details.tax_system == 'vat_flat' || $company_details.tax_system != 'vat_cash'}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.tax_amount|string_format:"%.2f"}</td>                                                            
                                                                        </tr>
                                                                    {/if}                                                                     
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.gross_amount|string_format:"%.2f"}</td>
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