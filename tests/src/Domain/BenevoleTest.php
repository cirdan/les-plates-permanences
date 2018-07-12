<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Exception\BenevoleContactException;
use PHPUnit\Framework\TestCase;

class BenevoleTest extends TestCase
{
    public function test_un_benevole_peut_activer_les_notifications()
    {
        $benevole=new Benevole();
        $benevole->ajouterEmail("sylvain@akenlab.fr");
        $benevole->ajouterTelephoneMobile("0102030405");
        $benevole->activerLesNotificationsParEmail();
        $this->assertTrue($benevole->recoitLesNotificationsParEmail());
        $benevole->activerLesNotificationsParTelephone();
        $this->assertTrue($benevole->recoitLesNotificationsParTelephone());

    }
    public function test_activer_les_notifications_par_email_necessite_un_email()
    {
        $this->expectException(BenevoleContactException::class);
        $benevole=new Benevole();
        $benevole->activerLesNotificationsParEmail();
    }
    public function test_activer_les_notifications_par_telephone_necessite_un_telephone()
    {
        $this->expectException(BenevoleContactException::class);
        $benevole=new Benevole();
        $benevole->activerLesNotificationsParTelephone();
    }
    public function test_un_benevole_peut_desactiver_les_notifications()
    {
        $benevole=new Benevole();
        $benevole->desactiverLesNotificationsParEmail();
        $this->assertFalse($benevole->recoitLesNotificationsParEmail());
        $benevole->desactiverLesNotificationsParTelephone();
        $this->assertFalse($benevole->recoitLesNotificationsParTelephone());

    }
    public function test_supprimer_l‘email_desactive_les_notifications_par_email()
    {
        $benevole=new Benevole();
        $benevole->ajouterEmail("sylvain@akenlab.fr");
        $benevole->activerLesNotificationsParEmail();
        $this->assertTrue($benevole->recoitLesNotificationsParEmail());
        $benevole->supprimerEmail();

        $this->assertFalse($benevole->recoitLesNotificationsParEmail());
    }
    public function test_supprimer_le_telephone_desactive_les_notifications_par_telephone()
    {
        $benevole=new Benevole();
        $benevole->ajouterTelephoneMobile("0908070605");
        $benevole->activerLesNotificationsParTelephone();
        $this->assertTrue($benevole->recoitLesNotificationsParTelephone());
        $benevole->supprimerTelephoneMobile();

        $this->assertFalse($benevole->recoitLesNotificationsParTelephone());
    }
}
