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
                    <td class="menuhead2" width="80%">&nbsp;{t}Refund Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">  
                                                    
                                                    <form action="index.php?page=refund:edit&refund_id={$refund_id}" method="POST" name="edit_refund" id="edit_refund" autocomplete="off">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Refund ID{/t}</b></td>
                                                            <td colspan="3">{$refund_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Payee{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3"><input name="payee" class="olotd5" size="50" value="{$refund_details.payee}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <input id="date" name="date" class="olotd5" size="10" value="{$refund_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                <input id="date_button" type="button" value="+">                                                                    
                                                                <script>                                                                    
                                                                    Calendar.setup( {
                                                                        trigger     : "date_button",
                                                                        inputField  : "date",
                                                                        dateFormat  : "{$date_format}"                                                                                            
                                                                    } );                                                                    
                                                                </script>                                                                    
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="refundType" name="type" class="olotd5" col="30" style="width: 150px" value="{$refund_details.type}"/>
                                                                    <option value="1"{if $refund_details.type== '1'} selected{/if}>{t}REFUND_TYPE_1{/t}</option>
                                                                    <option value="2"{if $refund_details.type== '2'} selected{/if}>{t}REFUND_TYPE_2{/t}</option>
                                                                    <option value="3"{if $refund_details.type== '3'} selected{/if}>{t}REFUND_TYPE_3{/t}</option>
                                                                    <option value="4"{if $refund_details.type== '4'} selected{/if}>{t}REFUND_TYPE_4{/t}</option>
                                                                    <option value="5"{if $refund_details.type== '5'} selected{/if}>{t}REFUND_TYPE_5{/t}</option>                                                                        
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Payment Method{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="refundPaymentMethod" name="payment_method" class="olotd5" style="width: 150px" value="{$refund_details.payment_method}"/>
                                                                    <option value="1"{if $refund_details.method == '1'} selected{/if}>{t}REFUND_PAYMENT_METHOD_1{/t}</option>
                                                                    <option value="2"{if $refund_details.method == '2'} selected{/if}>{t}REFUND_PAYMENT_METHOD_2{/t}</option>
                                                                    <option value="3"{if $refund_details.method == '3'} selected{/if}>{t}REFUND_PAYMENT_METHOD_3{/t}</option>
                                                                    <option value="4"{if $refund_details.method == '4'} selected{/if}>{t}REFUND_PAYMENT_METHOD_4{/t}</option>
                                                                    <option value="5"{if $refund_details.method == '5'} selected{/if}>{t}REFUND_PAYMENT_METHOD_5{/t}</option>
                                                                    <option value="6"{if $refund_details.method == '6'} selected{/if}>{t}REFUND_PAYMENT_METHOD_6{/t}</option>
                                                                    <option value="7"{if $refund_details.method == '7'} selected{/if}>{t}REFUND_PAYMENT_METHOD_7{/t}</option>
                                                                    <option value="8"{if $refund_details.method == '8'} selected{/if}>{t}REFUND_PAYMENT_METHOD_8{/t}</option>
                                                                    <option value="9"{if $refund_details.method == '9'} selected{/if}>{t}REFUND_PAYMENT_METHOD_9{/t}</option>
                                                                    <option value="10"{if $refund_details.method == '10'} selected{/if}>{t}REFUND_PAYMENT_METHOD_10{/t}</option>
                                                                    <option value="11"{if $refund_details.method == '11'} selected{/if}>{t}REFUND_PAYMENT_METHOD_11{/t}</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Net Amount{/t}</b></td>
                                                            <td><input name="net_amount" class="olotd5" size="10" value="{$refund_details.net_amount}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"></b></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><span style="color: #ff0000"></span><b>{t}Tax Rate{/t}</b></td>
                                                            <td><input name="tax_rate" class="olotd5" size="5" value="{$refund_details.tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/><b>%</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Tax Amount{/t}</b></td>
                                                            <td><input name="tax_amount" class="olotd5" size="10" value="{$refund_details.tax_amount}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Gross Amount{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><input name="gross_amount" class="olotd5" size="10" value="{$refund_details.gross_amount}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Items{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><textarea class="olotd5 mceCheckForContent" name="items" cols="50" rows="15">{$refund_details.items}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Notes{/t}</b></td>
                                                            <td><textarea class="olotd5" name="notes" cols="50" rows="15">{$refund_details.notes}</textarea></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td></td>
                                                            <td><button type="submit" name="submit" value="update">{t}Update{/t}</button></td>
                                                            
                                                        </tr>
                                                    </form>
                                                    
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
        </td>
    </tr>
</table>