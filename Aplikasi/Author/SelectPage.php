<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridsubknowledgeitem { //subknowledgeitem class @5-BB616816

//Variables @5-CF39C612

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

//Class_Initialize Event @5-E2C252B6
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

        $this->lblAction = new clsControl(ccsLabel, "lblAction", "lblAction", ccsText, "", CCGetRequestParam("lblAction", ccsGet));
        $this->lblAction->HTML = true;
        $this->SubKnowlItemTitle = new clsControl(ccsLabel, "SubKnowlItemTitle", "SubKnowlItemTitle", ccsText, "", CCGetRequestParam("SubKnowlItemTitle", ccsGet));
        $this->SubKnowItemID = new clsControl(ccsHidden, "SubKnowItemID", "SubKnowItemID", ccsInteger, "", CCGetRequestParam("SubKnowItemID", ccsGet));
        $this->Sorter_SubKnowlItemTitle = new clsSorter($this->ComponentName, "Sorter_SubKnowlItemTitle", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @5-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @5-DA6ACAA1
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
                $this->SubKnowlItemTitle->SetValue($this->ds->SubKnowlItemTitle->GetValue());
                $this->SubKnowItemID->SetValue($this->ds->SubKnowItemID->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->lblAction->Show();
                $this->SubKnowlItemTitle->Show();
                $this->SubKnowItemID->Show();
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
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @5-63AED02B
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->lblAction->Errors->ToString();
        $errors .= $this->SubKnowlItemTitle->Errors->ToString();
        $errors .= $this->SubKnowItemID->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End subknowledgeitem Class @5-FCB6E20C

class clssubknowledgeitemDataSource extends clsDBConnection1 {  //subknowledgeitemDataSource Class @5-AE58F599

//DataSource Variables @5-A050FF0B
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $SubKnowlItemTitle;
    var $SubKnowItemID;
//End DataSource Variables

//Class_Initialize Event @5-57B9ACB1
    function clssubknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid subknowledgeitem";
        $this->Initialize();
        $this->SubKnowlItemTitle = new clsField("SubKnowlItemTitle", ccsText, "");
        $this->SubKnowItemID = new clsField("SubKnowItemID", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @5-A0B6ECB0
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_SubKnowlItemTitle" => array("SubKnowlItemTitle", "")));
    }
//End SetOrder Method

//Prepare Method @5-3A535BC5
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowItemID", ccsInteger, "", "", $this->Parameters["urlKnowItemID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "KnowItemID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @5-0B1FD354
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

//SetValues Method @5-F3145287
    function SetValues()
    {
        $this->SubKnowlItemTitle->SetDBValue($this->f("SubKnowlItemTitle"));
        $this->SubKnowItemID->SetDBValue(trim($this->f("SubKnowItemID")));
    }
//End SetValues Method

} //End subknowledgeitemDataSource Class @5-FCB6E20C

//Initialize Page @1-CA02B7DF
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

$FileName = "SelectPage.php";
$Redirect = "";
$TemplateFileName = "SelectPage.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-D21C2762
$DBConnection1 = new clsDBConnection1();

// Controls
$subknowledgeitem = new clsGridsubknowledgeitem();
$subknowledgeitem->Initialize();

// Events
include("./SelectPage_events.php");
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

//Go to destination page @1-6F9FD7CC
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload");
    $DBConnection1->close();
    header("Location: " . $Redirect);
    exit;
}
//End Go to destination page

//Show Page @1-B0894C06
$subknowledgeitem->Show();
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
