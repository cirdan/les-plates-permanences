<?php

namespace LesPlates\Permanences\Domain;

interface EngagementRepository
{
    public function save(Engagement $engagement);
    public function findAll();
    public function findById($uuid);
    public function remove(Engagement $engagement);
    public function engagementExiste(Engagement $engagement): bool;
    public function findByBenevole(Benevole $benevole);
}