<?php

// REGISTER PLUGIN
class MMP_Register_Plugin
{

    public function __construct()
    {

        /** HANDLE IN ACTIVATION AND DEACTIVATION */
        add_action("activated_plugin", array($this, 'ACTIVATION'));
        add_action("deactivated_plugin", array($this, 'DE_ACTIVATION'));

    }

    public function ACTIVATION()
    {

        MMP_Database::Table('mmp_artist',
            [
                [
                    'name'           => 'artist_id',
                    'key'            => true,
                    'type'           => 'int(11)',
                    'not_null'       => true,
                    'auto_increment' => true
                ],
                [
                    'name'     => 'name',
                    'type'     => 'varchar(150)',
                    'not_null' => true,
                ],
                [
                    'name'     => 'date',
                    'type'     => 'varchar(20)',
                    'not_null' => true,
                ],
                [
                    'name' => 'thumb_image',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'back_image',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'gender',
                    'type' => 'varchar(150)',
                ],
                [
                    'name' => 'country',
                    'type' => 'varchar(150)',
                ],
                [
                    'name' => 'date_birth',
                    'type' => 'varchar(20)',
                ],
                [
                    'name' => 'year_active',
                    'type' => 'varchar(20)',
                ],
                [
                    'name'     => 'status',
                    'type'     => 'tinyint(4)',
                    'not_null' => true,
                ],
                [
                    'name' => 'album_id',
                    'type' => 'int(11)',
                ]
            ]
        );
        MMP_Database::Table('mmp_genres',
            [
                [
                    'name'           => 'genres_id',
                    'key'            => true,
                    'type'           => 'int(11)',
                    'not_null'       => true,
                    'auto_increment' => true
                ],
                [
                    'name'     => 'name',
                    'type'     => 'varchar(150)',
                    'not_null' => true,
                ],
                [
                    'name' => 'thumb_image',
                    'type' => 'text',
                ]
            ]
        );
        MMP_Database::Table('mmp_genres_relation',
            [
                [
                    'name'           => 'genres_relation_id',
                    'key'            => true,
                    'type'           => 'int(11)',
                    'not_null'       => true,
                    'auto_increment' => true
                ],
                [
                    'name'     => 'artist_id',
                    'type'     => 'int(11)',
                    'not_null' => true,
                ],
                [
                    'name'     => 'genres_id',
                    'type'     => 'int(11)',
                    'not_null' => true,
                ]
            ]
        );
        MMP_Database::Table('mmp_genres_music_relation',
            [
                [
                    'name'           => 'genres_relation_id',
                    'key'            => true,
                    'type'           => 'int(11)',
                    'not_null'       => true,
                    'auto_increment' => true
                ],
                [
                    'name'     => 'music_id',
                    'type'     => 'int(11)',
                    'not_null' => true,
                ],
                [
                    'name'     => 'genres_id',
                    'type'     => 'int(11)',
                    'not_null' => true,
                ]
            ]
        );
        MMP_Database::Table('mmp_music',
            [
                [
                    'name'           => 'music_id',
                    'key'            => true,
                    'type'           => 'int(11)',
                    'not_null'       => true,
                    'auto_increment' => true
                ],
                [
                    'name'     => 'name',
                    'type'     => 'varchar(150)',
                    'not_null' => true,
                ],
                [
                    'name' => 'description',
                    'type' => 'longtext',
                ],
                [
                    'name'     => 'date',
                    'type'     => 'varchar(20)',
                    'not_null' => true,
                ],
                [
                    'name' => 'back_image',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'thumb_image',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'music',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'product_id',
                    'type' => 'int(11)',
                ],
                [
                    'name' => 'artist',
                    'type' => 'text',
                ],
                [
                    'name' => 'genres_id',
                    'type' => 'int(11)',
                ],
                [
                    'name'    => 'play',
                    'type'    => 'int(11)',
                    'default' => 0,
                ],
                [
                    'name'     => 'status',
                    'type'     => 'tinyint(4)',
                    'not_null' => true,
                ]
            ]
        );

    }

    public function DE_ACTIVATION()
    {
        // EMPTY
    }

}

new MMP_Register_Plugin();