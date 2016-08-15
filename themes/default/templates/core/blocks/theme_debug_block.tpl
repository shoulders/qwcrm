<!-- theme_debug_block.tpl -->
<div style="width: 900px; margin-bottom: 20px;">
    <table width="300" border="0" cellspacing="0" cellpadding="0" style="margin: auto auto;">
        <tr>
            <td align="center" colspan="2"><p><b><span class="text3">This is the debug section</span></b></p></td>        
        </tr>

        <!-- Visitor Details -->
        <tr>
            <td width="50%"><b><span class="text3">IP Address:</span></b></td>
            <td>{$IPaddress}</td>
        </tr>

        <!-- Page -->
        <tr>
            <td><b><span class="text3">Page Load Time:</span></b></td>
            <td>{$pageLoadTime} s</td>
        </tr>
        <tr>
            <td><b><span class="text3">Page Controller:</span></b></td>
            <td>{$pageDisplayController}</td>
        </tr>
        <tr>
            <td><b><span class="text3">Module:</span></b></td>
            <td>{$loadedModule}</td>
        </tr>
        <tr>
            <td><b><span class="text3">Page:</span></b></td>
            <td>{$loadedPage}</td>
        </tr>    

        <!-- Memory Usage -->
        <tr>
            <td><b><span class="text3">Start PHP Memory</span></b></td>
            <td>{$startMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">Current PHP Memory</span></b></td>
            <td>{$currentMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">Peak PHP Memory</span></b></td>
            <td>{$peakMem} MB</td>
        </tr>
        
    </table>
</div>