<?php

class myUser extends sfGuardSecurityUser
{
  public function canEdit(Fiche $fiche)
  {
    if($this->hasGroup('technicien')) {
      return (is_null($fiche->getSfGuardUserId()) || $fiche->getSfGuardUserId() == $this->getGuardUser()->getPrimaryKey()) && !$fiche->getIsResolved() && !$fiche->getIsClosed();
    }
    elseif($this->hasGroup('coordinateur')) {
      return !$fiche->getIsFinished();
    }
    elseif($this->hasGroup('consultant')) {
      return false;
    }
    return true;
  }
}
