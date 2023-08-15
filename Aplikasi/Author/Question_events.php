<?php
//BindEvents Method @1-6D2DD362
function BindEvents()
{
    global $lblTopLink;
    global $question;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $question->CCSEvents["BeforeShowRow"] = "question_BeforeShowRow";
    $question->CCSEvents["BeforeSelect"] = "question_BeforeSelect";
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
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","ccsForm"))."\">Daftar Sub Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Question.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Pertanyaan</a>";

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
//question_BeforeShowRow @7-C8744A84
function question_BeforeShowRow()
{
    $question_BeforeShowRow = true;
//End question_BeforeShowRow

//Custom Code @18-B40A91D4
// -------------------------
    global $question;
			$question->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($question->QueTitle->GetValue())."','Question.php?QueID=".$question->lblDelete->GetValue()."&del_que=true&". CCGetQueryString("QueryString", Array("QueID","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_BeforeShowRow @7-997FFDE1
    return $question_BeforeShowRow;
}
//End Close question_BeforeShowRow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
//question_BeforeSelect @7-A658FD63
function question_BeforeSelect()
{
    $question_BeforeSelect = true;
//End question_BeforeSelect

//Custom Code @19-B40A91D4
// -------------------------
    global $question;

	$del_que=CCGetFromGet("del_que", "");
	$QueID=CCGetFromGet("QueID", "");

	if($del_que)
	{
	 		$db = new clsDBConnection1();
   	 		$SQL = "DELETE FROM question where QueID=" . $db->ToSQL($QueID, ccsInteger) ;
    		$db->query($SQL);
			MyUpdateModule();
	 }
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_BeforeSelect @7-1F32CB2D
    return $question_BeforeSelect;
}
//End Close question_BeforeSelect


?>
