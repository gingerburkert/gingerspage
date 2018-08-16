<?php $collections = qxGetCollections(); ?>
<style>
  body{
    font-family: sans-serif;
  }
  .qx-collection-list{ list-style:none; padding:0; margin:0 10px; }
  .qx-collection-list li{
    background : #fff;
    padding: 10px 1.1rem 13px; margin: 10px 0;
    border-radius: 2px;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
  }
  .qx-collection-title{ width: 40%; float: left; }
  .label{
    background: #fafbfb;
    padding: 3px 7px;
    font-size: 10px; color: #999;
    border: 1px solid #eee;
    border-radius: 2px;
  }
  .label-section,
  .label-layout{ color: #fff; text-transform: uppercase; letter-spacing: .5px; }
  .label-section{ background: #9575cd; border-color: #9575cd;}
  .label-layout{ background: #26a69a; border-color: #23b3a5; padding: 3px 10px; }
  .pull-right{ float: right; }
  .btn{
    border-radius: 2px;
    display: inline-block;
    height: 22px;
    line-height: 22px;
    outline: 0;
    padding: 0 2rem;
    text-transform: uppercase;
    color: #fff;
    background-color: #26a69a;
    text-align: center;
    font-size: 11px;
    letter-spacing: .5px;
    text-decoration: none;
    margin-left: 5px;
    transition: all 0.2s linear;
  }
  .btn:hover{
    background-color: #2bbbad;
    box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18),0 4px 15px 0 rgba(0,0,0,0.15);
  }
  .btn--new{ margin: 5px 0 5px 10px; padding: .3rem 2rem; }
  .btn--edit{ background: #00bcd4; }
  .btn--edit:hover{ background-color: #4dd0e1; }
</style>

<a class="btn btn--new open-editor-button"  href="<?php echo qxGetCreateNewCollectionUrl(); ?>">Create New</a>
<ul class="qx-collection-list">
<?php foreach ( $collections as $collection ): ?>
    <li>
    <span class="qx-collection-title"><?php echo $collection->title ?></span>
    <span class="label label-shortcode">[quix id='<?php echo $collection->id ?>']</span>
    <span class="label label-<?php echo $collection->type ?>">
      <?php echo $collection->type ?>
    </span>
    <a class="qx-insert-shortcode btn pull-right" href="#"
      data-shortcode="<?php echo qxGetShortcode($collection->id, $collection->title); ?>">
      Insert
    </a>
    <a class="btn btn--edit pull-right open-editor-button"
      href="<?php echo qxGetCollectionEditorUrl($collection->id) ?>">
      Edit
    </a>
  </li>
<?php endforeach; ?>
</ul>
<script>
  parent.quix.hideSpinner();
  parent.quix.shrinkModal();

	[].map.call(document.querySelectorAll(".qx-insert-shortcode"), function(btn){
		btn.addEventListener("click", function(){
			parent.quix.insertShortCode(this.dataset.shortcode);
		});
	});

  [].map.call(document.querySelectorAll(".open-editor-button"), function(btn){
    btn.addEventListener("click", function(){
      parent.quix.expandModal();
    });
  });
</script>
