<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('IN_ADMIN_PANEL'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  stdhead ('Calculator');
  _form_header_open_ ('Calculator');
  print '' . '
<script type="text/javascript">
// Script © By Martijns Web Hosting \\
// www.martijnswebhosting.tk \\
// Made for Anouksweb.nl \\
function calc(from) {
gb = document.sizes.gb.value; mb = document.sizes.mb.value; kb = document.sizes.kb.value; b = document.sizes.byte.value;
if(from==\'gb\') { document.sizes.mb.value=""+gb+""; document.sizes.mb.value*="1024"; document.sizes.kb.value=""+gb+""; document.sizes.kb.value*="1024"; document.sizes.kb.value*="1024"; document.sizes.byte.value=""+gb+""; document.sizes.byte.value*="1024"; document.sizes.byte.value*="1024"; document.sizes.byte.value*="1024"; }
else if(from==\'mb\') { document.sizes.gb.value=""+mb+""; document.sizes.gb.value/="1024"; document.sizes.kb.value=""+mb+""; document.sizes.kb.value*="1024"; document.sizes.byte.value=""+mb+""; document.sizes.byte.value*="1024"; document.sizes.byte.value*="1024"; }
else if(from==\'kb\') { document.sizes.gb.value=""+kb+""; document.sizes.gb.value/="1024"; document.sizes.gb.value/="1024"; document.sizes.mb.value=""+kb+""; document.sizes.mb.value/="1024"; document.sizes.byte.value=""+kb+""; document.sizes.byte.value*="1024"; }
else if(from==\'byte\') { document.sizes.gb.value=""+b+""; document.sizes.gb.value/="1024"; document.sizes.gb.value/="1024"; document.sizes.gb.value/="1024"; document.sizes.mb.value=""+b+""; document.sizes.mb.value/="1024"; document.sizes.mb.value/="1024"; document.sizes.kb.value=""+b+""; document.sizes.kb.value/="1024"; }
}
</script>

<form name="sizes">
<table border="0" width="100%" cellspacing="5" cellpadding="2">
<tr>
<td width="6%" class=none align=right>GB&nbsp;</td>
<td width="20%" class=none>&nbsp<input type="text" name="gb" size="20"></td>
<td width="74%" class=none>&nbsp<input onclick="javascript:calc(\'gb\')" type="button" value="Calculate From GB "></td>
</tr>
<tr>
<td width="6%" class=none align=right>MB&nbsp;</td>
<td width="20%" class=none>&nbsp;<input type="text" name="mb" size="20"></td>
<td width="74%" class=none>&nbsp;<input onclick="javascript:calc(\'mb\')" type="button" value="Calculate From MB "></td>
</tr>
<tr>
<td width="6%" class=none align=right>KB&nbsp;</td>
<td width="20%" class=none>&nbsp;<input type="text" name="kb" size="20"></td>
<td width="74%" class=none>&nbsp;<input onclick="javascript:calc(\'kb\')" type="button" value="Calculate From KB "></td>
</tr>
<tr>
<td width="6%" class=none align=right>Byte&nbsp;</td>
<td width="20%" class=none>&nbsp;<input type="text" name="byte" size="20"></td>
<td width="74%" class=none>&nbsp;<input onclick="javascript:calc(\'byte\')" type="button" value="Calculate From Byte"></td>
</tr>
</table>
</form>';
  _form_header_close_ ();
  stdfoot ();
?>
