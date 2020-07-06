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
    
    // Dynamically Copy, Process and add an new Table Row
    function createNewTableRow(section) {
        
        // Get Table + info for refactoring
        var tbl = document.getElementById(section+'_items');
        
        // Get Next Row Number
        var iteration = tbl.rows.length - 1; 
        
        // Clone Dummy Row        
        var clonedRow = $('#'+section+'_items_row_dummy').clone();
                
        // Get the outerHTML
        var clonedRowStr = clonedRow.prop("outerHTML");   
                
        // Refactor variables
        clonedRowStr = clonedRowStr.replace(/ hidden/, "");
        clonedRowStr = clonedRowStr.replace(/ disabled/g, "");
        clonedRowStr = clonedRowStr.replace(/dummy/g, iteration);        
        
        // Append the row to the end of the table
        $(tbl).append(clonedRowStr);
        
        // Convert description cell into a combobox
        var combo = dhtmlXComboFromSelect('qform['+section+'_items]['+iteration+'][description]');
        //combo.setSize(400);    
        combo.DOMelem_input.maxLength = 100;    
        combo.DOMelem_input.required = true;
        combo.setComboText('');
        combo.setFontSize("10px","10px");
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {
            if(onlyAlphaNumericPunctuation(e)) { return true; }
            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } ); 
                
        // Unit Net Cell        
        var combo = dhtmlXComboFromSelect('qform['+section+'_items]['+iteration+'][unit_net]');
        combo.setSize(90);
        combo.DOMelem_input.maxLength = 10;
        combo.DOMelem_input.setAttribute('pattern', '{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}');
        combo.DOMelem_input.required = true;
        combo.setComboText('');
        combo.setFontSize("10px","10px");
        dhtmlxEvent(combo.DOMelem_input, "keypress", function(e) {
            if(onlyNumberPeriod(e)) { return true; }
            e.cancelBubble=true;
            if (e.preventDefault) e.preventDefault();
                return false;
        } );
                 
    }
    
    // Populater Labour and Parts Tables with records form the database
    function populateTables() {
        
        // read variable in the html
        // labour/parts sepearate  : cycle thourhg each record and run the fucntion above to create a new row and populate it
        // delete the erroneous variable
    }

    function oonanychange() {
     
        //when any of the input feilds are change recalculate all of the totals
        // if htere are changes disable all buttons until the save is hit which will casue the page to realod and the falg to be removed. perhaps also grey out the buttons and add hover over messages
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
                                                        <input id="date" name="qform[date]" class="olotd4" size="10" value="{$invoice_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                        <input id="due_date" name="qform[due_date]" class="olotd4" size="10" value="{$invoice_details.due_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                <td>{$currency_sym}{$invoice_details.unit_gross|string_format:"%.2f"}</td>
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
                                                        <input type="text" class="olotd4" size="4" name="qform[unit_discount_rate]" value="{$invoice_details.unit_discount_rate|string_format:"%.2f"}"> %<br>
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
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=invoice&themeVar=print');">{t}Print HTML{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_pdf&print_content=invoice&themeVar=print');"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Print PDF{/t}</button>
                                                        <button type="button" onclick="confirmChoice('Are you sure you want to email this invoice to the client?') && $.ajax( { url:'index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=email_pdf&print_content=invoice&themeVar=print', success: function(data) { $('body').append(data); } } );"><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">{t}Email PDF{/t}</button>
                                                        <button type="button" onclick="window.open('index.php?component=invoice&page_tpl=print&invoice_id={$invoice_details.invoice_id}&print_type=print_html&print_content=client_envelope&themeVar=print');">{t}Print Client Envelope{/t}</button>                                            
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
                                                                {if $invoice_details.tax_system != 'no_tax'}
                                                                    <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                                {else}
                                                                    <td class="row2"><b>{t}Unit Gross{/t}</b></td> 
                                                                {/if}                                                                 
                                                                {if $invoice_details.tax_system != 'no_tax'}
                                                                    <td class="row2"><b>{t}Net{/t}</b></td>
                                                                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="row2"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                    <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                                    <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                                {/if}
                                                                <td class="row2"><b>{t}Gross{/t}</b></td>
                                                                <td class="row2"><b>{t}Actions{/t}</b></td>                                                                
                                                            </tr>
                                                            {section name=l loop=$labour_items}
                                                                <tr class="olotd4">
                                                                    <td>{$smarty.section.q.index+1}</td>
                                                                    <td>{$labour_items[l].description}</td>
                                                                    <td>{$labour_items[l].unit_qty|string_format:"%.2f"}</td>                                                                
                                                                    <td>{$currency_sym}{$labour_items[l].unit_net|string_format:"%.2f"}</td>                                                                      
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <td>{$currency_sym}{$labour_items[l].sub_total_net|string_format:"%.2f"}</td>                                                                        
                                                                        {if $labour_items[l].sales_tax_exempt}
                                                                            <td colspan="2" align="center">{t}Exempt{/t}</td>
                                                                        {elseif $labour_items[l].vat_tax_code == 'T2'}
                                                                            <td colspan="3" align="center">{t}Exempt{/t}</td>
                                                                        {else}    
                                                                            {if '/^vat_/'|preg_match:$invoice_details.tax_system}
                                                                                <td>
                                                                                    {section name=s loop=$vat_tax_codes}
                                                                                        {if $labour_items[l].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                                    {/section}
                                                                                </td>                                                                               
                                                                            {/if}
                                                                            <td>{$labour_items[l].unit_tax_rate|string_format:"%.2f"}%</td> 
                                                                            <td>{$currency_sym}{$labour_items[l].sub_total_tax|string_format:"%.2f"}</td>
                                                                        {/if}
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
                                                                            {if $invoice_details.tax_system != 'no_tax'}
                                                                                <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$labour_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>                                                                            
                                                                                <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$labour_items_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                                                                            {/if}
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
                                                                    <td class="row2" style="width: 453px;"><b>{t}Description{/t}</b></td>                                                                    
                                                                    <td class="row2" style="width: 66px;"><b>{t}Unit Qty{/t}</b></td>                                                                    
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <td class="row2" style="width: 110px;"><b>{t}Unit Net{/t}</b></td>
                                                                    {else}
                                                                        <td class="row2" style="width: 110px;"><b>{t}Unit Gross{/t}</b></td>
                                                                    {/if}                                                                     
                                                                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="row2" style="width: 66px;"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                    {if $invoice_details.tax_system == 'sales_tax_cash'}<td class="row2" style="width: 110px;"><b>{t}Exempt from Sales Tax{/t}</b></td>{/if}
                                                                    <td class="row2" style="width: 110px;"><b>{t}Actions{/t}</b></td>
                                                                </tr>
                                                                
                                                                <!-- Labour Items Dummy Row -->
                                                                <tr id="labour_items_row_dummy" class="olotd4" hidden>
                                                                    <!-- Description -->
                                                                    <td>                                                                        
                                                                        <select style='width:200px;' id="qform[labour_items][dummy][description]" name="qform[labour_items][dummy][description]" value="" disabled>
                                                                            {section loop=$labour_prefill_items name=i}
                                                                                <option value="{$labour_prefill_items[i].description}">{$labour_prefill_items[i].description}</option>
                                                                            {/section}                                                                            
                                                                        </select>
                                                                    </td>
                                                                    <!-- Unit Qty -->
                                                                    <td>
                                                                        <input id="qform[labour_items][dummy][unit_qty]" name="qform[labour_items][dummy][unit_qty]" size="6" value="1.00" type="text" maxlength="6" required onkeydown="return onlyNumberPeriod(event);" disabled>                                                                        
                                                                    </td>
                                                                    <!-- Unit Net -->
                                                                    <td>
                                                                        <b>£&nbsp;</b>
                                                                        <select id="qform[labour_items][dummy][unit_net]" name="qform[labour_items][dummy][unit_net]" value="" required disabled>
                                                                        {section loop=$labour_prefill_items name=i}
                                                                            <option value="{$labour_prefill_items[i].unit_net}">{$labour_prefill_items[i].unit_net}</option>
                                                                        {/section}
                                                                    </td>
                                                                    <!-- VAT Tax Code -->
                                                                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}  
                                                                        <td>
                                                                            <select id="qform[labour_items][dummy][vat_tax_code]" name="qform[labour_items][dummy][vat_tax_code]" required disabled>                                                                            
                                                                                {section loop=$vat_tax_codes name=i}
                                                                                    <option value="{$vat_tax_codes[i].tax_key}"{if $default_vat_tax_code == $vat_tax_codes[i].tax_key} selected{/if}>{$vat_tax_codes[i].tax_key} - {$vat_tax_codes[i].display_name} @ {$vat_tax_codes[i].rate|string_format:"%.2f"}%</option>
                                                                                {/section}                                                                            
                                                                            </select>
                                                                        </td>
                                                                    {/if}
                                                                    <!-- Sales Tax Exempt -->
                                                                    {if $invoice_details.tax_system == 'sales_tax_cash'}  
                                                                        <td>
                                                                            <input id="qform[labour_items][dummy][sales_tax_exempt]" name="qform[labour_items][dummy][sales_tax_exempt]" type="checkbox">
                                                                        </td>
                                                                    {/if}
                                                                    <!-- Actions -->
                                                                    <td>                                                                       
                                                                        <img src="/projects/qwcrm/src/themes/default/images/icons/delete.gif" alt="" border="0" height="14" width="14" onmouseover="ddrivetip('<b>Delete Labour Record</b>');" onmouseout="hideddrivetip();" onclick="return confirmChoice('Are you Sure you want to delete this Labour Record?') && $(this).parent().parent().remove();">
                                                                    </td>
                                                                </tr>
                                                                
                                                                <!-- Additional Rows are added here -->
                                                                
                                                            </table>
                                                            <p>                                                                
                                                                <button type="button" onclick="createNewTableRow('labour');">{t}Add{/t}</button>                                                                
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
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <td class="row2"><b>{t}Unit Net{/t}</b></td>
                                                                    {else}
                                                                        <td class="row2"><b>{t}Unit Gross{/t}</b></td> 
                                                                    {/if}                                                                      
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <td class="row2"><b>{t}Net{/t}</b></td>
                                                                        {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="row2"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                        <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                                        <td class="row2"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                                    {/if}
                                                                    <td class="row2"><b>{t}Gross{/t}</b></td>
                                                                    <td class="row2"><b>{t}Actions{/t}</b></td>                                                                    
                                                                </tr>
                                                                {section name=p loop=$parts_items}
                                                                    <tr class="olotd4">
                                                                        <td>{$smarty.section.w.index+1}</td>
                                                                        <td>{$parts_items[p].description}</td>
                                                                        <td>{$parts_items[p].unit_qty|string_format:"%.2f"}</td>                                                                
                                                                        <td>{$currency_sym}{$parts_items[p].unit_net|string_format:"%.2f"}</td>                                                                         
                                                                        {if $invoice_details.tax_system != 'no_tax'}
                                                                            <td>{$currency_sym}{$parts_items[p].sub_total_net|string_format:"%.2f"}</td>                                                                            
                                                                            {if $parts_items[p].sales_tax_exempt}
                                                                                <td colspan="2" align="center">{t}Exempt{/t}</td> 
                                                                            {elseif $parts_items[p].vat_tax_code == 'T2'}
                                                                                <td colspan="3" align="center">{t}Exempt{/t}</td>
                                                                            {else}
                                                                                {if '/^vat_/'|preg_match:$invoice_details.tax_system}
                                                                                    <td>
                                                                                        {section name=s loop=$vat_tax_codes}
                                                                                            {if $parts_items[p].vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                                                        {/section}
                                                                                    </td>                                                                                  
                                                                                {/if}
                                                                                <td>{$parts_items[p].unit_tax_rate|string_format:"%.2f"}%</td> 
                                                                                <td>{$currency_sym}{$parts_items[p].sub_total_tax|string_format:"%.2f"}</td>                                                                                
                                                                            {/if}
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
                                                                                {if $invoice_details.tax_system != 'no_tax'}
                                                                                    <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$parts_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>                                                                                
                                                                                    <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$parts_items_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                                                                                {/if}
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
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <td class="row2" style="width: 110px;"><b>{t}Unit Net{/t}</b></td>
                                                                    {else}
                                                                        <td class="row2" style="width: 110px;"><b>{t}Unit Gross{/t}</b></td>
                                                                    {/if}
                                                                    {if $invoice_details.tax_system == 'sales_tax_cash'}<td class="row2" style="width: 110px;"><b>{t}Exempt from Sales Tax{/t}</b></td>{/if}
                                                                    {if '/^vat_/'|preg_match:$invoice_details.tax_system}<td class="row2" style="width: 66px;"><b>{t}VAT Tax Code{/t}</b></td>{/if}
                                                                </tr>
                                                                <!-- Additional Rows are added here -->
                                                                
                                                                {* temp code
                                                                <tr class="olotd4">
                                                                    <td>1</td>
                                                                    <td>
                                                                        <span>
                                                                            <div style="width: 398px;" class="dhxcombo_dhx_skyblue">
                                                                                <input type="text" class="dhxcombo_input" style="width: 374px; margin-left: 0px;" autocomplete="off" maxlength="100" required="">
                                                                                <input type="hidden" value="" name="qform[parts_items][1][description]">
                                                                                <input type="hidden" value="true" name="qform_new_value[parts_items][1][description]">
                                                                                <div class="dhxcombo_select_button"><div class="dhxcombo_select_img"></div></div>                                                                                    
                                                                            </div>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <input id="qform[parts_items][1][unit_qty]" name="qform[parts_items][1][unit_qty]" size="6" value="1.00" type="text" maxlength="6" required="" onkeydown="return onlyNumberPeriod(event);">
                                                                    </td>
                                                                    <td>
                                                                        <div style="float:left;"><b>£&nbsp;</b></div>
                                                                        <div>
                                                                            <span>
                                                                                <div style="width: 88px;" class="dhxcombo_dhx_skyblue"><input type="text" class="dhxcombo_input" style="width: 64px; margin-left: 0px;" autocomplete="off" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required="">
                                                                                    <input type="hidden" value="" name="qform[parts_items][1][unit_net]">
                                                                                    <input type="hidden" value="true" name="qform_new_value[parts_items][1][unit_net]">
                                                                                    <div class="dhxcombo_select_button"><div class="dhxcombo_select_img"></div></div>                                                                                        
                                                                                </div>
                                                                            </span>                                                                                
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <select id="qform[parts_items][1][vat_tax_code]" name="qform[parts_items][1][vat_tax_code]" required="">
                                                                            <option value="T0">T0 - Zero Rate @ 0.00%</option>
                                                                            <option value="T1" selected="true">T1 - Standard Rate @ 20.00%</option>
                                                                            <option value="T2">T2 - Exempt @ 0.00%</option>
                                                                            <option value="T4">T4 - Sales - Goods - EC VAT Customers @ 0.00%</option>
                                                                            <option value="T5">T5 - Reduced Rate @ 5.00%</option>
                                                                            <option value="T7">T7 - Zero Rate Purchases - Goods - EC @ 0.00%</option>
                                                                            <option value="T8">T8 - Standard Rate Purchases - Goods - EC @ 0.00%</option>
                                                                            <option value="T9">T9 - Transactions not involving VAT @ 0.00%</option>
                                                                            <option value="T20">T20 - Reverse Charges @ 0.00%</option>
                                                                            <option value="T22">T22 - Sales - Services - EC VAT Customers @ 0.00%</option>
                                                                            <option value="T23">T23 - Zero Rate / Exempt Purchases - Services - EC @ 0.00%</option>
                                                                            <option value="T24">T24 - Standard Rate Purchases - Services - EC @ 0.00%</option>
                                                                            <option value="T25">T25 - Flat Rate Capital Asset @ 0.00%</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                *}
                                                                
                                                                
                                                                
                                                                
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
                                        <td colspan="10" style="text-align:right;">
                                            <table style="margin-top: 10px;" width="750" cellpadding="3" cellspacing="0" style="border-collapse: collapse;" align="right">
                                                <tr>
                                                    <td style="text-align:right;"><b>{t}Voucher{/t} {t}Totals{/t}</b></td>
                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                        <td width="80" align="right">{t}Net{/t}: {$currency_sym}{$voucher_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>                                                    
                                                        <td width="80" align="right">{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}: {$currency_sym}{$voucher_items_sub_totals.sub_total_tax|string_format:"%.2f"}</td>
                                                    {/if}
                                                    <td width="80" align="right">{t}Gross{/t}: {$currency_sym}{$voucher_items_sub_totals.sub_total_gross|string_format:"%.2f"}</td>
                                                </tr>
                                            </table>  
                                        </td>
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
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Discount{/t} (@ {$invoice_details.unit_discount_rate|string_format:"%.2f"}%)</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_discount|string_format:"%.2f"}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Vouchers{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$voucher_items_sub_totals.sub_total_net|string_format:"%.2f"}</td>
                                                                    </tr> 
                                                                    {if $invoice_details.tax_system != 'no_tax'}
                                                                        <tr>
                                                                            <td class="olotd4" width="80%" align="right"><b>{t}Net{/t}</b></td>
                                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_net|string_format:"%.2f"}</td>
                                                                        </tr>                                                                    
                                                                        <tr>                                                            
                                                                            <td class="olotd4" width="80%" align="right"><b>{if '/^vat_/'|preg_match:$invoice_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$invoice_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
                                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_tax|string_format:"%.2f"}</td>                                                            
                                                                        </tr>
                                                                    {/if}                                                                     
                                                                    <tr>
                                                                        <td class="olotd4" width="80%" align="right"><b>{t}Gross{/t}</b></td>
                                                                        <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice_details.unit_gross|string_format:"%.2f"}</td>
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