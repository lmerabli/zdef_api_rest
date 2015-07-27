<?php

namespace RefPays\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class PaysTable
{
    protected $tableGateway;
    
    /**
     * 
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * Méthode get : récupère un ou plusieurs pays
     * 
     * @param string $code
     * @return array
     * @throws \Exception
     */
    public function getPays($code = "", $returnObject = false)
    {
        $select = new Select();
        $where = null;
        $result = [];
        
        try {
            $select->from('pays');

            if ($code) {
                $where = $this->constructWhereFromCode($code);

                $select->where($where);
            }
            
            $rowset = $this->tableGateway->selectWith($select);
            
            if ($rowset->count() > 1) {
                for ($i=0; ($row = $rowset->current()) && $i < 30; $rowset->next(), $i++) {
                    $result[] = $row->toArray();
                }
            } else if ($rowset->count() > 0) {
                if ($returnObject) {
                    $result[] = $rowset->current();
                } else {
                    $result[] = $rowset->current()->toArray();
                }
            }
            
            if(empty($result)) {
                throw new \Exception("Nous n'avons pas trouve le pays possedant ce code, alpha2 ou alpha3 egal  $code");
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Retourne la liste des pays de la vue d'admin
     * 
     * @return type
     */
    public function getPaysAdmin($code = "")
    {
        if ($code) {
            return $this->getPays($code, true);
        }
        
        return $this->getPays();
    }
    
    public function savePays(Pays $pays, $isApi = false)
    {
        $data = [
            'id'        => $pays->getId(),
            'code'  => $pays->getCode(),
            'alpha2'  => $pays->getAlpha2(),
            'alpha3'  => $pays->getAlpha3(),
            'nom_en_gb'  => $pays->getNomEnGb(),
            'nom_fr_fr' => $pays->getNomFrFr(),
            'devise'  => $pays->getDevise(),
            'tauxTva'  => $pays->getTauxTva(),
        ];
        
        $id = (int)$pays->getId();
        $code = $pays->getCode();
        
        if ($id == 0 && !$isApi) {
            $this->tableGateway->insert($data);
        } else if ($isApi && $this->getPays($code)) {
            unset($data['id']);
            $this->tableGateway->update($data, array('code' => $code));
        } else {
            if ($this->getPaysById($id)) { 
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }
    
    public function getPaysById($id)
    {
        $rowset = $this->tableGateway->select(array('id'=> (int) $id));
        
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
    }
    
    /**
     * Méthode get : retourne au format XML
     * 
     * @param type $code
     * @return type
     */
    public function getPaysXml($code = "")
    {
        $paysXml = new \SimpleXMLElement("<?xml version=\"1.0\"?><pays></pays>");
        $paysListe = $this->getPays($code);
        
        foreach($paysListe as $id => $pays) {
            if (is_string($pays)) {
                $paysXml->addChild("error", mb_convert_encoding($pays, "UTF-8"));
            } else {
                $subnode = $paysXml->addChild("pays-".($id+1));
                
                foreach($pays as $key => $value) {
                    $subnode->addChild($key, $value);
                }
            }
        }
        
        return $paysXml->asXML();
    }
    
    /**
     * Supprime un pays en base de données depuis son code, alpha2 ou alpha3
     * 
     * @param type $code
     */
    public function deletePays($code)
    {
        $delete = new \Zend\Db\Sql\Delete();
        $where = $this->constructWhereFromCode($code);
        
        $delete->from('pays')->where($where);
        
        $this->tableGateway->deleteWith($delete);
    }
    
    /**
     * Retourne une clause where sur le code fourni en paramètre
     * 
     * @param type $code
     * @return Where
     */
    private function constructWhereFromCode($code)
    {
        $where = new Where();
        
        $where->equalTo('code', $code);
        $where->OR->equalTo('alpha2', $code);
        $where->OR->equalTo('alpha3', $code);
        
        return $where;
    }
}