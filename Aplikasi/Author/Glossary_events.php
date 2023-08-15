<?php
//BindEvents Method @1-E21EC5BD
function BindEvents()
{
    global $lblTopLink;
    global $glossary;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $glossary->CCSEvents["AfterSubmit"] = "glossary_AfterSubmit";
}
//End BindEvents Method

//lblTopLink_BeforeShow @26-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @27-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","KnowItemID","ccsForm"))."\">Daftar Topik</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"SubKnowledgeItem.php". "?" . CCGetQueryString("QueryString", Array("SubKnowItemID","ccsForm"))."\">Daftar Sub Topik</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Glossary.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Glossary</a>";


	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @26-FEF64FEE
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
//glossary_AfterSubmit @9-481676C0
function glossary_AfterSubmit()
{
    $glossary_AfterSubmit = true;
//End glossary_AfterSubmit

//Custom Code @29-5976F4DD
// -------------------------
    global $glossary;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close glossary_AfterSubmit @9-F26A1390
    return $glossary_AfterSubmit;
}
//End Close glossary_AfterSubmit


?>
