<?php
class clsHeader { //Header class @1-CC982CB1

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

//Class_Initialize Event @1-B71840CE
    function clsHeader()
    {
        $this->Visible = true;
        if($this->Visible)
        {
            $this->FileName = "Header.php";
            $this->Redirect = "";
            $this->TemplateFileName = "Header.html";
            $this->BlockToParse = "main";

            // Create Components
            $this->lblDate = new clsControl(ccsLabel, "lblDate", "lblDate", ccsText, "", CCGetRequestParam("lblDate", ccsGet));
            $this->lblDate->HTML = true;
        }
    }
//End Class_Initialize Event

//Class_Terminate Event @1-A3749DF6
    function Class_Terminate()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUnload");
    }
//End Class_Terminate Event

//BindEvents Method @1-FD8CABE2
    function BindEvents()
    {
        $this->CCSEvents["BeforeShow"] = "Header_BeforeShow";
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

//Show Method @1-4FC07F74
    function Show($Name)
    {
        global $Tpl;
        $block_path = $Tpl->block_path;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow");
        if(!$this->Visible)
            return "";
        $Tpl->LoadTemplate($this->TemplatePath . $this->TemplateFileName, $Name);
        $Tpl->block_path = $Name;
        $this->lblDate->Show();
        $Tpl->Parse();
        $Tpl->SetVar($Name, $Tpl->GetVar());
        $Tpl->block_path = $block_path;
    }
//End Show Method

} //End Header Class @1-FCB6E20C

//Include Event File @1-5627F15D
include(RelativePath . "/Author/Header_events.php");
//End Include Event File


?>
