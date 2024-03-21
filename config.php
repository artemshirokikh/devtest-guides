<?php

return array(
    'name' => 'Guide System',
    'version' => '0.0.1',

    'persistence' => [
        'class' => 'Services\Persistence\SerializablePersistence',
        'connection' => 'serialized.data',

        'guides' => [
            // todo
        ],
    ],
);
