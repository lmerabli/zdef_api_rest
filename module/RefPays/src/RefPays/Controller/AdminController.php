<?php

namespace RefPays\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Http;
use Zend\Authentication\Adapter\Http\FileResolver;
use RefPays\Form\Pays;

class AdminController extends AbstractActionController
{
    
    public function indexAction()
    {
        //$this->getAuthService();
        
        $view = new ViewModel();
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        $paysList = $paysTable->getPaysAdmin("", "nom_fr_fr", true);
        
        $view->setVariable('pays', $paysList);
        var_dump($paysList);
        return $view;
    }
    
    /**
     * Supprime un pays dans la liste admin
     * @return ViewModel
     */
    public function deleteAction()
    {
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');

            $paysTable->deletePays($code);
        }
        
        return $this->redirect()->toRoute('api_admin');
    }
    
    /**
     * Create pays
     */
    public function paysCreateAction()
    {
        $view = new ViewModel();
        $form = new Pays(true);
        
        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            $form->setData($post);

            if (false === $form->isValid()) {
                return $view;
            }

            $cleanedData = $form->getData();

            $this->savePays($cleanedData);
            
            return $this->redirect()->toRoute('api_admin');
        }
        
        $view->setVariable("form", $form);
        
        return $view;
    }
    
    /**
     * Update pays
     * @return ViewModel
     */
    public function paysUpdateAction()
    {
        $view = new ViewModel();
        $form = new Pays();
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');
            $paysArray = $paysTable->getPaysAdmin($code);
            
            if (isset($paysArray["error"])) {
                return $this->redirect()->toRoute('api_admin');
            }
            
            $form->bind($paysArray[0]);
            
            if ($this->request->isPost()) {
                $post = $this->request->getPost();
                
                $form->setData($post);
                
                if (false === $form->isValid()) {
                    return $view;
                }
                
                $cleanedData = $form->getData();
                
                $this->savePays($cleanedData);
            }

            $view->setVariable("form", $form);
            $view->setVariable("code", $code);
        }
        
        return $view;
    }
    
    public function savePays($data)
    {
        if (is_array($data)) {
            $pays = new \RefPays\Model\Pays();
            $pays->exchangeArray($data);
        } else {
            $pays = $data;
        }
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        $paysTable->savePays($pays);
        
        return true;
    }
    
    // FONCTION A VIRER
    protected function getAuthService()
    {
        $config = array(
            'accept_schemes' => 'basic',
            'realm'          => 'ref-pays-admin',
            //'digest_domains' => '/admin',
            //'nonce_timeout'  => 3600,
        );
        
//        if (null == $this->authService){
            $httpAuthAdapter = new Http($config);
            $authService = new AuthenticationService();
            $basicResolver = new FileResolver();
            
            $basicResolver->setFile(dirname(dirname(dirname(dirname(dirname(__DIR__))))).'\public\files\basicPasswd.txt');
            
            $httpAuthAdapter->setBasicResolver($basicResolver);
            
            $httpAuthAdapter->setRequest($this->getRequest());
            $httpAuthAdapter->setResponse($this->getResponse());

            
            $result = $httpAuthAdapter->authenticate();
            
            
            if (!$result->isValid()) {
                die(var_dump($result->getMessages()));
            }
            
            die('654645');
            
            $authService->setAdapter($httpAuthAdapter);
            $this->authService = $authService;
//        }
        
        return $this->authService;
    }
}
