<?php /* Smarty version 2.6.9, created on 2010-02-17 15:24:34
         compiled from customer/email.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'customer/email.tpl', 179, false),)), $this); ?>
<!-- Customer Details TPL -->
<?php echo '
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "advlink,iespell,preview",
        theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste",
        theme_advanced_toolbar_location : "bottom",
        theme_advanced_toolbar_align : "center",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        width : "100%"
    });
</script>
'; ?>

<br>
<table width="100%"
       <tr>
        <td>
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab">New Email</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab">Past Emails</a></li>
                 </ul>

                <!-- This is used so the contents don't appear to the right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="100%" border="0" cellpadding="5" cellspacing="5">
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['customer_details']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                                            <td class="menuhead2" width="80%">
                                                &nbsp;Send Email to <?php echo $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_DISPLAY_NAME']; ?>
</td>
                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="?page=customer:edit&customer_id=<?php echo $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_ID']; ?>
&page_title=Edit%20Customer%20Information" target="new"><img src="images/icons/edit.gif"  alt="" height="16" border="0"> Edit</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="menutd"> <?php if ($this->_tpl_vars['error_msg'] != ""): ?>
                                                            <br> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "core/error.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                            <br> <?php endif; ?>
                                                            <!-- Content -->
                                                            <form  action="index.php?page=customer:email" method="POST" enctype="multipart/form-data" >
                                                            <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>From:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_from" value="<?php echo $this->_tpl_vars['employee_details']['EMPLOYEE_EMAIL']; ?>
" size="60" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>To:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_to" value="<?php echo $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_EMAIL']; ?>
" size="60" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>Subject:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_subject" value="" size="60">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <p></p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Message:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <textarea name="message_body" rows="15" cols="70" >
                                                                        </textarea>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>BCC:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="checkbox" name="bcc">
                                                                    </td>
                                                                </tr>
                                                                <!--TODO: Set read Receipts for sent emails
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Read Receipt?</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="checkbox" name="rr" value="1">
                                                                    </td>
                                                                </tr>-->
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Priority:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                    <select class="olotd5" name="priority">
                                                                    <option value="1">Low</option>
                                                                    <option value="2" SELECTED>Normal</option>
                                                                    <option value="3">High</option>
                                                                </select>

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Attachment:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                        <input type="file" name="attachment" size="50" id="attachment">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <input type="submit" name="submit" id="submit" value="Send" >
                                                                    </td>
                                                                </tr>
                                                                                                                               
                                                                <?php $this->assign('customer_id', $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_ID']); ?> <?php $this->assign('customer_name', $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_DISPLAY_NAME']); ?>
                                                            </table>
                                                            <?php endfor; endif; ?>
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
                    </div>

                    <!-- Tab 2 Contents -->
                    <div id="tab_2_contents" class="tab_contents">
                        <br>
                        <b><?php echo $this->_tpl_vars['translate_customer_open_work_orders']; ?>
</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_wo_id']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_date_open']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_scope']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_status']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_tech']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_action']; ?>
</td>
                            </tr> <?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['open_work_orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>
                            <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['CUSTOMER_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['translate_customer_work_order_id']; ?>
 <?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
,';" class="row1">
                                <td class="olotd4">
                                    <a href="?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['CUSTOMER_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['translate_customer_work_order_id']; ?>
 <?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
"><?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
</a></td>
                                <td class="olotd4">
                                    <?php echo ((is_array($_tmp=$this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_OPEN_DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%m-%y") : smarty_modifier_date_format($_tmp, "%d-%m-%y")); ?>
</td>
                                <td class="olotd4">
                                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['customer_details']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
 echo $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_DISPLAY_NAME'];  endfor; endif; ?></td>
                                <td class="olotd4">
                                    <?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_SCOPE']; ?>
</td>
                                <td class="olotd4">
                                    <?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['CONFIG_WORK_ORDER_STATUS']; ?>
</td>
                                <td class="olotd4"> <?php if ($this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_ID'] != ''): ?>
                                    <img src="images/icons/16x16/view+.gif" border="0" alt="" <?php echo 'onMouseOver="ddrivetip(\'<center><b>';  echo $this->_tpl_vars['translate_contact'];  echo '</b></center><hr>
                                    <b>';  echo $this->_tpl_vars['translate_work'];  echo ' </b>
                                    ';  echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_WORK_PHONE'];  echo '
                                    <br>
                                    <b>';  echo $this->_tpl_vars['translate_mobile']; ?>
 <?php echo '</b>
                                    ';  echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_MOBILE_PHONE'];  echo '
                                    <br>
                                    <b>';  echo $this->_tpl_vars['translate_home']; ?>
 <?php echo '</b> ';  echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_HOME_PHONE']; ?>
')" onMouseOut="hideddrivetip()">
                                    <a class="link1" href="?page=employees:employee_details&employee_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
"><?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
</a> <?php else: ?> Not Assigned <?php endif; ?></td>
                                <td class="olotd4" align="center">
                                    <a href="?page=workorder:print&wo_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['CUSTOMER_ID']; ?>
&escape=1" target="new">
                                        <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('<?php echo $this->_tpl_vars['translate_customer_print']; ?>
')" onMouseOut="hideddrivetip()"></a>
                                    <a href="?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['open_work_orders'][$this->_sections['a']['index']]['CUSTOMER_ID']; ?>
">
                                        <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('<?php echo $this->_tpl_vars['translate_customer_view_wo']; ?>
')" onMouseOut="hideddrivetip()"></a> </td>
                            </tr> <?php endfor; endif; ?>
                        </table>
                        <br>
                        <b><?php echo $this->_tpl_vars['translate_customer_closed_work_orders']; ?>
</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_wo_id']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_date_open']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_scope']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_status']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_tech']; ?>
</td>
                                <td class="olohead">
                                    <?php echo $this->_tpl_vars['translate_customer_action']; ?>
</td>
                            </tr> <?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['closed_work_orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['b']['show'] = true;
$this->_sections['b']['max'] = $this->_sections['b']['loop'];
$this->_sections['b']['step'] = 1;
$this->_sections['b']['start'] = $this->_sections['b']['step'] > 0 ? 0 : $this->_sections['b']['loop']-1;
if ($this->_sections['b']['show']) {
    $this->_sections['b']['total'] = $this->_sections['b']['loop'];
    if ($this->_sections['b']['total'] == 0)
        $this->_sections['b']['show'] = false;
} else
    $this->_sections['b']['total'] = 0;
if ($this->_sections['b']['show']):

            for ($this->_sections['b']['index'] = $this->_sections['b']['start'], $this->_sections['b']['iteration'] = 1;
                 $this->_sections['b']['iteration'] <= $this->_sections['b']['total'];
                 $this->_sections['b']['index'] += $this->_sections['b']['step'], $this->_sections['b']['iteration']++):
$this->_sections['b']['rownum'] = $this->_sections['b']['iteration'];
$this->_sections['b']['index_prev'] = $this->_sections['b']['index'] - $this->_sections['b']['step'];
$this->_sections['b']['index_next'] = $this->_sections['b']['index'] + $this->_sections['b']['step'];
$this->_sections['b']['first']      = ($this->_sections['b']['iteration'] == 1);
$this->_sections['b']['last']       = ($this->_sections['b']['iteration'] == $this->_sections['b']['total']);
?>
                            <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['CUSTOMER_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['translate_customer_work_order_id']; ?>
 <?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
,';" class="row1">
                                <td class="olotd4">
                                    <a href="?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['CUSTOMER_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['translate_customer_work_order_id']; ?>
 <?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
"><?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
</a></td>
                                <td class="olotd4">
                                    <?php echo ((is_array($_tmp=$this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_OPEN_DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['date_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['date_format'])); ?>
</td>
                                <td class="olotd4">
                                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['customer_details']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
 echo $this->_tpl_vars['customer_details'][$this->_sections['i']['index']]['CUSTOMER_DISPLAY_NAME'];  endfor; endif; ?></td>
                                <td class="olotd4">
                                    <?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_SCOPE']; ?>
</td>
                                <td class="olotd4">
                                    <?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['CONFIG_WORK_ORDER_STATUS']; ?>
</td>
                                <td class="olotd4"> <?php if ($this->_tpl_vars['closed_work_orders'][$this->_sections['a']['index']]['EMPLOYEE_ID'] != ''): ?>
                                    <img src="images/icons/16x16/view+.gif" border="0" alt="" <?php echo 'onMouseOver="ddrivetip(\'<center><b>{$translate_contact}</b></center><hr>
                                    <b>{$translate_work} </b>
                                    {$open_work_orders[a].EMPLOYEE_WORK_PHONE}
                                    <br>
                                    <b>{$translate_mobile} </b>
                                    {$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}
                                    <br>
                                    <b>{$translate_home} </b> {literal}{$closed_work_orders[a].EMPLOYEE_HOME_PHONE}\')" onMouseOut="hideddrivetip()">'; ?>

                                    <a class="link1" href="?page=employees:employee_details&employee_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['EMPLOYEE_ID']; ?>
&page_title=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
"><?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
</a> <?php else: ?> Not Assigned <?php endif; ?></td>
                                <td class="olotd4" align="center">
                                    <a href="?page=workorder:print&wo_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['CUSTOMER_ID']; ?>
&escape=1" target="new">
                                        <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('<?php echo $this->_tpl_vars['translate_customer_print']; ?>
')" onMouseOut="hideddrivetip()"></a>
                                    <a href="?page=workorder:view&wo_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['WORK_ORDER_ID']; ?>
&customer_id=<?php echo $this->_tpl_vars['closed_work_orders'][$this->_sections['b']['index']]['CUSTOMER_ID']; ?>
">
                                        <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('<?php echo $this->_tpl_vars['translate_customer_view_wo']; ?>
')" onMouseOut="hideddrivetip()"></a> </td>
                            </tr> <?php endfor; endif; ?>
                        </table>
                    </div>                  
                </div>
            </div>
        </td>
    </tr>
</table>