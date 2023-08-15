<?php
//BindEvents Method @1-0B5C5059
function BindEvents()
{
    global $lblTopLink;
    global $lblDir;
    global $MakeDir;
    global $NewRecord1;
    global $lblTable;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $lblDir->CCSEvents["BeforeShow"] = "lblDir_BeforeShow";
    $MakeDir->btnMakeDir->CCSEvents["OnClick"] = "MakeDir_btnMakeDir_OnClick";
    $NewRecord1->FileUpload1->CCSEvents["BeforeProcessFile"] = "NewRecord1_FileUpload1_BeforeProcessFile";
    $NewRecord1->Button_Insert->CCSEvents["OnClick"] = "NewRecord1_Button_Insert_OnClick";
    $lblTable->CCSEvents["BeforeShow"] = "lblTable_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @23-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @24-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="<a class='CobaltDataLinkTop' href=\"UploadDocument.php". "?" . CCGetQueryString("QueryString", Array("","","ccsForm"))."\">Direktori Gambar</a>";
	$lblTopLink->SetValue($MyLink);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @23-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//lblDir_BeforeShow @19-5276778F
function lblDir_BeforeShow()
{
    $lblDir_BeforeShow = true;
//End lblDir_BeforeShow

//Custom Code @21-7FBBEFCD
// -------------------------
    global $lblDir;
	global $dirpath;
	global $Pat;


	 $MyArray=explode("/",$Pat);
	 $str="";
	 $strlink="";
	for($i=1;$i<count($MyArray);$i++)
	{
	$strlink=$strlink."/".$MyArray[$i];
	$str=$str." / <a href=\"UploadDocument.php?Pat=".$strlink."\">$MyArray[$i]</a>";
	}
	$lblDir->SetValue("Direktori : ".$str);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblDir_BeforeShow @19-8EA32CF9
    return $lblDir_BeforeShow;
}
//End Close lblDir_BeforeShow

//MakeDir_btnMakeDir_OnClick @16-E1888A0B
function MakeDir_btnMakeDir_OnClick()
{
    $MakeDir_btnMakeDir_OnClick = true;
//End MakeDir_btnMakeDir_OnClick

//Custom Code @20-D0E171F9
// -------------------------
    global $MakeDir;
	global $dirpath;

	if ($MakeDir->txtDir->GetValue() != "")
	{
	@mkdir($dirpath."/".$MakeDir->txtDir->GetValue(),0777);
	} 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close MakeDir_btnMakeDir_OnClick @16-C7DEE9A1
    return $MakeDir_btnMakeDir_OnClick;
}
//End Close MakeDir_btnMakeDir_OnClick

//NewRecord1_FileUpload1_BeforeProcessFile @11-863EAFF5
function NewRecord1_FileUpload1_BeforeProcessFile()
{
    $NewRecord1_FileUpload1_BeforeProcessFile = true;
//End NewRecord1_FileUpload1_BeforeProcessFile

//Custom Code @22-FF3FD3DA
// -------------------------
    global $NewRecord1;
	global $Pat;
	$NewRecord1->FileUpload1->FileFolder="../Document".$Pat."/";
    // Write your own code here.
// -------------------------
//End Custom Code

//Close NewRecord1_FileUpload1_BeforeProcessFile @11-BC94E78D
    return $NewRecord1_FileUpload1_BeforeProcessFile;
}
//End Close NewRecord1_FileUpload1_BeforeProcessFile

//NewRecord1_Button_Insert_OnClick @8-07835AFE
function NewRecord1_Button_Insert_OnClick()
{
    $NewRecord1_Button_Insert_OnClick = true;
//End NewRecord1_Button_Insert_OnClick

//Custom Code @12-FF3FD3DA
// -------------------------
    global $NewRecord1;
	$NewRecord1->FileUpload1->SetValue($NewRecord1->FileUpload1->GetValue());
	$NewRecord1->FileUpload1->Move();
    // Write your own code here.
// -------------------------
//End Custom Code

//Close NewRecord1_Button_Insert_OnClick @8-A9FC55FD
    return $NewRecord1_Button_Insert_OnClick;
}
//End Close NewRecord1_Button_Insert_OnClick

//lblTable_BeforeShow @17-A2A9417A
function lblTable_BeforeShow()
{
    $lblTable_BeforeShow = true;
//End lblTable_BeforeShow

//Custom Code @18-850AA535
// -------------------------
    global $lblTable;


	$Table="";
	$Table=$Table."<table width=\"100%\" class=\"CobaltFormTABLE\" cellpadding=\"3\" border=\"0\">";
	$Table=$Table."<tr><td class=\"CobaltColumnTD\" align=\"center\" width=\"60\"> &nbsp; </td><td align=\"center\" class=\"CobaltColumnTD\" >Nama File</td><td align=\"center\" class=\"CobaltColumnTD\" width=\"50\">Tipe</td> <td class=\"CobaltColumnTD\" align=\"center\" width=\"50\">Byte</td></tr>";
//begin data dir
	global $dirpath;
	global $Pat;

	$File="";
	$TableFolder="";
	$TableFile="";

//delete
$DelFolder=CCGetRequestParam("DelFolder", ccsGet);
$DelFile=CCGetRequestParam("DelFile", ccsGet);
$Javascript="";

if($DelFolder !="" && CCGetFromGet("ccsForm")=="")
{
		@rmdir($dirpath."/".$DelFolder) or $Javascript="<script language=\"javascript\">function MyInformation(){alert(\"Folder tidak dapat dihapus.\\n Ada data pada Sub folder\");}window.onload=MyInformation;</script>";

}

 
 if($DelFile !="")
{
	
	unlink($dirpath."/".$DelFile);
}

$dh = @opendir ($dirpath);

 $LinkArray=explode("/",$Pat);
  $SubLink= $LinkArray[count($LinkArray)-1];
$str=$Pat;
 $strx=substr($Pat,0,strlen($Pat)-strlen($SubLink)-1);

 		
		while ($entry = @readdir ($dh))
		{
			$File=$dirpath."/".$entry;
			if (is_file($File))
 			{
			$TableFile=$TableFile."<tr><td class=\"CobaltDataTD\" align=\"center\"><a href=\"javascript:#\" onclick=\"javascript:return Delete('".substr($entry,16)."','UploadDocument.php?Pat=$Pat&DelFile=$entry',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a></td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href=\"javascript:WindowOpen('$File','".substr($entry,0,15)."',600,300,1)\" >".substr($entry,16)."</a></td><td class=\"CobaltDataTD\" align=\"center\" >File</td> <td class=\"CobaltDataTD\" align=\"center\">". filesize($File) ." </td></tr>";
	
			}else {
						if ($entry == '.' || $entry == '..') {
							if(($Pat != "")&&($entry != ".")&&($Pat != "."))
							{
								$TableFolder=$TableFolder."<tr><td class=\"CobaltDataTD\" align=\"center\"> &nbsp; </td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href='UploadDocument.php?Pat=$strx' class='memberlink'>$entry</a></td><td class=\"CobaltDataTD\" align=\"center\" >&nbsp;</td> <td class=\"CobaltDataTD\" align=\"center\">&nbsp;</td></tr>";
							}
						}
						else{

								$TableFolder=$TableFolder."<tr><td class=\"CobaltDataTD\" align=\"center\"> <a href=\"javascript:#\" onclick=\"javascript:return Delete('".$entry."','UploadDocument.php?Pat=$Pat&DelFolder=$entry',this)\"><img title=\"Hapus\" src=\"../Images/delete.gif\" border=\"0\"></a> </td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href='UploadDocument.php?Pat=$str/$entry' >$entry</a></td><td class=\"CobaltDataTD\" align=\"center\" >Folder</td> <td class=\"CobaltDataTD\" align=\"center\">&nbsp;</td></tr>";
							}
					}		
		}
//end data dir
	$Table=$Table.$TableFolder.$TableFile;
	$Table=$Table."<tr><td align=\"center\" class=\"\" colspan=\"2\">&nbsp; </tr>";

	$Table=$Table."</table>";


	$lblTable->SetValue($Table.$Javascript);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTable_BeforeShow @17-0CB03DF2
    return $lblTable_BeforeShow;
}
//End Close lblTable_BeforeShow


?>
