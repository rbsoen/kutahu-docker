<?php
//BindEvents Method @1-F1BC900A
function BindEvents()
{
    global $lblTopLink;
    global $authors;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $authors->Button_Insert->CCSEvents["OnClick"] = "authors_Button_Insert_OnClick";
    $authors->Button_Update->CCSEvents["OnClick"] = "authors_Button_Update_OnClick";
    $authors->CCSEvents["BeforeInsert"] = "authors_BeforeInsert";
    $authors->CCSEvents["BeforeUpdate"] = "authors_BeforeUpdate";
    $authors->CCSEvents["BeforeShow"] = "authors_BeforeShow";
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
	$AutUsername=CCGetFromGet("AutUsername", "");

	$MyLink="<a class='CobaltDataLinkTop' href=\"AuthorList.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Daftar Penulis</a>";
	if($AutUsername =="")
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"AuthorMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Tambah Penulis</a>";
	$lblTitle->SetValue("Tambah Penulis");
	}
	else
	{
	$MyLink=$MyLink." > <a class='CobaltDataLinkTop' href=\"AuthorMaint.php". "?" . CCGetQueryString("QueryString", Array("ccsForm"))."\">Ubah Penulis</a>";
	$lblTitle->SetValue("Ubah Penulis");
	}
		$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @5-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//authors_Button_Insert_OnClick @9-51EE9C34
function authors_Button_Insert_OnClick()
{
    $authors_Button_Insert_OnClick = true;
//End authors_Button_Insert_OnClick

//Custom Code @50-08448E95
// -------------------------
    global $authors;
	$db = new clsDBConnection1();
    $SQL = "select * from authors where AutUsername=". $db->ToSQL($authors->AutUsername->GetValue(), ccsText);
    $db->query($SQL);
 	$Result = $db->next_record();
    if($Result)
    {
        $authors_Button_Insert_OnClick=false;
		$authors->Errors->addError("Login ID Sudah ada.");
	}
    $db->close();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_Button_Insert_OnClick @9-5EF75820
    return $authors_Button_Insert_OnClick;
}
//End Close authors_Button_Insert_OnClick

//authors_Button_Update_OnClick @10-DC046DED
function authors_Button_Update_OnClick()
{
    $authors_Button_Update_OnClick = true;
//End authors_Button_Update_OnClick

//Custom Code @69-08448E95
// -------------------------
    global $authors;
	$AutUsername=CCGetFromGet("AutUsername", "");
if($authors->AutUsername->GetValue()!=$AutUsername)
{
	$db = new clsDBConnection1();
    $SQL = "select * from authors where AutUsername=". $db->ToSQL($authors->AutUsername->GetValue(), ccsText);
    $db->query($SQL);
 	$Result = $db->next_record();
    if($Result)
    {
        $authors_Button_Update_OnClick=false;
		$authors->Errors->addError("Login ID Sudah ada.");
	}
    $db->close();
}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_Button_Update_OnClick @10-F560709D
    return $authors_Button_Update_OnClick;
}
//End Close authors_Button_Update_OnClick

//authors_BeforeInsert @8-D388893D
function authors_BeforeInsert()
{
    $authors_BeforeInsert = true;
//End authors_BeforeInsert

//Custom Code @60-08448E95
// -------------------------
    global $authors;
	if($authors->txtExperience1->GetValue()!="")
	{
	$authors->ds->Experience1=$authors->txtExperience1->GetValue()."|".$authors->txtTahun1->GetValue();
	} else{$authors->ds->Experience1="";}
	if($authors->txtExperience2->GetValue()!="")
	{
	$authors->ds->Experience2=$authors->txtExperience2->GetValue()."|".$authors->txtTahun2->GetValue();
	}else{$authors->ds->Experience2="";}
	if($authors->txtExperience3->GetValue()!="")
	{
	$authors->ds->Experience3=$authors->txtExperience3->GetValue()."|".$authors->txtTahun3->GetValue();
	}else{$authors->ds->Experience3="";}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_BeforeInsert @8-BD5BE93D
    return $authors_BeforeInsert;
}
//End Close authors_BeforeInsert

//authors_BeforeUpdate @8-F1BE1826
function authors_BeforeUpdate()
{
    $authors_BeforeUpdate = true;
//End authors_BeforeUpdate

//Custom Code @61-08448E95
// -------------------------
    global $authors;
		if($authors->txtExperience1->GetValue()!="")
	{
	$authors->ds->Experience1=$authors->txtExperience1->GetValue()."|".$authors->txtTahun1->GetValue();
	} else{$authors->ds->Experience1="";}
	if($authors->txtExperience2->GetValue()!="")
	{
	$authors->ds->Experience2=$authors->txtExperience2->GetValue()."|".$authors->txtTahun2->GetValue();
	}else{$authors->ds->Experience2="";}
	if($authors->txtExperience3->GetValue()!="")
	{
	$authors->ds->Experience3=$authors->txtExperience3->GetValue()."|".$authors->txtTahun3->GetValue();
	}else{$authors->ds->Experience3="";}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_BeforeUpdate @8-727228B2
    return $authors_BeforeUpdate;
}
//End Close authors_BeforeUpdate

//authors_BeforeShow @8-9CB4B510
function authors_BeforeShow()
{
    $authors_BeforeShow = true;
//End authors_BeforeShow

//Custom Code @65-08448E95
// -------------------------
    global $authors;
	if(CCGetFromGet("AutUsername", "")!="")
	{
		$data=explode("|",$authors->AutExperience1->GetValue());
		$authors->txtExperience1->SetValue($data[0]);
		$authors->txtTahun1->SetValue($data[1]);

		$data=explode("|",$authors->AutExperience2->GetValue());
		$authors->txtExperience2->SetValue($data[0]);
		$authors->txtTahun2->SetValue($data[1]);

		$data=explode("|",$authors->AutExperience3->GetValue());
		$authors->txtExperience3->SetValue($data[0]);
		$authors->txtTahun3->SetValue($data[1]);
	
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close authors_BeforeShow @8-6D99E997
    return $authors_BeforeShow;
}
//End Close authors_BeforeShow


?>
