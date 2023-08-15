<?php
//BindEvents Method @1-650FD5B7
function BindEvents()
{
    global $lblTopLink;
    global $module;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $module->AutName->CCSEvents["BeforeShow"] = "module_AutName_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @24-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @25-89D6DE21
// -------------------------
    global $lblTopLink;
		$MyLink="";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>";
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @24-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//module_AutName_BeforeShow @9-C5ECFF9B
function module_AutName_BeforeShow()
{
    $module_AutName_BeforeShow = true;
//End module_AutName_BeforeShow

//Custom Code @22-B6F83FBE
// -------------------------
    global $module;
		$module->AutName->SetValue("<a href=\"javascript:WindowOpen('AuthorProfile.php?AutUsername=".$module->AutUsername->GetValue()."','AuthorProfile',500,310,'yes')\" >".$module->AutName->GetValue()."</a>");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_AutName_BeforeShow @9-0C9CB25A
    return $module_AutName_BeforeShow;
}
//End Close module_AutName_BeforeShow


?>
