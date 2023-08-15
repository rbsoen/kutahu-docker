<?php
//BindEvents Method @1-493CAFFD
function BindEvents()
{
    global $Label1;
    $Label1->CCSEvents["BeforeShow"] = "Label1_BeforeShow";
}
//End BindEvents Method

//Label1_BeforeShow @5-61C72663
function Label1_BeforeShow()
{
    $Label1_BeforeShow = true;
//End Label1_BeforeShow

//Custom Code @8-85BC3D26
// -------------------------
    global $Label1;
	$Label1->SetValue(CCGetSession("AutID")."/".CCGetSession("AutLevel").SCRIPT_NAME);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Label1_BeforeShow @5-B48DF954
    return $Label1_BeforeShow;
}
//End Close Label1_BeforeShow


?>
