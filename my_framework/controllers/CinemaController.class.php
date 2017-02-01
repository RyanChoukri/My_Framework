<?php
namespace MyFramework;

class CinemaController extends CinemaModel
{
    public function defaultAction()
    {
        $search = [];
        if(!empty($_POST)) {
            if(!empty($_POST['titre'])) {
                $search['film.titre'] = $_POST['titre'];
            }
            if(!empty($_POST['genre'])) {
                $search['genre.nom'] = $_POST['genre'];
            }
            if(!empty($_POST['distrib'])) {
                $search['distrib.nom'] = $_POST['distrib'];
            }
            $all_film = $this->getFilm($search);
        }
        else {
            $all_film = $this->getFilm();
        }

        
        $this->isLogged();
        if(empty($all_film)) {
            $data['empty_result'] = '<p>Aucun résulat trouvé</p>';
            $this->render($data);
            return;
        }
        $raw = "<table><tr>";
        foreach ($all_film[0] as $th_key => $th_value){
            $raw .= "<th>" . $th_key . "</th>";
        }
        $raw .= "</tr>";

        foreach ($all_film as $all_k => $all_v) {
            $row = "<tr>";
            foreach ($all_v as $row_key => $row_value) {
                if(strlen($row_value) > 40) {
                    $big = $row_value;
                    $row_value = substr($row_value, 0, 40) . "...";
                    $row .= "<td>" . html_entity_decode($row_value) .
                    "</td>". PHP_EOL;
                }
                else {
                    if(empty($row_value)) {
                        $row_value = "---";
                    }
                    $row .= "<td>" . html_entity_decode($row_value) .
                    "</td>". PHP_EOL;
                }
            }
            $raw .= $row . "</tr>" . PHP_EOL;   
        }
        $data['data_loop'] = $raw . "</table>";
        $this->render($data);
    }

    public function abonnementAction()
    {
        $this->isLogged();
        if(empty($all_abo = $this->getAbo())) {
            $data['empty_result'] = '<p>Aucun résulat trouvé</p>';
            $this->render($data);
            return;
        }
        $raw = "<table><tr>";
        $bool = true;
        foreach ($all_abo[0] as $th_key => $th_value){
            if(!$bool) {
                $raw .= "<th>" . $th_key . "</th>";
            }
            $bool = false;
        }
        $raw .= "</tr>";

        foreach ($all_abo as $all_k => $all_v) {
            $row = "<tr>";
            $status = true;
            foreach ($all_v as $row_key => $row_value) {
                if(!$status) {
                    if(empty($row_value)) {
                        $row_value = "---";
                    }
                    $row .= "<td>" . html_entity_decode($row_value) .
                    "</td>". PHP_EOL;
                }
                $status = false;
            }
            $raw .= $row . "</tr>" . PHP_EOL;   
        }
        $data['data_loop'] = $raw . "</table>";
        $this->render($data);
    }
    public function reductionAction()
    {
        $this->isLogged();
        if(empty($all_abo = $this->getReduc())) {
            $data['empty_result'] = '<p>Aucun résulat trouvé</p>';
            $this->render($data);
            return;
        }
        $raw = "<table><tr>";
        $bool = true;
        foreach ($all_abo[0] as $th_key => $th_value){
            if(!$bool) {
                $raw .= "<th>" . $th_key . "</th>";
            }
            $bool = false;
        }
        $raw .= "</tr>";

        foreach ($all_abo as $all_k => $all_v) {
            $row = "<tr>";
            $status = true;
            foreach ($all_v as $row_key => $row_value) {
                if(!$status) {
                    if(empty($row_value)) {
                        $row_value = "---";
                    }
                    $row .= "<td>" . html_entity_decode($row_value) .
                    "</td>". PHP_EOL;
                }
                $status = false;
            }
            $raw .= $row . "</tr>" . PHP_EOL;   
        }
        $data['data_loop'] = $raw . "</table>";
        $this->render($data);
    }
}
?>