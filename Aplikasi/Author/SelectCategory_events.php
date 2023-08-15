<?php
//BindEvents Method @1-2931AA87
function BindEvents()
{
    global $category;
    $category->CCSEvents["BeforeShowRow"] = "category_BeforeShowRow";
}
//End BindEvents Method

//category_BeforeShowRow @2-841EBE53
function category_BeforeShowRow()
{
    $category_BeforeShowRow = true;
//End category_BeforeShowRow

//Custom Code @8-994B7D54
// -------------------------
    global $category;
	$category->lblSelect->SetValue("<a href=\"javascript:window.close();window.opener.MyDisplay('".$category->CatID->GetValue()."','".$category->CatTitle->GetValue()."');\"><img src=\"../Images/select.gif\" border=\"0\" title=\"Pilih\"></a>");
    // Write your own code here.
// -------------------------
//End Custom Code

//Close category_BeforeShowRow @2-057FCB9E
    return $category_BeforeShowRow;
}
//End Close category_BeforeShowRow


?>
