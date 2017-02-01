<?php
namespace MyFramework;

class RegistrationController extends RegistrationModel
{
    public function defaultAction()
    {
        $user = [];
        $data['url'] = $_SERVER['REQUEST_URI'];
        if (empty($_POST['login']) || empty($_POST['password'])
        || empty($_POST['name'])) {
            $this->render($data);
            return;
        }
        //Verification Login
        $login = trim($_POST['login']);
        $login_size = strlen($login);
        if (!preg_match("/^[a-z0-9-_]+$/", $login) || $login_size == 0
        || $login_size < 4 || $login_size > 10) {
            $data['errorLogin'] = "<p>Login Incorect</p>";
            $this->render($data);
            return;
        }
        //Verification Password
        $pass = trim($_POST['password']);
        $pass_size = strlen($pass);
        if ($pass_size == 0 || $pass_size < 4 || $pass_size > 20) {
            $data['errorPass'] = "<p>Mot de passe Incorect</p>";
            $this->render($data);
            return;
        }
        //Hash du Password
        $salt = "LeCloudc'estCommeLesErreurPHP".
        "MoinsTuEnAsMieuxTuTePorte";
        $pass = hash('ripemd160', $pass . $salt);

        //Verification Nom
        $name = strtolower(trim($_POST['name']));
        $name_size = strlen($name);
        if(!preg_match("/^[a-z-]*$/", $name) ||
        $name_size == 0 || $name_size > 64) {
            $data['errorName'] = "<p>Nom Incorect</p>";
            $this->render($data);
            return;
        }
        $user['login'] = $login;
        $user['password'] = $pass;
        $user['name'] = $name;
        if(!$this->find_user($user['login'])) {
            $data['errorFind'] = "<p>Login déjà utilisé</p>";
            $this->render($data);
            return;
        }
        if(!$this->add($user)) {
            $data['errorAdd'] = "<p>Ajout non réussi</p>";
            $this->render($data);
            return;
        }
        $curent_user = $this->find_user($user['login']);
        $data['success'] = "<p>Inscription Reussie</p>";
        $_SESSION['id' . TOKEN] = $user['id_user'];
        $_SESSION['login' . TOKEN] = $user['login'];
        $_SESSION['name' . TOKEN] = $user['name'];
        $_SESSION['state' . TOKEN] = $user['state'];
        header('Location: /' . BASE_URI . '/');
        return;
    }

    public function connexionAction()
    {
        $data = [];
        $user = [];
        $data['url'] = $_SERVER['REQUEST_URI'];
        if (empty($_POST['login']) || empty($_POST['password'])) {
            $this->render($data);
            return;
        }
        if(empty(trim($_POST['login'])) || empty(trim($_POST['password']))) {
            $this->render($data);
            return;
        }
        $user['login'] = $_POST['login'];
        $user['password'] = $_POST['password'];
        if(!empty($user = $this->find_user($user, true))) {
            $_SESSION['id' . TOKEN] = $user[0]['id_user'];
            $_SESSION['login' . TOKEN] = $user[0]['login'];
            $_SESSION['name' . TOKEN] = $user[0]['name'];
            $_SESSION['state' . TOKEN] = $user[0]['state'];
            header('Location: /' . BASE_URI . '/');
            return;
        }
        else {
            $data['errorfind'] = "<p>Mauvais Identifiant</p>";
            $this->render($data);
            return;
        }
    }

    public function disconnectAction() {
        session_destroy();
        session_unset();
        header('Location: /' . BASE_URI . '/');
        return;
    }
}
?>