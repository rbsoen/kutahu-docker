<?php
// //Events @1-F81417CB

//Header_BeforeShow @1-DACF0966
function Header_BeforeShow()
{
    $Header_BeforeShow = true;
//End Header_BeforeShow

//Custom Code @2-DBA303E4
// -------------------------
    global $Header;
	$Header->lblDate->SetValue("<font class='SmallFontBold'>".CCGetSession("UserFullName")."</font><font class='SmallFont'> (".CCGetSession("UserDate").")</font>");
		if(CCGetSession("UserID")=="")
		{
			Header("Location:../Login.php");
		}
		
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Header_BeforeShow @1-E0152CE0
    return $Header_BeforeShow;
}
//End Close Header_BeforeShow


?>
