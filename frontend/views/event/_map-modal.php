
<?php

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\helpers\BaseUrl;
?>
<style>
    .modal{
        width: 100% !important;
    }
    .gm-style-mtc {
        display: none !important;
    }
</style>
<?php $img_url = BaseUrl::base() . '/images/'; ?>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg"> 
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div style="margin-bottom:5px;margin-right:10px">
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                </div>
                <div>
                    <?php
                    $coord = new LatLng(['lat' => 32.154377, 'lng' => 74.184227]);
                    $map = new Map([
                        'center' => $coord,
                        'zoom' => 8,
                        'width' => '100%',
                        'height' => '400',
//                        'scrollwheel' => false,
                    ]);
                    $map->setName('fullmap');
                    ?>
                    <?php foreach ($events as $event) {  ?>
                        <?php foreach ($event['locations'] as $location) { ?>
                            <?php
                            $long_lat = $location['geometry']['coordinates'];
                            $coord = new LatLng(['lng' => $long_lat[0], 'lat' => $long_lat[1]]);
                            $marker = new Marker([
                                'position' => $coord,
                                'title' => $event['title'],
                                'animation' => 'google.maps.Animation.DROP',
                                'visible' => 'true',
                                'icon' => $img_url . 'custom-marker.png',
                            ]);
                            $content = "<a class='marker-info' href='" .BaseUrl::base() . "/event/detail?eid=" .(string)$event['_id']. "'>" . $event['title'] . "</a>";
                            $marker->attachInfoWindow(
                                    new InfoWindow(['content' => $content])
                            );
                            $map->addOverlay($marker);
                            ?>
                        <?php } ?>
                    <?php } ?>
                    <?php
                    $map->center = $map->getMarkersCenterCoordinates();
                    $map->zoom = $map->getMarkersFittingZoom() - 1;
                    ?>
                    <?php echo $map->display(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
