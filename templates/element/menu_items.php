<ul class="menu-items">
    <?php foreach ($items as $item): ?>
        <li class="menu-item">
            <?php if (isset($item['url'])): ?>
                <a href="<?= $item['url'] ?>" class="item-link">
                    <span><?= $item['title'] ?></span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <div class="item-group">
                    <div class="group-title"><?= $item['title'] ?></div>
                    <?php if (!empty($item['items'])): ?>
                        <ul class="sub-items">
                            <?php foreach ($item['items'] as $subItem): ?>
                                <li>
                                    <a href="<?= $subItem['url'] ?>" class="sub-item-link">
                                        <?= $subItem['title'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>