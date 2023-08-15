<?php
//BindEvents Method @1-C3FCB82D
function BindEvents()
{
    global $lblTopLink;
    global $category;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $category->CCSEvents["BeforeShowRow"] = "category_BeforeShowRow";
    $category->CCSEvents["BeforeSelect"] = "category_BeforeSelect";
}
//End BindEvents Method

//lblTopLink_BeforeShow @2-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @6-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("ModID","del_cat","CatID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("del_cat","ccsForm"))."\">Daftar Bab</a>";
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @2-FEF64FEE
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
//category_BeforeShowRow @8-841EBE53
function category_BeforeShowRow()
{
    $category_BeforeShowRow = true;
//End category_BeforeShowRow

//Custom Code @21-994B7D54
// -------------------------
    global $category;
	$category->lblDelete->SetValue("<a href=\"javascript:#\" onclick=\"javascript:return Delete('".StrDelete($category->CatTitle->GetValue())."','Category.php?CatID=".$category->lblDelete->GetValue()."&del_cat=true&". CCGetQueryString("QueryString", Array("CatID","del_cat","ccsForm"))."',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a>");
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_BeforeShowRow @8-057FCB9E
    return $category_BeforeShowRow;
}
//End Close category_BeforeShowRow
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
//category_BeforeSelect @8-459F02C5
function category_BeforeSelect()
{
    $category_BeforeSelect = true;
//End category_BeforeSelect

//Custom Code @22-994B7D54
// -------------------------
    global $category;
	$del_cat=CCGetFromGet("del_cat", "");
	$CatID=CCGetFromGet("CatID", "");
	if($del_cat)
	{
		 	$db = new clsDBConnection1();
	  	 	$SqlSelect = "select * FROM knowledgearea where CatID=" . $db->ToSQL($CatID, ccsInteger) ;
  	 		$SqlDelete = "DELETE FROM category where CatID=" . $db->ToSQL($CatID, ccsInteger) ;
			$ErrorMessage="Bab tidak dapat dihapus";
    		MyTrigger($SqlSelect,$SqlDelete,$ErrorMessage);
	 }
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_BeforeSelect @8-16021DE9
    return $category_BeforeSelect;
}
//End Close category_BeforeSelect

?>
