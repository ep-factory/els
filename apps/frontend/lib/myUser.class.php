<?php

class myUser extends sfGuardSecurityUser
{
  public function canEdit(Fiche $fiche)
  {
    // Si la fiche est close, seul Dieu peut l'éditer
    if($fiche->getIsFinished()) {
      return $this->isSuperAdmin();
    }
    // Si la fiche n'est pas fermée, seul son propriétaire peut l'éditer
    elseif(!$fiche->getIsResolved()) {
      return $this->hasCredential('edit-own') && $this->getGuardUser()->getPrimaryKey() == $fiche->getSfGuardUserId();
    }
    // Si la fiche est fermée, seul un coordinateur pour l'éditer
    return $this->hasCredential('edit-resolved');
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
