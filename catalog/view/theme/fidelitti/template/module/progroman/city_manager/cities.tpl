<div class="modal fade" tabindex="-1" role="dialog" data-show="true" style="background: rgba(0, 0, 0, 0.6);">
    <div class="modal-dialog modal-lg" style="width:95%; height:100%; max-width:1600px;">
        <div class="modal-content" style="margin: 0;background: #fff;padding:50px;margin:0px; height: 800px;">
            <div class="modal-header">                
                <div class="large-12" style="text-align:center;padding-bottom:35px;"><?= $text_choose_region; ?></div>
            </div>
            <div class="modal-body" style="top: 0;left: 0;width: 100%;height: 900px;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
			<div class="row">
				<div class="large-12 medium-12 small-12"  style="column-count:3;">
                <?php foreach ($columns as $column) { ?>
                    <?php foreach ($column as $city) { ?>
                        <div class="prmn-cmngr-cities__city none<?= $city['fias_id']; ?>" style="padding-left:80px;">
                            <a class="prmn-cmngr-cities__city-name <?= $city['fias_id']; ?>" data-id="<?= $city['fias_id']; ?>">
                                <img src="/image/country/<?= $city['image']; ?>.png" alt="">
                                 <?php if ($actual_language == "ru-ru"){ ?>
                                    <?= $city['country_ru']; ?>
                                 <? } elseif ($actual_language == "en-gb"){ ?>
                                    <?= $city['image']; ?>
                                 <? }else{ ?>
                                    <?= $city['country_ua']; ?>
                                <? } ?>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
				</div>
                <button type="button" class="close close__country" data-dismiss="modal" style="top:-82px;">
                    <span>&times;</span>
            </div>
        </div>
    </div>
</div>
