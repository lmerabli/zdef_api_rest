<?php

namespace RefPays\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractRestfulController
{
    protected $acceptCriteria = array(
      'Zend\View\Model\ViewModel' => array(
         'application/xml',
      ),
   );
    
    /**
     * Gère les requêtes entrantes
     * 
     * @return type
     */
    public function indexAction()
    {
	    //select
        if ($this->getRequest()->getMethod() == 'GET') {
            $view = $this->getAction();
            
            $view->setTemplate("/ref-pays/api/get.phtml");
            
            return $view;
            
        //insert 
        } else if ($this->getRequest()->getMethod() == "POST") {
            $json = $this->getRequest()->getContent();
            $data = json_decode($json, true);
            
            $this->save($data);
            
        //update
        } else if ($this->getRequest()->getMethod() == "PATCH") {
            $code = $this->params('code');
            
            $paysTable = $this->getServiceLocator()->get('pays-table');
            $pays = $paysTable->getPays($code, "", true)[0];
            
            $dataPays = $pays->toArray();
            
            $json = $this->getRequest()->getContent();
            $data = array_merge($dataPays, json_decode($json, true));
            
            if (empty($data['code'])) {
                return false;
            }
            
            $this->save($data, true);
        } else if ($this->getRequest()->getMethod() == 'DELETE') {
            $this->deleteAction();
        }
    }
    
    /**
     * Méthode POST
     * @param type $data
     */
    public function save($data, $isApi = false)
    {
        $paysTable = $this->getServiceLocator()->get('pays-table');
        
        $pays = new \RefPays\Model\Pays();
        $pays->exchangeArray($data);
        
        $paysTable->savePays($pays, $isApi);
    }
    
    /**
     * Méthode GET de l'API
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getAction()
    {
        $view = $this->acceptableViewModelSelector($this->acceptCriteria, false);
        
        if (!$view) {
            $view = new JsonModel();
        }
        
        $code = $this->params('code');
        $fieldsString = $this->params()->fromQuery('fields');
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        
        if (get_class($view) == 'Zend\View\Model\ViewModel') {
            $view->setTerminal(true);
            
            $this->response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
            
            $paysList = $paysTable->getPaysXml($code, $fieldsString);
        } else {
            $paysList = $paysTable->getPays($code, $fieldsString);
        }
        
        $view->setVariable('pays', $paysList);
        
        return $view;
    }
    
    /**
     * Supprime un pays en BDD
     * 
     * @param type $code
     */
    public function deleteAction()
    {
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');

            $paysTable->deletePays($code);
        }
    }
}

