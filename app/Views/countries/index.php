<div>
    <?php
    foreach ($data as $country): ?>
        <div class='country'>
            <div class="item-info" data-id='<?= $country['id'] ?>'>
                <p class='country__name item-info__title'>
                    <?= $country['name'] ?>
                </p>
                <p class='country__desc item-info__desc'><?= $country['desc'] ?></p>
            </div>
            <?php if ($country['cities']): ?>
                <?php foreach ($country['cities'] as $city): ?>
                    <?= get_content('countries/components/city.php', ['city' => $city]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($country['regions']): ?>
                <?php foreach ($country['regions'] as $region): ?>
                    <?= get_content('countries/components/region.php', ['region' => $region]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<div>