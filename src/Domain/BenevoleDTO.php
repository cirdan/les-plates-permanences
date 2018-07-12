<?php

namespace LesPlates\Permanences\Domain;


use LesPlates\Permanences\Domain\Exception\BenevoleContactException;

class BenevoleDTO
{
    public $telephoneMobile;
    public $email;
    public $nom;
    public $notificationsParEmail;
    public $notificationsParTelephone;

    public static function fromBenevole(Benevole $benevole): self
    {
        $benevoleDTO = new self();
        $benevoleDTO->telephoneMobile = $benevole->telephoneMobile();
        $benevoleDTO->email = $benevole->email();
        $benevoleDTO->nom = $benevole->nom();
        $benevoleDTO->notificationsParEmail = $benevole->recoitLesNotificationsParEmail();
        $benevoleDTO->notificationsParTelephone = $benevole->recoitLesNotificationsParTelephone();
        return $benevoleDTO;
    }
}