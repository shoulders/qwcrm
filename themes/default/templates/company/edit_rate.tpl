<!-- edit_rate.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%">&nbsp;Edit Billing Rates</td>
                </tr>
                <tr>
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
                                                        <td class="olohead" align="center">Manufacturer</td>
                                                        <td class="olohead" align="center">Action</td>
                                                    </tr>
                                                    <tr>
                                                        {section name=q loop=$rate}
                                                            <form method="POST" action="?page=company:edit_rate">
                                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">
                                                                    <td class="olotd4" nowrap>{$rate[q].LABOR_RATE_ID}</td>
                                                                    <td class="olotd4" nowrap><input name="display" class="olotd5" size="50" value="{$rate[q].LABOR_RATE_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$rate[q].LABOR_RATE_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="cost" class="olotd5" size="10" value="{$rate[q].LABOR_RATE_COST}" type="text" maxlength="10" pattern="{literal}[[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="active">
                                                                            <option value="0" {if $rate[q].LABOR_RATE_ACTIVE == 0} selected{/if}>No</option>
                                                                            <option value="1" {if $rate[q].LABOR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="type">
                                                                            <option value="Parts" {if $rate[q].LABOR_TYPE == "Parts"} selected{/if}>Parts</option>
                                                                            <option value="Service" {if $rate[q].LABOR_TYPE == "Service"} selected{/if}>Service</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap><input name="manufacturer" class="olotd5" size="20" value="{$rate[q].LABOR_MANUF}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <input type="hidden" name="id" value="{$rate[q].LABOR_RATE_ID}">
                                                                        <input type="submit" name="submit" value="Delete">
                                                                        <input type="submit" name="submit" value="Edit">
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        {/section}            
                                                    </tr>                                                    
                                                </table>
                                                <b>Add New</b>
                                                <form method="POST" action="?page=control:edit_rate">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olohead">Display</td>
                                                            <td class="olohead">Amount</td>
                                                            <td class="olohead">Cost</td>
                                                            <td class="olohead">Type</td>                                                            
                                                            <td class="olohead">Manufacturer</td>
                                                            <td class="olohead">Action</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4"><input name="display" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            <td class="olotd4">{$currency_sym}<input name="amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                            <td class="olotd4">{$currency_sym}<input name="cost" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                            <td class="olotd4" nowrap>
                                                                <select class="olotd5" name="type">
                                                                    <option value="Parts">Parts</option>
                                                                    <option value="Service" selected>Service</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap><input name="manufacturer" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            <td class="olotd4"><input type="submit" name="submit" value="New"></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                                {if $login_account_type_id.EMPLOYEE_TYPE == 1 ||  $login_account_type_id.EMPLOYEE_TYPE == 2 || $login_account_type_id.EMPLOYEE_TYPE == 3}                                                    
                                                    <script>
                                                    {literal} 
                                                        $(function(){
                                                            $("#newfile").click(function(event) {
                                                                event.preventDefault();
                                                                $("#newuserform").slideToggle();
                                                            });
                                                            $("#newuserform a").click(function(event) {
                                                                event.preventDefault();
                                                                $("#newuserform").slideUp();
                                                            });
                                                        });
                                                    {/literal}   
                                                    </script>                                                    
                                                    <a href="#" id="newfile">{$translate_invoice_rates_add_file}</a>
                                                    <div id="newuserform">
                                                        <table width="100%">
                                                            <tr>
                                                                <td><a>{$translate_invoice_rates_example}</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td><img src="{$theme_images_dir}rate_upload.PNG" alt="CSV Example screenshot" height="150"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <form action="?page=control:edit_rate" method="post" enctype="multipart/form-data">
                                                                        <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                            <tr>
                                                                                <td width="246">
                                                                                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                                    <input name="userfile" type="file" id="userfile">
                                                                                </td>
                                                                                <td width="80"><input name="upload" type="submit" class="box" id="upload" value="Load"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                {/if}
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