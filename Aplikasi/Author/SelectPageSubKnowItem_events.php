<?php
//BindEvents Method @1-A539A8D9
function BindEvents()
{
    global $lblTopLink;
    global $subknowledgeitem;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $subknowledgeitem->lblAction->CCSEvents["BeforeShow"] = "subknowledgeitem_lblAction_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @13-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @14-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="";
	$MyLink=$MyLink."<a class='CobaltDataLinkTop' href=\"SelectPageCategory.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SelectPageKnowArea.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SelectPageKnowItem.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","ccsForm"))."\">Daftar Topik</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SelectPageSubKnowItem.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Sub Topik</a>";
	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @13-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//subknowledgeitem_lblAction_BeforeShow @10-6A47D3D7
function subknowledgeitem_lblAction_BeforeShow()
{
    $subknowledgeitem_lblAction_BeforeShow = true;
//End subknowledgeitem_lblAction_BeforeShow

//Custom Code @11-575F2A73
// -------------------------
    global $subknowledgeitem;
	$subknowledgeitem->lblAction->SetValue("<a href=\"javascript:window.close();window.opener.document.forms[0].href.value='../User/KnowledgeItem.php?SubKnowItemID=".$subknowledgeitem->SubKnowItemID->GetValue()."&".CCGetQueryString("QueryString", Array("SubKnowItemID","ccsForm"))."';\"><img title=\"Pilih\" src=\"../Images/select.gif\" border=\"0\"></a>");
    // Write your own code here.
// -------------------------
//End Custom Code

//Close subknowledgeitem_lblAction_BeforeShow @10-8ECCBC7B
    return $subknowledgeitem_lblAction_BeforeShow;
}
//End Close subknowledgeitem_lblAction_BeforeShow


?>
