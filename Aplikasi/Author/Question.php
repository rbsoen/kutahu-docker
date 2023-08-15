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

class clsGridquestion1 { //question1 class @22-F58350A4

//Variables @22-0B3A0FB0

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

//Class_Initialize Event @22-A311DE23
    function clsGridquestion1()
    {
        global $FileName;
        $this->ComponentName = "question1";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid question1";
        $this->ds = new clsquestion1DataSource();
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
    }
//End Class_Initialize Event

//Initialize Method @22-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @22-00A635B1
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowAreaID"] = CCGetFromGet("KnowAreaID", "");

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
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ModTitle->Show();
                $this->CatTitle->Show();
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
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @22-BE359065
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ModTitle->Errors->ToString();
        $errors .= $this->CatTitle->Errors->ToString();
        $errors .= $this->KnowAreaTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End question1 Class @22-FCB6E20C

class clsquestion1DataSource extends clsDBConnection1 {  //question1DataSource Class @22-44ED50A4

//DataSource Variables @22-99018C3E
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
//End DataSource Variables

//Class_Initialize Event @22-4AAB555D
    function clsquestion1DataSource()
    {
        $this->ErrorBlock = "Grid question1";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->CatTitle = new clsField("CatTitle", ccsText, "");
        $this->KnowAreaTitle = new clsField("KnowAreaTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @22-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @22-E37CD042
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowAreaID", ccsInteger, "", "", $this->Parameters["urlKnowAreaID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "knowledgearea.KnowAreaID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @22-00E00D3A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM (category INNER JOIN knowledgearea ON " .
        "knowledgearea.CatID = category.CatID) INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID";
        $this->SQL = "SELECT KnowAreaTitle, CatTitle, ModTitle  " .
        "FROM (category INNER JOIN knowledgearea ON " .
        "knowledgearea.CatID = category.CatID) INNER JOIN `module` ON " .
        "category.ModID = `module`.ModID";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @22-2F178BEE
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
        $this->KnowAreaTitle->SetDBValue($this->f("KnowAreaTitle"));
    }
//End SetValues Method

} //End question1DataSource Class @22-FCB6E20C

class clsGridquestion { //question class @7-15F72B84

//Variables @7-AABC9F5F

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
    var $Sorter_QueTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-78BE7351
    function clsGridquestion()
    {
        global $FileName;
        $this->ComponentName = "question";
        $this->Visible = True;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid question";
        $this->ds = new clsquestionDataSource();
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
        $this->SorterName = CCGetParam("questionOrder", "");
        $this->SorterDirection = CCGetParam("questionDir", "");

        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->QueTitle = new clsControl(ccsLabel, "QueTitle", "QueTitle", ccsText, "", CCGetRequestParam("QueTitle", ccsGet));
        $this->QueModule = new clsControl(ccsCheckBox, "QueModule", "QueModule", ccsInteger, "", CCGetRequestParam("QueModule", ccsGet));
        $this->QueModule->CheckedValue = $this->QueModule->GetParsedValue(true);
        $this->QueModule->UncheckedValue = $this->QueModule->GetParsedValue(false);
        $this->QueCategory = new clsControl(ccsCheckBox, "QueCategory", "QueCategory", ccsInteger, "", CCGetRequestParam("QueCategory", ccsGet));
        $this->QueCategory->CheckedValue = $this->QueCategory->GetParsedValue(true);
        $this->QueCategory->UncheckedValue = $this->QueCategory->GetParsedValue(false);
        $this->Sorter_QueTitle = new clsSorter($this->ComponentName, "Sorter_QueTitle", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("QueID", "del_que", "ccsForm"));
        $this->Link1->Page = "QuestionMaint.php";
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple);
    }
//End Class_Initialize Event

//Initialize Method @7-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @7-61981576
    function Show()
    {
        global $Tpl;
        if(!$this->Visible) return;

        $ShownRecords = 0;

        $this->ds->Parameters["urlKnowAreaID"] = CCGetFromGet("KnowAreaID", "");

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
                $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("del_que", "ccsForm"));
                $this->ImageLink1->Parameters = CCAddParam($this->ImageLink1->Parameters, "QueID", $this->ds->f("QueID"));
                $this->ImageLink1->Page = "QuestionMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->QueTitle->SetValue($this->ds->QueTitle->GetValue());
                $this->QueModule->SetValue($this->ds->QueModule->GetValue());
                $this->QueCategory->SetValue($this->ds->QueCategory->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink1->Show();
                $this->lblDelete->Show();
                $this->QueTitle->Show();
                $this->QueModule->Show();
                $this->QueCategory->Show();
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
        $this->Sorter_QueTitle->Show();
        $this->Link1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-DC9FAD58
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink1->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->QueTitle->Errors->ToString();
        $errors .= $this->QueModule->Errors->ToString();
        $errors .= $this->QueCategory->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End question Class @7-FCB6E20C

class clsquestionDataSource extends clsDBConnection1 {  //questionDataSource Class @7-C9FD7A76

//DataSource Variables @7-97599E39
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $QueTitle;
    var $QueModule;
    var $QueCategory;
//End DataSource Variables

//Class_Initialize Event @7-42CFABC1
    function clsquestionDataSource()
    {
        $this->ErrorBlock = "Grid question";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->QueTitle = new clsField("QueTitle", ccsText, "");
        $this->QueModule = new clsField("QueModule", ccsInteger, "");
        $this->QueCategory = new clsField("QueCategory", ccsInteger, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-7D3FEBB4
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "QueID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_QueTitle" => array("QueTitle", "")));
    }
//End SetOrder Method

//Prepare Method @7-DEF678A3
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowAreaID", ccsInteger, "", "", $this->Parameters["urlKnowAreaID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "KnowAreaID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @7-FE15183A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM question";
        $this->SQL = "SELECT *  " .
        "FROM question";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-FC5B0711
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("QueID"));
        $this->QueTitle->SetDBValue($this->f("QueTitle"));
        $this->QueModule->SetDBValue(trim($this->f("QueModule")));
        $this->QueCategory->SetDBValue(trim($this->f("QueCategory")));
    }
//End SetValues Method

} //End questionDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-B309130C
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

$FileName = "Question.php";
$Redirect = "";
$TemplateFileName = "Question.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-1B23E081
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
$question1 = new clsGridquestion1();
$question = new clsGridquestion();
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$question1->Initialize();
$question->Initialize();

// Events
include("./Question_events.php");
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

//Show Page @1-65C4B0FB
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$question1->Show();
$question->Show();
$Footer->Show("Footer");
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
