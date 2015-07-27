<?php

namespace RefPays\Model;

class Pays
{
    protected $id;
    protected $code;
    protected $alpha2;
    protected $alpha3;
    protected $nomEnGb;
    protected $nomFrFr;
    protected $devise;
    protected $tauxTva;
    
    function getId()
    {
        return $this->id;
    }

    function getCode()
    {
        return $this->code;
    }

    function getAlpha2()
    {
        return $this->alpha2;
    }

    function getAlpha3()
    {
        return $this->alpha3;
    }

    function getNomEnGb()
    {
        return $this->nomEnGb;
    }

    function getNomFrFr()
    {
        return $this->nomFrFr;
    }

    function getDevise()
    {
        return $this->devise;
    }

    function getTauxTva()
    {
        return $this->tauxTva;
    }

    function setId($id)
    {
        $this->id = (int) $id;
    }

    function setCode($code)
    {
        $this->code = (int) $code;
    }

    function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;
    }

    function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;
    }

    function setNomEnGb($nomEnGb)
    {
        $this->nomEnGb = $nomEnGb;
    }

    function setNomFrFr($nomFrFr)
    {
        $this->nomFrFr = $nomFrFr;
    }

    function setDevise($devise)
    {
        $this->devise = $devise;
    }

    function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->code = (isset($data['code'])) ? $data['code'] : null;
        $this->alpha2 = (isset($data['alpha2'])) ? $data['alpha2'] : null;
        $this->alpha3 = (isset($data['alpha3'])) ? $data['alpha3'] : null;
        $this->devise = (isset($data['devise'])) ? $data['devise'] : null;
        $this->nomEnGb = (isset($data['nom_en_gb'])) ? $data['nom_en_gb'] : null;
        $this->nomFrFr = (isset($data['nom_fr_fr'])) ? $data['nom_fr_fr'] : null;
        $this->tauxTva = (isset($data['tauxTva'])) ? $data['tauxTva'] : null;
    }
    
    /**
     * Renvoie l'objet sous forme de tableau
     * 
     * @return type
     */
    public function toArray()
    {
        $array = [];
        
        if ((isset($this->id))) {
            $array['id'] = $this->id;
        }
        
        if ((isset($this->code))) {
            $array['code'] = $this->code;
        }
        
        if ((isset($this->alpha2))) {
            $array['alpha2'] = $this->alpha2;
        }
        
        if ((isset($this->alpha3))) {
            $array['alpha3'] = $this->alpha3;
        }
        
        if ((isset($this->devise))) {
            $array['devise'] = $this->devise;
        }
        
        if ((isset($this->nomEnGb))) {
            $array['nom_en_gb'] = $this->nomEnGb;
        }
        
        if ((isset($this->nomFrFr))) {
            $array['nom_fr_fr'] = $this->nomFrFr;
        }
        
        if ((isset($this->tauxTva))) {
            $array['tauxTva'] = $this->tauxTva;
        }
        
        return $array;
    }
    
    public function getArrayCopy()
    {
        return $this->toArray();
    }
}