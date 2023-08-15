<?php
//BindEvents Method @1-0E656538
function BindEvents()
{
    global $subknowledgeitem;
    $subknowledgeitem->lblAction->CCSEvents["BeforeShow"] = "subknowledgeitem_lblAction_BeforeShow";
}
//End BindEvents Method

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
