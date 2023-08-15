<?php
//BindEvents Method @1-48253CD6
function BindEvents()
{
    global $lblDir;
    global $lblTable;
    $lblDir->CCSEvents["BeforeShow"] = "lblDir_BeforeShow";
    $lblTable->CCSEvents["BeforeShow"] = "lblTable_BeforeShow";
}
//End BindEvents Method

//lblDir_BeforeShow @4-5276778F
function lblDir_BeforeShow()
{
    $lblDir_BeforeShow = true;
//End lblDir_BeforeShow

//Custom Code @5-7FBBEFCD
// -------------------------
    global $lblDir;
	global $Pat;


	 $MyArray=explode("/",$Pat);
	 $str="";
	 $strlink="";
	for($i=1;$i<count($MyArray);$i++)
	{
	$strlink=$strlink."/".$MyArray[$i];
	$str=$str." / <a href=\"SelectDocument.php?Pat=".$strlink."\">$MyArray[$i]</a>";
	}
	$lblDir->SetValue("Direktori : ".$str);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblDir_BeforeShow @4-8EA32CF9
    return $lblDir_BeforeShow;
}
//End Close lblDir_BeforeShow

//lblTable_BeforeShow @2-A2A9417A
function lblTable_BeforeShow()
{
    $lblTable_BeforeShow = true;
//End lblTable_BeforeShow

//Custom Code @3-850AA535
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
if($DelFolder !="")
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
			$TableFile=$TableFile."<tr><td class=\"CobaltDataTD\" align=\"center\"><a href=\"javascript:window.close();window.opener.document.forms[0].src.value='$File';\"><img title=\"Pilih\" src=\"../Images/select.gif\" border=\"0\"></a></td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href=\"javascript:WindowOpen('$File','".substr($entry,0,15)."',600,300,1)\" >".substr($entry,16)."</a></td><td class=\"CobaltDataTD\" align=\"center\" >File</td> <td class=\"CobaltDataTD\" align=\"center\">". filesize($File) ." </td></tr>";
	
			}else {
						if ($entry == '.' || $entry == '..') {
							if(($Pat != "")&&($entry != ".")&&($Pat != "."))
							{
								$TableFolder=$TableFolder."<tr><td class=\"CobaltDataTD\" align=\"center\"> &nbsp; </td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href='SelectDocument.php?Pat=$strx' class='memberlink'>$entry</a></td><td class=\"CobaltDataTD\" align=\"center\" >File</td> <td class=\"CobaltDataTD\" align=\"center\">". filesize($File) ." </td></tr>";
							}
						}
						else{

								$TableFolder=$TableFolder."<tr><td class=\"CobaltDataTD\" align=\"center\">&nbsp;</td><td align=\"left\" class=\"CobaltDataTD\" ><a class='SulfurDataLink' href='SelectDocument.php?Pat=$str/$entry' >$entry</a></td><td class=\"CobaltDataTD\" align=\"center\" >Folder</td> <td class=\"CobaltDataTD\" align=\"center\">&nbsp;</td></tr>";
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

//Close lblTable_BeforeShow @2-0CB03DF2
    return $lblTable_BeforeShow;
}
//End Close lblTable_BeforeShow


?>
