<div class="breadcrumb mb-24">
    <ul class="flex-align gap-4">
        <?php foreach ($items as $i => $item): ?>
            <?php if ($i > 0): ?>
                <li><span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span></li>
            <?php endif; ?>
            <li>
                <?php if (!empty($item['url']) && $i < count($items) - 1): ?>
                    <a href="<?= $item['url'] ?>" class="text-gray-200 fw-normal text-15 hover-text-main-600"><?= $item['label'] ?></a>
                <?php else: ?>
                    <span class="text-main-600 fw-normal text-15"><?= $item['label'] ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div> 