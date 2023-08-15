<?php
//BindEvents Method @1-C6B8DAB2
function BindEvents()
{
    global $lblTopLink;
    global $category;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $category->lblCatTitle->CCSEvents["BeforeShow"] = "category_lblCatTitle_BeforeShow";
    $category->lblLinkCategory->CCSEvents["BeforeShow"] = "category_lblLinkCategory_BeforeShow";
    $category->CCSEvents["AfterInsert"] = "category_AfterInsert";
    $category->CCSEvents["AfterUpdate"] = "category_AfterUpdate";
}
//End BindEvents Method

//lblTopLink_BeforeShow @14-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @15-89D6DE21
// -------------------------
    global $lblTopLink;
	global $lblTitle;
	$CatID=CCGetFromGet("CatID", "");

	$MyLink="<a class='CobaltDataLinkTop' href=\"ModuleList.php". "?" . CCGetQueryString("QueryString", Array("ModID","ccsForm"))."\">Daftar Modul</a>";
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"Category.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Bab</a>";
	if($CatID =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"CategoryMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Bab</a>";
	$lblTitle->SetValue("Tambah Bab");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"CategoryMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Bab</a>";
	$lblTitle->SetValue("Ubah Bab");
	}
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @14-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//category_lblCatTitle_BeforeShow @17-6120FCAE
function category_lblCatTitle_BeforeShow()
{
    $category_lblCatTitle_BeforeShow = true;
//End category_lblCatTitle_BeforeShow

//Custom Code @27-994B7D54
// -------------------------
    global $category;
    // Write your own code here.
// -------------------------
//End Custom Code
$category->lblCatTitle->SetValue("<span id=\"lblCatTitle\" name=\"lblCatTitle\">".$category->lblCatTitle->GetValue()."</span>");

//Close category_lblCatTitle_BeforeShow @17-9837D0F1
    return $category_lblCatTitle_BeforeShow;
}
//End Close category_lblCatTitle_BeforeShow

//category_lblLinkCategory_BeforeShow @18-7DB11DB6
function category_lblLinkCategory_BeforeShow()
{
    $category_lblLinkCategory_BeforeShow = true;
//End category_lblLinkCategory_BeforeShow

//Custom Code @34-994B7D54
// -------------------------
    global $category;
		$ModID=CCGetFromGet("ModID", "");
		$category->lblLinkCategory->SetValue("<a href=\"javascript:MyDelete();\"><img src=\"../Images/delete.gif\" border=\"0\" title=\"Hapus\"></a> <a href=\"javascript:WindowOpen('SelectCategory.php?CatID=".$category->lblLinkCategory->GetValue()."&ModID=".$ModID."','selectcategory',500,400,'yes')\" ><img src=\"../Images/select.gif\" border=\"0\" title=\"Pilih Bab\"></a>");

    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_lblLinkCategory_BeforeShow @18-F6F0A8A7
    return $category_lblLinkCategory_BeforeShow;
}
//End Close category_lblLinkCategory_BeforeShow
function MyUpdateModule()	
{
	$ModID=CCGetFromGet("ModID", "");

	$db = new clsDBConnection1();    
    $db->query("update module set ModModify ='".date("Y-m-d H:i:s")."' where ModID=".$ModID);
 	$db->close();
}
//category_AfterInsert @5-6716802D
function category_AfterInsert()
{
    $category_AfterInsert = true;
//End category_AfterInsert

//Custom Code @37-994B7D54
// -------------------------
    global $category;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_AfterInsert @5-C95FD2B9
    return $category_AfterInsert;
}
//End Close category_AfterInsert

//category_AfterUpdate @5-45201136
function category_AfterUpdate()
{
    $category_AfterUpdate = true;
//End category_AfterUpdate

//Custom Code @38-994B7D54
// -------------------------
    global $category;
	MyUpdateModule();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_AfterUpdate @5-06761336
    return $category_AfterUpdate;
}
//End Close category_AfterUpdate


?>
