<?php
//Include Common Files @1-8E58AE89
define("RelativePath", "..");
include(RelativePath . "/Common.php");
include(RelativePath . "/Template.php");
include(RelativePath . "/Sorter.php");
include(RelativePath . "/Navigator.php");
  
//End Include Common Files

class clsGridknowledgearea { //knowledgearea class @2-7CC6EB49

//Variables @2-DB5EE657

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
    var $Sorter_KnowAreaTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @2-D5F8946D
    function clsGridknowledgearea()
    {
        global $FileName;
        $this->ComponentName = "knowledgearea";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid knowledgearea";
        $this->ds = new clsknowledgeareaDataSource();
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
        $this->SorterName = CCGetParam("knowledgeareaOrder", "");
        $this->SorterDirection = CCGetParam("knowledgeareaDir", "");

        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->KnowAreaTitle = new clsControl(ccsLabel, "KnowAreaTitle", "KnowAreaTitle", ccsText, "", CCGetRequestParam("KnowAreaTitle", ccsGet));
        $this->Sorter_KnowAreaTitle = new clsSorter($this->ComponentName, "Sorter_KnowAreaTitle", $FileName);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @2-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-7C9A96C1
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlCatID"] = CCGetFromGet("CatID", "");

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
                $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("ccsForm"));
                $this->ImageLink1->Parameters = CCAddParam($this->ImageLink1->Parameters, "KnowAreaID", $this->ds->f("KnowAreaID"));
                $this->ImageLink1->Page = "SelectPageKnowItem.php";
                $this->KnowAreaTitle->SetValue($this->ds->KnowAreaTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink1->Show();
                $this->KnowAreaTitle->Show();
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
        $this->Sorter_KnowAreaTitle->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @2-DE005245
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink1->Errors->ToString();
        $errors .= $this->KnowAreaTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End knowledgearea Class @2-FCB6E20C

class clsknowledgeareaDataSource extends clsDBConnection1 {  //knowledgeareaDataSource Class @2-F981DACE

//DataSource Variables @2-DCAC6D71
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $KnowAreaTitle;
//End DataSource Variables

//Class_Initialize Event @2-66C12765
    function clsknowledgeareaDataSource()
    {
        $this->ErrorBlock = "Grid knowledgearea";
        $this->Initialize();
        $this->KnowAreaTitle = new clsField("KnowAreaTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @2-A67D38F3
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_KnowAreaTitle" => array("KnowAreaTitle", "")));
    }
//End SetOrder Method

//Prepare Method @2-C56F6D54
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCatID", ccsInteger, "", "", $this->Parameters["urlCatID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "CatID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-7F842570
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM knowledgearea";
        $this->SQL = "SELECT *  " .
        "FROM knowledgearea";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-B31DC84C
    function SetValues()
    {
        $this->KnowAreaTitle->SetDBValue($this->f("KnowAreaTitle"));
    }
//End SetValues Method

} //End knowledgeareaDataSource Class @2-FCB6E20C

//Initialize Page @1-F21CD1C1
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

$FileName = "SelectPageKnowArea.php";
$Redirect = "";
$TemplateFileName = "SelectPageKnowArea.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-2955EC68
$DBConnection1 = new clsDBConnection1();

// Controls
$lblTopLink = new clsControl(ccsLabel, "lblTopLink", "lblTopLink", ccsText, "", CCGetRequestParam("lblTopLink", ccsGet));
$lblTopLink->HTML = true;
$knowledgearea = new clsGridknowledgearea();
$knowledgearea->Initialize();

// Events
include("./SelectPageKnowArea_events.php");
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

//Show Page @1-0CE945E2
$knowledgearea->Show();
$lblTopLink->Show();
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
