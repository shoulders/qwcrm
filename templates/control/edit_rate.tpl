<!-- edit rates -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Edit Billing Rates</td>
                </tr><tr>
                    <td class="menutd2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td>
                                                <b>Billing rates per Unit.</b>
                                                

                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">SKU</td>
                                                        <td class="olohead">Description</td>
                                                        <td class="olohead" align="center">Amount</td>
                                                        <td class="olohead" align="center">Cost</td>
                                                        <td class="olohead" align="center">Active</td>
                                                        <td class="olohead" align="center">Type</td>
                                                        <td class="olohead" align="center">Action</td>
                                                    </tr>
							{section name=q loop=$rate}
                                                    <form method="POST" action="?page=control:edit_rate">
                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" class="row1">
                                                            <td class="olotd4" nowrap>{$rate[q].LABOR_RATE_ID}</td>
                                                            <td class="olotd4" nowrap><input class="olotd5" type="text" name="display" value="{$rate[q].LABOR_RATE_NAME}" size="50"></td>
                                                            <td class="olotd4" nowrap>$<input class="olotd5" type="text" name="amount" value="{$rate[q].LABOR_RATE_AMOUNT}" size="6"></td>
                                                            <td class="olotd4" nowrap>$<input class="olotd5" type="text" name="cost" value="{$rate[q].LABOR_RATE_COST}" size="6"></td>
                                                            <td class="olotd4" nowrap><select class="olotd5" name="active">
                                                                    <option value="0" {if $rate[q].LABOR_RATE_ACTIVE == 0} selected{/if}>No</option>
                                                                    <option value="1" {if $rate[q].LABOR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap><select class="olotd5" name="type">
                                                                    <option value="Parts" {if $rate[q].LABOR_TYPE == "Parts"} selected{/if}>Parts</option>
                                                                    <option value="Service" {if $rate[q].LABOR_TYPE == "Service"} selected{/if}>Service</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap>
                                                                <input type="hidden" name="id" value="{$rate[q].LABOR_RATE_ID}">
                                                                <input type="submit" name="submit" value="Delete">
                                                                <input type="submit" name="submit" value="Edit">
                                                            </td>
                                                        </tr>
                                                    </form>
						{/section}
                                                </table>
                                                <b>Add New</b>
                                                <form method="POST" action="?page=control:edit_rate">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olohead">Display</td>
                                                            <td class="olohead">Amount</td>
                                                            <td class="olohead">Cost</td>
                                                            <td class="olohead">Type</td>
                                                            <td class="olohead">Action</td>
                                                            <!--<td class="olohead">Manufacture</td> -->
                                                        </tr><tr>
                                                            <td class="olotd4"><input class="olotd5" type="text" name="display" size="60"></td>
                                                            <td class="olotd4">$<input class="olotd5" type="text" name="amount" size="6"></td>
                                                            <td class="olotd4">$<input class="olotd5" type="text" name="cost" size="6"></td>
                                                            <td class="olotd4" nowrap><select class="olotd5" name="type">
                                                                    <option value="Parts">Parts</option>
                                                                    <option value="Service" SELECTED>Service</option>
                                                                </select></td>
                                                            <td class="olotd4"><input type="submit" name="submit" value="New"></td>
                                                            <!--<td class="olotd4" nowrap><input class="olotd5" type="text" name="manufacture" value="{$rate[q].LABOR_MANUF}" size="20"></td> -->
                                                        </tr>


                                                        <!--<form action="?page=control:edit_rate" method="post" enctype="multipart/form-data">
                                                                    <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                        <tr>
                                                                            <td width="246">
                                                                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                                <input name="userfile" type="file" id="userfile">
                                                                            </td>
                                                                            <td width="80"><input name="upload" type="submit" class="box" id="upload" value=" Upload "></td>
                                                                        </tr>
                                                                    </table>
                                                                </form> -->



                                                    </table>
                                                    {if $cred.EMPLOYEE_TYPE == 1 ||  $cred.EMPLOYEE_TYPE == 2 || $cred.EMPLOYEE_TYPE == 4}
                                                    <table>
                                                        <tr><td>{include file="../js/myitcrm.js"}
                                                                <a>Upload Rate file and Update List</a>
                                                                {literal}
                                                                <form action="?page=control:edit_rate" method="post" enctype="multipart/form-data" onsubmit="try { var myValidator = validate_rate_upload; } catch(e) { return true; } return myValidator(this);">
                                                                    {/literal}
                                                                    <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                        <tr>
                                                                            <td width="246">
                                                                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                                <input name="userfile" type="file" id="userfile">
                                                                            </td>
                                                                            <td width="80"><input name="upload" type="submit" class="box" id="upload" value=" Load " ><br>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </form>
                                                            </td></tr>
                                                    </table>
                                                    {/if}
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


    