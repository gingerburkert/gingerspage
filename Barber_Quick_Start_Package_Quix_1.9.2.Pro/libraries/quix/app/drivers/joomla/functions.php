<?php

function qxGetShortcode( $id, $name ) {
  return "[quix id='{$id}' name='{$name}']";
}

function qxGetCreateNewCollectionUrl() {
  return "index.php?option=com_quix&task=collection.edit&view=collection&layout=edit";
}

function qxGetCollectionEditorUrl( $id ) {
  return qxGetCreateNewCollectionUrl() . "&id={$id}";
}