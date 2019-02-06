<!-- prefill_items.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Invoice Prefill Items{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_PREFILL_ITEMS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_PREFILL_ITEMS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td>
                                                <b>{t}Current Invoice Prefill Items{/t}</b>                                                
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">{t}ID{/t}</td>
                                                        <td class="olohead">{t}Description{/t}</td>
                                                        <td class="olohead" align="center">{t}Type{/t}</td>
                                                        <td class="olohead" align="center">{t}Net Amount{/t}</td>
                                                        <td class="olohead" align="center">{t}Active{/t}</td>                                                                                                               
                                                        <td class="olohead" align="center">{t}Action{/t}</td>
                                                    </tr>
                                                    <tr>
                                                        {section name=q loop=$invoice_prefill_items}
                                                            <form method="post" action="index.php?component=invoice&page_tpl=prefill_items">
                                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">
                                                                    <td class="olotd4" nowrap>{$invoice_prefill_items[q].invoice_prefill_id}</td>
                                                                    <td class="olotd4" nowrap><input name="description" class="olotd5" size="50" value="{$invoice_prefill_items[q].description}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumericPunctuation(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="type">
                                                                            <option value="Labour" {if $invoice_prefill_items[q].type == 'Labour'} selected{/if}>{t}Labour{/t}</option>
                                                                            <option value="Parts" {if $invoice_prefill_items[q].type == 'Parts'} selected{/if}>{t}Parts{/t}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_prefill_items[q].net_amount}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"></td>
                                                                    <td class="olotd4" nowrap>
                                                                        <select class="olotd5" name="active">
                                                                            <option value="0" {if $invoice_prefill_items[q].active == 0} selected{/if}>{t}No{/t}</option>
                                                                            <option value="1" {if $invoice_prefill_items[q].active == 1} selected{/if}>{t}Yes{/t}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="olotd4" nowrap>
                                                                        <input type="hidden" name="invoice_prefill_id" value="{$invoice_prefill_items[q].invoice_prefill_id}">
                                                                        <button type="submit" name="submit" value="delete" onclick="return confirmChoice('{t}Are You sure you want to delete this labour rate item.{/t}');">{t}Delete{/t}</button>
                                                                        <button type="submit" name="submit" value="update" onclick="return confirmChoice('{t}Are You sure you want to update this labour rate item.{/t}');">{t}Update{/t}</button>
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        {/section}            
                                                    </tr>                                                    
                                                </table>
                                                <b>{t}Add New Prefill Item{/t}</b>
                                                <form method="post" action="index.php?component=invoice&page_tpl=prefill_items">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="olohead">{t}Description{/t}</td>
                                                            <td class="olohead">{t}Type{/t}</td>
                                                            <td class="olohead">{t}Amount{/t}</td>
                                                            <td class="olohead">{t}Active{/t}</td>                                                            
                                                            <td class="olohead">{t}Action{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4"><input name="description" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyAlphaNumericPunctuation(event);"></td>
                                                            <td class="olotd4" nowrap>
                                                                <select class="olotd5" name="type">
                                                                    <option value="Labour" selected>{t}Labour{/t}</option>
                                                                    <option value="Parts">{t}Parts{/t}</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4">{$currency_sym}<input name="net_amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"></td>
                                                            <td class="olotd4" nowrap>
                                                                <select class="olotd5" name="active">
                                                                    <option value="0" selected>{t}No{/t}</option>
                                                                    <option value="1">{t}Yes{/t}</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4"><button type="submit" name="submit" value="new">{t}New{/t}</button</td>
                                                        </tr>
                                                    </table>
                                                </form>
                                                <div>
                                                    <form method="post" action="index.php?component=invoice&page_tpl=prefill_items">
                                                        <strong><span style="color: green;">{t}Export Prefill Items as a CSV file{/t}</span></strong>                                                        
                                                        <button type="submit" name="export_invoice_prefill_items" value="export">{t}Export{/t}</button>
                                                    </form>
                                                </div>
                                                {if $login_usergroup_id == 1 || $login_usergroup_id == 2}                                                  
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
                                                    <a href="javascript:void(0)" id="newfile">{t}Upload a Prefill Items CSV file{/t}</a>
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
                                                                    <form action="index.php?component=invoice&page_tpl=prefill_items" method="post" enctype="multipart/form-data">
                                                                        <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                                                                            <tr>
                                                                                <td width="246">                                                                                    
                                                                                    <input name="invoice_prefill_csv" type="file" accept=".csv">
                                                                                </td>
                                                                                <td width="80"><button id="csv_upload" name="submit" type="submit" class="box" value="csv_upload" onclick="return confirmChoice('{t}Are You sure you want to upload this CSV file with new prefill items.{/t}');">{t}Upload{/t}</button></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="246"><input id="empty_prefill_items_table" name="empty_prefill_items_table" type="checkbox" value="1">{t}Empty Prefill Table{/t}</td>                                                                                
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