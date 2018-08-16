<?php
$classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
]);
// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?> >
    <?php if($field['style_select'] == 'style_1') : ?>
        <div class="qx-row">
            <div class="qx-col-sm-9">
                <div class="qx-ctac">
                    <?php if($field['title']):?>
                        <h3 class="qx-title"><?php echo $field['title']; ?></h3>
                    <?php endif;?>

                    <?php if($field['description']):?>
                        <div class="qx-description"><?php echo $field['description']?></div>
                    <?php endif;?>
                </div>
            </div><!--/.col-->
            <div class="qx-col-sm-3">
                <div class="qx-ctab">
                    <?php if($field['button']['url']):?>
                        <a class="qx-btn <?php echo $field['style']; ?>" href="<?php echo $field['button']['url'] ?>" <?php echo ( $field['button']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>>
                            <?php echo $field['button']['text'] ?>
                        </a>
                    <?php endif;?>
                </div>
            </div><!--/.col-->
        </div><!--/.row-->

    <?php else : ?>

        <div class="style_2">
            <div class="qx-ctac">
                <?php if($field['title']):?>
                    <h3 class="qx-title"><?php echo $field['title']; ?></h3>
                <?php endif;?>

                <?php if($field['description']):?>
                    <div class="qx-description"><?php echo $field['description']?></div>
                <?php endif;?>
            </div>

            <div class="qx-ctab">
                <?php if($field['button']['url']):?>
                    <a class="qx-btn <?php echo $field['style']; ?>" href="<?php echo $field['button']['url'] ?>" <?php echo ( $field['button']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>>
                        <?php echo $field['button']['text'] ?>
                    </a>
                <?php endif;?>
            </div>
        </div>
                
    <?php endif; ?>

</div>
<!-- qx-element-call2action -->