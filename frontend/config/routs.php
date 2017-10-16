<?php

return [
    //--------------------- event detail page
    'healthcare-events' => 'event/detail',
    'healthcare-events/<category>' => 'event/detail',
    'healthcare-events/<category>/<services>' => 'event/detail',
    // end event detail page
    'provider/<id:[a-zA-Z0-9_ -]+>' => 'provider/',
    'provider/<id:[a-zA-Z0-9_ -]+>/events/' => 'provider/events/',
];
