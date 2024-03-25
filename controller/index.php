<?php

namespace controller;

use model\Annonce;
use model\Photo;
use model\Annonceur;

class index
{
    protected $annonce = array();

    public function displayAllAnnonce($twig, $chemin, $cat)
    {
        $menu = [
            [
                'href' => $chemin,
                'text' => 'Accueil'
            ],
        ];

        $this->getAllAnnonces();
        $this->renderTemplate($twig, "index.html.twig", [
            "breadcrumb" => $menu,
            "chemin"     => $chemin,
            "categories" => $cat,
            "annonces"   => $this->annonce
        ]);
    }

    private function renderTemplate($twig, $templateName, $data)
    {
        $template = $twig->load($templateName);
        echo $template->render($data);
    }

    public function getAllAnnonces()
    {
        $tmp = Annonce::with("Annonceur")->orderBy('id_annonce', 'desc')->take(12)->get();
        $annonce = [];
        foreach ($tmp as $t) {
            $t->nb_photo = Photo::where("id_annonce", "=", $t->id_annonce)->count();
            if ($t->nb_photo > 0) {
                $t->url_photo = Photo::select("url_photo")
                    ->where("id_annonce", "=", $t->id_annonce)
                    ->first()->url_photo;
            } else {
                $t->url_photo = '/img/noimg.png';
            }
            $t->nom_annonceur = Annonceur::select("nom_annonceur")
                ->where("id_annonceur", "=", $t->id_annonceur)
                ->first()->nom_annonceur;
            array_push($annonce, $t);
        }
        $this->annonce = $annonce;
    }

}
