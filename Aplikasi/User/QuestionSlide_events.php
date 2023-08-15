<?php
//BindEvents Method @1-40AF4155
function BindEvents()
{
    global $lblTopLink;
    global $question;
    global $lblEnd;
    global $CCSEvents;
    $lblTopLink->CCSEvents["BeforeShow"] = "lblTopLink_BeforeShow";
    $question->lblTable->CCSEvents["BeforeShow"] = "question_lblTable_BeforeShow";
    $question->ds->CCSEvents["BeforeExecuteSelect"] = "question_ds_BeforeExecuteSelect";
    $question->CCSEvents["BeforeShowRow"] = "question_BeforeShowRow";
    $lblEnd->CCSEvents["BeforeShow"] = "lblEnd_BeforeShow";
    $CCSEvents["BeforeShow"] = "Page_BeforeShow";
}
//End BindEvents Method

//lblTopLink_BeforeShow @7-1DF6B4AB
function lblTopLink_BeforeShow()
{
    $lblTopLink_BeforeShow = true;
//End lblTopLink_BeforeShow

//Custom Code @8-89D6DE21
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

//Close lblTopLink_BeforeShow @7-FEF64FEE
    return $lblTopLink_BeforeShow;
}
//End Close lblTopLink_BeforeShow

//question_lblTable_BeforeShow @26-DAAF34CE
function question_lblTable_BeforeShow()
{
    $question_lblTable_BeforeShow = true;
//End question_lblTable_BeforeShow

//Custom Code @27-B40A91D4
// -------------------------
    global $question;


	//selected

	$Pos=CCGetRequestParam("Pos", ccsGet);
	$End=CCGetRequestParam("End", ccsGet);
	$MyData=CCGetRequestParam("MyData", ccsGet);
	$MyAnswer=CCGetRequestParam("MyAnswer", ccsGet);
	$QueType=CCGetRequestParam("QueType", ccsGet);
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$CatID=CCGetRequestParam("CatID", ccsGet);
	$KnowAreaID=CCGetRequestParam("KnowAreaID", ccsGet);
	
	$Data=explode(";",$MyData);
		$MyQueID=explode(":",$Data[$Pos]);
		$Selected=$MyQueID[2];
		$ArrRandomSelected=explode("-",$MyQueID[1]);
		$ViewSelected= array("A","B","C","D","E");
		if ($Selected=="A"){$SelectedA="checked";}else
		if ($Selected=="B"){$SelectedB="checked";}else
		if ($Selected=="C"){$SelectedC="checked";}else
		if ($Selected=="D"){$SelectedD="checked";}else
		if ($Selected=="E"){$SelectedE="checked";}
	//selected
if($question->QueChoiceA->GetValue() !=""){$QuestionTitle["A"]=$question->QueChoiceA->GetValue();}
if($question->QueChoiceB->GetValue() !=""){$QuestionTitle["B"]=$question->QueChoiceB->GetValue();}
if($question->QueChoiceC->GetValue() !=""){$QuestionTitle["C"]=$question->QueChoiceC->GetValue();}
if($question->QueChoiceD->GetValue() !=""){$QuestionTitle["D"]=$question->QueChoiceD->GetValue();}
if($question->QueChoiceE->GetValue() !=""){$QuestionTitle["E"]=$question->QueChoiceE->GetValue();}

	$ViewSelect="";
	$Table="<table width=\"100%\" class=\"CobaltFormTABLE\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	$Table=$Table."<tr><td  colspan=\"2\"><table  class=\"CobaltColumnTD\"  cellpadding=\"3\" border=\"0\" height=\"100%\" width=\"100%\" ><tr><td width=\"100\"> Pertanyaan :</td><td>".$question->QueTitle->GetValue()."</td></table></td></tr>";
	$Table=$Table."<tr><td  colspan=\"2\"><table  class=\"CobaltFormTABLE\"  cellpadding=\"3\" border=\"0\" height=\"100%\" width=\"100%\" >";
	$j=0;
	$Repeat=null;
	for($i=0;$i<count($ArrRandomSelected);$i++)
	{
		if($QuestionTitle[$ArrRandomSelected[$i]] !="")
			{
			$Repeat[$j]=$ArrRandomSelected[$i];
			$j++;
			}
	}

	for($i=0;$i<count($Repeat);$i++)
	{
		if($Selected==$ViewSelected[$i])
		{
		$ViewSelect=$ViewSelect."<input type=\"radio\" name=\"Select\" value=\"$ViewSelected[$i]\" onclick=\"MySelect(this)\" checked>$ViewSelected[$i] ";
		}
		else
		{
		$ViewSelect=$ViewSelect."<input type=\"radio\" name=\"Select\" value=\"$ViewSelected[$i]\" onclick=\"MySelect(this)\" >$ViewSelected[$i] ";
		}

	$Table=$Table."<tr><td align=\"middle\" class=\"CobaltDataTD\" width=\"100\">$ViewSelected[$i]</td> <td class=\"CobaltDataTD\">".$QuestionTitle[$Repeat[$i]]."</td></tr>";
	}

	$Table=$Table."<tr><td align=\"middle\" class=\"CobaltDataTD\">Jawaban</td> <td class=\"CobaltDataTD\">".$ViewSelect."</td></tr>";
	$Table=$Table."</table></td></tr>";




$Javascript=$Javascript."<script language=\"javascript\">";
$Javascript=$Javascript."function CekSelect(){if(document.frmNavigator.answer.value ==\"\"){ alert(\"Pilih Jawaban Anda\");return false;}";
$Javascript=$Javascript."else {x=document.frmNavigator.MyData.value.split(\";\");sep=\"\";str=\"\";for(i=0;i<x.length;i++){if(str !=\"\"){sep=\";\"} if(document.frmNavigator.TmpPos.value!=i){str=str+sep+x[i]}else{y=x[i].split(\":\");str=str+sep+(y[0]+\":\"+y[1]+\":\"+document.frmNavigator.answer.value);};};document.frmNavigator.MyData.value=str;return true;} } ";
$Javascript=$Javascript."function MySelect(x){document.frmNavigator.answer.value=x.value;}function prev(){if(document.frmNavigator.answer.value !=\"\"){ document.frmNavigator.End.value=false;document.frmNavigator.Pos.value=eval(document.frmNavigator.Pos.value)-1;}} function next(){if(document.frmNavigator.answer.value !=\"\"){ document.frmNavigator.End.value=false;document.frmNavigator.Pos.value=eval(document.frmNavigator.Pos.value)+1;}} function MyEnd(){if(document.frmNavigator.answer.value !=\"\"){ document.frmNavigator.End.value=true;}}</script>";

$URLHidden=$URLHidden."<input type=\"hidden\" name=\"Pos\" value=\"$Pos\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"TmpPos\" value=\"$Pos\">";

$URLHidden=$URLHidden."<input type=\"hidden\" name=\"End\" value=\"$End\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"MyData\" value=\"$MyData\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"QueType\" value=\"$QueType\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"ModID\" value=\"$ModID\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"CatID\" value=\"$CatID\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"KnowAreaID\" value=\"$KnowAreaID\">";

	$Table=$Table."<tr><td class=\"\" colspan=\"2\" align=\"middle\">";

	$Data=explode(";",$MyData);


	$Table=$Table."<form name=\"frmNavigator\"action=\"QuestionSlide.php\" method=\"get\" onsubmit=\"return CekSelect();\">";

	if($Pos>0)
	{
	$Table=$Table."<input style=\"WIDTH: 90px\" class=\"CobaltButton\" type=\"submit\" value=\"Sebelumnya\" onclick=\"prev();\">";
	}


	if($Pos<Count($Data)-1)
	{
	$Table=$Table." <input style=\"WIDTH: 90px\" class=\"CobaltButton\" type=\"submit\" value=\"Selanjutnya\" onclick=\"next();\">";
	} else {
		$Table=$Table." <input style=\"WIDTH: 90px\" class=\"CobaltButton\" type=\"submit\" value=\"Selesai\" onclick=\"MyEnd();\">";
			}
	$Table=$Table.$URLHidden."<input style=\"WIDTH: 90px\" type=\"hidden\" name=\"answer\" value=\"$Selected\"></form></td></tr>";
	$Table=$Table."</table>";



	$question->lblTable->SetValue($Javascript.$Table);
 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_lblTable_BeforeShow @26-DD8AEA0B
    return $question_lblTable_BeforeShow;
}
//End Close question_lblTable_BeforeShow

//question_ds_BeforeExecuteSelect @9-2E3A83F7
function question_ds_BeforeExecuteSelect()
{
    $question_ds_BeforeExecuteSelect = true;
//End question_ds_BeforeExecuteSelect

//Custom Code @21-B40A91D4
// -------------------------
    global $question;

	$MyData=CCGetRequestParam("MyData", ccsGet);
	$Pos=CCGetRequestParam("Pos", ccsGet);
	$End=CCGetRequestParam("End", ccsGet);

	$Data=explode(";",$MyData);
	$x=0;
	if($End !="true")
	{
	$MyQueID=explode(":",$Data[$Pos]);
	$x=$MyQueID[0];
		if($x=="")
		{
		$x=0;
		global $lblWarning;
		$lblWarning->SetValue("Ada kesalahan teknis. Ulangi Test Anda dari awal.");
		}
	}
	$question->ds->SQL="SELECT * FROM question where QueID=".$x;
 
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_ds_BeforeExecuteSelect @9-E56EE1F9
    return $question_ds_BeforeExecuteSelect;
}
//End Close question_ds_BeforeExecuteSelect

//question_BeforeShowRow @9-C8744A84
function question_BeforeShowRow()
{
    $question_BeforeShowRow = true;
//End question_BeforeShowRow

//Custom Code @24-B40A91D4
// -------------------------
    global $question;
    // Write your own code here.
// -------------------------
//End Custom Code

//Close question_BeforeShowRow @9-997FFDE1
    return $question_BeforeShowRow;
}
//End Close question_BeforeShowRow
function PositionAnswer($x)
{
if($x=="A"){$z=0;} else
if($x=="B"){$z=1;} else
if($x=="C"){$z=2;} else
if($x=="D"){$z=3;} else
if($x=="E"){$z=4;} 
return $z;
}
function ABCDEPositionAnswer($x)
{
if($x=="0"){$z=A;} else
if($x=="1"){$z=B;} else
if($x=="2"){$z=C;} else
if($x=="3"){$z=D;} else
if($x=="4"){$z=E;} 
return $z;
}
//lblEnd_BeforeShow @28-002BD448
function lblEnd_BeforeShow()
{
    $lblEnd_BeforeShow = true;
//End lblEnd_BeforeShow

//Custom Code @29-58C58007
// -------------------------
    global $lblEnd;
	$Pos=CCGetRequestParam("Pos", ccsGet);
	$End=CCGetRequestParam("End", ccsGet);
	$MyData=CCGetRequestParam("MyData", ccsGet);
	$MyAnswer=CCGetRequestParam("MyAnswer", ccsGet);
	$QueType=CCGetRequestParam("QueType", ccsGet);
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$CatID=CCGetRequestParam("CatID", ccsGet);
	$KnowAreaID=CCGetRequestParam("KnowAreaID", ccsGet);

	$Desc="";
	$TitleDesc="";

	if($End=="true")
	{
			$Data=explode(";",$MyData);
			
			$db = new clsDBConnection1();
			$answer="";
			$MyTrue=0;
			$MyFalse=0;
	$answer="";
	$answer=$answer."<table width=\"\" class=\"CobaltFormTABLE\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\">";
	$answer=$answer."<tr><td class=\"CobaltColumnTD\" align=\"center\">No</td><td class=\"CobaltColumnTD\" align=\"center\">Kunci Jawaban</td><td class=\"CobaltColumnTD\" align=\"center\">Jawaban</td><td class=\"CobaltColumnTD\" align=\"center\">&nbsp;</td></tr>";

			for($i=0;$i<Count($Data);$i++)
			{
				$QueID=explode(":",$Data[$i]);
				$ArrRandomSelected=explode("-",$QueID[1]);
$MySql="";
$MySql=$MySql."SELECT question.*,`module`.ModTitle,category.CatTitle,knowledgearea.KnowAreaTitle ";
  $MySql=$MySql."FROM ((category INNER JOIN `module` ON ";
  $MySql=$MySql."category.ModID = `module`.ModID) INNER JOIN knowledgearea ON ";
  $MySql=$MySql."knowledgearea.CatID = category.CatID) INNER JOIN question ON ";
  $MySql=$MySql."question.KnowAreaID = knowledgearea.KnowAreaID ";
  $MySql=$MySql."where question.QueID=".$QueID[0];

   	 			//$SQL = "SELECT * FROM question where QueID=".$QueID[0];
    			$db->query($MySql);	
				
    			while($db->next_record())
				{

if($db->f("QueChoiceA") !=""){$QuestionTitle["A"]=$db->f("QueChoiceA");}
if($db->f("QueChoiceB") !=""){$QuestionTitle["B"]=$db->f("QueChoiceB");}
if($db->f("QueChoiceC") !=""){$QuestionTitle["C"]=$db->f("QueChoiceC");}
if($db->f("QueChoiceD") !=""){$QuestionTitle["D"]=$db->f("QueChoiceD");}
if($db->f("QueChoiceE") !=""){$QuestionTitle["E"]=$db->f("QueChoiceE");}
			//title
				
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
				//
					$Repeat=null;
					$j=0;
					for($k=0;$k<count($ArrRandomSelected);$k++)
					{
						if($QuestionTitle[$ArrRandomSelected[$k]] !="")
						{
						$Repeat[$j]=$ArrRandomSelected[$k];
						$j++;
						}
					}
					$kond=0;
					$m=0;
					$KeyAnswer="";
					while(($m<count($Repeat)) && ($kond==0))
					{
						if($db->f("QueAnswer")==$Repeat[$m])
						{
						$KeyAnswer=ABCDEPositionAnswer($m);
							if(PositionAnswer($QueID[2])==$m)
							{
							$kond=1;
							}
						}
						$m++;
					}
					$TextKond="";
					if($kond==1)
					{
						$MyTrue=$MyTrue+1;$TextKond="Benar";
					} else {
							$MyFalse=$MyFalse+1;$TextKond="Salah";
							}
	$answer=$answer."<tr><td class=\"CobaltDataTD\" align=\"center\">".($i+1)."</td><td class=\"CobaltDataTD\" align=\"center\">"./*$db->f("QueAnswer").*/$KeyAnswer."</td><td class=\"CobaltDataTD\" align=\"center\">".$QueID[2]."</td><td class=\"CobaltDataTD\" align=\"center\">".$TextKond."</td></tr>";

				}

	
			}
	$answer=$answer."</table>";


$URLHidden=$URLHidden."<input type=\"hidden\" name=\"Pos\" value=\"$Pos\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"TmpPos\" value=\"$Pos\">";

$URLHidden=$URLHidden."<input type=\"hidden\" name=\"End\" value=\"$End\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"MyData\" value=\"$MyData\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"QueType\" value=\"$QueType\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"ModID\" value=\"$ModID\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"CatID\" value=\"$CatID\">";
$URLHidden=$URLHidden."<input type=\"hidden\" name=\"KnowAreaID\" value=\"$KnowAreaID\">";


	$Table="";
	$Table=$Table."<table width=\"100%\" class=\"CobaltFormTABLE\" cellpadding=\"3\" border=\"0\">";
	$Table=$Table."<tr><td colspan=\"2\" class=\"CobaltColumnTD\">Nilai&nbsp;</td></tr>";
	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\" width=\"1%\" nowrap>".$TitleDesc."</td> <td class=\"CobaltDataTD\">".$Desc."</td></tr>";
	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\" nowrap>Jumlah Pertanyaan</td> <td class=\"CobaltDataTD\">".($MyTrue+$MyFalse)."</td></tr>";
	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\">Benar</td> <td class=\"CobaltDataTD\">$MyTrue</td></tr>";
	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\">Salah</td> <td class=\"CobaltDataTD\">$MyFalse</td></tr>";
	$MyValue=( ($MyTrue/($MyTrue+$MyFalse))*100  );
	$MyValue=substr("".$MyValue,0,5);
	$URLHidden=$URLHidden."<input type=\"hidden\" name=\"MyValue\" value=\"$MyValue\">";

	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\">Nilai</td> <td class=\"CobaltDataTD\">".$MyValue."</td></tr>";
//	$Table=$Table."</table>";
//	$Table=$Table."<tr><td align=\"left\" class=\"CobaltDataTD\">Detail</td> <td class=\"CobaltDataTD\">$answer</td></tr>";
	$Table=$Table."<tr><td align=\"center\" class=\"\" colspan=\"2\"><form name=\"frmNavigator\"action=\"QuestionSlide.php\" method=\"get\">$URLHidden<input  class=\"CobaltButton\" type=\"submit\" value=\"Simpan\"></form></td></tr>";

	$Table=$Table."</table>";
	
	$lblEnd->SetValue($Table);
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close lblEnd_BeforeShow @28-25BAC5A8
    return $lblEnd_BeforeShow;
}
//End Close lblEnd_BeforeShow

//Page_BeforeShow @1-D8BD2467
function Page_BeforeShow()
{
    $Page_BeforeShow = true;
//End Page_BeforeShow

//Custom Code @31-E4E5460E
// -------------------------
    global $QuestionSlide;
	$MyValue=CCGetRequestParam("MyValue", ccsGet);
	global $lblWarning;
	$lblWarning->SetValue($MyValue);
	if($MyValue !="")
	{
	UpdateTest();
	}
    // Write your own code here.
// -------------------------
//End Custom Code

//Close Page_BeforeShow @1-4BC230CD
    return $Page_BeforeShow;
}
//End Close Page_BeforeShow

function UpdateTest()	
{
	$UserUsername=CCGetSession("UserID");
	$QueType=CCGetRequestParam("QueType", ccsGet);
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$CatID=CCGetRequestParam("CatID", ccsGet);
	$KnowAreaID=CCGetRequestParam("KnowAreaID", ccsGet);
	$MyValue=CCGetRequestParam("MyValue", ccsGet);
	$MyDate=date("Y-m-d H:i:s");
 	$SQL ="";
	$SQLUpdate="";
	$SQLInsert="";
	$Link="";
	$db = new clsDBConnection1();
	if($QueType=="m")
	{
   	$SQL = $SQL."select * from test";
	$SQL = $SQL." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQL = $SQL." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQL = $SQL." and ModID=". $ModID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	
	$SQLUpdate = $SQLUpdate."UPDATE test set ModID=$ModID, KnowAreaID='$KnowAreaID', CatID='$CatID', UserUsername=".$db->ToSQL($UserUsername, ccsText).", TestType='$QueType', TestValue=$MyValue, TestDateTime='$MyDate'";
	$SQLUpdate = $SQLUpdate." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQLUpdate = $SQLUpdate." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQLUpdate = $SQLUpdate." and ModID=". $ModID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	$Link="Question.php?QueType=".$QueType."&ModID=".$ModID;
	} else if($QueType=="c")
	{
   	$SQL = $SQL."select * from test";
	$SQL = $SQL." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQL = $SQL." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQL = $SQL." and ModID=". $ModID." and CatID=". $CatID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	
	$SQLUpdate = $SQLUpdate."UPDATE test set ModID=$ModID, KnowAreaID='$KnowAreaID', CatID='$CatID', UserUsername=".$db->ToSQL($UserUsername, ccsText).", TestType='$QueType', TestValue=$MyValue, TestDateTime='$MyDate'";
	$SQLUpdate = $SQLUpdate." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQLUpdate = $SQLUpdate." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQLUpdate = $SQLUpdate." and ModID=". $ModID." and CatID=". $CatID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	$Link="Question.php?QueType=".$QueType."&ModID=".$ModID."&CatID=". $CatID;
	} else if($QueType=="k")
	{
   	$SQL = $SQL."select * from test";
	$SQL = $SQL." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQL = $SQL." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQL = $SQL." and ModID=". $ModID." and CatID=". $CatID." and KnowAreaID=". $KnowAreaID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	
	$SQLUpdate = $SQLUpdate."UPDATE test set ModID=$ModID, KnowAreaID='$KnowAreaID', CatID='$CatID', UserUsername=".$db->ToSQL($UserUsername, ccsText).", TestType='$QueType', TestValue=$MyValue, TestDateTime='$MyDate'";
	$SQLUpdate = $SQLUpdate." where UserUsername=". $db->ToSQL($UserUsername, ccsText);
	$SQLUpdate = $SQLUpdate." and TestType=". $db->ToSQL($QueType, ccsText);
	$SQLUpdate = $SQLUpdate." and ModID=". $ModID." and CatID=". $CatID. " and KnowAreaID=". $KnowAreaID." and test.UserUsername=".$db->ToSQL($UserUsername, ccsText);
	$Link="Question.php?QueType=".$QueType."&ModID=".$ModID."&CatID=". $CatID. "&KnowAreaID=". $KnowAreaID;
	} 

	$SQLInsert = $SQLInsert."INSERT INTO test ( ModID, KnowAreaID, CatID, UserUsername, TestType, TestValue, TestDateTime) VALUES ( '$ModID', '$KnowAreaID', '$CatID', ".$db->ToSQL($UserUsername, ccsText).", '$QueType', '$MyValue', '$MyDate')";

    $db->query($SQL);
 	$Result = $db->next_record();
	$SQL="";
    if($Result)
    {
 		$db->query($SQLUpdate);
	}
	else
	{
		$db->query($SQLInsert);
	}
				Header("Location:$Link");
    

    $db->close();
}



?>
