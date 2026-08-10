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
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=expense&page_tpl=details&expense_id={$expense_id}">{t}Expense{/t} {$expense_id}</a></td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}EXPENSE_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}EXPENSE_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Unapprove{/t}</td>
                            </tr>
                            <tr>

                                <!-- Update Status Button -->
                                <td class="olotd4" align="center" width="50%" height="150">
                                    <p><b>{t}Current Status{/t}:</b> {$expense_status_display_name}</p>
                                    {if $allowed_to_change_status}
                                        <p>&nbsp;</p>
                                        <form action="index.php?component=expense&page_tpl=status&expense_id={$expense_id}" method="post">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$expense_selectable_statuses}
                                                    <option value="{$expense_selectable_statuses[s].status_key}"{if $expense_status == $expense_selectable_statuses[s].status_key} selected{/if}>{t}{$expense_selectable_statuses[s].display_name}{/t}</option>
                                                {/section}
                                            </select>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />
                                        </form>
                                    {else}
                                        {t}This Expense cannot have it's status changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Unapprove Button -->
                                <td class="olotd4" align="center" width="50%" height="150">
                                    {if $allowed_to_unapprove}
                                        <form method="post" action="index.php?component=expense&page_tpl=status&expense_id={$expense_id}">
                                            <input class="olotd4" name="unapprove_expense" value="{t}Unapprove{/t}" type="submit" onclick="confirm('{t}Are you sure you want to unapprove this expense?{/t}');">
                                            <p>{t}This not a normal financial practice, but I am keeping this feature for now.{/t}</p>
                                        </form>
                                    {else}
                                        {t}This expense cannot be unapproved. You can only unapprove an expense if it is unpaid and does not have any transactions against it.{/t}
                                    {/if}
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Void{/t}</td>
                                <td class="olohead" align="center">{t}Delete{/t}</td>
                            </tr>
                            <tr>

                                <!-- Void Button -->
                                <td class="olotd4" align="center" width="50%" height="150">
                                    {if $allowed_to_void}
                                        <form method="post" action="index.php?component=expense&page_tpl=status&expense_id={$expense_id}">
                                            <textarea id="qform[reason_for_voiding]" name="qform[reason_for_voiding]" class="olotd5 mceNoEditor" cols="25" rows="3" maxlength="100" onkeydown="return onlyAlphaNumeric(event);" required placeholder="{t}Reason for Voiding{/t}"></textarea>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="void_expense" value="{t}Void{/t}" type="submit" onclick="confirm('{t}Are you sure you want to void this expense? All records relating to this expense will be kept but removed from the relevant financial calculations.{/t}');">
                                        </form>
                                    {else}
                                        {t}This expense cannot be voided. You can only void an expense if it is open and does not have any payments.{/t}
                                    {/if}
                                </td>

                                <!-- Delete Button -->
                                <td class="olotd4" align="center" width="50%" height="150">
                                    {if $allowed_to_delete}
                                        <form method="post" action="index.php?component=expense&page_tpl=status&expense_id={$expense_id}">
                                            <input name="delete_expense" value="{t}Delete{/t}" type="submit" onclick="return confirm('{t}Are you sure you want to delete this expense?{/t}');">
                                        </form>
                                    {else}
                                        {t}This Expense cannot be deleted because it's current state does not allow it.{/t}
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
