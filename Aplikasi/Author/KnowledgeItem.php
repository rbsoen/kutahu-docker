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

class clsGridknowledgearea { //knowledgearea class @26-7CC6EB49

//Variables @26-0B3A0FB0

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

//Class_Initialize Event @26-7AC6473F
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

//Initialize Method @26-03626367
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->ds->PageSize = $this->PageSize;
        $this->ds->AbsolutePage = $this->PageNumber;
        $this->ds->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @26-00A635B1
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

//GetErrors Method @26-BE359065
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

} //End knowledgearea Class @26-FCB6E20C

class clsknowledgeareaDataSource extends clsDBConnection1 {  //knowledgeareaDataSource Class @26-F981DACE

//DataSource Variables @26-99018C3E
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

//Class_Initialize Event @26-45EBB324
    function clsknowledgeareaDataSource()
    {
        $this->ErrorBlock = "Grid knowledgearea";
        $this->Initialize();
        $this->ModTitle = new clsField("ModTitle", ccsText, "");
        $this->CatTitle = new clsField("CatTitle", ccsText, "");
        $this->KnowAreaTitle = new clsField("KnowAreaTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @26-9E1383D1
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @26-E37CD042
    function Prepare()
    {
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlKnowAreaID", ccsInteger, "", "", $this->Parameters["urlKnowAreaID"], 0, false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "knowledgearea.KnowAreaID", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @26-00E00D3A
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

//SetValues Method @26-2F178BEE
    function SetValues()
    {
        $this->ModTitle->SetDBValue($this->f("ModTitle"));
        $this->CatTitle->SetDBValue($this->f("CatTitle"));
        $this->KnowAreaTitle->SetDBValue($this->f("KnowAreaTitle"));
    }
//End SetValues Method

} //End knowledgeareaDataSource Class @26-FCB6E20C

class clsGridknowledgeitem { //knowledgeitem class @7-C568B058

//Variables @7-D392A55E

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
    var $Sorter_KnowItemTitle;
    var $Navigator;
//End Variables

//Class_Initialize Event @7-E275E941
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
            $this->PageSize = 25;
        else if ($this->PageSize > 100)
            $this->PageSize = 100;
        else
            $this->PageSize = intval($this->PageSize);
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        $this->SorterName = CCGetParam("knowledgeitemOrder", "");
        $this->SorterDirection = CCGetParam("knowledgeitemDir", "");

        $this->ImageLink1 = new clsControl(ccsImageLink, "ImageLink1", "ImageLink1", ccsText, "", CCGetRequestParam("ImageLink1", ccsGet));
        $this->lblDelete = new clsControl(ccsLabel, "lblDelete", "lblDelete", ccsText, "", CCGetRequestParam("lblDelete", ccsGet));
        $this->lblDelete->HTML = true;
        $this->ImageLink2 = new clsControl(ccsImageLink, "ImageLink2", "ImageLink2", ccsText, "", CCGetRequestParam("ImageLink2", ccsGet));
        $this->KnowItemTitle = new clsControl(ccsLabel, "KnowItemTitle", "KnowItemTitle", ccsText, "", CCGetRequestParam("KnowItemTitle", ccsGet));
        $this->Sorter_KnowItemTitle = new clsSorter($this->ComponentName, "Sorter_KnowItemTitle", $FileName);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet));
        $this->Link1->Parameters = CCGetQueryString("QueryString", Array("KnowItemID", "del_knowitem", "ccsForm"));
        $this->Link1->Page = "KnowledgeItemMaint.php";
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

//Show Method @7-163224E8
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
                $this->ImageLink1->Parameters = CCGetQueryString("QueryString", Array("del_knowitem", "ccsForm"));
                $this->ImageLink1->Parameters = CCAddParam($this->ImageLink1->Parameters, "KnowItemID", $this->ds->f("KnowItemID"));
                $this->ImageLink1->Page = "KnowledgeItemMaint.php";
                $this->lblDelete->SetValue($this->ds->lblDelete->GetValue());
                $this->ImageLink2->Parameters = CCGetQueryString("QueryString", Array("del_knowitem", "ccsForm"));
                $this->ImageLink2->Parameters = CCAddParam($this->ImageLink2->Parameters, "KnowItemID", $this->ds->f("KnowItemID"));
                $this->ImageLink2->Page = "SubKnowledgeItem.php";
                $this->KnowItemTitle->SetValue($this->ds->KnowItemTitle->GetValue());
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow");
                $this->ImageLink1->Show();
                $this->lblDelete->Show();
                $this->ImageLink2->Show();
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
        $this->Navigator->PageNumber = $this->ds->AbsolutePage;
        $this->Navigator->TotalPages = $this->ds->PageCount();
        $this->Sorter_KnowItemTitle->Show();
        $this->Link1->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->ds->close();
    }
//End Show Method

//GetErrors Method @7-E4A37B69
    function GetErrors()
    {
        $errors = "";
        $errors .= $this->ImageLink1->Errors->ToString();
        $errors .= $this->lblDelete->Errors->ToString();
        $errors .= $this->ImageLink2->Errors->ToString();
        $errors .= $this->KnowItemTitle->Errors->ToString();
        $errors .= $this->Errors->ToString();
        $errors .= $this->ds->Errors->ToString();
        return $errors;
    }
//End GetErrors Method

} //End knowledgeitem Class @7-FCB6E20C

class clsknowledgeitemDataSource extends clsDBConnection1 {  //knowledgeitemDataSource Class @7-B8FDAC3F

//DataSource Variables @7-F84D73DD
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $BlockExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $lblDelete;
    var $KnowItemTitle;
//End DataSource Variables

//Class_Initialize Event @7-0C71E6C2
    function clsknowledgeitemDataSource()
    {
        $this->ErrorBlock = "Grid knowledgeitem";
        $this->Initialize();
        $this->lblDelete = new clsField("lblDelete", ccsText, "");
        $this->KnowItemTitle = new clsField("KnowItemTitle", ccsText, "");

    }
//End Class_Initialize Event

//SetOrder Method @7-E7484AA2
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "KnowItemID";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_KnowItemTitle" => array("KnowItemTitle", "")));
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

//Open Method @7-252EFB82
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect");
        $this->CountSQL = "SELECT COUNT(*)  " .
        "FROM knowledgeitem";
        $this->SQL = "SELECT *  " .
        "FROM knowledgeitem";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect");
        $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect");
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @7-A7BF23F7
    function SetValues()
    {
        $this->lblDelete->SetDBValue($this->f("KnowItemID"));
        $this->KnowItemTitle->SetDBValue($this->f("KnowItemTitle"));
    }
//End SetValues Method

} //End knowledgeitemDataSource Class @7-FCB6E20C

//Include Page implementation @3-5CD56755
include_once("./Footer.php");
//End Include Page implementation

//Initialize Page @1-5745C5B6
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

$FileName = "KnowledgeItem.php";
$Redirect = "";
$TemplateFileName = "KnowledgeItem.html";
$BlockToParse = "main";
$PathToRoot = "../";
//End Initialize Page

//Initialize Objects @1-78D7E520
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
$knowledgearea = new clsGridknowledgearea();
$knowledgeitem = new clsGridknowledgeitem();
$lblError = new clsControl(ccsLabel, "lblError", "lblError", ccsText, "", CCGetRequestParam("lblError", ccsGet));
$lblError->HTML = true;
$Footer = new clsFooter();
$Footer->BindEvents();
$Footer->TemplatePath = "./";
$Footer->Initialize();
$knowledgearea->Initialize();
$knowledgeitem->Initialize();

// Events
include("./KnowledgeItem_events.php");
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

//Show Page @1-5CBBA7FE
$Header->Show("Header");
$MenuAuthor->Show("MenuAuthor");
$knowledgearea->Show();
$knowledgeitem->Show();
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
