<!-- new_gift.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
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
                    <td class="menuhead2" width="80%">&nbsp;{t}New Gift Certificate for{/t} {t}Client{/t} {$client_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}GIFTCERT_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}GIFTCERT_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">     
                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                            <tr>
                                <td class="olotd4">                                    
                                    <form method="post" action="index.php?component=giftcert&page_tpl=new" name="giftcert_new" id="giftcert_new">
                                        <table>
                                            <tr>
                                                <td><b>{t}Client{/t}</b></td>
                                                <td><a href="index.php?component=client&page_tpl=details&client_id={$client_id}">{$client_details.display_name}</a></td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Expires{/t}</b></td>
                                                <td>
                                                    <input id="date_expires" name="date_expires" class="olotd4" size="10" value="" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                    <button type="button" id="date_expires_button">+</button>
                                                    <script>                                                       
                                                        Calendar.setup( {
                                                            trigger     : "date_expires_button",
                                                            inputField  : "date_expires",
                                                            dateFormat  : "{$date_format}"
                                                        } );                                                     
                                                    </script>                                                                                                        
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>{t}Amount{/t}</b></td>                                                
                                                <td>{$currency_sym}<input name="amount" class="olotd5" size="10" value="" type="text" maxlength="10" required pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>{t}Note{/t}:</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><textarea class="olotd5" rows="15" cols="70" name="note"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input name="client_id" value="{$client_id}" type="hidden" />
                                                    <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>
                                                    <button type="button" class="olotd4" onclick="window.location.href='index.php?component=client&page_tpl=details&client_id={$client_id}';">{t}Cancel{/t}</button>
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
        </td>
    </tr>
</table>