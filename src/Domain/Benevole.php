<?php

namespace LesPlates\Permanences\Domain;


use LesPlates\Permanences\Domain\Exception\BenevoleContactException;

class Benevole
{
    private $id;
    private $recoitNotificationsParEmail;
    private $recoitNotificationsParTelephone;
    private $email;
    private $nom;
    private $telephoneMobile;

    public function activerLesNotificationsParEmail()
    {
        if(!$this->email()){
            throw new BenevoleContactException();
        }
        $this->recoitNotificationsParEmail=true;
    }
    public function activerLesNotificationsParTelephone()
    {
        if(!$this->telephoneMobile()){
            throw new BenevoleContactException("Pour activer les notifications par SMS, il faut un numéro de téléphone");
        }
        $this->recoitNotificationsParTelephone=true;
    }
    public function email()
    {
        return $this->email;
    }
    public function nom()
    {
        return $this->nom;
    }
    public function peutEtreNotifie()
    {
        return $this->aUnMoyenDeContact();
    }
    public function aUnMoyenDeContact()
    {
        return ($this->email || $this->telephoneMobile);
    }
    public function ajouterEmail(string $email=null)
    {
        $email=filter_var($email, FILTER_VALIDATE_EMAIL);
        if($email){
            $this->email=$email;
        }
    }
    public function supprimerEmail()
    {
        $this->email=null;
        $this->recoitNotificationsParEmail=false;
    }
    public function supprimerTelephoneMobile()
    {
        $this->telephoneMobile=null;
        $this->recoitNotificationsParTelephone=false;
    }
    public function ajouterTelephoneMobile(string $telephone=null)
    {
        $this->telephoneMobile=$telephone;
    }
    public function enregistrerNom(string $nom=null)
    {
        $this->nom=$nom;
    }
    public function telephoneMobile()
    {
        return $this->telephoneMobile;
    }
    public function desactiverLesNotificationsParEmail()
    {
        $this->recoitNotificationsParEmail=false;
    }
    public function desactiverLesNotificationsParTelephone()
    {
        $this->recoitNotificationsParTelephone=false;
    }
    public function recoitLesNotificationsParEmail()
    {
        return $this->recoitNotificationsParEmail;
    }
    public function recoitLesNotificationsParTelephone()
    {
        return $this->recoitNotificationsParTelephone;
    }
    public function __toString()
    {
        return $this->id;
    }
    public function id()
    {
        return $this->id;
    }
}