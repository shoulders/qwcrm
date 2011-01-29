<!-- Refund View and Search TPL -->

{include file="refund/javascripts.js"}

<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
                                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_refund_view_title}</td>
                                    <td class="menuhead2" width="20%" align="right" valign="middle">
                                        <a><img src="images/icons/16x16/help.gif" border="0" alt=""
                                            onMouseOver="ddrivetip('<b>{$translate_refund_search_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_refund_search_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                            onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="menutd2" colspan="2">
                                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                            <td class="menutd">

                                                                    <!-- Content -->

                                                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                                        <tr>

                                                                        <!-- Category Search -->

                                                                            <td valign="top">
                                                                                    <form action="index.php?page=refund:search&page_title={$translate_refund_search_title}" method="post" name="refund_search" id="refund_search" autocomplete="off" >
                                                                                    <div>
                                                                                    <input name="page" type="hidden" value="refund:search" />
                                                                                    <table border="0">
                                                                                        <tr>
                                                                                            <td align="left" valign="top"><b>{$translate_refund_search}</b>
                                                                                               <br />
                                                                                                <select class="olotd5" id="refund_search_category" name="refund_search_category">
                                                                                                    <option value="ID">{$translate_refund_id}</option>
                                                                                                    <option value="PAYEE">{$translate_refund_payee}</option>
                                                                                                    <option value="DATE">{$translate_refund_date}</option>
                                                                                                    <option value="TYPE">{$translate_refund_type}</option>
                                                                                                    <option value="PAYMENT_METHOD">{$translate_refund_payment_method}</option>
                                                                                                    <option value="NET_AMOUNT">{$translate_refund_net_amount}</option>
                                                                                                    <option value="TAX_RATE">{$translate_refund_tax_rate}</option>
                                                                                                    <option value="TAX_AMOUNT">{$translate_refund_tax_amount}</option>
                                                                                                    <option value="GROSS_AMOUNT">{$translate_refund_gross_amount}</option>
                                                                                                    <option value="NOTES">{$translate_refund_notes}</option>
                                                                                                    <option value="ITEMS">{$translate_refund_items}</option>
                                                                                                </select>
                                                                                               <br />
                                                                                               <b>{$translate_refund_for}</b>
                                                                                               <br />
                                                                                               <input class="olotd4" name="refund_search_term" type="text" value="{$refund_search_term}" onkeypress="return OnlyAlphaNumeric();" />
                                                                                               <input class="olotd4" name="submit" value="{$translate_refund_search_button}" type="submit" />
                                                                                               <input class="olotd4" type="button" value="{$translate_refund_reset_button}" onclick="window.location.href='index.php?page=refund%3Asearch&page_title={$translate_refund_view_title}'">                                                                                       </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td><font color="RED">{$translate_refund_search_criteria_warning}</font></td>
                                                                                            </tr>
                                                                                    </table>
                                                                                    </div>
                                                                                    </form>

                                                                                    <!-- This script sets the dropdown to the correct item -->
                                                                                    <script type="text/javascript">dropdown_select_view_category("{$refund_search_category}");</script>
                                                                            </td>
                                                                            
                                                                            <!-- end of Category Search -->

                                                                            <!-- Navigation Section  -->

                                                                            <td valign="top" nowrap>
                                                                            <form id="1">

                                                                                    <!-- Left buttons -->
                                                                                    <a href="?page=refund%3Asearch&refund_search_category={$refund_search_category}&refund_search_term={$refund_search_term}&submit=submit&page_no=1&page_title={$translate_refund_view_title}"><img src="images/rewnd_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {if $previous != ''}
                                                                                    <a href="?page=refund%3Asearch&refund_search_category={$refund_search_category}&refund_search_term={$refund_search_term}&submit=submit&page_no={$previous}&page_title={$translate_refund_view_title}"><img src="images/back_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {/if}
                                                                                    <!-- end of Left Side Buttons -->

                                                                                    <!-- Right Side Buttons -->
                                                                                    {if $next != ''}
                                                                                    <a href="?page=refund%3Asearch&refund_search_category={$refund_search_category}&refund_search_term={$refund_search_term}&submit=submit&page_no={$next}&page_title={$translate_refund_view_title}"><img src="images/forwd_24.gif" border="0" alt=""></a>
                                                                                    {/if}
                                                                                    <a href="?page=refund%3Asearch&refund_search_category={$refund_search_category}&refund_search_term={$refund_search_term}&submit=submit&page_no={$total_pages}&page_title={$translate_refund_view_title}"><img src="images/fastf_24.gif" border="0" alt=""></a>
                                                                                    <!-- end of Right Side Buttons -->

                                                                                    <!-- Page Number Display -->
                                                                                    <br>
                                                                                    {$translate_page} {$page_no} {$translate_of} {$total_pages}
                                                                                    <br />
                                                                                    {$total_results} {$translate_records_found}.
                                                                                    <!-- end of Page Number Display -->

                                                                                    </form>

                                                                            <!-- end of Navigation Section -->

                                                                                    <!-- Goto Page Form -->
                                                                                    {literal}
                                                                                    <form  method="POST" name="goto_page" id="goto_page" autocomplete="off"  onsubmit="try { var myValidator = validate_refund_goto_page; } catch(e) { return true; } return myValidator(this);">
                                                                                    {/literal}
                                                                                    <input class="olotd5" size="10" id="goto_page_no" name="goto_page_no" type="text" onkeypress="return onlyNumbers();" />
                                                                                    <input class="olotd5" name="submit" value="{$translate_refund_search_goto_page_button}" type="submit" />
                                                                                    </form>
                                                                                    <!-- End of Goto Page Form -->

                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td valign="top" colspan="2">

                                                                                <!-- Records Table -->

                                                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                                                        <tr>
                                                                                            <td class="olohead">{$translate_refund_id}</td>
                                                                                            <td class="olohead">{$translate_refund_payee}</td>
                                                                                            <td class="olohead">{$translate_refund_date}</td>
                                                                                            <td class="olohead">{$translate_refund_type}</td>
                                                                                            <td class="olohead">{$translate_refund_payment_method}</td>
                                                                                            <td class="olohead">{$translate_refund_net_amount}</td>
                                                                                            <td class="olohead">{$translate_refund_tax_rate}</td>
                                                                                            <td class="olohead">{$translate_refund_tax_amount}</td>
                                                                                            <td class="olohead">{$translate_refund_gross_amount}</td>
                                                                                            <td class="olohead">{$translate_refund_notes}</td>
                                                                                            <td class="olohead">{$translate_refund_items}</td>
                                                                                            <td class="olohead">{$translate_action}</td>
                                                                                        </tr>
                                                                                        {section name=i loop=$refund_search_result}

                                                                                        <!-- This allows double clicking on a row and opens the corresponding refund view details -->
                                                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=refund:refund_details&refundID={$refund_search_result[i].REFUND_ID}&page_title={$translate_refund_details_title}';" class="row1">

                                                                                            <!-- Refund ID Column -->
                                                                                            <td class="olotd4" nowrap><a href="index.php?page=refund:refund_details&refundID={$refund_search_result[i].REFUND_ID}&page_title={$translate_refund_details_title}">{$refund_search_result[i].REFUND_ID}</a></td>

                                                                                            <!-- Payee Column -->
                                                                                            <td class="olotd4" nowrap>{$refund_search_result[i].REFUND_PAYEE}</td>

                                                                                            <!-- Date Column -->
                                                                                            <td class="olotd4" nowrap>{$refund_search_result[i].REFUND_DATE|date_format:$date_format}</td>

                                                                                            <!-- Refund Type Column -->
                                                                                            <td class="olotd4" nowrap>

                                                                                                        {if $refund_search_result[i].REFUND_TYPE ==1}
                                                                                                                {$translate_refund_type_1}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_TYPE ==2}
                                                                                                                {$translate_refund_type_2}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_TYPE ==3}
                                                                                                                {$translate_refund_type_3}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_TYPE ==4}
                                                                                                                {$translate_refund_type_4}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_TYPE ==5}
                                                                                                                {$translate_refund_type_5}
                                                                                                        {/if}
                                                                                            </td>

                                                                                            <!-- Payment Method -->
                                                                                            <td class="olotd4" nowrap>

                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==1}
                                                                                                                {$translate_refund_payment_method_1}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==2}
                                                                                                                {$translate_refund_payment_method_2}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==3}
                                                                                                                {$translate_refund_payment_method_3}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==4}
                                                                                                                {$translate_refund_payment_method_4}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==5}
                                                                                                                {$translate_refund_payment_method_5}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==6}
                                                                                                                {$translate_refund_payment_method_6}
                                                                                                        {/if}
                                                                                                         {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==7}
                                                                                                                {$translate_refund_payment_method_7}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==8}
                                                                                                                {$translate_refund_payment_method_8}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==9}
                                                                                                                {$translate_refund_payment_method_9}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==10}
                                                                                                                {$translate_refund_payment_method_10}
                                                                                                        {/if}
                                                                                                        {if $refund_search_result[i].REFUND_PAYMENT_METHOD ==11}
                                                                                                                {$translate_refund_payment_method_11}
                                                                                                        {/if}
                                                                                            </td>

                                                                                            <!-- Net Amount Column -->
                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$refund_search_result[i].REFUND_NET_AMOUNT}</td>

                                                                                            <!-- Tax Rate Column -->
                                                                                            <td class="olotd4" nowrap>{$refund_search_result[i].REFUND_TAX_RATE} %</td>

                                                                                            <!-- Tax Amount Column -->
                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$refund_search_result[i].REFUND_TAX_AMOUNT}</td>

                                                                                            <!-- Gross Amount Column -->

                                                                                            <td class="olotd4" nowrap>{$currency_sym} {$refund_search_result[i].REFUND_GROSS_AMOUNT}</td>

                                                                                            <!-- Notes Column -->
                                                                                            <td class="olotd4" nowrap>{if !$refund_search_result[i].REFUND_NOTES == ""}
                                                                                                <img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_refund_notes}</b><hr><p>{$refund_search_result[i].REFUND_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()">{/if}</td>

                                                                                            <!-- Items Column  -->
                                                                                            <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_refund_items}</b><hr><p>{$refund_search_result[i].REFUND_ITEMS|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()"></td>

                                                                                            <!-- Action Column -->
                                                                                            <td class="olotd4" nowrap>
                                                                                                <a href="index.php?page=refund:refund_details&refundID={$refund_search_result[i].REFUND_ID}&page_title={$translate_refund_details_title}">
                                                                                                    <img src="images/icons/16x16/viewmag.gif" alt="" border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_refund_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=refund:edit&refundID={$refund_search_result[i].REFUND_ID}&page_title={$translate_refund_edit_title}">
                                                                                                    <img src="images/icons/16x16/small_edit.gif" alt=""  border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_refund_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=refund:search&amp;page_title={$translate_refund_search_title}" onclick="confirmDelete({$refund_search_result[i].REFUND_ID});">
                                                                                                    <img src="images/icons/delete.gif" alt="" border="0" height="14" width="14"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_refund_search_delete_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>																										 

                                                                                        </tr>
                                                                                        {/section}
                                                                                    </table>

                                                                                    <!-- end of Records Table -->

                                                                                   </td>
                                                                                </tr>
                                                                             </table>

                                                                             <!-- end of Content -->

                                                                            </td>
                                                                    </tr>
                                                            </table>
                                                    </td>
                                            </tr>
                                    </table>
                            </td>
                    </tr>
            </table>
