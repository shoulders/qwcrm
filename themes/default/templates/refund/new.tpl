<!-- new.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script>{include file="refund/javascripts.js"}</script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_refund_new_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_refund_new_help_title}</b><hr><p>{$translate_refund_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();" onClick="window.location;">
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
                                                <input type="hidden" name="page" value="refund:edit">
                                                {literal}
                                                <form action="index.php?page=refund:new" method="POST" name="new_refund" id="new_refund" autocomplete="off">
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
                                                                            <td align="right"><b>{$translate_refund_id}</b></td><td>{$new_record_id}</td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_payee}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3"><input id="refundPayee" name="refundPayee" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_date}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="refundDate" name="refundDate" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                                <input id="refundDate_button" value="+" type="button">                                                                                
                                                                                <script>
                                                                                {literal}
                                                                                    Calendar.setup({
                                                                                        trigger     : "refundDate_button",
                                                                                        inputField  : "refundDate",
                                                                                        dateFormat  : "{/literal}{$date_format}{literal}"                                                                                            
                                                                                    });
                                                                                {/literal}   
                                                                                </script>                                                                                
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_type}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select name="refundType" class="olotd5" col="30" style="width: 150px"/>
                                                                                    <option value="1">{$translate_refund_type_1}</option>
                                                                                    <option value="2">{$translate_refund_type_2}</option>
                                                                                    <option value="3">{$translate_refund_type_3}</option>
                                                                                    <option value="4">{$translate_refund_type_4}</option>
                                                                                    <option value="5">{$translate_refund_type_5}</option>                                                                                    
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_payment_method}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select name="refundPaymentMethod" class="olotd5" style="width: 150px"/>
                                                                                    <option value="1">{$translate_refund_payment_method_1}</option>
                                                                                    <option value="2">{$translate_refund_payment_method_2}</option>
                                                                                    <option value="3">{$translate_refund_payment_method_3}</option>
                                                                                    <option value="4">{$translate_refund_payment_method_4}</option>
                                                                                    <option value="5">{$translate_refund_payment_method_5}</option>
                                                                                    <option value="6">{$translate_refund_payment_method_6}</option>
                                                                                    <option value="7">{$translate_refund_payment_method_7}</option>
                                                                                    <option value="8">{$translate_refund_payment_method_8}</option>
                                                                                    <option value="9">{$translate_refund_payment_method_9}</option>
                                                                                    <option value="10">{$translate_refund_payment_method_10}</option>
                                                                                    <option value="11">{$translate_refund_payment_method_11}</option>
                                                                                </select>                                                                                
                                                                            </td>
                                                                        </tr>                                                                                                          
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_net_amount}</b></td>
                                                                            <td><input name="refundNetAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"></b></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_refund_tax_rate}</td>
                                                                            <td><input name="refundTaxRate" class="olotd5" size="5" value="{$tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_tax_amount}</b></td>
                                                                            <td><input name="refundTaxAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_gross_amount}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input name="refundGrossAmount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
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
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_notes}</b></td>
                                                                            <td><textarea name="refundNotes" class="olotd5" cols="50" rows="15"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_refund_items}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><textarea name="refundItems" class="olotd5 mceCheckForContent" cols="50" rows="15"></textarea></td>
                                                                        </tr>
                                                                    </tbody>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <input name="submit" class="olotd5" value="{$translate_refund_submit_button}" type="submit">
                                                                                <input name="submitandnew" class="olotd5" value="{$translate_refund_submit_and_new_button}" type="submit">
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