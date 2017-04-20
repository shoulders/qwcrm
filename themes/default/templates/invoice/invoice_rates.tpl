<!-- invoice_rates.tpl -->
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
                                                        {section name=q loop=$invoice_rates_items}
                                                            <form method="POST" action="?page=invoice:invoice_rates">
                                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">
                                                                    <td class="olotd4" nowrap>{$invoice_rates_items[q].LABOUR_RATE_ID}</td>
                                                                    <td class="olotd4" nowrap><input name="display" class="olotd5" size="50" value="{$invoice_rates_items[q].LABOUR_RATE_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_rates_items[q].LABOUR_RATE_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="cost" class="olotd5" size="10" value="{$invoice_rates_items[q].LABOUR_RATE_COST}" type="text" maxlength="10" pattern="{literal}[[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="active">
                                                                            <option value="0" {if $invoice_rates_items[q].LABOUR_RATE_ACTIVE == 0} selected{/if}>No</option>
                                                                            <option value="1" {if $invoice_rates_items[q].LABOUR_RATE_ACTIVE == 1} selected{/if}>Yes</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="type">
                                                                            <option value="Parts" {if $invoice_rates_items[q].LABOUR_TYPE == "Parts"} selected{/if}>Parts</option>
                                                                            <option value="Service" {if $invoice_rates_items[q].LABOUR_TYPE == "Service"} selected{/if}>Service</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap><input name="manufacturer" class="olotd5" size="20" value="{$invoice_rates_items[q].LABOUR_MANUF}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <input type="hidden" name="labour_rate_id" value="{$invoice_rates_items[q].LABOUR_RATE_ID}">
                                                                        <button type="submit" name="submit" value="delete">Delete</button>
                                                                        <button type="submit" name="submit" value="update">Update</button>
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        {/section}            
                                                    </tr>                                                    
                                                </table>
                                                <b>Add New</b>
                                                <form method="POST" action="?page=invoice:invoice_rates">
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
                                                            <td class="olotd4"><button type="submit" name="submit" value="new">New</button</td>
                                                        </tr>
                                                    </table>
                                                </form>
                                                {if $login_account_type_id == 1 || $login_account_type_id == 2}                                                  
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
                                                                <td><img src="{$theme_images_dir}rate_upload.png" alt="CSV Example screenshot" height="150"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <form action="?page=invoice:invoice_rates" method="post" enctype="multipart/form-data">
                                                                        <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                            <tr>
                                                                                <td width="246">                                                                                    
                                                                                    <input name="invoice_rates_csv" type="file" id="invoice_rates_csv">
                                                                                </td>
                                                                                <td width="80"><button id="csv_upload" name="csv_upload" type="submit" class="box" value="csv_upload">CSV Upload</button></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="246"><input id="empty_invoice_rates" name="empty_invoice_rates" type="checkbox" value="1">Empty Invoice Rates Table</td>                                                                                
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