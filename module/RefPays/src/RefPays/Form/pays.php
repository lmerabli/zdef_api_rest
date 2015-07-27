<?php

namespace RefPays\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;

class Pays extends Form
{
    public function __construct($isCreate = false) {
        parent::__construct('Pays');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'application/x-www-form-urlencoded');
        
        $id = new Hidden('id');
        
        $code = new Text('code');
        $code->setLabel('Code :');
        $code->setAttribute('required', true);
        
        $alpha2 = new Text('alpha2');
        $alpha2->setLabel('Alpha 2 :');
        $alpha2->setAttribute('required', true);
        
        $alpha3 = new Text('alpha3');
        $alpha3->setLabel('Alpha 3 :');
        $alpha3->setAttribute('required', true);
        
        $nomEnGb = new Text('nom_en_gb');
        $nomEnGb->setLabel('Nom anglais :');
        $nomEnGb->setAttribute('required', true);
        
        $nomFrFr = new Text('nom_fr_fr');
        $nomFrFr->setLabel('Nom français :');
        $nomFrFr->setAttribute('required', true);
        
        $devise = new Text('devise');
        $devise->setLabel('Devise :');
        
        $tauxTva = new Text('tauxTva');
        $tauxTva->setLabel('Taux TVA :');
        
        $submit = new Submit('send');
        
        if($isCreate) {
            $submit->setValue("Créer");
        } else {
            $submit->setValue("Modifier");
        }
        
        
        
        //$validator = new Zend\Validator\EmailAddress();
        
        //$result = $validator->isValid($email);
        
        // Ajout des champs
        $this->add($id);
        $this->add($code);
        $this->add($alpha2);
        $this->add($alpha3);
        $this->add($nomEnGb);
        $this->add($nomFrFr);
        $this->add($devise);
        $this->add($tauxTva);
        $this->add($submit);
    }
}

