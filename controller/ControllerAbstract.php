<?php

namespace controller;

abstract class ControllerAbstract
{
    public function renderTemplate($twig, $templateName, $data)
    {
        $template = $twig->load($templateName);
        echo $template->render($data);
    }
}