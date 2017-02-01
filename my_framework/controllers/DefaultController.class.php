<?php
namespace MyFramework;

class DefaultController extends DefaultModel
{
    public function defaultAction()
    {
        $this->isLogged();
        $this->render(['prenom' => $this->getLogin()]);
    }

    public function connexionAction()
    {
        $this->isLogged();
        $data = [];
        $data['url'] = $_SERVER['REQUEST_URI'];
        $data['login'] = (!empty($_POST)) ?
        htmlspecialchars($_POST['login']) : "";
        $this->render($data);
    }
}
?>