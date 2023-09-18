<?php 
if (! function_exists('getTemplateUrl')) {
    function getTemplateUrl($templateName)
    {
        return asset("themes/$templateName/");
    }
}
if (! function_exists('getTemplateUri')) {
    function getTemplateUri($templateName)
    {
        return public_path("themes/$templateName/");
    }
}
?>