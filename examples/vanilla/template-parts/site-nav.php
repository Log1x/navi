<?php
/**
 * This is an example of Navi used in a vanilla theme such as _'s.
 *
 * @link https://github.com/log1x/navi
 */

$navigation = new \Log1x\Navi\Navi()->build('primary-menu');
?>

<?php if ( $navigation->isNotEmpty() ) ) : ?>
    <nav id="site-navigation" class="main-navigation">
        <ul id="primary-menu">
            <?php foreach ( $navigation->toArray() as $item ) : ?>
                <li class="<?php echo $item->classes; ?> <?php echo $item->active ? 'current-item' : ''; ?>">
                    <a href="<?php echo $item->url; ?>">
                        <?php echo $item->label; ?>
                    </a>

                    <?php if ( $item->children ) : ?>
                        <ul>
                            <?php foreach ( $item->children as $child ) : ?>
                                <li class="<?php echo $child->classes; ?> <?php echo $child->active ? 'current-item' : ''; ?>">
                                    <a href="<?php echo $child->url; ?>">
                                        <?php echo $child->label; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>
