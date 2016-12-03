<!-- new.tpl -->

{include file="invoice/javascripts.js"}

<link rel="stylesheet" type="text/css" media="all" href="includes/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="includes/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="includes/jscalendar/calendar-setup_stripped.js"></script>

<script src="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.js"></script>
<!--<link rel="stylesheet" href="{$theme_js_dir}dhtmlxcombo/fonts/font_roboto/roboto.css"/>-->
<link rel="stylesheet" href="{$theme_js_dir}dhtmlxcombo/dhtmlxcombo.css">

<script>

    {literal}
 
// LABOUR    
    
    //// Add Row to Labour Table    
    function addRowToTableLabor(){
        var tbl = document.getElementById('labor');
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
        el.setAttribute('type', 'text');
        el.setAttribute('name', 'labour_hour['+iteration+']');
        el.setAttribute('id', 'labour_hour['+iteration+']');
        el.setAttribute('size', '4');        
        el.setAttribute('value', '1');
        buildRow.appendChild(el);
        
        

        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '300px');
        //buildRow.setAttribute('class', 'olotd4');
        
        // Description Cell - Create Select Input
        var el = document.createElement('select');        
        el.setAttribute('name', 'labour_description['+iteration+']');
        el.setAttribute('id', 'labour_description['+iteration+']');
        //el.setAttribute('size', '100');        
        buildRow.appendChild(el);
        {/literal}
                
        // Description Cell - Populate the Select Options
        {section loop=$rate name=i}
        el.options[{$smarty.section.i.index}] = new Option('{$rate[i].LABOR_RATE_NAME} - {$currency_sym}{$rate[i].LABOR_RATE_AMOUNT}', '{$rate[i].LABOR_RATE_NAME}');
        {/section}
            
        {literal} 
        // Description Cell - Convert Select Input to a real Combo Box using dhtmlxcombo
        var combo = dhtmlXComboFromSelect('labour_description['+iteration+']');                
        combo.setSize(400); // This sets the width of the combo box and drop down options width        
        
        

        // Rate Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');
        
        // Rate Cell - Create Select Input
        var el = document.createElement('select');        
        el.setAttribute('name', 'labour_rate['+iteration+']');
        el.setAttribute('id', 'labour_rate['+iteration+']');
        el.setAttribute('size', '50');        
        buildRow.appendChild(el);
        {/literal}
            
        // Rate Cell - Populate the Select Options
        {section loop=$rate name=i}
        el.options[{$smarty.section.i.index}] = new Option('${$rate[i].LABOR_RATE_AMOUNT}', '{$rate[i].LABOR_RATE_AMOUNT}');
        {/section}
             
        // Rate Cell - Add some HTML to add the Currency Symbol        
        buildRow.innerHTML = '<div style="float:left;"><b>{$currency_sym}&nbsp;</b></div><div>' + buildRow.innerHTML + '</div>';
        
        {literal}            
        // Rate Cell - Convert Select Input to a real Combo Box using dhtmlxcombo - Run after adding currency symbol to the cell otherwise it does not work
        var combo = dhtmlXComboFromSelect('labour_rate['+iteration+']');
        combo.setSize(90);  // This sets the width of the combo box and drop down options width        

    }

    //// Remove row from Labour tab;e
    function removeRowFromTableLabor(){
        var tbl = document.getElementById('labor');
        var lastRow = tbl.rows.length;
        if (lastRow > 1) tbl.deleteRow(lastRow - 1);
    }
    
    //// Validate Labour Data?
    function validateRowLabor(frm){
        var chkb = document.getElementById('chkValidate');
        if (chkb.checked) {
            var tbl = document.getElementById('labor');
            var lastRow = tbl.rows.length - 1;
            var i;
            for (i=1; i<=lastRow; i++) {
                var aRow = document.getElementById('txtRow' + i);
                if (aRow.value.length <= 0) {
                    alert('Row ' + i + ' is empty');
                    return;
                }
            }
        }
        openInNewWindow(frm);
    }



// PARTS



    //// Add Row to Parts Table
    function addRowToTableParts(){
        var tbl = document.getElementById('parts');
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
        el.setAttribute('type', 'text');
        el.setAttribute('name', 'parts_qty['+iteration+']');
        el.setAttribute('id', 'parts_qty['+iteration+']');
        el.setAttribute('size', '4');        
        el.setAttribute('value', '1');
        buildRow.appendChild(el);

        // Description Cell - Create Cell
        var buildRow = row.insertCell(2);        
        //buildRow.setAttribute('width', '100px');
        //buildRow.setAttribute('class', 'olotd4');
        
        // Description Cell - Create Select Input
        var el = document.createElement('input');        
        el.setAttribute('name', 'parts_description['+iteration+']');
        el.setAttribute('id', 'parts_description['+iteration+']');
        el.setAttribute('size', '62');        
        buildRow.appendChild(el);
        //el.onkeypress = keyPressTestLabor;

        // Price Cell - Create Cell
        var buildRow = row.insertCell(3);        
        //buildRow.setAttribute('width', '40px');
        //buildRow.setAttribute('class', 'olotd4');
        
        // Price Cell - Create Select Input
        var el = document.createElement('input');        
        el.setAttribute('name', 'parts_price['+iteration+']');
        el.setAttribute('id', 'parts_price['+iteration+']');
        el.setAttribute('size', '10');        
        buildRow.appendChild(el);
        
        {/literal}
        // Price Cell - Add some HTML to add the Currency Symbol        
        buildRow.innerHTML = '<b>{$currency_sym}&nbsp;</b>' + buildRow.innerHTML;
        {literal}
    }


    //// Remove row from Parts table
    function removeRowFromTableParts(){
        var tbl = document.getElementById('parts');
        var lastRow = tbl.rows.length;
        if (lastRow > 1) tbl.deleteRow(lastRow - 1);
    }

    //// Validate Parts Data?
    function validateRowParts(frm){
        var tbl = document.getElementById('parts');
        var lastRow = tbl.rows.length - 1;
        var i;
        for (i=1; i<=lastRow; i++) {
            var aRow = document.getElementById('txtRow' + i);
            if (aRow.value.length <= 0) {
                alert('Row ' + i + ' is empty');
                return;
            }
        }
    }
    
// OTHER
    
    function keyPressTestLabor(e, obj){
        var validateChkb = document.getElementById('chkValidateOnKeyPress');
        if (validateChkb.checked) {
            var displayObj = document.getElementById('spanOutput');
            var key;
            if(window.event) {
                key = window.event.keyCode;
            }
            else if(e.which) {
                key = e.which;
            }
            var objId;
            if (obj != null) {
                objId = obj.id;
            } else {
                objId = this.id;
            }
            displayObj.innerHTML = objId + ' : ' + String.fromCharCode(key);
        }
    }
    
    function keyPressTestParts(e, obj){

    }    
</script>
{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_invoice_for}{$wo_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt=""
                                    onMouseOver="ddrivetip('<b>{$translate_invoice_new_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_invoice_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                    onMouseOut="hideddrivetip()"></a>
                    </td>
                </tr><tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    {if $error_msg != ""}
                                    <br>
                                        {include file="core/error.tpl"}
                                    <br>
                                    {/if}
                                    <!-- Content -->




                                {literal}
                                    <form action="index.php?page=invoice:new" method="POST" name="new_invoice" id="new_invoice" onsubmit="try { var myValidator = validate_new_invoice; } catch(e) { return true; } return myValidator(this);">
                                {/literal}
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{$translate_invoice_id}</b></td>
                                                <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                <td class="row2"><b>{$translate_invoice_due}</b></td>
                                                <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                <td class="row2"><b>{$translate_invoice_tech}</b></td>
                                                <td class="row2"><b>{$translate_invoice_work_order}</b></td>
                                                <td class="row2"><b>{$translate_invoice_balance}</b></td>
                                            </tr><tr class="olotd4">

                                                <td>{$invoice.INVOICE_ID}</td>
                                                <td>
                                                    <input size="10" name="date" type="text" id="date" value="{$invoice.INVOICE_DATE|date_format:$date_format}" class="olotd4"/>
                                                           <input type="button" id="trigger_date" value="+">
                                             {literal}
                                                    <script type="text/javascript">
                                                        Calendar.setup(
                                                        {
                                                            inputField  : "date",
                                                            ifFormat    : "{/literal}{$date_format}{literal}",
                                                            button      : "trigger_date"
                                                        }
                                                    );
                                                    </script>
                                            {/literal}
                                                </td>
                                                <td>{$item.INVOICE_DUE}
                                                    <input size="10" name="due_date" type="text" id="due_date" value="{$invoice.INVOICE_DUE|date_format:$date_format}" class="olotd4"/>
                                                           <input type="button" id="trigger_due_date" value="+">
                                             {literal}
                                                    <script type="text/javascript">
                                                        Calendar.setup(
                                                        {
                                                            inputField  : "due_date",
                                                            ifFormat    : "{/literal}{$date_format}{literal}",
                                                            button      : "trigger_due_date"
                                                        }
                                                    );
                                                    </script>
                                            {/literal}
                                                </td>
                                                <td>{$currency_sym}{$invoice.INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                                <td>{$invoice.EMPLOYEE_DISPLAY_NAME}</td>
                                                <td><a href="?page=workorder:details&wo_id={$invoice.WORKORDER_ID}&page_title={$translate_invoice_wo_id} {$invoice.WORKORDER_ID}">{$invoice.WORKORDER_ID}</a></td>
                                                <td><font color="#CC0000">{$currency_sym}{$invoice.BALANCE|string_format:"%.2f"}</font>
                                                </td>

                                            </tr><tr>
                                                <td colspan="3" valign="top" align="left">
                                                    <b>{$translate_invoice_bill}</b>
                                        {foreach item=item from=$customer_details}
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td valign="top">
                                                                <a href="?page=customer:customer_details&customer_id={$item.CUSTOMER_ID}&page_title={$item.CUSTOMER_DISPLAY_NAME}">{$item.CUSTOMER_DISPLAY_NAME}</a><br>
                                                        {$item.CUSTOMER_ADDRESS|nl2br}<br>
                                                        {$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}<br>
                                                        {$item.CUSTOMER_PHONE}<br>
                                                        {$item.CUSTOMER_EMAIL}<br>
                                                        <br>
                                                        TERMS: <FONT color="red" size="+1">{$item.CREDIT_TERMS}</FONT><br><br><br>
                                                        Current customer discount rate is :
                                                                <input type="hidden" name="customer_id" value="{$item.CUSTOMER_ID}">
                                                                <input type="text" class="olotd4" size="4" name="discount" value="{$item.DISCOUNT}"> % <br>
                                                                <b>**Change this if you want to temporarily override the discount rate for this invoice ONLY **</b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                            {/foreach}
                                                </td>
                                                <td colspan="4" valign="top" >
                                                    <b>{$translate_invoice_pay}</b>
                                                    <table cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td valign="top">
                                                        {section name=x loop=$company}
                                                            {$company[x].COMPANY_NAME} <br>
                                                            {$company[x].COMPANY_ADDRESS}<br>
                                                            {$company[x].COMPANY_CITY}, {$company[x].COMPANY_STATE} {$company[x].COMPANY_ZIP}<br>
                                                            {$company[x].COMPANY_PHONE}<br>

                                                        {/section}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <br>
                                {if $invoice.balance > 0}
                                        <!-- Trans Log -->
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" id="trans_log">
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_trans_log}</td>
                                            </tr><tr>
                                                <td class="menutd2">
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_trans_id}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_date}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_amount}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_type}</b></td>
                                                        </tr>
                                                {section name=r loop=$trans}
                                                        <tr class="olotd4">
                                                            <td>{$trans[r].TRANSACTION_ID}</td>
                                                            <td>{$trans[r].DATE|date_format:"$date_format"}</td>
                                                            <td><b>$</b>{$trans[r].AMOUNT|string_format:"%.2f"}</td>
                                                            <td>
                                                    {if $trans[r].TYPE == 1}
                                                        {$translate_invoice_cc}
                                                    {elseif $trans[r].TYPE == 2}
                                                        {$translate_invoice_check}
                                                    {elseif $trans[r].TYPE == 3}
                                                        {$translate_invoice_cash}
                                                    {elseif $trans[r].TYPE == 4}
                                                        {$translate_invoice_gift}
                                                    {elseif $trans[r].TYPE == 5}
                                                        {$translate_invoice_paypal}
                                                    {/if}
                                                            </td>
                                                        </tr><tr class="olotd4">
                                                            <td><b>{$translate_invoice_memo}</b></td>
                                                            <td colspan="3">{$trans[r].MEMO}</td>
                                                        </tr>
                                                {/section}
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                {/if}

                                {if $invoice.INVOICE_AMOUNT > '' }
                                        <p>
                     {if $wo_status == '6'}
                                            <button type="button" name="pdf" OnClick=window.open('?page=invoice:pdf&wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&theme=off')><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf"> {$translate_invoice_pdf}</button>
                     {/if}
                     {if $wo_status == '9' || $wo_id == '0'}
                                            <button type="button" name="{$translate_invoice_print}" onClick=window.open('?page=invoice:print&amp;print_type=html&amp;wo_id={$invoice.WORKORDER_ID}&amp;customer_id={$invoice.CUSTOMER_ID}&amp;invoice_id={$invoice.INVOICE_ID}&amp;theme=off')>{$translate_invoice_print}</button>
                                            <button type="button" name="{$translate_invoice_pdf}" OnClick=window.open('?page=invoice:print&amp;print_type=pdf&amp;wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&theme=off')><img src="{$theme_images_dir}icons/pdf_small.png"  height="14" alt="pdf">&nbsp;{$translate_invoice_pdf}</button>
                                            <button type="button" name="{$translate_invoice_bill_customer}" OnClick=location.href='?page=billing:new&wo_id={$invoice.WORKORDER_ID}&customer_id={$invoice.CUSTOMER_ID}&invoice_id={$invoice.INVOICE_ID}&page_title=Receiving%20Payment%20for%20{$invoice.INVOICE_ID}'>{$translate_invoice_bill_customer}</button>
                         {if $invoice.INVOICE_AMOUNT == 0 || "" }
                                            <button type="button" name="{$translate_invoice_delete}" OnClick=location.href='?page=invoice:delete&amp;customer_id={$invoice.CUSTOMER_ID}&amp;invoice_id={$invoice.INVOICE_ID}&amp;page_title=Deleting&nbsp;Invoice&nbsp;-{$invoice.INVOICE_ID}'>{$translate_invoice_delete}</button>
                         {/if}
                                               {else}
                                            <button type="button" name="Close Work Order" OnClick=location.href='?page=workorder:resolution&amp;wo_id={$invoice.WORKORDER_ID}&amp;page_title=Closing%20Work%20Order{$invoice.WORKORDER_ID}'>{$translate_invoice_close_wo}</button>
                                            {$translate_invoice_msg}
                                         {/if}


                                        </p>
                                {/if}

                                        <br>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_labor}</td>
                                            </tr><tr>
                                                <td class="menutd2">
                                        {if $labor != '0'}

                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable">
                                                        <tr  class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2" width="12"><b>{$translate_invoice_hours}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_rate}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_actions}</b></td>

                                                        </tr>

                                                {section name=q loop=$labor}
                                                        <tr class="olotd4">
                                                            <td>{$smarty.section.q.index+1}</td>
                                                            <td>{$labor[q].INVOICE_LABOR_UNIT}</td>
                                                            <td>{$labor[q].INVOICE_LABOR_DESCRIPTION}</td>
                                                            <td>{$currency_sym}{$labor[q].INVOICE_LABOR_RATE|string_format:"%.2f"}</td>
                                                            <td>{$currency_sym}{$labor[q].INVOICE_LABOR_SUBTOTAL|string_format:"%.2f"}</td>
                                                            <td>
                                                                <a href="javascript:void(0)" onclick="confirmLabourDelete({$labor[q].INVOICE_LABOR_ID}, {$invoice.INVOICE_ID}, {$wo_id}, {$customer_id});">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14"
                                                                    onMouseOver="ddrivetip('<b>{$translate_invoice_delete_labour_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                    onMouseOut="hideddrivetip()"></a>
                                                            </td>
                                                        </tr>
                                                        {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{$translate_invoice_labour_total}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$labour_sub_total_sum}</td>
                                                        </tr>
                                                    </table>
                                    {/if}
                                                    <br>
                                                    <!-- Additional Javascript Labour Table -->
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0" class="olotable" id="labor">
                                                        <tr  class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_hours}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>&nbsp;&nbsp;{$translate_invoice_rate}</b></td>
                                                        </tr>
                                                    </table>
                                                    <p>
                                                        <input type="button" value="{$translate_invoice_add}" onclick="addRowToTableLabor();" />
                                                        <input type="button" value="{$translate_invoice_remove}" onclick="removeRowFromTableLabor();" />
                                                    </p>

                                                </td>
                                            </tr>
                                        </table>

                                        <br>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_parts}</td>
                                            </tr><tr>
                                                <td class="menutd2">
                                        {if $parts != '0'}
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_count}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_total}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_actions}</b></td>
                                                        </tr>
                                                {section name=w loop=$parts}
                                                        <tr class="olotd4">
                                                            <td>{$smarty.section.w.index+1}</td>
                                                            <td>{$parts[w].INVOICE_PARTS_COUNT}</td>
                                                            <td>{$parts[w].INVOICE_PARTS_DESCRIPTION}</td>
                                                            <td>{$currency_sym}{$parts[w].INVOICE_PARTS_AMOUNT|string_format:"%.2f"}</td>
                                                            <td>{$currency_sym}{$parts[w].INVOICE_PARTS_SUBTOTAL|string_format:"%.2f"}</td>
                                                            <td>
                                                                <a href="javascript:void(0)" onclick="confirmPartsDelete({$parts[w].INVOICE_PARTS_ID}, {$invoice.INVOICE_ID}, {$wo_id}, {$customer_id});">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14"
                                                                    onMouseOver="ddrivetip('<b>{$translate_invoice_delete_parts_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                    onMouseOut="hideddrivetip()"></a>
                                                            </td>
                                                        </tr>
                                                         {/section}
                                                        <tr>
                                                            <td colspan="5" style="text-align:right;"><b>{$translate_invoice_parts_total}</b></td>
                                                            <td style="text-align:left;">{$currency_sym}{$parts_sub_total_sum}</td>
                                                        </tr>
                                                    </table>

                                        {/if}
                                                    <br>
                                                    <!-- Additional Javascript Parts Table -->
                                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable" id="parts">
                                                        <tr class="olotd4">
                                                            <td class="row2"><b>{$translate_invoice_no}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_count}-QTY</b></td>
                                                            <td class="row2"><b>{$translate_invoice_description}</b></td>
                                                            <td class="row2"><b>{$translate_invoice_price}</b></td>
                                                        </tr>

                                                    </table>
                                                    <p>
                                                        <input type="button" value="{$translate_invoice_add}" onclick="addRowToTableParts();" />
                                                        <input type="button" value="{$translate_invoice_remove}" onclick="removeRowFromTableParts();" />
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2">&nbsp;{$translate_invoice_total}</td>
                                            </tr><tr>
                                                <td class="menutd2">
                                                    <table width="100%" border="1"  cellpadding="3" cellspacing="0" class="olotable">
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_sub_total}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.SUB_TOTAL}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_discount}</b></td>
                                                            <td class="olotd4" width="20%" align="right">- {$currency_sym}{$invoice.DISCOUNT|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_shipping}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.SHIPPING}
                                                            <input type="hidden" name="shipping"  value="{$invoice.SHIPPING|string_format:"%.2f"}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_tax}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.TAX}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4" width="80%" align="right"><b>{$translate_invoice_total}</b></td>
                                                            <td class="olotd4" width="20%" align="right">{$currency_sym}{$invoice.INVOICE_AMOUNT}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table width="100%"  cellpadding="3" cellspacing="0" border="0">
                                            <tr>
                                                <td align="left" valign="top" width="25%">
                                                    <input type="hidden" name="chkValidateOnKeyPress" value="checked">
                                                    <input type="hidden" name="invoice_id"    value="{$invoice.INVOICE_ID}">
                                                    <input type="hidden" name="sub_total"     value="{$invoice.SUB_TOTAL|string_format:"%.2f"}">
                                                    <input type="hidden" name="page"          value="invoice:new">
                                                    <input type="hidden" name="create_by"     value="{$login_id}">
                                                    <input type="hidden" name="wo_id"         value="{$wo_id}">
                                                    <input type="submit" name="submit"        value="{$translate_invoice_submit}">
                                                </td>
                                                <td align="right" width="75%">

                                                </td>
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