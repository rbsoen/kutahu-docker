<?php
//BindEvents Method @1-A690A530
function BindEvents()
{
    global $lblTopLink;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @8-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @9-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="";
	$MyLink=$MyLink."<a class='CobaltDataLinkTop' href=\"SelectPageCategory.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Bab</a>";
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @8-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow


?>
