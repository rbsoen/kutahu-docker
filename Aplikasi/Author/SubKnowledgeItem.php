<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

//Include Page implementation @2-39DC296A
include_once("./Header.php");
//End Include Page implementation

//Include Page implementation @4-D20A616D
include_once("./MenuAuthor.php");
//End Include Page implementation

class clsGridknowledgeitem { //knowledgeitem class @19-C568B058

//Variables @19-0B3A0FB0

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
//End Variables

//Class_Initialize Event @19-6FC0EAA0
    function clsGridknowledgeitem()
    {
        global $FileName;
        $this->ComponentName = "knowledgeitem";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid knowledgeitem";
        $this->ds = new clsknowledgeitemDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));

        $this->ModTitle = new clsControl(ccsLabel, "ModTitle", "ModTitle", ccsText, "", CCGetRequestParam("ModTitle", ccsGet));
        $this->CatTitle = new clsControl(ccsLabel, "CatTitle", "CatTitle", ccsText, "", CCGetRequestParam("CatTitle", ccsGet));
        $this->KnowAreaTitle = new clsControl(ccsLabel, "KnowAreaTitle", "KnowAreaTitle", ccsText, "", CCGetRequestParam("KnowAreaTitle", ccsGet));
        $this->KnowItemTitle = new clsControl(ccsLabel, "KnowItemTitle", "KnowItemTitle", ccsText, "", CCGetRequestParam("KnowItemTitle", ccsGet));
    }
//End Class_Initialize Event

//Initialize Method @19-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @19-2E214B2B
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowItemID"] = CCGetFromGet("KnowItemID", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ModTitle->SetValue($this->ds->ModTitle->GetValue());
                $this->CatTitle->SetValue($this->ds->CatTitle->GetValue());
                $this->KnowAreaTitle->SetValue($this->ds->KnowAreaTitle->GetValue());
                $this->KnowItemTitle->SetValue($this->ds->KnowItemTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ModTitle->Show();
                $this->CatTitle->Show();
                $this->KnowAreaTitle->Show();
                $this->KnowItemTitle->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @19-20F8D93E
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ModTitle->Errors->ToString();
        $errors .= $this->CatTitle->Errors->ToString();
        $errors .= $this->KnowAreaTitle->Errors->ToString();
        $errors .= $this->KnowItemTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End knowledgeitem Class @19-FCB6E20C

class clsknowledgeitemDataSource extends clsDBConnection1 {  //knowledgeitemDataSource Class @19-B8FDAC3F

//DataSource Variables @19-5C7DB57E
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ModTitle;
    var $CatTitle;
    var $KnowAreaTitle;
    var $KnowItemTitle;
//End DataSource Variables

//Class_Initialize Event @19-439778C4
    function clsknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid knowledgeitem";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->CatTitle = new clsField("CatTitle", ccsText, "");
        $this->KnowAreaTitle = new clsField("KnowAreaTitle", ccsText, "");
        $this->KnowItemTitle = new clsField("KnowItemTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @19-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @19-3170C2B5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "knowledgeitem.KnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @19-8ED4F1DF
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM ((knowledgearea INNER JOIN knowledgeitem ON " .
        "knowledgeitem.KnowAreaID = knowledgearea.KnowAreaID) INNER JOIN category ON " .
        "knowledgearea.CatID = category.CatID) INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID";
        $this->SQL = "SELECT KnowItemTitle, KnowAreaTitle, CatTitle, ModTitle  " .
        "FROM ((knowledgearea INNER JOIN knowledgeitem ON " .
        "knowledgeitem.KnowAreaID = knowledgearea.KnowAreaID) INNER JOIN category ON " .
        "knowledgearea.CatID = category.CatID) INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @19-C67275C6
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
        $this->KnowAreaTitle->SetDBValue($this->f("KnowAreaTitle"));
        $this->KnowItemTitle->SetDBValue($this->f("KnowItemTitle"));
    }
//End SetValues Method

} //End knowledgeitemDataSource Class @19-FCB6E20C

class clsGridsubknowledgeitem { //subknowledgeitem class @8-BB616816

//Variables @8-CF39C612

    // Public variables
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds; var $PageSize;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;

    var $CCSEvents = "";
    var $CCSEventResult;

    // Grid Controls
    var $StaticControls; var $RowControls;
    var $Sorter_SubKnowlItemTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @8-D8FCBA76
    function clsGridsubknowledgeitem()
    {
        global $FileName;
        $this->ComponentName = "subknowledgeitem";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->ds = new clssubknowledgeitemDataSource();
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("subknowledgeitemOrder", "");
        $this->SorterDirection = CCGetParam("subknowledgeitemDir", "");

        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->ImageLink2 = new clsControl(ccsImageLink, "ImageLink2", "ImageLink2", ccsText, "", CCGetRequestParam("ImageLink2", ccsGet));
        $this->SubKnowItemTitle = new clsControl(ccsLabel, "SubKnowItemTitle", "SubKnowItemTitle", ccsText, "", CCGetRequestParam("SubKnowItemTitle", ccsGet));
        $this->Sorter_SubKnowlItemTitle = new clsSorter($this->ComponentName, "Sorter_SubKnowlItemTitle", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("SubKnowItemID", "del_subknowitem", "ccsForm"));
        $this->Link1->Page = "SubKnowledgeItemMaint.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @8-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @8-E2ED4EBA
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowItemID"] = CCGetFromGet("KnowItemID", "");

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect");


        $this->ds->Prepare();
        $this->ds->Open();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        $is_next_record = $this->ds->next_record();
        if($is_next_record && $ShownRecords < $this->PageSize)
        {
            do {
                    $this->ds->SetValues();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("del_subknowitem", "ccsForm"));
                $this->ImageLink1->Parameters = CCAddParam($this->ImageLink1->Parameters, "SubKnowItemID", $this->ds->f("SubKnowItemID"));
                $this->ImageLink1->Page = "SubKnowledgeItemMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->ImageLink2->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->ImageLink2->Parameters = CCAddParam($this->ImageLink2->Parameters, "SubKnowItemID", $this->ds->f("SubKnowItemID"));
                $this->ImageLink2->Page = "Glossary.php";
                $this->SubKnowItemTitle->SetValue($this->ds->SubKnowItemTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink1->Show();
                $this->lblDelete->Show();
                $this->ImageLink2->Show();
                $this->SubKnowItemTitle->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
                $ShownRecords++;
                $is_next_record = $this->ds->next_record();
            } while ($is_next_record && $ShownRecords < $this->PageSize);
        }
        else // Show NoRecords block if no records are found
        {
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_SubKnowlItemTitle->Show();
        $this->Link1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @8-42F9A217
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink1->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->ImageLink2->Errors->ToString();
        $errors .= $this->SubKnowItemTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End subknowledgeitem Class @8-FCB6E20C

class clssubknowledgeitemDataSource extends clsDBConnection1 {  //subknowledgeitemDataSource Class @8-AE58F599

//DataSource Variables @8-64AEFCE2
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $SubKnowItemTitle;
//End DataSource Variables

//Class_Initialize Event @8-E0F794D7
    function clssubknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->SubKnowItemTitle = new clsField("SubKnowItemTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @8-0EAE5404
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "SubKnowItemID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_SubKnowlItemTitle" => array("SubKnowlItemTitle", "")));
    }
//End SetOrder Method

//Prepare Method @8-3A535BC5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "KnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @8-0B1FD354
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM subknowledgeitem";
        $this->SQL = "SELECT *  " .
        "FROM subknowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @8-D8A2EEA8
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("SubKnowItemID"));
        $this->SubKnowItemTitle->SetDBValue($this->f("SubKnowlItemTitle"));
    }
//End SetValues Method

} //End subknowledgeitemDataSource Class @8-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-CD167748
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = "SubKnowledgeItem.php";
$Redirect = "";
$TemplateFileName = "SubKnowledgeItem.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-0D8989E4
$DBConnection1 = new clsDBConnection1();

// Controls
$Header = new clsHeader();
$Header->BindEvents();
$Header->TemplatePath = "./";
$Header->Initialize();
$MenuAuthor = new clsMenuAuthor();
$MenuAuthor->BindEvents();
$MenuAuthor->TemplatePath = "./";
$MenuAuthor->Initialize();
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$knowledgeitem = new clsGridknowledgeitem();
$subknowledgeitem = new clsGridsubknowledgeitem();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$knowledgeitem->Initialize();
$subknowledgeitem->Initialize();

// Events
include("./SubKnowledgeItem_events.php");
BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize");

if($Charset) {
    header("Content-Type: text/html; charset=" . $Charset);
}
//End Initialize Objects

//Initialize HTML Template @1-A0111C9D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView");
$Tpl = new clsTemplate();
$Tpl->LoadTemplate(TemplatePath . $TemplateFileName, "main");
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow");
//End Initialize HTML Template

//Execute Components @1-1A7139A9
$Header->Operations();
$MenuAuthor->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-BAECD012
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$knowledgeitem->Show();
$subknowledgeitem->Show();
$Footer->Show("Footer");
$lblTopLink->Show();
$lblError->Show();
$Tpl->Parse("main", false);
$main_block = $Tpl->GetVar("main");
$generated_with = "<center><font face=\"Arial\"><small></small></font></center>";
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", $generated_with . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", $generated_with . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= $generated_with;
}
echo $main_block;
//End Show Page

//Unload Page @1-A4D34ABE
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
$DBConnection1->close();
unset($Tpl);
//End Unload Page


?>
