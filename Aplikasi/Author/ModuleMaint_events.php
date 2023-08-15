<?php
//BindEvents Method @1-57FBDD8A
function BindEvents()
{
    global $lblTopLink;
    global $module;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $module->lblModTitle->CCSEvents["BeforeShow"] = "module_lblModTitle_BeforeShow";
    $module->lblLinkModule->CCSEvents["BeforeShow"] = "module_lblLinkModule_BeforeShow";
    $module->CCSEvents["BeforeInsert"] = "module_BeforeInsert";
    $module->CCSEvents["BeforeUpdate"] = "module_BeforeUpdate";
}
//End BindEvents Method

//lblTopLink_BeforeShow @32-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @33-89D6DE21
// -------------------------
    global $lblTopLink;
	global $lblTitle;
	$ModID=CCGetFromGet("ModID", "");

	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Modul</a>";
	if($ModID =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"ModuleMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Modul</a>";
	$lblTitle->SetValue("Tambah Modul");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"ModuleMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Modul</a>";
	$lblTitle->SetValue("Ubah Modul");
	}
		$lblTopLink->SetValue($MyLink);
 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @32-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//module_lblModTitle_BeforeShow @35-10A1937F
function module_lblModTitle_BeforeShow()
{
    $module_lblModTitle_BeforeShow = true;
//End module_lblModTitle_BeforeShow

//Custom Code @41-B6F83FBE
// -------------------------
    global $module;
    // Write your own code here.
// -------------------------
//End Custom Code
$module->lblModTitle->SetValue("<span id=\"lblModTitle\" name=\"lblModTitle\">".$module->lblModTitle->GetValue()."</span>");
//Close module_lblModTitle_BeforeShow @35-A0EB6455
    return $module_lblModTitle_BeforeShow;
}
//End Close module_lblModTitle_BeforeShow

//module_lblLinkModule_BeforeShow @39-0DD86C76
function module_lblLinkModule_BeforeShow()
{
    $module_lblLinkModule_BeforeShow = true;
//End module_lblLinkModule_BeforeShow

//Custom Code @40-B6F83FBE
// -------------------------
    global $module;
	$module->lblLinkModule->SetValue("<a href=\"javascript:MyDelete();\"><img src=\"../Images/delete.gif\" border=\"0\" title=\"Hapus\"></a> <a href=\"javascript:WindowOpen('SelectModule.php?ModID=".$module->lblLinkModule->GetValue()."','selectmoodule',500,400,'yes')\" ><img src=\"../Images/select.gif\" border=\"0\" title=\"Pilih Modul\"></a>");
    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_lblLinkModule_BeforeShow @39-EA2F1235
    return $module_lblLinkModule_BeforeShow;
}
//End Close module_lblLinkModule_BeforeShow

//module_BeforeInsert @5-A4CEF45B
function module_BeforeInsert()
{
    $module_BeforeInsert = true;
//End module_BeforeInsert

//Custom Code @62-B6F83FBE
// -------------------------
    global $module;
	$module->ds->ModCreated=date("d/m/Y");
 $module->ds->ModModify=date("d/m/Y");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeInsert @5-FEC69A09
    return $module_BeforeInsert;
}
//End Close module_BeforeInsert

//module_BeforeUpdate @5-80B96E2C
function module_BeforeUpdate()
{
    $module_BeforeUpdate = true;
//End module_BeforeUpdate

//Custom Code @63-B6F83FBE
// -------------------------
    global $module;
	 $module->ds->ModModify=date("d/m/Y");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeUpdate @5-31EF5B86
    return $module_BeforeUpdate;
}
//End Close module_BeforeUpdate
?>
