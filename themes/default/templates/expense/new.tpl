<!-- new.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script>{include file="expense/javascripts.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_expense_new_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_expense_new_help_title}</b><hr><p>{$translate_expense_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();" onClick="window.location;">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                    
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="menutable" width="100%" border="0" cellpadding="2" cellspacing="2" >
                                        <tr>
                                            <td>                                                
                                                <input type="hidden" name="page" value="expense:edit">
                                                {literal}
                                                <form action="index.php?page=expense:new" method="POST" name="new_expense" id="new_expense">
                                                {/literal}
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td class="menuhead" colspan="3">{$translate_first_menu}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_id}</b></td>
                                                                            <td>{$new_record_id}</td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_payee}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3"><input id="expensePayee" name="expensePayee" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_date}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="expenseDate" name="expenseDate" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                                <input id="expenseDate_button" type="button" value="+">                                                                                
                                                                                <script>
                                                                                {literal}
                                                                                    Calendar.setup({
                                                                                        trigger     : "expenseDate_button",
                                                                                        inputField  : "expenseDate",
                                                                                        dateFormat  : "{/literal}{$date_format}{literal}"                                                                                            
                                                                                    });
                                                                                {/literal}
                                                                                </script>                                                                                
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_type}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select name="expenseType" class="olotd5" col="30" style="width: 150px;"/>
                                                                                    <option value="1">{$translate_expense_type_1}</option>
                                                                                    <option value="2">{$translate_expense_type_2}</option>
                                                                                    <option value="3">{$translate_expense_type_3}</option>
                                                                                    <option value="4">{$translate_expense_type_4}</option>
                                                                                    <option value="5">{$translate_expense_type_5}</option>
                                                                                    <option value="6">{$translate_expense_type_6}</option>
                                                                                    <option value="7">{$translate_expense_type_7}</option>
                                                                                    <option value="8">{$translate_expense_type_8}</option>
                                                                                    <option value="9">{$translate_expense_type_9}</option>
                                                                                    <option value="10">{$translate_expense_type_10}</option>
                                                                                    <option value="11">{$translate_expense_type_11}</option>
                                                                                    <option value="12">{$translate_expense_type_12}</option>
                                                                                    <option value="13">{$translate_expense_type_13}</option>
                                                                                    <option value="14">{$translate_expense_type_14}</option>
                                                                                    <option value="15">{$translate_expense_type_15}</option>
                                                                                    <option value="16">{$translate_expense_type_16}</option>
                                                                                    <option value="17">{$translate_expense_type_17}</option>
                                                                                    <option value="18">{$translate_expense_type_18}</option>
                                                                                    <option value="19">{$translate_expense_type_19}</option>
                                                                                    <option value="20">{$translate_expense_type_20}</option>
                                                                                    <option value="21">{$translate_expense_type_21}</option>
                                                                                </select>
                                                                            </td>                                                                                
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_payment_method}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select name="expensePaymentMethod" class="olotd5" style="width: 150px;"/>
                                                                                    <option value="1">{$translate_expense_payment_method_1}</option>
                                                                                    <option value="2">{$translate_expense_payment_method_2}</option>
                                                                                    <option value="3">{$translate_expense_payment_method_3}</option>
                                                                                    <option value="4">{$translate_expense_payment_method_4}</option>
                                                                                    <option value="5">{$translate_expense_payment_method_5}</option>
                                                                                    <option value="6">{$translate_expense_payment_method_6}</option>
                                                                                    <option value="7">{$translate_expense_payment_method_7}</option>
                                                                                    <option value="8">{$translate_expense_payment_method_8}</option>
                                                                                    <option value="9">{$translate_expense_payment_method_9}</option>
                                                                                    <option value="10">{$translate_expense_payment_method_10}</option>
                                                                                    <option value="11">{$translate_expense_payment_method_11}</option>
                                                                                </select>                                                                                
                                                                            </td>
                                                                        </tr>                                                                                                          
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_net_amount}</b></td>
                                                                            <td><a><input name="expenseNetAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"></b></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_expense_tax_rate}</td>
                                                                            <td><input name="expenseTaxRate" class="olotd5" size="5" value="{$tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_tax_amount}</b></td>
                                                                            <td><input name="expenseTaxAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_gross_amount}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input name="expenseGrossAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{$translate_additional_menu}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>                                                                    
                                                                    <tr>
                                                                        <td align="right"><b>{$translate_expense_notes}</b></td>
                                                                        <td><textarea name="expenseNotes" class="olotd5" cols="50" rows="15"></textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{$translate_expense_items}</b><span style="color: #ff0000"> *</span></td>
                                                                        <td><textarea name="expenseItems" class="olotd5 mceCheckForContent" cols="50" rows="15"></textarea></td>
                                                                    </tr>                                                                    
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input name="submit" class="olotd5" value="{$translate_expense_submit_button}" type="submit">
                                                                            <input name="submitandnew" class="olotd5" value="{$translate_expense_submit_and_new_button}" type="submit">
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
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>