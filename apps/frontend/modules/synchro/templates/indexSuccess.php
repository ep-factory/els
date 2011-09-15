<script type="text/javascript">
  $(document).ready(function(){
    $('button').live('click', function(event){
      event.preventDefault();
      $.ajax({
        url: '<?php echo url_for('@synchro') ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
          $(event.target).hide();
          $('.loading').css({display: 'block'});
        },
        error: function(){
          $('.loading').hide();
          $(event.target).show();
          $('<div class="status error"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_error.png" /><span>Erreur!</span> Une erreur est survenue</p></div>').insertBefore($('.contentcontainer')).delay(10000).fadeOut('fast', function(){$(this).remove();});
        },
        success: function(data){
          $('.loading').hide();
          $(event.target).show();
          if(data.code == "success") {
            $('<div class="status success"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_success.png" /><span>Succès!</span> ' + data.message + '</p></div>').insertBefore($('.contentcontainer')).delay(10000).fadeOut('fast', function(){$(this).remove();});
          }
          else {
            $('<div class="status error"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_error.png" /><span>Erreur!</span> ' + data.message + '</p></div>').insertBefore($('.contentcontainer')).delay(10000).fadeOut('fast', function(){$(this).remove();});
          }
        }
      });
    });
  });
</script>
<style type="text/css">
  button {
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    background: #5996CC;
    background: -webkit-gradient(linear, left top, left bottom, from(#5996CC), to(#07517D));
    background: -moz-linear-gradient(top,#5996CC,#07517D);
    background: -moz-linear-gradient(#5996CC, #07517D);
    background: -ms-linear-gradient(#5996CC, #07517D);
    background: -o-linear-gradient(#5996CC, #07517D);
    background: linear-gradient(#5996CC, #07517D);
    -pie-background: linear-gradient(#5996CC, #07517D);
    -webkit-box-shadow: 0 1px 2px #a3abae;
    -moz-box-shadow: 0 1px 2px #a3abae;
    box-shadow: 0 1px 2px #a3abae;
    border: none;
    color: white;
    cursor: pointer;
    display: block;
    font-weight: bold;
    margin: 0 auto;
    padding: 12px 50px;
    font-size: 1.2em;
  }
  .loading {
    display: none;
    margin: 10px auto;
  }
</style>
<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | Synchronisation') ?>
<?php slot('breadcrumb', array(array('url' => '@synchro', 'label' => 'Synchronisation'))) ?>
<?php //include_partial('synchro/flashes') ?>
<div class="contentcontainer med left">
  <div class="headings">
    <h2>Synchronisation</h2>
  </div>
  <div class="contentbox">
    <p>La synchronisation est une opération qui peut être longue. Veuillez ne pas fermer cette page durant son exécution.</p>
    <br />
    <button>Lancer la synchronisation</button>
    <?php echo image_tag(public_path("/sfAdminTemplatePlugin/images/ajax-loader.gif"), array('alt' => 'Chargement', 'title' => 'Chargement', 'class' => 'loading')) ?>
  </div>
</div>
<div style="clear: both;"></div>