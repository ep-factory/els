<script type="text/javascript">
  $(document).ready(function(){
    if($(location).attr('href').match(/\.local/)) {
      $('.sf_admin_show_field_filename span a').attr('href', "http://www.criels.com" + $('.sf_admin_show_field_filename span a').attr('href'));
    }
  });
</script>
<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_filename">
  <strong>Fichier</strong>
  <span><?php echo link_to("Télécharger le fichier", $document->getFilename(), array('target' => '_blank')) ?></span>
</div>