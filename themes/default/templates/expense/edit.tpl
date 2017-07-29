<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Expense Edit Page{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EXPENSE_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EXPENSE_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                     <tr>
                                         <td>                                                                                           
                                            <form method="post" action="index.php?page=expense:edit" name="edit_expense" id="edit_expense">                                                
                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">                                                    
                                                    <tr>
                                                        <td align="right"><b>{t}Expense ID{/t}</b></td>
                                                        <td colspan="3"><input name="expense_id" value="{$expense_details.expense_id}" type="hidden">{$expense_details.expense_id}</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Payee{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td colspan="3"><input name="expensePayee" class="olotd5" size="50" value="{$expense_details.expense_payee}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                    </tr><tr>
                                                        <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td>
                                                            <input id="expenseDate" name="expenseDate" class="olotd5" size="10" value="{$expense_details.expense_date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                            <input id="expenseDate_button" value="+" type="button">                                                            
                                                            <script>                                                                
                                                                Calendar.setup( {
                                                                    trigger     : "expenseDate_button",
                                                                    inputField  : "expenseDate",
                                                                    dateFormat  : "{$date_format}"                                                                                            
                                                                } );                                                                  
                                                            </script>                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td>
                                                            <select id="expenseType" name="expenseType" class="olotd5" col="30" style="width: 150px;" value="{$expense_details.expense_type}"/>
                                                                <option value="1">{t}EXPENSE_TYPE_1{/t}{if $expense_details.expense_type == '1'} selected{/if}</option>
                                                                <option value="2">{t}EXPENSE_TYPE_2{/t}{if $expense_details.expense_type == '2'} selected{/if}</option>
                                                                <option value="3">{t}EXPENSE_TYPE_3{/t}{if $expense_details.expense_type == '3'} selected{/if}</option>
                                                                <option value="4">{t}EXPENSE_TYPE_4{/t}{if $expense_details.expense_type == '4'} selected{/if}</option>
                                                                <option value="5">{t}EXPENSE_TYPE_5{/t}{if $expense_details.expense_type == '5'} selected{/if}</option>
                                                                <option value="6">{t}EXPENSE_TYPE_6{/t}{if $expense_details.expense_type == '6'} selected{/if}</option>
                                                                <option value="7">{t}EXPENSE_TYPE_7{/t}{if $expense_details.expense_type == '7'} selected{/if}</option>
                                                                <option value="8">{t}EXPENSE_TYPE_8{/t}{if $expense_details.expense_type == '8'} selected{/if}</option>
                                                                <option value="9">{t}EXPENSE_TYPE_9{/t}{if $expense_details.expense_type == '9'} selected{/if}</option>
                                                                <option value="10">{t}EXPENSE_TYPE_10{/t}{if $expense_details.expense_type == '10'} selected{/if}</option>
                                                                <option value="11">{t}EXPENSE_TYPE_11{/t}{if $expense_details.expense_type == '11'} selected{/if}</option>
                                                                <option value="12">{t}EXPENSE_TYPE_12{/t}{if $expense_details.expense_type == '12'} selected{/if}</option>
                                                                <option value="13">{t}EXPENSE_TYPE_13{/t}{if $expense_details.expense_type == '13'} selected{/if}</option>
                                                                <option value="14">{t}EXPENSE_TYPE_14{/t}{if $expense_details.expense_type == '14'} selected{/if}</option>
                                                                <option value="15">{t}EXPENSE_TYPE_15{/t}{if $expense_details.expense_type == '15'} selected{/if}</option>
                                                                <option value="16">{t}EXPENSE_TYPE_16{/t}{if $expense_details.expense_type == '16'} selected{/if}</option>
                                                                <option value="17">{t}EXPENSE_TYPE_17{/t}{if $expense_details.expense_type == '17'} selected{/if}</option>
                                                                <option value="18">{t}EXPENSE_TYPE_18{/t}{if $expense_details.expense_type == '18'} selected{/if}</option>
                                                                <option value="19">{t}EXPENSE_TYPE_19{/t}{if $expense_details.expense_type == '19'} selected{/if}</option>
                                                                <option value="20">{t}EXPENSE_TYPE_20{/t}{if $expense_details.expense_type == '20'} selected{/if}</option>
                                                                <option value="21">{t}EXPENSE_TYPE_21{/t}{if $expense_details.expense_type == '21'} selected{/if}</option>
                                                            </select>
                                                        </td>                                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Payment Method{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td>
                                                            <select id="expensePaymentMethod" name="expensePaymentMethod" class="olotd5" style="width: 150px;" value="{$expense_details.expense_payment_method}"/>
                                                                <option value="1">{t}EXPENSE_PAYMENT_METHOD_1{/t}{if $expense_details.expense_method == '1'} selected{/if}</option>
                                                                <option value="2">{t}EXPENSE_PAYMENT_METHOD_2{/t}{if $expense_details.expense_method == '2'} selected{/if}</option>
                                                                <option value="3">{t}EXPENSE_PAYMENT_METHOD_3{/t}{if $expense_details.expense_method == '3'} selected{/if}</option>
                                                                <option value="4">{t}EXPENSE_PAYMENT_METHOD_4{/t}{if $expense_details.expense_method == '4'} selected{/if}</option>
                                                                <option value="5">{t}EXPENSE_PAYMENT_METHOD_5{/t}{if $expense_details.expense_method == '5'} selected{/if}</option>
                                                                <option value="6">{t}EXPENSE_PAYMENT_METHOD_6{/t}{if $expense_details.expense_method == '6'} selected{/if}</option>
                                                                <option value="7">{t}EXPENSE_PAYMENT_METHOD_7{/t}{if $expense_details.expense_method == '7'} selected{/if}</option>
                                                                <option value="8">{t}EXPENSE_PAYMENT_METHOD_8{/t}{if $expense_details.expense_method == '8'} selected{/if}</option>
                                                                <option value="9">{t}EXPENSE_PAYMENT_METHOD_9{/t}{if $expense_details.expense_method == '9'} selected{/if}</option>
                                                                <option value="10">{t}EXPENSE_PAYMENT_METHOD_10{/t}{if $expense_details.expense_method == '10'} selected{/if}</option>
                                                                <option value="11">{t}EXPENSE_PAYMENT_METHOD_11{/t}{if $expense_details.expense_method == '11'} selected{/if}</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Net Amount{/t}</b></td>
                                                        <td><input name="expenseNetAmount" class="olotd5" size="10" value="{$expense_details.expense_net_amount}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><span style="color: #ff0000"></span><b>{t}Tax Rate{/t}</b></td>
                                                        <td><input name="expenseTaxRate" class="olotd5" size="4" value="{$expense_details.expense_tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Tax amount{/t}</b></td>
                                                        <td><input name="expenseTaxAmount" class="olotd5" size="10" value="{$expense_details.expense_tax_amount}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Gross Amount{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td><input name="expenseGrossAmount" class="olotd5" size="10" value="{$expense_details.expense_gross_amount}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Notes{/t}</b></td>
                                                        <td><textarea name="expenseNotes" class="olotd5" cols="50" rows="15">{$expense_details.expense_notes}</textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right"><b>{t}Items{/t}</b><span style="color: #ff0000"> *</span></td>
                                                        <td><textarea name="expenseItems" class="olotd5 mceCheckForContent" cols="50" rows="15">{$expense_details.expense_items}</textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td><input name="submit" class="olotd5" value="{t}Update{/t}" type="submit"></td>
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
        </td>
    </tr>
</table>