<!-- status.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=payment&page_tpl=details&payment_id={$payment_id}">{t}Payment{/t} {$payment_id}</a></td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}PAYMENT_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}PAYMENT_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Void{/t}</td>
                                <td class="olohead" align="center">{t}Delete{/t}</td>
                            </tr>
                            <tr>

                                <!-- Update Status Button -->
                                <td class="olotd4" align="center" width="33%">
                                    <p><b>{t}Current Status{/t}:</b> {$payment_status_display_name}</p>
                                    {if $allowed_to_change_status}
                                        <form action="index.php?component=payment&page_tpl=status&payment_id={$payment_id}" method="post">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$payment_selectable_statuses}
                                                    <option value="{$payment_selectable_statuses[s].status_key}"{if $payment_status == $payment_selectable_statuses[s].status_key} selected{/if}>{t}{$payment_selectable_statuses[s].display_name}{/t}</option>
                                                {/section}
                                            </select>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />
                                        </form>
                                    {else}
                                        {t}This Payment cannot have it's status changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Void Button -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_void}
                                        <form method="post" action="index.php?component=payment&page_tpl=status&payment_id={$payment_id}">
                                            <textarea id="qform[reason_for_voiding]" name="qform[reason_for_voiding]" class="olotd5 mceNoEditor" cols="25" rows="3" maxlength="100" onkeydown="return onlyAlphaNumeric(event);" required placeholder="{t}Reason for Voiding{/t}"/></textarea>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="void_payment" value="{t}Void{/t}" type="submit" onclick="confirm('{t}Are you sure you want to void this payment?{/t}');">
                                        </form>
                                    {else}
                                        {t}This Payment cannot be voided because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Delete Button -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_delete}
                                        <form method="post" action="index.php?component=payment&page_tpl=status&payment_id={$payment_id}">
                                            <input name="delete_payment" value="{t}Delete{/t}" type="submit" onclick="return confirm('{t}Are you sure you want to delete this Payment?{/t}');">
                                        </form>
                                    {else}
                                        {t}This Payment cannot be deleted because it's current state does not allow it.{/t}
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
