<!-- invoice_rates.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Labour Rates{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_LABOUR_RATES_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_LABOUR_RATES_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td>
                                                <b>{t}Billing rates per Unit.</b>                                                
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">{t}SKU{/t}</td>
                                                        <td class="olohead">{t}Description{/t}</td>
                                                        <td class="olohead" align="center">{t}Amount{/t}</td>
                                                        <td class="olohead" align="center">{t}Cost{/t}</td>
                                                        <td class="olohead" align="center">{t}Active{/t}</td>
                                                        <td class="olohead" align="center">{t}Type{/t}</td>
                                                        <td class="olohead" align="center">{t}Manufacturer{/t}</td>
                                                        <td class="olohead" align="center">{t}Action{/t}</td>
                                                    </tr>
                                                    <tr>
                                                        {section name=q loop=$invoice_labour_rates_items}
                                                            <form method="POST" action="index.php?page=invoice:labour_rates">
                                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">
                                                                    <td class="olotd4" nowrap>{$invoice_labour_rates_items[q].LABOUR_RATE_ID}</td>
                                                                    <td class="olotd4" nowrap><input name="display" class="olotd5" size="50" value="{$invoice_labour_rates_items[q].LABOUR_RATE_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_labour_rates_items[q].LABOUR_RATE_AMOUNT}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="cost" class="olotd5" size="10" value="{$invoice_labour_rates_items[q].LABOUR_RATE_COST}" type="text" maxlength="10" pattern="{literal}[[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="active">
                                                                            <option value="0" {if $invoice_labour_rates_items[q].LABOUR_RATE_ACTIVE == 0} selected{/if}>{t}No{/t}</option>
                                                                            <option value="1" {if $invoice_labour_rates_items[q].LABOUR_RATE_ACTIVE == 1} selected{/if}>{t}Yes{/t}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="type">
                                                                            <option value="Parts" {if $invoice_labour_rates_items[q].LABOUR_TYPE == 'Parts'} selected{/if}>{t}Parts{/t}</option>
                                                                            <option value="Service" {if $invoice_labour_rates_items[q].LABOUR_TYPE == 'Service'} selected{/if}>{t}Service{/t}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap><input name="manufacturer" class="olotd5" size="20" value="{$invoice_labour_rates_items[q].LABOUR_MANUF}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <input type="hidden" name="labour_rate_id" value="{$invoice_labour_rates_items[q].LABOUR_RATE_ID}">
                                                                        <button type="submit" name="submit" value="delete" onClick="return confirmDelete('{t}Are You sure you want to delete this labour rate item.{/t}');">{t}Delete{/t}</button>
                                                                        <button type="submit" name="submit" value="update" onClick="return confirmDelete('{t}Are You sure you want to update this labour rate item.{/t}');">{t}Update{/t}</button>
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        {/section}            
                                                    </tr>                                                    
                                                </table>
                                                <b>{t}Add New{/t}</b>
                                                <form method="POST" action="index.php?page=invoice:labour_rates">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olohead">{t}Display{/t}</td>
                                                            <td class="olohead">{t}Amount{/t}</td>
                                                            <td class="olohead">{t}Cost{/t}</td>
                                                            <td class="olohead">{t}Type{/t}</td>                                                            
                                                            <td class="olohead">{t}Manufacturer{/t}</td>
                                                            <td class="olohead">{t}Action{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4"><input name="display" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            <td class="olotd4">{$currency_sym}<input name="amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                            <td class="olotd4">{$currency_sym}<input name="cost" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"></td>
                                                            <td class="olotd4" nowrap>
                                                                <select class="olotd5" name="type">
                                                                    <option value="Parts">{t}Parts{/t}</option>
                                                                    <option value="Service" selected>{t}Service{/t}</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap><input name="manufacturer" class="olotd5" size="20" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            <td class="olotd4"><button type="submit" name="submit" value="new">{t}New{/t}</button</td>
                                                        </tr>
                                                    </table>
                                                </form>
                                                {if $login_account_type_id == 1 || $login_account_type_id == 2}                                                  
                                                    <script>                                                    
                                                        $(function() {
                                                            $("#newfile").click(function(event) {
                                                                event.preventDefault();
                                                                $("#newuserform").slideToggle();
                                                            } );
                                                            $("#newuserform a").click(function(event) {
                                                                event.preventDefault();
                                                                $("#newuserform").slideUp();
                                                            } );
                                                        } );                                                    
                                                    </script>                                                    
                                                    <a href="#" id="newfile">{t}Upload a csv file into rates table{/t}</a>
                                                    <div id="newuserform">
                                                        <table width="100%">
                                                            <tr>
                                                                <td><a>{t}CSV File example{/t}</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td><img src="{$theme_images_dir}rate_upload.png" alt="CSV Example screenshot" height="150"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <form action="index.php?page=invoice:labour_rates" method="post" enctype="multipart/form-data">
                                                                        <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                            <tr>
                                                                                <td width="246">                                                                                    
                                                                                    <input name="invoice_rates_csv" type="file" id="invoice_rates_csv">
                                                                                </td>
                                                                                <td width="80"><button id="csv_upload" name="submit" type="submit" class="box" value="csv_upload" onClick="return confirmDelete('{t}Are You sure you want to upload this CSV file with new labour rate items.{/t}');">{t}CSV Upload{/t}</button></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="246"><input id="empty_invoice_rates" name="empty_invoice_rates" type="checkbox" value="1">{t}Empty Invoice Rates Table{/t}</td>                                                                                
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