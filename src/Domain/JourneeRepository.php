<?php

namespace LesPlates\Permanences\Domain;


use League\Period\Period;
use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Exception\JourneeInconnueException;

class JourneeRepository
{
    public function findAll()
    {
        return $this->journees;
    }

    public function findOneByDate($date)
    {
        $matching = $this->journees->filter(
            function($journee) use ($date) {
                return $journee->date()==$date;
            }
        );
        if(!count($matching)){
            throw new JourneeInconnueException();
        }
        if(count($matching)===1){
            return $matching->first();
        }
        throw new JourneeException("Plusieurs journées correspondent alors que l'on en attend une seule.");
    }

    private $journees;

    public function __construct()
    {
        $this->journees=new ArrayCollection();
        $this->journees->add(new JourneeAgenda("2018-07-09","11:00","18:00"));
        $this->journees->add(new JourneeAgenda("2018-07-10","11:00","18:00"));
        $this->journees->add(new JourneeAgenda("2018-07-11","13:00","19:30"));
        $this->journees->add(new JourneeAgenda("2018-07-12","13:00","19:30"));
        $this->journees->add(new JourneeAgenda("2018-07-13","13:30","20:00"));
        $this->journees->add(new JourneeAgenda("2018-07-16","09:30","20:00"));
        $this->journees->add(new JourneeAgenda("2018-07-17","09:00","20:00"));
        $this->journees->add(new JourneeAgenda("2018-07-18","09:00","20:00"));
        $this->journees->add(new JourneeAgenda("2018-07-19","09:00","20:00"));
        $this->journees->add(new JourneeAgenda("2018-07-20","09:00","15:30"));
    }


}