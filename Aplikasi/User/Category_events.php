<?php
//BindEvents Method @1-DA8E98A1
function BindEvents()
{
    global $lblTopLink;
    global $category;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $category->category1_CatTitle->CCSEvents["BeforeShow"] = "category_category1_CatTitle_BeforeShow";
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
	$MyLink="&nbsp;<a class='CobaltDataLinkTop' href=\"Question.php". "?QueType=c&" . CCGetQueryString("QueryString", Array("MyData","Pos","QueType","ccsForm"))."\"><img Title=\"Pertanyaan Bab\" src=\"../Images/question.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"HomeUser.php\"><img Title=\"Daftar Modul\" src=\"../Images/home.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>";

	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//category_category1_CatTitle_BeforeShow @28-6102C9CF
function category_category1_CatTitle_BeforeShow()
{
    $category_category1_CatTitle_BeforeShow = true;
//End category_category1_CatTitle_BeforeShow

//Custom Code @30-994B7D54
// -------------------------
    global $category;
		if($category->Cat_CatID->GetValue()=="")
		{
		
			$category->category1_CatTitle->SetValue("-");
		}
		else
			{
				$category->category1_CatTitle->SetValue("<a href=\"Category.php?ModID=".CCGetFromGet("ModID")."&CatID=".$category->Cat_CatID->GetValue()."\" class=\"CobaltDataLink\">".$category->category1_CatTitle->GetValue()."</a>");
			}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_category1_CatTitle_BeforeShow @28-BEB1BE11
    return $category_category1_CatTitle_BeforeShow;
}
//End Close category_category1_CatTitle_BeforeShow


?>
