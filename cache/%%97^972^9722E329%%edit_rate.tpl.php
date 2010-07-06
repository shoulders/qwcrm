<?php /* Smarty version 2.6.9, created on 2010-07-06 15:49:30
         compiled from control/edit_rate.tpl */ ?>
<!-- edit rates -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../js/myitcrm.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%">&nbsp;Edit Billing Rates</td>
                </tr><tr>
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
                                                    <?php unset($this->_sections['q']);
$this->_sections['q']['name'] = 'q';
$this->_sections['q']['loop'] = is_array($_loop=$this->_tpl_vars['rate']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['q']['show'] = true;
$this->_sections['q']['max'] = $this->_sections['q']['loop'];
$this->_sections['q']['step'] = 1;
$this->_sections['q']['start'] = $this->_sections['q']['step'] > 0 ? 0 : $this->_sections['q']['loop']-1;
if ($this->_sections['q']['show']) {
    $this->_sections['q']['total'] = $this->_sections['q']['loop'];
    if ($this->_sections['q']['total'] == 0)
        $this->_sections['q']['show'] = false;
} else
    $this->_sections['q']['total'] = 0;
if ($this->_sections['q']['show']):

            for ($this->_sections['q']['index'] = $this->_sections['q']['start'], $this->_sections['q']['iteration'] = 1;
                 $this->_sections['q']['iteration'] <= $this->_sections['q']['total'];
                 $this->_sections['q']['index'] += $this->_sections['q']['step'], $this->_sections['q']['iteration']++):
$this->_sections['q']['rownum'] = $this->_sections['q']['iteration'];
$this->_sections['q']['index_prev'] = $this->_sections['q']['index'] - $this->_sections['q']['step'];
$this->_sections['q']['index_next'] = $this->_sections['q']['index'] + $this->_sections['q']['step'];
$this->_sections['q']['first']      = ($this->_sections['q']['iteration'] == 1);
$this->_sections['q']['last']       = ($this->_sections['q']['iteration'] == $this->_sections['q']['total']);
?>
                                                <form method="POST" action="?page=control:edit_rate">
                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" class="row1">
                                                            <td class="olotd4" nowrap><?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_ID']; ?>
</td>
                                                            <td class="olotd4" nowrap><input class="olotd5" type="text" name="display" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_NAME']; ?>
" size="50"></td>
                                                            <td class="olotd4" nowrap><?php echo $this->_tpl_vars['currency_sym']; ?>
<input class="olotd5" type="text" name="amount" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_AMOUNT']; ?>
" size="6"></td>
                                                            <td class="olotd4" nowrap><?php echo $this->_tpl_vars['currency_sym']; ?>
<input class="olotd5" type="text" name="cost" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_COST']; ?>
" size="6"></td>
                                                            <td class="olotd4" nowrap><select class="olotd5" name="active">
                                                                    <option value="0" <?php if ($this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_ACTIVE'] == 0): ?> selected<?php endif; ?>>No</option>
                                                                    <option value="1" <?php if ($this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_ACTIVE'] == 1): ?> selected<?php endif; ?>>Yes</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap><select class="olotd5" name="type">
                                                                    <option value="Parts" <?php if ($this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_TYPE'] == 'Parts'): ?> selected<?php endif; ?>>Parts</option>
                                                                    <option value="Service" <?php if ($this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_TYPE'] == 'Service'): ?> selected<?php endif; ?>>Service</option>
                                                                </select>
                                                            </td>
                                                            <td class="olotd4" nowrap><input class="olotd5" type="text" name="manufacturer" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_MANUF']; ?>
" size="15"></td>
                                                            <td class="olotd4" nowrap>
                                                                <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_RATE_ID']; ?>
">
                                                                <input type="submit" name="submit" value="Delete">
                                                                <input type="submit" name="submit" value="Update">
                                                            </td>
                                                        </tr>
                                                   </form>
						<?php endfor; endif; ?>    </table>
                                                
                                             


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
                                                        </tr><tr>
                                                                <td class="olotd4"><input class="olotd5" type="text" name="display" size="60"></td>
                                                                <td class="olotd4"><?php echo $this->_tpl_vars['currency_sym']; ?>
<input class="olotd5" type="text" name="amount" size="6"></td>
                                                                <td class="olotd4"><?php echo $this->_tpl_vars['currency_sym']; ?>
<input class="olotd5" type="text" name="cost" size="6"></td>
                                                                <td class="olotd4" nowrap><select class="olotd5" name="type">
                                                                        <option value="Parts">Parts</option>
                                                                        <option value="Service" SELECTED>Service</option>
                                                                    </select></td>
                                                                <td class="olotd4" nowrap><input class="olotd5" type="text" name="manufacturer" value="<?php echo $this->_tpl_vars['rate'][$this->_sections['q']['index']]['LABOR_MANUF']; ?>
" size="15"></td>
                                                                <td class="olotd4"><input type="submit" name="submit" value="Add"></td>
                                                            </tr>
                                                    </table>
                                                 </form>
                                                    <?php if ($this->_tpl_vars['cred']['EMPLOYEE_TYPE'] == 1 || $this->_tpl_vars['cred']['EMPLOYEE_TYPE'] == 2 || $this->_tpl_vars['cred']['EMPLOYEE_TYPE'] == 4): ?>
                                                    <?php echo '<script type="text/javascript">
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
                                                    </script>
                                                    '; ?>

                                                    <a href="#" id="newfile"><?php echo $this->_tpl_vars['translate_invoice_rates_add_file']; ?>
</a>
                                                    <div id="newuserform">
                                                        <table width="100%">
                                                            <tr>
                                                                <td>
                                                                    <a><?php echo $this->_tpl_vars['translate_invoice_rates_example']; ?>
</a><br>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <img src="images/rate_upload.PNG" alt="CSV Example screenshot" height="150"/>
                                                                </td>
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
                                                                                <td width="80"><input name="upload" type="submit" class="box" id="upload" value=" Load " ><br>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </form>
                                                                </td></tr>
                                                        </table>
                                                    </div>
                                                    <?php endif; ?>
                                               
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