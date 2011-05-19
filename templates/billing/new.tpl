<!--Billing Contents -->

<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_billing_title}{$wo_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="images/icons/16x16/help.gif" alt="" border="0"
                             onMouseOver="ddrivetip('<b>New Invoice</b><hr><p></p>')"
                             onMouseOut="hideddrivetip()">
                    </td>
                </tr><tr>
                    <td class="olotd5" colspan="2">

                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                            <tr class="olotd4">
                                <td class="row2"><b>{$translate_billing_invoice_id}</b></td>
                                <td class="row2"><b>{$translate_billing_date}</b></td>
                                <td class="row2"><b>{$translate_billing_due_date}</b></td>
                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                <td class="row2"><b>{$translate_billing_wo_id}</b></td>
                                <td class="row2"><b>{$translate_billing_balance}</b></td>
                            </tr><tr class="olotd4">
						{foreach item=item from=$invoice_details}
                                <td>{$item.INVOICE_ID}</td>
                                <td>{$item.INVOICE_DATE|date_format:"$date_format"}</td>
                                <td>{$item.INVOICE_DUE|date_format:"$date_format"}</td>
                                <td>{$currency_sym}{$item.INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                <td>{$item.WORKORDER_ID}</td>
                                <td>
								{if $item.BALANCE > 0}
                                    <font color="#CC0000"><b>{$currency_sym}{$item.INVOICE_AMOUNT-$item.PAID_AMOUNT|string_format:"%.2f"}</b></font>
								{else}
                                    <font color="#CC0000"><b>{$currency_sym}{$item.INVOICE_AMOUNT|string_format:"%.2f"}</b></font>
								{/if}
                                </td>
							{assign var="invoice_amount" value=$item.INVOICE_AMOUNT}
                                                        {assign var="invoice_paid_amount" value=$item.PAID_AMOUNT}
							{assign var="invoice_id"     value=$item.INVOICE_ID}
							{assign var="workorder_id"   value=$item.WORKORDER_ID}
							{assign var="balance"       value=$item.BALANCE}
						{/foreach}
                            </tr><tr>
                                <td colspan="6" valign="top">
							 {foreach item=item from=$customer_details}
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td valign="top">
                                                {$item.CUSTOMER_DISPLAY_NAME}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {$item.CUSTOMER_ADDRESS}
                                                <br>
                                                {$item.CUSTOMER_CITY}, {$item.CUSTOMER_STATE} {$item.CUSTOMER_ZIP}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{$translate_billing_email}</b> {$item.CUSTOMER_EMAIL}</td>
                                        </tr><tr>
                                            <td><b>{$translate_billing_phone}</b> {$item.CUSTOMER_PHONE}
                                    </table>
							{assign var="customer_id" value=$item.CUSTOMER_ID}
							{/foreach}		
                                </td>

                            </tr>
                        </table>

                        <br>
					{if $balance > 0 }

                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                            <tr>
                                <td class="menuhead2">&nbsp;{$translate_billing_trans_log}</td>
                            </tr><tr>
                                <td class="menutd2">
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_billing_trans}</b></td>
                                            <td class="row2"><b>{$translate_billing_date}</b></td>
                                            <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            <td class="row2"><b>{$translate_billing_type}</b></td>
                                        </tr>
                                        {section name=r loop=$trans}
                                        <tr class="olotd4">
                                            <td>{$trans[r].TRANSACTION_ID}</td>
                                            <td>{$trans[r].DATE|date_format:"$date_format"}</td>
                                            <td><b>{$currency_sym}</b>{$trans[r].AMOUNT|string_format:"%.2f"}</td>
                                            <td>
                                                {if $trans[r].TYPE == 1}
                                                        {$translate_billing_credit_card}
                                                {elseif $trans[r].TYPE == 2}
                                                        {$translate_billing_check}
                                                {elseif $trans[r].TYPE == 3}
                                                        {$translate_billing_cash}
                                                {elseif $trans[r].TYPE == 4}
                                                        {$translate_billing_gift}
                                                {elseif $trans[r].TYPE == 5}
                                                        {$translate_billing_paypal}
                                                {/if}
                                            </td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><b>{$translate_billing_memo}</b></td>
                                            <td colspan="3">{$trans[r].MEMO}</td>
                                        </tr>
                                        {/section}
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
					{/if}
                        <!-- Content -->
					{if $billing_options.cc_billing == '1'}
                        <form method="POST" action="">
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_credit_card}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"></td>
                                                <td class="row2"><b>{$translate_billing_type}</b></td>
                                                <td class="row2"><b>{$translate_billing_cc_num}</b></td>
                                                <td class="row2"><b>{$translate_billing_ccv}</b></td>
                                                <td class="row2"><b>{$translate_billing_exp}</b></td>
                                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td><select name="card_type" class="olotd4">
													{foreach key=key item=item from=$cc_cards}
                                                        <option value="{$key}">{$item}</option>
													{/foreach}
                                                    </select>
                                                </td>
                                                <td><input type="text" name="cc_number" size="20" class="olotd4"></td>
                                                <td><input type="text" name="cc_ccv" size="4" class="olotd4" ></td>
                                                <td>{html_select_date prefix="StartDate" time=$time month_format="%m"
												end_year="+7" display_days=false}
                                                </td>
                                                <td>{$currency_sym}<input type="text" name="cc_amount" {if $balance > 0} value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if} size="6">

                                                </td>
                                            </tr>
                                        </table>
                                        <p>

                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_cc">
                                            <input type="submit" name="submit" value="Submit CC Payment">
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </form>

					{/if}

					{if $billing_options.deposit_billing == '1'}
                        <form method="POST" action="">
                            <br>
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_deposit}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"></td>
                                                <td class="row2"><b>{$translate_billing_deposit_id}</b></td>
                                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td><input type="text" name="deposit_recieved" size="20" class="olotd4"></td>
                                                <td>{$currency_sym}<input type="text" name="deposit_amount" size="8"
                                                            {if $balance > 0 } value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"
                                                            {else}
                                                            value="{$invoice_amount|string_format:"%.2f"}"
                                                            {/if} class="olotd4"></td>
                                            </tr><tr>
                                                <td valign="top"><b>{$translate_billing_memo}</b></td>
                                                <td colspan="2" ><textarea name="deposit_memo" cols="60" rows="4" class="olotd4"></textarea></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_deposit">
                                            <input type="submit" name="submit" value="{$translate_billing_deposit_payment}">
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </form>
					{/if}
                                        {if $billing_options.cheque_billing == '1'}
                        <form method="POST" action="">
                            <br>
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_check}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"></td>
                                                <td class="row2"><b>{$translate_billing_cheque_no}</b></td>
                                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td><input type="text" name="cheque_recieved" size="8" class="olotd4"></td>
                                                <td>{$currency_sym}<input type="text" name="cheque_amount" size="8"
                                                            {if $balance > 0} value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"
                                                            {else}
                                                            value="{$invoice_amount|string_format:"%.2f"}"
                                                            {/if} class="olotd4"></td>
                                            </tr><tr>
                                                <td valign="top"><b>{$translate_billing_memo}</b></td>
                                                <td colspan="2" ><textarea name="cheque_memo" cols="60" rows="4" class="olotd4"></textarea></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_check">
                                            <input type="submit" name="submit" value="Submit Cheque Payment">
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </form>
					{/if}

					{if $billing_options.cash_billing == '1'}
                        <form method="POST" action="">
                            <br>

                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_cash}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%"  class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{$translate_billing_cash}</b></td>
                                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td>
							{$currency_sym}<input type="text" name="cash_amount" size="8"
                                                            {if $balance > 0 }
                                                            value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"
                                                            {else}
                                                            value="{$invoice_amount|string_format:"%.2f"}"
                                                            {/if}
                                                            class="olotd4">
                                                </td>
                                            </tr><tr>
                                                <td valign="top"><b>{$translate_billing_memo}</b></td>
                                                <td><textarea name="cash_memo" cols="60" rows="4" class="olotd4"></textarea></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_cash">
                                            <input type="submit" name="submit" value="Submit Cash Payment">
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </form>
					{/if}

					{if $billing_options.gift_billing == '1'}
                        <form method="POST" action="">
                            <br>
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_gift}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{$translate_billing_gift}</b></td>
                                                <td class="row2"><b>{$currency_sym}{$translate_billing_amount}</b></td>
                                                <td class="row2"><b>{$translate_billing_gift_code}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td><input type="text" name="gift_amount" size="8" {if $balance > 0} value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if} class="olotd4"></td>
                                                <td><input type="text" name="gift_code" size="16" class="olotd4"><br>
                                                {$translate_billing_gift_code_2}</td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_gift">
                                            <input type="submit" name="submit" value="Submit Gift Certificate">
                                        </p>
                                    </td>
                                </tr>

                            </table>
                        </form>
					{/if}

					{if $billing_options.paypal_billing == '1'}
                        <br>
                        <form method="POST" action="?page=billing:proc_paypal">
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_paypal}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"><b>{$translate_billing_paypal}</b></td>
                                                <td class="row2"><b>{$currency_sym}{$translate_billing_amount}</b></td>
                                                <td class="row2"></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td>
							$<input type="text" name="paypal_amount" size="8"
                                                            {if $balance > 0 }
                                                            value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"
                                                            {else}
                                                            value="{$invoice_amount|string_format:"%.2f"}"
                                                            {/if}
                                                            class="olotd4">
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="submit" name="submit" value="Submit PayPal Payment">
                                        </p>
                                    </td>
                                </tr>

                            </table>

                        </form>
					{/if}
                                        {if $billing_options.paymate_billing == '1'}
                        <form method="POST" action="">
                            <br>
                            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2">&nbsp;{$translate_billing_paymate}</td>
                                </tr><tr>
                                    <td class="menutd2">
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                            <tr class="olotd4">
                                                <td class="row2"></td>
                                                <td class="row2"><b>{$translate_billing_paymate_trans}</b></td>
                                                <td class="row2"><b>{$translate_billing_amount}</b></td>
                                            </tr><tr class="olotd4">
                                                <td></td>
                                                <td><input type="text" name="paymate_recieved" size="20" class="olotd4"></td>
                                                <td>{$currency_sym}<input type="text" name="paymate_amount" size="8"
                                                            {if $balance > 0 } value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"
                                                            {else}
                                                            value="{$invoice_amount|string_format:"%.2f"}"
                                                            {/if} class="olotd4"></td>
                                            </tr><tr>
                                                <td valign="top"><b>{$translate_billing_memo}</b></td>
                                                <td colspan="2" ><textarea name="paymate_memo" cols="60" rows="4" class="olotd4"></textarea></td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input type="hidden" name="customer_id"  value="{$customer_id}">
                                            <input type="hidden" name="invoice_id"   value="{$invoice_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input type="hidden" name="page"         value="billing:proc_paymate">
                                            <input type="submit" name="submit" value="{$translate_billing_paymate_submit}">
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </form>{/if}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End Billing Contents -->
