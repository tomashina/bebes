<table class="form browserCache">
  <tr>
    <td>Browser Cache Status<span class="help">Enable/Disable client-side browser caching.</span></td>
    <td>
    <select name="Nitro[BrowserCache][Enabled]" class="NitroBrowserCacheEnabled form-control">
        <option value="yes" <?php echo( (!empty($nitroData['Nitro']['BrowserCache']['Enabled']) && $nitroData['Nitro']['BrowserCache']['Enabled'] == 'yes')) ? 'selected=selected' : ''?>>Enabled (Recommended)</option>
        <option value="no" <?php echo (empty($nitroData['Nitro']['BrowserCache']['Enabled']) || $nitroData['Nitro']['BrowserCache']['Enabled'] != 'yes') ? 'selected=selected' : ''?>>Disabled</option>
    </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>CSS, JavaScript, XML and text</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>css, js, txt, xml</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][CSSJS][Period]" class="form-control NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($nitroData['Nitro']['BrowserCache']['CSSJS']['Period'])&& $nitroData['Nitro']['BrowserCache']['CSSJS']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (empty($nitroData['Nitro']['BrowserCache']['CSSJS']['Period']) || $nitroData['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( !empty($nitroData['Nitro']['BrowserCache']['CSSJS']['Period']) &&  $nitroData['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['CSSJS']['Period']) && $nitroData['Nitro']['BrowserCache']['CSSJS']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['CSSJS']['Period']) && $nitroData['Nitro']['BrowserCache']['CSSJS']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>Images</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>jpg, jpeg, png, gif, swf</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][Images][Period]" class="form-control NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($nitroData['Nitro']['BrowserCache']['Images']['Period'])&& $nitroData['Nitro']['BrowserCache']['Images']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Images']['Period']) && $nitroData['Nitro']['BrowserCache']['Images']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( empty($nitroData['Nitro']['BrowserCache']['Images']['Period']) ||  $nitroData['Nitro']['BrowserCache']['Images']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Images']['Period']) && $nitroData['Nitro']['BrowserCache']['Images']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Images']['Period']) && $nitroData['Nitro']['BrowserCache']['Images']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
  <tr><td colspan="2"><h4>Icons, Fonts, PDF and Flash video</h4></td></tr>
  <tr>
    <td style="vertical-align:top;">Cache <i>otf,ico,pdf,flv</i> files for a period of: </td>
    <td>
        <select name="Nitro[BrowserCache][Icons][Period]" class="form-control NitroBrowserCacheEnabled">
        	<option value="no-cache" <?php echo( !empty($nitroData['Nitro']['BrowserCache']['Icons']['Period'])&& $nitroData['Nitro']['BrowserCache']['Icons']['Period'] == 'no-cache') ? 'selected=selected' : ''?>>0 - Do not cache it</option>
            <option value="1 week" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Icons']['Period']) && $nitroData['Nitro']['BrowserCache']['Icons']['Period'] == '1 week') ? 'selected=selected' : ''?>>1 week</option>
            <option value="1 month" <?php echo( empty($nitroData['Nitro']['BrowserCache']['Icons']['Period']) ||  $nitroData['Nitro']['BrowserCache']['Icons']['Period'] == '1 month') ? 'selected=selected' : ''?>>1 month</option>
            <option value="6 months" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Icons']['Period']) && $nitroData['Nitro']['BrowserCache']['Icons']['Period'] == '6 months') ? 'selected=selected' : ''?>>6 months</option>
            <option value="1 year" <?php echo (!empty($nitroData['Nitro']['BrowserCache']['Icons']['Period']) && $nitroData['Nitro']['BrowserCache']['Icons']['Period'] == '1 year') ? 'selected=selected' : ''?>>1 year</option>
        </select>
    </td>
  </tr>
</table>            
<script type="text/javascript">
$('.infoPopover').popover();
</script>
