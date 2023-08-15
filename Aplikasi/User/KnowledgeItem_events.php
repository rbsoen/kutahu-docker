<?php
//BindEvents Method @1-8451F942
function BindEvents()
{
    global $lblTopLink;
    global $knowledgeitem;
    global $glossary;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $knowledgeitem->lblTitle->CCSEvents["BeforeShow"] = "knowledgeitem_lblTitle_BeforeShow";
    $knowledgeitem->ds->CCSEvents["BeforeExecuteSelect"] = "knowledgeitem_ds_BeforeExecuteSelect";
    $glossary->CCSEvents["BeforeShow"] = "glossary_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @10-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @11-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"HomeUser.php\"><img Title=\"Daftar Modul\" src=\"../Images/home.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>";


	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @10-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//knowledgeitem_lblTitle_BeforeShow @20-692BCFC2
function knowledgeitem_lblTitle_BeforeShow()
{
    $knowledgeitem_lblTitle_BeforeShow = true;
//End knowledgeitem_lblTitle_BeforeShow

//Custom Code @21-F45BB0F1
// -------------------------
    global $knowledgeitem;
	$SubKnowItemID=CCGetFromGet("SubKnowItemID", "");
	$Title="Topik";
	if($SubKnowItemID != "")
	{
		$Title="Sub Topik";
	}
	$knowledgeitem->lblTitle->SetValue($Title);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_lblTitle_BeforeShow @20-E5BB9BF4
    return $knowledgeitem_lblTitle_BeforeShow;
}
//End Close knowledgeitem_lblTitle_BeforeShow

//knowledgeitem_ds_BeforeExecuteSelect @5-BF7C52DD
function knowledgeitem_ds_BeforeExecuteSelect()
{
    $knowledgeitem_ds_BeforeExecuteSelect = true;
//End knowledgeitem_ds_BeforeExecuteSelect

//Custom Code @19-F45BB0F1
// -------------------------
    global $knowledgeitem;
			$SubKnowItemID=CCGetFromGet("SubKnowItemID", "");

	if($SubKnowItemID!="")
	{
	$knowledgeitem->ds->SQL = "SELECT SubKnowlItemTitle as Title,SubKnowlItemContent as Content  FROM subknowledgeitem where SubKnowItemID=".$SubKnowItemID;
	}

    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgeitem_ds_BeforeExecuteSelect @5-2F497B0A
    return $knowledgeitem_ds_BeforeExecuteSelect;
}
//End Close knowledgeitem_ds_BeforeExecuteSelect

//glossary_BeforeShow @22-A0098F23
function glossary_BeforeShow()
{
    $glossary_BeforeShow = true;
//End glossary_BeforeShow

//Custom Code @36-5976F4DD
// -------------------------
    global $glossary;
	 if ($glossary->ds->RecordsCount == 0) {
     $glossary->Visible = False;
  }

    // Write your own code here.
// -------------------------
//End Custom Code

//Close glossary_BeforeShow @22-5B695ECE
    return $glossary_BeforeShow;
}
//End Close glossary_BeforeShow


?>
