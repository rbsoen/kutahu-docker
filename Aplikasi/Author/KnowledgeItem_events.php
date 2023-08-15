<?php
//BindEvents Method @1-AC5426BF
function BindEvents()
{
    global $lblTopLink;
    global $knowledgeitem;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $knowledgeitem->CCSEvents["BeforeShowRow"] = "knowledgeitem_BeforeShowRow";
    $knowledgeitem->CCSEvents["BeforeSelect"] = "knowledgeitem_BeforeSelect";
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
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","del_knowitem","KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","del_knowitem","KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","del_knowitem","KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","del_knowitem","ccsForm"))."\">Daftar Topik</a>";

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
//knowledgeitem_BeforeShowRow @7-1ABF8DAB
function knowledgeitem_BeforeShowRow()
{
    $knowledgeitem_BeforeShowRow = true;
//End knowledgeitem_BeforeShowRow

//Custom Code @19-F45BB0F1
// -------------------------
    global $knowledgeitem;
		$knowledgeitem->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($knowledgeitem->KnowItemTitle->GetValue())."','KnowledgeItem.php?KnowItemID=".$knowledgeitem->lblDelete->GetValue()."&del_knowitem=true&". CCGetQueryString("QueryString", Array("KnowItemID","del_knowitem","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_BeforeShowRow @7-69AB7048
    return $knowledgeitem_BeforeShowRow;
}
//End Close knowledgeitem_BeforeShowRow
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
//knowledgeitem_BeforeSelect @7-40588B70
function knowledgeitem_BeforeSelect()
{
    $knowledgeitem_BeforeSelect = true;
//End knowledgeitem_BeforeSelect

//Custom Code @21-F45BB0F1
// -------------------------
    global $knowledgeitem;
	$del_knowitem=CCGetFromGet("del_knowitem", "");
	$KnowItemID=CCGetFromGet("KnowItemID", "");

	if($del_knowitem)
	{
		 	$db = new clsDBConnection1();
	  	 	$SqlSelect = "select * FROM subknowledgeitem where KnowItemID=" . $db->ToSQL($KnowItemID, ccsInteger) ;
  	 		$SqlDelete = "DELETE FROM knowledgeitem where KnowItemID=" . $db->ToSQL($KnowItemID, ccsInteger) ;
			$ErrorMessage="Topik tidak dapat dihapus";
    		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);
	 }
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_BeforeSelect @7-C42C26BD
    return $knowledgeitem_BeforeSelect;
}
//End Close knowledgeitem_BeforeSelect


?>
