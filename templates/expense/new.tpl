<!-- Add New Expense tpl -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "exact",
        elements : "editor1, editor2, editor3",
		theme : "advanced",
		plugins : "advlink,iespell,insertdatetime,preview",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center",

	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	});
</script>
{/literal}

        <link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
        <script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
        <script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
        <script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <!-- Begin page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_expense_new_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="images/icons/16x16/help.gif" alt="" border="0"
                                onMouseOver="ddrivetip('<b>{$translate_expense_new_help_title}</b><hr><p>{$translate_expense_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                onMouseOut="hideddrivetip()"
                                onClick="window.location"></a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                            {if $error_msg != ""}
                                    {include file="core/error.tpl"}
                            {/if}
                            {include file="expense/javascripts.js"}
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">

                                    <!-- start of form content -->

                                    <table class="menutable" width="100%" border="0" cellpadding="2" cellspacing="2" >
                                        <tr>
                                            <td>                                                
                                                <input type="hidden" name="page" value="expense:edit">
						{literal}
                                                <form  action="index.php?page=expense:new" method="POST" name="new_expense" id="new_expense" autocomplete="off" onsubmit="try { var myValidator = validate_expense; } catch(e) { return true; } return myValidator(this);">
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
                                                                              <td align="right"><b>{$translate_expense_id}</b></td><td>{$new_record_id}</td>
                                                                              <td colspan="2"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_payee}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3"><input class="olotd5" size="60" id="expensePayee" name="expensePayee" type="text" onkeypress="return OnlyAlphaNumeric();" /></td>
                                                                        </tr><tr>
                                                                            <td align="right"><b>{$translate_expense_date}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input class="olotd5" size="10" name="expenseDate" type="text" id="expenseDate" />                                                                            
                                                                                <input type="button" id="trigger_date" value="+">
                                                                                {literal}
                                                                                    <script type="text/javascript">
                                                                                    Calendar.setup(
                                                                                    {
                                                                                    inputField  : "expenseDate",
                                                                                    ifFormat    : "{/literal}{$date_format}{literal}",
                                                                                    button      : "trigger_date"
                                                                                    }
                                                                                    );
                                                                                    </script>
                                                                                {/literal}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_type}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select class="olotd5" name="expenseType" col="30" style="width: 150px"/>
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
                                                                                    </td>
                                                                                </select>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_payment_method}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select class="olotd5" name="expensePaymentMethod" style="width: 150px"/>
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
                                                                            <td><a><input type="text" size="10" name="expenseNetAmount" value="" class="olotd5" onkeypress="return onlyNumbersPeriods();"></b></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{$translate_expense_tax_rate}</td>
                                                                            <td><input class="olotd5" name="expenseTaxRate" type="text" size="4" value="{$tax_rate}" onkeypress="return onlyNumbersPeriods();"/><b>%</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                        <td align="right"><b>{$translate_expense_tax_amount}</b></td>
                                                                        <td><input class="olotd5" name="expenseTaxAmount" type="text" size="10" onkeypress="return onlyNumbersPeriods();"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                        <td align="right"><b>{$translate_expense_gross_amount}</b><span style="color: #ff0000"> *</span></td>
                                                                        <td><input class="olotd5" name="expenseGrossAmount" type="text" size="10" onkeypress="return onlyNumbersPeriods();"/></td>
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
                                                                            <td align="right"><b>{$translate_expense_notes}</b></td>
                                                                            <td><textarea class="olotd5" name="expenseNotes" cols="50" rows="15" id="editor1"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{$translate_expense_items}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><textarea class="olotd5" name="expenseItems" cols="50" rows="15" id="editor2"></textarea></td>
                                                                        </tr>
                                                                    </tbody>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td><input class="olotd5" name="submit" value="{$translate_expense_submit_button}" type="submit" /><input class="olotd5" name="submitandnew" value="{$translate_expense_submit_and_new_button}" type="submit" /></td>
                                                                        </tr>
                                                                </table>
                                                            </td>
                                                        </tr>                                  
                                                    </table>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- end of form content -->
                                    
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>



