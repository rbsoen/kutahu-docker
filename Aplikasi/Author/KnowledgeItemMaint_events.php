<?php
//BindEvents Method @1-6EBD3E87
function BindEvents()
{
    global $lblTopLink;
    global $knowledgeitem;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $knowledgeitem->CCSEvents["AfterInsert"] = "knowledgeitem_AfterInsert";
    $knowledgeitem->CCSEvents["AfterUpdate"] = "knowledgeitem_AfterUpdate";
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
	global $lblTitle;
	$KnowItemID=CCGetFromGet("KnowItemID", "");


	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Topik</a>";
	if($KnowItemID =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItemMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Topik</a>";
	$lblTitle->SetValue("Tambah Topik");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItemMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Topik</a>";
	$lblTitle->SetValue("Ubah Topik");
	}

	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
//knowledgeitem_AfterInsert @7-E5F7AE65
function knowledgeitem_AfterInsert()
{
    $knowledgeitem_AfterInsert = true;
//End knowledgeitem_AfterInsert

//Custom Code @21-F45BB0F1
// -------------------------
    global $knowledgeitem;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_AfterInsert @7-EAE17B9F
    return $knowledgeitem_AfterInsert;
}
//End Close knowledgeitem_AfterInsert

//knowledgeitem_AfterUpdate @7-AFDE13FC
function knowledgeitem_AfterUpdate()
{
    $knowledgeitem_AfterUpdate = true;
//End knowledgeitem_AfterUpdate

//Custom Code @22-F45BB0F1
// -------------------------
    global $knowledgeitem;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_AfterUpdate @7-25C8BA10
    return $knowledgeitem_AfterUpdate;
}
//End Close knowledgeitem_AfterUpdate


?>
