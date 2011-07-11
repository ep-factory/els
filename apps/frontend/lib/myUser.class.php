<?php

class myUser extends sfGuardSecurityUser
{
  public function canEdit(Fiche $fiche)
  {
    if($this->hasGroup('technicien')) {
      return (is_null($fiche->getSfGuardUserId()) || $fiche->getSfGuardUserId() == $this->getGuardUser()->getPrimaryKey()) && !$fiche->getIsResolved() && !$fiche->getIsFinished();
    }
    elseif($this->hasGroup('coordinateur')) {
      return !$fiche->getIsFinished();
    }
    elseif($this->hasGroup('consultant')) {
      return false;
    }
    return true;
  }

  /**
   * Convert seconds to time
   *
   * @param string $time Milliseconds
   * @return string Time
   */
  public static function convert_seconds_to_time($time) {
    // calcul du nombre de minutes
    $min = str_pad(round(($time / 60000) % 60), 2, "0", STR_PAD_LEFT);
    $heures = str_pad(round(($time / 60000) / 60), 2, "0", STR_PAD_LEFT);
    return $heures."h".$min;
  }

}
