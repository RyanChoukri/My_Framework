<?php
namespace MyFramework;

class CinemaModel extends Core
{
    public function getFilm($entry = null)
    {
        $orm = new ORM;
        if(empty($entry)) {
            return $orm->find('film',["inner" => ['distrib', 'genre'] ,
            "select" => ['film.titre AS Film',
            'genre.nom AS Genre',
            'film.resum AS Resumé',
            'distrib.nom AS Distributeur',
            'film.annee_prod AS Année_de_prod'],
            'order_by' => ['id_film'],
            "limit" => ['10']]);
        }
        $inner = ["inner" => ['distrib', 'genre'],
            "select" => ['film.titre AS Film',
            'genre.nom AS Genre',
            'film.resum AS Resumé',
            'distrib.nom AS Distributeur',
            'film.annee_prod AS Année_de_prod'],
            'order_by' => ['id_film'],
            "limit" => ['10']];

        $merge = ['where' => $entry];
        $send = array_merge($inner, $merge);
        return $orm->find('film',$send);
    }

    public function getAbo() {
        $orm = new ORM;
        return $orm->find('abonnement');
    }
    public function getReduc() {
        $orm = new ORM;
        return $orm->find('reduction');
    }
}
?>