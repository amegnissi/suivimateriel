<?php

namespace App\Data;

class RechercheData
{

    private $objet;
    private $reference;
    private $dateDebut;
    private $dateFin;
    private $partenaire;

    private $nature;

    /**
     * @return mixed
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * @param mixed $objet
     * @return RechercheData
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     * @return RechercheData
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param mixed $date
     * @return RechercheData
     */
    public function setDateDebut($date)
    {
        $this->dateDebut = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartenaire()
    {
        return $this->partenaire;
    }

    /**
     * @param mixed $partenaire
     * @return RechercheData
     */
    public function setPartenaire($partenaire)
    {
        $this->partenaire = $partenaire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param mixed $dateFin
     * @return RechercheData
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * @param mixed $nature
     * @return RechercheData
     */
    public function setNature($nature)
    {
        $this->nature = $nature;
        return $this;
    }


}
