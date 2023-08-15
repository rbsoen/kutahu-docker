<?php
//BindEvents Method @1-E6AB10F1
function BindEvents()
{
    global $module;
    $module->CCSEvents["BeforeShowRow"] = "module_BeforeShowRow";
}
//End BindEvents Method

//module_BeforeShowRow @2-7AA95475
function module_BeforeShowRow()
{
    $module_BeforeShowRow = true;
//End module_BeforeShowRow

//Custom Code @10-B6F83FBE
// -------------------------
    global $module;
	$module->lblSelect->SetValue("<a href=\"javascript:window.close();window.opener.MyDisplay('".$module->ModID->GetValue()."','".$module->ModTitle->GetValue()."');\"><img src=\"../Images/select.gif\" border=\"0\" title=\"Pilih\"></a>");
	//ModID ModTitle

    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeShowRow @2-0D0F6BCD
    return $module_BeforeShowRow;
}
//End Close module_BeforeShowRow


?>
