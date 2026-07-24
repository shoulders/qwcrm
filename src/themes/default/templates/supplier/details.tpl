<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Supplier Details for{/t} {$supplier_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SUPPLIER_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}SUPPLIER_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <div id="tabs_container">
                                        <ul class="tabs">
                                            <li class="active"><a href="javascript:void(0)" rel="#tab_1_contents" class="tab">{t}Supplier Details{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_2_contents" class="tab">{t}Expenses{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_3_contents" class="tab">{t}Other Incomes{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_4_contents" class="tab">{t}Payments{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_5_contents" class="tab">{t}Credit Notes{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_6_contents" class="tab">{t}Account{/t}</a></li>
                                        </ul>

                                        <!-- This is used so the contents don't appear to the right of the tabs -->
                                        <div class="clear"></div>

                                        <!-- This is a div that hold all the tabbed contents -->
                                        <div class="tab_contents_container">

                                            <!-- Tab 1 Contents (Supplier Details) -->
                                            <div id="tab_1_contents" class="tab_contents tab_contents_active">
                                                {include file='supplier/blocks/display_supplier_details_block.tpl'}
                                            </div>

                                            <!-- Tab 2 Contents (Expenses) -->
                                            <div id="tab_2_contents" class="tab_contents">
                                                {include file='expense/blocks/display_expenses_block.tpl' display_expenses=$display_expenses block_title=_gettext("Expenses")}
                                            </div>

                                            <!-- Tab 3 Contents (Other Incomes) -->
                                            <div id="tab_3_contents" class="tab_contents">
                                                {include file='otherincome/blocks/display_otherincomes_block.tpl' display_otherincomes=$display_otherincomes block_title=_gettext("Other Incomes")}
                                            </div>

                                            <!-- Tab 4 Contents (Payments) -->
                                            <div id="tab_4_contents" class="tab_contents">
                                                {include file='payment/blocks/display_payments_block.tpl' display_payments=$payments_credits block_title=_gettext("Credits")}
                                                <br>
                                                {include file='payment/blocks/display_payments_block.tpl' display_payments=$payments_debits block_title=_gettext("Debits")}
                                            </div>

                                            <!-- Tab 5 Contents (Credit Notes) -->
                                            <div id="tab_5_contents" class="tab_contents">
                                                {include file='creditnote/blocks/display_creditnotes_block.tpl' display_creditnotes=$display_creditnotes block_title=_gettext("Credit Notes")}
                                            </div>

                                            <!-- Tab 6 Contents (Account) -->
                                            <div id="tab_6_contents" class="tab_contents">
                                                {include file='supplier/blocks/details_account_block.tpl'}
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
