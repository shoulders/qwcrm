<!-- edit rates -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Edit Billing Rates </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <form method="post" action="?page=control:edit_rate">
                                        <table class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td class="olohead">ID</td>
                                                <td class="olohead">Description</td>
                                                <td class="olohead" align="center">Amount</td>
                                                <td class="olohead" align="center">Cost</td>
                                                <td class="olohead" align="center">Active</td>
                                                <td class="olohead" align="center">Type</td>
                                                <td class="olohead" align="center">Manufacturer</td>
                                                <td class="olohead" align="center">Action</td>
                                            </tr>
                                            {section name=q loop=$rate}
                                            <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" class="row1">
                                                <td class="olotd4"><b>{$rate[q].LABOR_RATE_ID}</b></td>
                                                <td class="olotd4" nowrap><input class="olotd5" type="text" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_RATE_NAME]" value="{$rate[q].LABOR_RATE_NAME}" size="50"></td>
                                                <td class="olotd4" nowrap>$<input class="olotd5" type="text" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_RATE_AMOUNT]" value="{$rate[q].LABOR_RATE_AMOUNT}" size="6"></td>
                                                <td class="olotd4" nowrap>$<input class="olotd5" type="text" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_RATE_COST]" value="{$rate[q].LABOR_RATE_COST}" size="6"></td>
                                                <td class="olotd4" nowrap><select class="olotd5" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_RATE_ACTIVE]">
                                                        <option value="0" {if $rate[q].LABOR_RATE_ACTIVE == 0} selected{/if}>No</option>
                                                        <option value="1" {if $rate[q].LABOR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
                                                    </select>
                                                </td>
                                                <td class="olotd4" nowrap><select class="olotd5" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_TYPE]">
                                                        <option value="Parts" {if $rate[q].LABOR_TYPE == "Parts"} selected{/if}>Parts</option>
                                                        <option value="Service" {if $rate[q].LABOR_TYPE == "Service"} selected{/if}>Service</option>
                                                    </select>
                                                </td>
                                                <td class="olotd4" nowrap><input class="olotd5" type="text" name="{$rate[q].LABOR_RATE_ID|escape}[LABOR_MANUF]" value="{$rate[q].LABOR_MANUF}" size="30"></td>
                                                <td class="olotd4"><input type="submit" name="submit" value="Delete"></td>
                                            </tr>
                                        {/section}
                                        </table>
                                        <input type="submit" name="submit" value="Submit">
                                    </form>
                                    <!-- Content -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>



