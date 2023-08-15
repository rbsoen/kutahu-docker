<?php
//BindEvents Method @1-68C668C3
function BindEvents()
{
    global $lblTopLink;
    global $subknowledgeitem;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $subknowledgeitem->CCSEvents["BeforeShowRow"] = "subknowledgeitem_BeforeShowRow";
    $subknowledgeitem->CCSEvents["BeforeSelect"] = "subknowledgeitem_BeforeSelect";
}
//End BindEvents Method

//lblTopLink_BeforeShow @5-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @7-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","ccsForm"))."\">Daftar Topik</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SubKnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Sub Topik</a>";
	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
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
//subknowledgeitem_BeforeShowRow @8-5EE344C6
function subknowledgeitem_BeforeShowRow()
{
    $subknowledgeitem_BeforeShowRow = true;
//End subknowledgeitem_BeforeShowRow

//Custom Code @15-575F2A73
// -------------------------
    global $subknowledgeitem;
			$subknowledgeitem->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($subknowledgeitem->SubKnowItemTitle->GetValue())."','SubKnowledgeItem.php?SubKnowItemID=".$subknowledgeitem->lblDelete->GetValue()."&del_subknowitem=true&". CCGetQueryString("QueryString", Array("SubKnowItemID","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_BeforeShowRow @8-950A6891
    return $subknowledgeitem_BeforeShowRow;
}
//End Close subknowledgeitem_BeforeShowRow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
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
		MyUpdateModule();
	}
    $db->close();
}
//subknowledgeitem_BeforeSelect @8-688FA73C
function subknowledgeitem_BeforeSelect()
{
    $subknowledgeitem_BeforeSelect = true;
//End subknowledgeitem_BeforeSelect

//Custom Code @17-575F2A73
// -------------------------
    global $subknowledgeitem;
	$del_subknowitem=CCGetFromGet("del_subknowitem", "");
	$SubKnowItemID=CCGetFromGet("SubKnowItemID", "");

	if($del_subknowitem)
	{

		 	$db = new clsDBConnection1();
			$SqlSelect = "select * FROM glossary where SubKnowItemID=" . $db->ToSQL($SubKnowItemID, ccsInteger) ;
  	 		$SqlDelete = "DELETE FROM subknowledgeitem where SubKnowItemID=" . $db->ToSQL($SubKnowItemID, ccsInteger) ;
			$ErrorMessage="Sub Topik tidak dapat dihapus";
    		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);

	 }
    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_BeforeSelect @8-DCBC83E6
    return $subknowledgeitem_BeforeSelect;
}
//End Close subknowledgeitem_BeforeSelect


?>
