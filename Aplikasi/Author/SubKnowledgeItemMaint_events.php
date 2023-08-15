<?php
//BindEvents Method @1-4EAB91B8
function BindEvents()
{
    global $lblTopLink;
    global $subknowledgeitem;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $subknowledgeitem->hdnURL->CCSEvents["BeforeShow"] = "subknowledgeitem_hdnURL_BeforeShow";
    $subknowledgeitem->CCSEvents["AfterInsert"] = "subknowledgeitem_AfterInsert";
    $subknowledgeitem->CCSEvents["AfterUpdate"] = "subknowledgeitem_AfterUpdate";
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
	global $lblTitle;
	$SubKnowItemID=CCGetFromGet("SubKnowItemID", "");

		$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("KnowItemID","ccsForm"))."\">Daftar Topik</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SubKnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Sub Topik</a>";

	if($SubKnowItemID =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SubKnowledgeItemMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Sub Topik</a>";
	$lblTitle->SetValue("Tambah Sub Topik");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SubKnowledgeItemMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Sub Topik</a>";
	$lblTitle->SetValue("Ubah Sub Topik");
	}
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//subknowledgeitem_hdnURL_BeforeShow @20-001EACE7
function subknowledgeitem_hdnURL_BeforeShow()
{
    $subknowledgeitem_hdnURL_BeforeShow = true;
//End subknowledgeitem_hdnURL_BeforeShow

//Custom Code @21-575F2A73
// -------------------------
    global $subknowledgeitem;
$subknowledgeitem->hdnURL->SetValue(CCGetQueryString("QueryString", Array("ccsForm")));
    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_hdnURL_BeforeShow @20-42DDC686
    return $subknowledgeitem_hdnURL_BeforeShow;
}
//End Close subknowledgeitem_hdnURL_BeforeShow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
//subknowledgeitem_AfterInsert @8-859C7431
function subknowledgeitem_AfterInsert()
{
    $subknowledgeitem_AfterInsert = true;
//End subknowledgeitem_AfterInsert

//Custom Code @22-575F2A73
// -------------------------
    global $subknowledgeitem;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_AfterInsert @8-CD035748
    return $subknowledgeitem_AfterInsert;
}
//End Close subknowledgeitem_AfterInsert

//subknowledgeitem_AfterUpdate @8-48584162
function subknowledgeitem_AfterUpdate()
{
    $subknowledgeitem_AfterUpdate = true;
//End subknowledgeitem_AfterUpdate

//Custom Code @23-575F2A73
// -------------------------
    global $subknowledgeitem;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_AfterUpdate @8-022A96C7
    return $subknowledgeitem_AfterUpdate;
}
//End Close subknowledgeitem_AfterUpdate
?>
