<?php
//BindEvents Method @1-2BCFCB4E
function BindEvents()
{
    global $lblTopLink;
    global $question;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $question->CCSEvents["AfterInsert"] = "question_AfterInsert";
    $question->CCSEvents["AfterUpdate"] = "question_AfterUpdate";
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
	$QueID=CCGetFromGet("QueID", "");

		$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ccsForm"))."\">Daftar Katagori</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","ccsForm"))."\">Daftar Knowledge Area</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Question.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Pertanyaan</a>";

if($QueID =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"QuestionMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Pertanyaan</a>";
	$lblTitle->SetValue("Tambah Pertanyaan");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"QuestionMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Pertanyaan</a>";
	$lblTitle->SetValue("Ubah Pertanyaan");
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
//question_AfterInsert @7-E55D6570
function question_AfterInsert()
{
    $question_AfterInsert = true;
//End question_AfterInsert

//Custom Code @34-B40A91D4
// -------------------------
    global $question;
	MyUpdateModule();	
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_AfterInsert @7-4FC53DB5
    return $question_AfterInsert;
}
//End Close question_AfterInsert

//question_AfterUpdate @7-C76BF46B
function question_AfterUpdate()
{
    $question_AfterUpdate = true;
//End question_AfterUpdate

//Custom Code @35-B40A91D4
// -------------------------
    global $question;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_AfterUpdate @7-80ECFC3A
    return $question_AfterUpdate;
}
//End Close question_AfterUpdate


?>
