<?
/***********************************************/
/*=========[TS Special Edition v.5.6]==========*/
/*=============[Special Thanks To]=============*/
/*        DrNet - wWw.SpecialCoders.CoM        */
/*          Vinson - wWw.Decode4u.CoM          */
/*    MrDecoder - wWw.Fearless-Releases.CoM    */
/*           Fynnon - wWw.BvList.CoM           */
/***********************************************/


  if (!defined ('IN_SCRIPT_TSSEv56'))
  {
    exit ('<font face=\'verdana\' size=\'2\' color=\'darkred\'><b>Error!</b> Direct initialization of this file is not allowed.</font>');
  }

  define ('R_VERSION', 'v.0.3');
  $lang->load ('javascript_resizer');
  echo '
<script type="text/javascript">
	//<![CDATA[
	' . $lang->javascript_resizer['ncode'] . '
	//]]>
</script>
<script type="text/javascript" src="' . $BASEURL . '/scripts/ncode_imageresizer.js?v=' . O_SCRIPT_VERSION . '"></script>
<script type="text/javascript">
	//<![CDATA[
	NcodeImageResizer.MODE = "floatbox";
	NcodeImageResizer.MAXWIDTH = 600;
	NcodeImageResizer.MAXHEIGHT = 800;
	NcodeImageResizer.MAXWIDTHSIGS = 400;
	NcodeImageResizer.MAXHEIGHTSIGS = 200;
	NcodeImageResizer.BBURL = "' . $BASEURL . '";
	//]]>
</script>
<style type="text/css">
	.ncode_imageresizer_warning
	{
		display: none;
	}
</style>
<script type="text/javascript" src="' . $BASEURL . '/scripts/floatbox/floatbox.js?v=' . O_SCRIPT_VERSION . '"></script>
<link rel="stylesheet" href="' . $BASEURL . '/scripts/floatbox/floatbox.css" type="text/css" media="screen" />
<script type="text/javascript">
	//<![CDATA[
	function setFloatboxOptions()
	{
		fb.resizeDuration = 2.5;
		fb.imageFadeDuration = 2.5;
		fb.overlayFadeDuration = 0;
		fb.navType = "both";
	};
	//]]>
</script>
';
?>
