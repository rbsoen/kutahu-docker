<?php
//BindEvents Method @1-AB161827
function BindEvents()
{
    global $lblTopLink;
    global $module;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $module->module1_ModTitle->CCSEvents["BeforeShow"] = "module_module1_ModTitle_BeforeShow";
    $module->CCSEvents["BeforeShowRow"] = "module_BeforeShowRow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @5-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @6-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="&nbsp;<a class='CobaltDataLinkTop' href=\"Question.php". "?QueType=m&" . CCGetQueryString("QueryString", Array("MyData","Pos","QueType","ccsForm"))."\"><img Title=\"Pertanyaan Modul\" src=\"../Images/question.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"HomeUser.php\"><img Title=\"Daftar Modul\" src=\"../Images/home.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"#\" onclick=\"javascript:window.open('../Help/HelpUser.pdf','winhelp');\"><img Title=\"Help\" src=\"../Images/help.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>&nbsp;";

	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//module_module1_ModTitle_BeforeShow @20-845DDEE4
function module_module1_ModTitle_BeforeShow()
{
    $module_module1_ModTitle_BeforeShow = true;
//End module_module1_ModTitle_BeforeShow

//Custom Code @22-B6F83FBE
// -------------------------
    global $module;
		
		if($module->Mod_ModID->GetValue()=="")
		{
		
			$module->module1_ModTitle->SetValue("-");
		}
		else
			{
				$module->module1_ModTitle->SetValue("<a href=\"Module.php?ModID=".$module->Mod_ModID->GetValue()."\">".$module->module1_ModTitle->GetValue()."</a>");
			}

    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_module1_ModTitle_BeforeShow @20-65AA49B6
    return $module_module1_ModTitle_BeforeShow;
}
//End Close module_module1_ModTitle_BeforeShow

//module_BeforeShowRow @7-7AA95475
function module_BeforeShowRow()
{
    $module_BeforeShowRow = true;
//End module_BeforeShowRow

//Custom Code @24-B6F83FBE
// -------------------------
    global $module;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeShowRow @7-0D0F6BCD
    return $module_BeforeShowRow;
}
//End Close module_BeforeShowRow


?>
