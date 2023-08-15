<?php
//BindEvents Method @1-15B02096
function BindEvents()
{
    global $lblTopLink;
    global $module;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $module->CCSEvents["BeforeShowRow"] = "module_BeforeShowRow";
    $module->CCSEvents["BeforeSelect"] = "module_BeforeSelect";
    $module->ds->CCSEvents["BeforeExecuteSelect"] = "module_ds_BeforeExecuteSelect";
}
//End BindEvents Method

//lblTopLink_BeforeShow @34-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @35-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("ModID","del_modul","ccsForm"))."\">Daftar Modul</a>";
	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @34-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow
function StrDelete($str)
{
$str=ereg_replace("'","`",$str);
$str=ereg_replace('"',"`",$str);
$str=htmlspecialchars($str);
return $str;
}
//module_BeforeShowRow @2-7AA95475
function module_BeforeShowRow()
{
    $module_BeforeShowRow = true;
//End module_BeforeShowRow

//Custom Code @29-B6F83FBE
// -------------------------
    global $module;
	$MyLink="";
	$MyLink=$MyLink."<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($module->ModTitle->GetValue())."','ModuleList.php?ModID=".$module->lblDelete->GetValue()."&del_modul=true&". CCGetQueryString("QueryString", Array("ModID","del_modul","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>";
	$MyLink=$MyLink." <a href=\"Category.php?ModID=".$module->lblDelete->GetValue()."&". CCGetQueryString("QueryString", Array("ModID","del_modul","ccsForm"))."\"><img title=\"Bab\" src=\"../Images/category.gif\" border=\"0\"></a>";

	$module->lblDelete->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeShowRow @2-0D0F6BCD
    return $module_BeforeShowRow;
}
//End Close module_BeforeShowRow
function MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage)	
{
	global $lblError;
	$db = new clsDBConnection1();    
    $db->query($SqlSelect);
 	$Result = $db->next_record();
    if($Result)
    {
		$lblError->SetValue("<script language=\"javascript\">function MyInformation(){alert(\"".$ErrorMessage."\");}window.onload=MyInformation;</script>");
	}
	else
	{
		$db->query($SqlDelete);
	}
    $db->close();
}
//module_BeforeSelect @2-81093815
function module_BeforeSelect()
{
    $module_BeforeSelect = true;
//End module_BeforeSelect

//Custom Code @31-B6F83FBE
// -------------------------
    global $module;
	$del_modul=CCGetFromGet("del_modul", "");
 	$ModID=CCGetFromGet("ModID", "");

	if($del_modul)
	{
		 	$db = new clsDBConnection1();
	  	 	$SqlSelect = "select * FROM category where ModID=" . $db->ToSQL($ModID, ccsInteger) ;
  	 		$SqlDelete = "DELETE FROM `module` where ModID=" . $db->ToSQL($ModID, ccsInteger) ;
			$ErrorMessage="Modul tidak dapat dihapus";
    		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);
	 }
    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_BeforeSelect @2-0B9F63A4
    return $module_BeforeSelect;
}
//End Close module_BeforeSelect

//module_ds_BeforeExecuteSelect @2-B9B6DC63
function module_ds_BeforeExecuteSelect()
{
    $module_ds_BeforeExecuteSelect = true;
//End module_ds_BeforeExecuteSelect

//Custom Code @47-B6F83FBE
// -------------------------
    global $module;
	if(CCGetSession("AutLevel")=="2")
		{

	$db = new clsDBConnection1();    
 
	$SQL=="";
	$SQL=$SQL."SELECT `module`.*, AutName FROM `module` INNER JOIN authors ON";
	$SQL=$SQL." `module`.AutUsername = authors.AutUsername";
	$SQL=$SQL." WHERE `module`.AutUsername =". $db->ToSQL(CCGetSession("AutID"), ccsText);

  	$module->ds->SQL =$SQL;
   		}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close module_ds_BeforeExecuteSelect @2-02DBA946
    return $module_ds_BeforeExecuteSelect;
}
//End Close module_ds_BeforeExecuteSelect


?>
