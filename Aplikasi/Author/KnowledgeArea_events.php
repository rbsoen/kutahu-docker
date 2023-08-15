<?php
//BindEvents Method @1-8793CE6B
function BindEvents()
{
    global $lblTopLink;
    global $knowledgearea;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $knowledgearea->CCSEvents["BeforeShowRow"] = "knowledgearea_BeforeShowRow";
    $knowledgearea->CCSEvents["BeforeSelect"] = "knowledgearea_BeforeSelect";
    $knowledgearea->CCSEvents["AfterSubmit"] = "knowledgearea_AfterSubmit";
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
	global $ModID;
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","del_area","CatID","ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","del_area","CatID","ccsForm"))."\">Daftar Bab</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"KnowledgeArea.php". "?" . CCGetQueryString("QueryString", Array("KnowAreaID","del_area","ccsForm"))."\">Daftar Sub Bab</a>";
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
//knowledgearea_BeforeShowRow @7-5805BF48
function knowledgearea_BeforeShowRow()
{
    $knowledgearea_BeforeShowRow = true;
//End knowledgearea_BeforeShowRow

//Custom Code @19-BE2DC11B
// -------------------------
    global $knowledgearea;
		if($knowledgearea->hdnKnowAreaID->GetValue() != "")
	{
	$MyAction="";
	$MyAction="<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($knowledgearea->KnowAreaTitle->GetValue())."','KnowledgeArea.php?KnowAreaID=".$knowledgearea->hdnKnowAreaID->GetValue()."&del_area=true&". CCGetQueryString("QueryString", Array("KnowAreaID","del_area","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>";
	$MyAction=$MyAction." <a class=\"CobaltDataLink\" href=\"KnowledgeItem.php?KnowAreaID=" .$knowledgearea->hdnKnowAreaID->GetValue()."&".CCGetQueryString("QueryString", Array("KnowAreaID","del_area","ccsForm"))."\"><img class=\"\" title=\"Topik\" src=\"../Images/knowledgeitem.gif\" border=\"0\"></a>";
	$MyAction=$MyAction." <a class=\"CobaltDataLink\" href=\"Question.php?KnowAreaID=" .$knowledgearea->hdnKnowAreaID->GetValue()."&".CCGetQueryString("QueryString", Array("KnowAreaID","del_area","ccsForm"))."\"><img class=\"\" title=\"Pertanyaan\" src=\"../Images/question.gif\" border=\"0\"></a>";
	$knowledgearea->lblAction->SetValue($MyAction);
	
	}
	else
	{
	$knowledgearea->lblAction->SetValue("");
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgearea_BeforeShowRow @7-FCB4C5B8
    return $knowledgearea_BeforeShowRow;
}
//End Close knowledgearea_BeforeShowRow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
function MyTrigger($SqlSelect1,$SqlSelect2,$SqlDelete,$ErrorMessage)	
{
	global $lblError;
	$db = new clsDBConnection1();  
    $db->query($SqlSelect1);
 	$Result = $db->next_record();

	$db2 = new clsDBConnection1();  
    $db2->query($SqlSelect2);
 	$Result2 = $db2->next_record();

    if($Result || $Result2)
    {
		$lblError->SetValue("<script language=\"javascript\">function MyInformation(){alert(\"".$ErrorMessage."\");}window.onload=MyInformation;</script>");
	}
	else
	{
		$db->query($SqlDelete);
		MyUpdateModule();

	}
    $db->close();
	$db2->close();
}
//knowledgearea_BeforeSelect @7-892398D1
function knowledgearea_BeforeSelect()
{
    $knowledgearea_BeforeSelect = true;
//End knowledgearea_BeforeSelect

//Custom Code @22-BE2DC11B
// -------------------------
    global $knowledgearea;
	$del_area=CCGetFromGet("del_area", "");
	$KnowAreaID=CCGetFromGet("KnowAreaID", "");

	if($del_area)
	{
		 	$db = new clsDBConnection1();
	  	 	$SqlSelect1 = "select * FROM knowledgeitem where KnowAreaID=" . $db->ToSQL($KnowAreaID, ccsInteger) ;
	  	 	$SqlSelect2 = "select * FROM question where KnowAreaID=" . $db->ToSQL($KnowAreaID, ccsInteger) ;
 
  	 		$SqlDelete = "DELETE FROM knowledgearea where KnowAreaID=" . $db->ToSQL($KnowAreaID, ccsInteger) ;
			$ErrorMessage="Sub Bab tidak dapat dihapus";
    		MyTrigger($SqlSelect1,$SqlSelect2,$SqlDelete,$ErrorMessage);
	 }

    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgearea_BeforeSelect @7-64D35475
    return $knowledgearea_BeforeSelect;
}
//End Close knowledgearea_BeforeSelect

//knowledgearea_AfterSubmit @7-0CB897D9
function knowledgearea_AfterSubmit()
{
    $knowledgearea_AfterSubmit = true;
//End knowledgearea_AfterSubmit

//Custom Code @45-BE2DC11B
// -------------------------
    global $knowledgearea;
MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close knowledgearea_AfterSubmit @7-E83164C9
    return $knowledgearea_AfterSubmit;
}
//End Close knowledgearea_AfterSubmit


?>
