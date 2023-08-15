<?php
// //Events @1-F81417CB
function MyGetSub($MySqlSub,$Title,$ID,$MyLink,$UrlVar,$Parent,$no)
{
			$db = new clsDBConnection1();
    		$db->query($MySqlSub);
			$i=0;
			$MyJS="";
   		while($db->next_record())
	   		{
        	$MyTitle=$db->f("$Title"); 
			$MyID= $db->f("$ID"); 
			$MyTitleShort=$MyTitle;

			$name="aux".$no;
			if($Title=="KnowItemTitle")
			{
			$MyJS=$MyJS."insDoc(".$Parent.", gLnk(\"S\",\"". htmlspecialchars($MyTitleShort) ."\", \"".$MyLink. "?"."$UrlVar"."$ID=$MyID\"));";
 			}
			else
			{
			$MyJS=$MyJS.$name." = insFld(".$Parent.", gFld(\"". htmlspecialchars($MyTitleShort) ."\", \"".$MyLink. "?"."$UrlVar"."$ID=$MyID\"));";

			}
			$MyJSSub="";
				if($Title=="CatTitle")
				{			
				$MyJSSub=$MyJSSub.MyGetSub("SELECT * FROM knowledgearea WHERE CatID=" .$db->f("CatID")." order by KnowAreaID","KnowAreaTitle","KnowAreaID","KnowledgeArea.php",$ID."=".$MyID."&".$UrlVar,$name,($no+1));
				
					
				}
				
	
				if($Title=="KnowAreaTitle")
				{			
					$MyJSSub=$MyJSSub.MyGetSub("SELECT * FROM knowledgeitem WHERE KnowAreaID=" .$db->f("KnowAreaID")." order by KnowItemID","KnowItemTitle","KnowItemID","KnowledgeItem.php",$ID."=".$MyID."&".$UrlVar,$name,($no+1));

				}
								$MyJS=$MyJS.$MyJSSub;
		
		//	if($i>0){$Separator=","; }else{$Separator="";}
			//$MyJS=$MyJS.$MyJSSub;

						$i++;      
    		}		
    		$db->close();
			unset($db);
			return $MyJS;
}
//MyTree_lblTree_BeforeShow @2-0F92112F
function MyTree_lblTree_BeforeShow()
{
    $MyTree_lblTree_BeforeShow = true;
//End MyTree_lblTree_BeforeShow

//Custom Code @3-B66102F3
// -------------------------
	$ModID=CCGetRequestParam("ModID", ccsGet);
	$ModTitle="";
	if($ModID!="")
	{

			$db = new clsDBConnection1();
   	 		$SQL = "SELECT * FROM module WHERE ModID=" . $db->ToSQL($ModID, ccsInteger);
    		$db->query($SQL);
    		$Result = $db->next_record();
			if($Result)
    		{
        	$ModTitle=$db->f("ModTitle");        
    		}
    		$db->close();
			unset($db);
		
	}

	//---begin
		$ModTitleShort=$ModTitle;
		if(strlen($ModTitleShort)>30)
			{
				$ModTitleShort=substr($ModTitleShort,0,30)."...";
			}
	//---end

    global $MyTree;
	$DataTree="";
	$DataTree=$DataTree."<script language=\"javascript\">";
	$DataTree=$DataTree."foldersTree = gFld(\"". htmlspecialchars($ModTitle)."\", \""."Module.php". "?ModID=$ModID"."\");";
	$DataTree=$DataTree."foldersTree.iconSrc = ICONPATH + \"base.gif\";";
	$DataTree=$DataTree."foldersTree.iconSrcClosed = ICONPATH + \"base.gif\";";

	if($ModID!="")
	{
				$DataTree=$DataTree.MyGetSub("SELECT * FROM category WHERE ModID=" .$ModID." order by CatID","CatTitle","CatID","Category.php","ModID=$ModID&","foldersTree",1);

	}

	$DataTree=$DataTree."</script>";
	$MyTree->lblTree->SetValue($DataTree);

    // Write your own code here.
// -------------------------
//End Custom Code

//Close MyTree_lblTree_BeforeShow @2-8E2A8132
    return $MyTree_lblTree_BeforeShow;
}
//End Close MyTree_lblTree_BeforeShow


?>
