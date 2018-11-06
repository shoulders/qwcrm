<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Payments for{/t} {t}Invoice{/t} {$invoice_details.invoice_id} - {$client_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}PAYMENT_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}PAYMENT_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        
                        <table width="100%" border="0" cellpadding="10" cellspacing="0">

                            <!-- Invoice Details -->
                            <tr>
                                <td>                                                
                                    {include file='payment/blocks/new_invoice_details_block.tpl'}
                                </td>
                            </tr>

                            <!-- Cancel Button -->
                            <tr>
                                <td>                                                
                                    <button type="button" class="olotd4" onclick="window.location.href='index.php?component=invoice&page_tpl=edit&invoice_id={$invoice_id}';">{t}Cancel{/t}</button>
                                </td>
                            </tr>                            

                            <!-- Payments -->                           
                            <tr>
                                <td>                                                
                                    {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=_gettext("Payments")}
                                </td>
                            </tr>                            
                        </table>


                        <form method="post" action="index.php?component=payment&page_tpl=new&invoice_id={$invoice_id}">
                            <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                
                                <!-- Payment Methods -->
                                <tr>
                                    <td>
                                        {include file='payment/blocks/display_payment_methods_block.tpl'}
                                    </td>
                                </tr>                            

                                <!-- Submit Button -->
                                <tr>
                                    <td>                                        
                                        <button type="submit" name="submit" value="submit">{t}Submit Payment{/t}</button>
                                    </td>
                                </tr>
                                
                            </table>
                        </form>                          
                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>