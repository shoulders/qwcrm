<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

<td class="olotd4" align="center">
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '1'}{t}Ceated{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '2'}{t}Assigned{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '3'}{t}Waiting For Parts{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '6'}{t}Closed{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '7'}{t}Waiting For Payment{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '8'}{t}Payment Made{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '9'}{t}Pending{/t}{/if}
                                                                    {if $workorders[i].WORK_ORDER_STATUS == '10'}{t}Open{/t}{/if}
                                                                </td>