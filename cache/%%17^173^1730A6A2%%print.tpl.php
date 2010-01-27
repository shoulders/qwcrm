<?php /* Smarty version 2.6.9, created on 2010-01-27 17:09:52
         compiled from workorder/print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'workorder/print.tpl', 89, false),)), $this); ?>
<!-- Print Work Order -->
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['single_workorder_array']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<table  width="900" border="1" cellpadding="2" cellspacing="0" style="border-collapse: collapse;">
	<tr>		
		<!-- right column -->
		<td valign="top" align="center" ><img src="images/logo.jpg" height="50"></td>
		<!-- middle column -->
		<td valign="top" align="center" width="200">
			<font size="+3">TECHNICIAN COPY</font><br>
			Work Order ID# <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_ID']; ?>

		</td>
	</tr><tr>
		<!-- left Column -->
		<td valign="top" align="center" nowrap><b>Service Location</b></td>
		<!-- Center Column -->
		<td valign="top" align="center" nowrap><b>Service Details</b></td>
		<!-- right column -->
		<td valign="top" align="center" nowrap><b>Summary</b></td>
	</tr><tr>
		<!-- left Column -->
		<td valign="top" width="20%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_DISPLAY_NAME']; ?>
</b></td>
				</tr>
			</table>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_FIRST_NAME']; ?>
 <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_LAST_NAME']; ?>

					<br><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_ADDRESS']; ?>
<br>
							<?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_CITY']; ?>
, <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_STATE']; ?>
 <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_ZIP']; ?>

					</td>
				</tr><tr>
					<td><b>Home:</b> <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_PHONE']; ?>
<br>
						 <b>Work:</b> <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_WORK_PHONE']; ?>
<br>
						<b>Mobile:</b> <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_MOBILE_PHONE']; ?>

					</td>
				</tr><tr>
					<td><b>Email:</b> <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_EMAIL']; ?>
<br>
				</tr>
			</table>
			<!--OLD LINE-->
			<hr>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td><b>Company Contact</b></td>
				</tr>
				<?php unset($this->_sections['d']);
$this->_sections['d']['name'] = 'd';
$this->_sections['d']['loop'] = is_array($_loop=$this->_tpl_vars['company']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['d']['show'] = true;
$this->_sections['d']['max'] = $this->_sections['d']['loop'];
$this->_sections['d']['step'] = 1;
$this->_sections['d']['start'] = $this->_sections['d']['step'] > 0 ? 0 : $this->_sections['d']['loop']-1;
if ($this->_sections['d']['show']) {
    $this->_sections['d']['total'] = $this->_sections['d']['loop'];
    if ($this->_sections['d']['total'] == 0)
        $this->_sections['d']['show'] = false;
} else
    $this->_sections['d']['total'] = 0;
if ($this->_sections['d']['show']):

            for ($this->_sections['d']['index'] = $this->_sections['d']['start'], $this->_sections['d']['iteration'] = 1;
                 $this->_sections['d']['iteration'] <= $this->_sections['d']['total'];
                 $this->_sections['d']['index'] += $this->_sections['d']['step'], $this->_sections['d']['iteration']++):
$this->_sections['d']['rownum'] = $this->_sections['d']['iteration'];
$this->_sections['d']['index_prev'] = $this->_sections['d']['index'] - $this->_sections['d']['step'];
$this->_sections['d']['index_next'] = $this->_sections['d']['index'] + $this->_sections['d']['step'];
$this->_sections['d']['first']      = ($this->_sections['d']['iteration'] == 1);
$this->_sections['d']['last']       = ($this->_sections['d']['iteration'] == $this->_sections['d']['total']);
?>
					<tr>
						<td><?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_NAME']; ?>
<br>
						    <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_ADDRESS']; ?>
<br>
							<?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_CITY']; ?>
, <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_STATE']; ?>
 <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_ZIP']; ?>
</td>
					</tr><tr>
						<td>
						</td>
					</tr><tr>
						<td><b>Phone Numbers<br>
                  Primary:</b>&nbsp <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_PHONE']; ?>
<br>                  
						    <b>Toll Free:</b>&nbsp <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_TOLL_FREE']; ?>
<br>
                <b>Mobile #:</b>&nbsp <?php echo $this->_tpl_vars['company'][$this->_sections['d']['index']]['COMPANY_MOBILE']; ?>
<br>
							</td>
					</tr>
				<?php endfor; endif; ?>
			</table>	
			
			<hr>
			<p><center><b>Thank You &nbsp</b><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_FIRST_NAME']; ?>
 <?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['CUSTOMER_LAST_NAME']; ?>
<br><br>Thank you for using our service. Your
 			 business is greatly appreciated!</center></p>

		</td>
		<!-- Center Column -->
		<td valign="top" width="60%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b>Description</b></td>
				</tr><tr>
					<td><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_DESCRIPTION']; ?>
</td>
				</tr><tr>
					<td><b>Comments</b></td>
				</tr><tr>
					<td><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_COMMENT']; ?>
</td>
				</tr><tr>
					<td></td>
				</tr><tr>
					<td><?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['work_order_notes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
							<p><b>Service Notes</b>
					<br><?php echo $this->_tpl_vars['work_order_notes'][$this->_sections['b']['index']]['WORK_ORDER_NOTES_DESCRIPTION']; ?>
<br><br>
					<b>Entered By: </b><?php echo $this->_tpl_vars['work_order_notes'][$this->_sections['b']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
  
							<br><b>Date: </b> <?php echo ((is_array($_tmp=$this->_tpl_vars['work_order_notes'][$this->_sections['b']['index']]['WORK_ORDER_NOTES_DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])) : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format']))); ?>
<br>
							</p>
						<?php endfor; endif; ?>
					</td>
				</tr>
			</table>
			<hr>
			<table width="100%" cellpadding="4" cellspacing="0" border="0">
				<tr>
					<td align="center"><b>Schedule Details</b></td>
				</tr><tr>
					<td>
						<?php unset($this->_sections['e']);
$this->_sections['e']['name'] = 'e';
$this->_sections['e']['loop'] = is_array($_loop=$this->_tpl_vars['work_order_sched']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['e']['show'] = true;
$this->_sections['e']['max'] = $this->_sections['e']['loop'];
$this->_sections['e']['step'] = 1;
$this->_sections['e']['start'] = $this->_sections['e']['step'] > 0 ? 0 : $this->_sections['e']['loop']-1;
if ($this->_sections['e']['show']) {
    $this->_sections['e']['total'] = $this->_sections['e']['loop'];
    if ($this->_sections['e']['total'] == 0)
        $this->_sections['e']['show'] = false;
} else
    $this->_sections['e']['total'] = 0;
if ($this->_sections['e']['show']):

            for ($this->_sections['e']['index'] = $this->_sections['e']['start'], $this->_sections['e']['iteration'] = 1;
                 $this->_sections['e']['iteration'] <= $this->_sections['e']['total'];
                 $this->_sections['e']['index'] += $this->_sections['e']['step'], $this->_sections['e']['iteration']++):
$this->_sections['e']['rownum'] = $this->_sections['e']['iteration'];
$this->_sections['e']['index_prev'] = $this->_sections['e']['index'] - $this->_sections['e']['step'];
$this->_sections['e']['index_next'] = $this->_sections['e']['index'] + $this->_sections['e']['step'];
$this->_sections['e']['first']      = ($this->_sections['e']['iteration'] == 1);
$this->_sections['e']['last']       = ($this->_sections['e']['iteration'] == $this->_sections['e']['total']);
?>
							<b>Scheduled Start </b> <?php echo ((is_array($_tmp=$this->_tpl_vars['work_order_sched'][$this->_sections['e']['index']]['SCHEDULE_START'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])." %I:%M  %p") : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format'])." %I:%M  %p")); ?>
<br>
              <b>Scheduled End</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['work_order_sched'][$this->_sections['e']['index']]['SCHEDULE_END'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])." %I:%M  %p ") : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format'])." %I:%M  %p ")); ?>
 <br>
							<b>Schedule Notes</b><br>
								<?php echo $this->_tpl_vars['work_order_sched'][$this->_sections['e']['index']]['SCHEDULE_NOTES']; ?>

						<?php endfor; else: ?>
							No schedule has been set. Click the day on the calendar you want to set the schedule.
						<?php endif; ?>
					</td>
				</tr>
			</table>
			<hr>
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td><b>Resolution:</b><br>
					<?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['work_order_res']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
						<?php if ($this->_tpl_vars['work_order_res'][$this->_sections['r']['index']]['EMPLOYEE_DISPLAY_NAME'] != ''): ?>
							<b>Closed By:</b> <?php echo $this->_tpl_vars['work_order_res'][$this->_sections['r']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>
 <b>Date:</b>  <?php echo ((is_array($_tmp=$this->_tpl_vars['work_order_res'][$this->_sections['r']['index']]['WORK_ORDER_CLOSE_DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])) : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format']))); ?>

							<?php echo $this->_tpl_vars['work_order_res'][$this->_sections['r']['index']]['WORK_ORDER_RESOLUTION']; ?>

						<?php endif; ?>
					<?php endfor; endif; ?>
					</td>
				</tr><tr>
					<td><br><br><br><br><br><br><br><br></td>
			</table>
		</td>
		<!-- right column -->
		<td valign="top" width="20%">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td valign="top" nowrap><b>Scope:</b></td>
					<td valign="top"><?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_SCOPE']; ?>
</td>
				</tr><tr>
					<td valign="top" nowrap><b>Date Opened:</b></td>
					<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_OPEN_DATE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])) : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format']))); ?>
</td>
				</tr><tr>
					<td valign="top"><b>Status:</b></td>
					<td valign="top"><?php if ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '1'): ?>
							Created
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '2'): ?>
							Assigned
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '3'): ?>
							Waiting For Parts
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '6'): ?>
							Closed
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '7'): ?>	
							Waiting For Payment
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '8'): ?>	
							Payment Made
						<?php elseif ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['WORK_ORDER_CURRENT_STATUS'] == '9'): ?>	
							Pending	
						<?php endif; ?>
					</td>
				</tr><tr>
					<td valign="top"><b>Tech:</b></td>
					<td valign="top"><?php if ($this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['EMPLOYEE_DISPLAY_NAME'] == ""): ?>
							Not Assigned
						<?php else: ?>
							<?php echo $this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['EMPLOYEE_DISPLAY_NAME']; ?>

						<?php endif; ?>
					</td>
				</tr><tr>
					<td><b>Last Changed:</b></td>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['single_workorder_array'][$this->_sections['i']['index']]['LAST_ACTIVE'])) ? $this->_run_mod_handler('date_format', true, $_tmp, ($this->_tpl_vars['date_format'])) : smarty_modifier_date_format($_tmp, ($this->_tpl_vars['date_format']))); ?>
</td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><b>Service Time</b></td>
				</tr><tr>
					<td><b>Arrival</b></td>
					<td>___/____/____ __:__</td>
				</tr><tr>
					<td><b>Departed</b></td>
					<td>___/____/____ __:__</td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center"><b>Notes</b></td>
				</tr><tr>
					<td><br><br><br><br><br><br><br><br></td>
				</tr>
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center"><b>Feedback</b></td>
				</tr>
        <tr>
				  <td align="center">Please Rate this service 1(poor) & 5(excellent)</td>
        </tr>
        <tr>
				<td align="center"><b>Your rating is &nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;5</b></td>
				</tr>
					<td>Comments:<br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          <br>_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
          <br>
          </td>
				
			</table>
			<hr>
			<table width="100%" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" colspan="2"><b>Signature</b></td>
				</tr><tr>
					<td><b>Client Name</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Signature</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Tech Name</b></td>
					<td>__________________</td>
				</tr><tr>
					<td><b>Signature</b></td>
					<td>__________________</td>
				</tr>
			</table>
			<br>
			</td></tr>
</table>
<br>
<table width="900" border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td align="center" ><b>This Work Order is confidential and contains privileged information.</b></td>
				</tr>
</table>
<?php endfor; endif; ?>