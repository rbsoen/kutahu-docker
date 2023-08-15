<?php
//BindEvents Method @1-E0B1828B
function BindEvents()
{
    global $lblTopLink;
    global $lblQueIDArray;
    global $test;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $lblQueIDArray->CCSEvents["BeforeShow"] = "lblQueIDArray_BeforeShow";
    $test->ds->CCSEvents["BeforeExecuteSelect"] = "test_ds_BeforeExecuteSelect";
}
//End BindEvents Method

//lblTopLink_BeforeShow @24-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @25-89D6DE21
// -------------------------
    global $lblTopLink;
	$MyLink="";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"HomeUser.php\"><img Title=\"Daftar Modul\" src=\"../Images/home.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"#\" onclick=\"javascript:window.open('../Help/HelpUser.pdf','winhelp');\"><img Title=\"Help\" src=\"../Images/help.gif\" border=\"0\"></a>";
	$MyLink=$MyLink."&nbsp;<a class='CobaltDataLinkTop' href=\"../Login.php?Logout=1\"><img Title=\"Keluar\" src=\"../Images/logout.gif\" border=\"0\"></a>";

	$lblTopLink->SetValue($MyLink);
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblTopLink_BeforeShow @24-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//lblQueIDArray_BeforeShow @27-7F32FC86
function lblQueIDArray_BeforeShow()
{
    $lblQueIDArray_BeforeShow = true;
//End lblQueIDArray_BeforeShow

//Custom Code @48-C44559A2
// -------------------------
    global $lblQueIDArray;
	global $Record;

 
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$QueType=CCGetRequestParam("QueType", ccsGet);
	$CatID=CCGetRequestParam("CatID", ccsGet);
	$KnowAreaID=CCGetRequestParam("KnowAreaID", ccsGet);
  	$db = new clsDBConnection1();
  
  $MySql=$MySql."SELECT question.*,`module`.ModTitle,category.CatTitle,knowledgearea.KnowAreaTitle ";
  $MySql=$MySql."FROM ((category INNER JOIN `module` ON ";
  $MySql=$MySql."category.ModID = `module`.ModID) INNER JOIN knowledgearea ON ";
  $MySql=$MySql."knowledgearea.CatID = category.CatID) INNER JOIN question ON ";
  $MySql=$MySql."question.KnowAreaID = knowledgearea.KnowAreaID ";
  $MySql=$MySql."WHERE `module`.ModID = $ModID";
  
  $Desc="";
  	if ($QueType=="m")
  	{
  		$MySql=$MySql." and question.QueModule=1";
  	} else 	if ($QueType=="c")
  			{
  				$MySql=$MySql." and category.CatID=$CatID and question.QueCategory=1";
  			} else if($QueType=="k")
  					{
  						$MySql=$MySql." and category.CatID=$CatID and  knowledgearea.KnowAreaID=$KnowAreaID";
  
  					}
  
      $db->query($MySql);
  	global $MyData;
  	$MyCount=0;
      while($db->next_record())
      {
  	if($MyData!= ""){$sep=";";}
  	else
  	{
 	$Desc="";$TitleDesc="";
					 if ($QueType=="m")
  						{
  							$Desc=$db->f("ModTitle");$TitleDesc="Modul";
  						}else if($QueType=="c")
  								{
  								$Desc=$db->f("CatTitle");$TitleDesc="Bab";
  								}else if($QueType=="k")
  										{
  										$Desc=$db->f("KnowAreaTitle");$TitleDesc="Sub Bab";
  										}
  	}
  	$MyData=$MyData.$sep.$db->f("QueID");
  	$MyCount=$MyCount+1;
  	}
  
      $db->close();

	//random data
	srand ((double) microtime() * 10000000);
	$Data=explode(";",$MyData);
	$rand_keys = array_rand ($Data, count($Data));
	if(count($Data)>1)
	{
		$MyData="";
		for($i=0;$i<count($Data);$i++)
		{
	  		if($MyData!= ""){
			$separator=";";
			}
		
		//ABCDE
		$separatorABCDE="";
		srand ((double) microtime() * 10000000);
		$ABCDE = array ("A", "B", "C", "D", "E");
		$rand_ABCDE = array_rand ($ABCDE, count($ABCDE));
			$MyABCDE=":";
			for($j=0;$j<count($ABCDE);$j++)
			{
				if($MyABCDE!= ":"){$separatorABCDE="-";}
				$MyABCDE=$MyABCDE.$separatorABCDE.$ABCDE[$rand_ABCDE[$j]];
			}
		//ABCDE

			$MyData=$MyData.$separator.$Data[$rand_keys[$i]].$MyABCDE.":";
		}	
	}else {
			//ABCDE
		$separatorABCDE="";
		srand ((double) microtime() * 10000000);
		$ABCDE = array ("A", "B", "C", "D", "E");
		$rand_ABCDE = array_rand ($ABCDE, count($ABCDE));
			$MyABCDE=":";
			for($j=0;$j<count($ABCDE);$j++)
			{
				if($MyABCDE!= ":"){$separatorABCDE="-";}
				$MyABCDE=$MyABCDE.$separatorABCDE.$ABCDE[$rand_ABCDE[$j]];
			}
		//ABCDE

			$MyData=$MyData.$separator.$Data[$rand_keys[$i]].$MyABCDE.":";
			}

	//random data

  	$MyTable="";
  	if($MyData!="")
  	{
  	$MyTable=$MyTable."<table class=\"CobaltFormTABLE\" cellpadding=\"3\" border=\"0\" width=\"100%\">";
  	$MyTable=$MyTable."<tr><td class=\"CobaltColumnTD\" nowrap colspan=\"2\">Pertanyaan</td></tr>"; 
	$MyTable=$MyTable."<tr><td class=\"CobaltDataTD\" nowrap width=\"120\">$TitleDesc</td><td class=\"CobaltDataTD\" >$Desc</td></tr>"; 
	$MyTable=$MyTable."<tr><td class=\"CobaltDataTD\" nowrap width=\"120\">Jumlah Pertanyaan</td><td class=\"CobaltDataTD\" >$MyCount</td></tr>"; 
  	$MyTable=$MyTable."</table>";
  //tombol	
	 global $lblStart;
	$Pos=0;
	$QueType=CCGetRequestParam("QueType", ccsGet);
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$CatID=CCGetRequestParam("CatID", ccsGet);
	$KnowAreaID=CCGetRequestParam("KnowAreaID", ccsGet);

	$URLHidden="";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"MyData\" value=\"$MyData\">";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"QueType\" value=\"$QueType\">";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"Pos\" value=\"$Pos\">";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"ModID\" value=\"$ModID\">";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"CatID\" value=\"$CatID\">";
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"KnowAreaID\" value=\"$KnowAreaID\">";
	
	$Redirect="QuestionSlide.php". "?Pos=0&MyData=" .$MyData."&".CCGetQueryString("QueryString", Array("Pos","MyData","ccsForm"));
 	$lblStart->SetValue("<table border=\"0\" width=\"100%\"><tr><td align=\"center\"><form action=\"$Redirect\" method=\"get\">$URLHidden<input type=\"submit\" value=\"Mulai\" class=\"CobaltButton\"></form></td></tr></table>");
 
   	} else
  		{
  			
  			$MyTable="<table class=\"CobaltFormTABLE\" cellpadding=\"3\" border=\"0\" width=\"100%\">";
  			$MyTable=$MyTable."<tr><td class=\"CobaltColumnTD\" nowrap>Pertanyaan</td></tr>"; 
  			$MyTable=$MyTable."<tr><td class=\"CobaltErrorDataTD\" nowrap>Jumlah Pertanyaan : $MyCount</td></tr>"; 
  			$MyTable=$MyTable."</table>";
  			$Record->Visible=false;	
  
  		}
  	$lblQueIDArray->SetValue($MyTable);
 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblQueIDArray_BeforeShow @27-81DF4B16
    return $lblQueIDArray_BeforeShow;
}
//End Close lblQueIDArray_BeforeShow

//test_ds_BeforeExecuteSelect @40-C3A68CC2
function test_ds_BeforeExecuteSelect()
{
    $test_ds_BeforeExecuteSelect = true;
//End test_ds_BeforeExecuteSelect

//Custom Code @47-BC68F432
// -------------------------
    global $test;
	$MySql="";
	$ModID=CCGetFromGet("ModID", "");
	$CatID=CCGetFromGet("CatID", "");
	$KnowAreaID=CCGetFromGet("KnowAreaID", "");

	$QueType=CCGetFromGet("QueType", "");
	$UserUsername=CCGetSession("UserID");

	$db = new clsDBConnection1();
	$MySql=$MySql."SELECT * FROM test";
	if ($QueType=="m")
  	{
  		$MySql=$MySql." where test.ModID=".$ModID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText)." and TestType=".$db->ToSQL($QueType, ccsText);
  	} else 	if ($QueType=="c")
  			{
  			$MySql=$MySql." where test.ModID=".$ModID." and test.CatID=".$CatID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText)." and TestType=".$db->ToSQL($QueType, ccsText);

			}
			else 	if ($QueType=="k")
  					{
 					$MySql=$MySql." where test.ModID=".$ModID." and test.CatID=".$CatID." and test.KnowAreaID=".$KnowAreaID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText)." and TestType=".$db->ToSQL($QueType, ccsText);

					}
	$test->ds->SQL = $MySql;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close test_ds_BeforeExecuteSelect @40-8CE9EF04
    return $test_ds_BeforeExecuteSelect;
}
//End Close test_ds_BeforeExecuteSelect

//DEL  // -------------------------
//DEL       // -------------------------

//DEL  // -------------------------
//DEL      global $Record;
//DEL  		global $MyData;
//DEL  	$Record->MyData->SetValue("x".$MyData);
//DEL   
//DEL      // Write your own code here.
//DEL  // -------------------------



?>
