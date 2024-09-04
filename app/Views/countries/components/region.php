<div class='region tab-element' data-id='<?= $region['id'] ?>'>
    <p class='country__name item-info__title'>
        <?= $region['name'] ?>
    </p>
    <p class='country__desc item-info__desc'><?= $region['desc'] ?></p>
    <?php if ($region['cities']): ?>
        <?php foreach ($region['cities'] as $city): ?>
            <?= get_content('countries/components/city.php', ['city' => $city]) ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>