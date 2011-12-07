<?php

class ExportFormFilter extends FicheFormFilter
{
  /**
   * Init filters
   */
  public function configure()
  {
    parent::configure();
    $this->useFields(array('batiment_id', 'search', 'appareil_id', 'category_id', 'sf_guard_user_id', 'demandeur_id', 'start_hour', 'end_hour', 'is_finished', 'is_resolved'));
  }
}
