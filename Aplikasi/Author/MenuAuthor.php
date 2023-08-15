<?php

class clsMenuAuthor { //MenuAuthor class @1-4308CF1F

//Variables @1-1987AB94
    var $FileName = "";
    var $Redirect = "";
    var $Tpl = "";
    var $TemplateFileName = "";
    var $BlockToParse = "";
    var $ComponentName = "";

    // Events;
    var $CCSEvents = "";
    var $CCSEventResult = "";
    var $TemplatePath;
    var $Visible;
//End Variables

//Class_Initialize Event @1-A788D76B
    function clsMenuAuthor()
    {
        $this->Visible = true;
        if($this->Visible)
        {
            $this->FileName = "MenuAuthor.php";
            $this->Redirect = "";
            $this->TemplateFileName = "MenuAuthor.html";
            $this->BlockToParse = "main";

            // Create Components
            $this->lblMenu = new clsControl(ccsLabel, "lblMenu", "lblMenu", ccsText, "", CCGetRequestParam("lblMenu", ccsGet));
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-29B71912
    function BindEvents()
    {
        $this->CCSEvents["BeforeShow"] = "MenuAuthor_BeforeShow";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInitialize");
    }
//End BindEvents Method

//Operations Method @1-7E2A14CF
    function Operations()
    {
        global $Redirect;
        if(!$this->Visible)
            return "";
    }
//End Operations Method

//Initialize Method @1-EDD74DD5
    function Initialize()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnInitializeView");
        if(!$this->Visible)
            return "";
    }
//End Initialize Method

//Show Method @1-1E86AF59
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name);
        $Tpl->block_path = $Name;
        $this->lblMenu->Show();
        $Tpl->Parse();
        $Tpl->SetVar($Name, $Tpl->GetVar());
        $Tpl->block_path = $block_path;
    }
//End Show Method

} //End MenuAuthor Class @1-FCB6E20C

//Include Event File @1-A5E72B5C
include(RelativePath . "/Author/MenuAuthor_events.php");
//End Include Event File
?>
