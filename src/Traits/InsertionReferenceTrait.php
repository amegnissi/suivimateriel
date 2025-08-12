<?php

namespace App\Traits;

trait InsertionReferenceTrait
{
 public function insertion ($identifier,$entity,$form)
 {
     $entity->setReferenceSysteme($identifier);
     if (empty($form->get('referenceManuel')->getData())) {
         $entity->setReferenceManuel($identifier);
     }
 }
}
