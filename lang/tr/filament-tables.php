<?php

return [
    'columns' => [
        'text' => [
            'actions' => [
                'collapse_list' => 'Daha az göster (:count)',
                'expand_list' => 'Daha fazla göster (:count)',
            ],

            'more_list_items' => 've :count tane daha',
        ],
    ],

    'fields' => [
        'bulk_select_page' => [
            'label' => 'Toplu işlemler için sayfadaki tüm öğeleri seç/seçimi kaldır.',  
        ],

        'bulk_select_record' => [
            'label' => 'Toplu işlemler için :key öğesini seç/seçimi kaldır.',
        ],

        'bulk_select_group' => [
            'label' => 'Toplu işlemler için :title grubunu seç/seçimi kaldır.',
        ],

        'search' => [
            'label' => 'Ara',
            'placeholder' => 'Ara',
            'indicator' => 'Ara',
        ],
    ],

    'summary' => [
        'heading' => 'Özet',

        'subheadings' => [
            'all' => 'Tüm :label',
            'group' => ':group özeti',
            'page' => 'Bu sayfa',
        ],

        'summarizers' => [
            'average' => 'Ortalama',
            'count' => 'Sayı',
            'sum' => 'Toplam',
        ],
    ],

    'actions' => [
        'disable_reordering' => [
            'label' => 'Kayıtları yeniden sıralamayı bitir',
        ],

        'enable_reordering' => [
            'label' => 'Kayıtları yeniden sırala',
        ],

        'filter' => [
            'label' => 'Filtrele',
        ],

        'group' => [
            'label' => 'Grupla',
        ],

        'open_bulk_actions' => [
            'label' => 'Toplu işlemler',
        ],

        'toggle_columns' => [
            'label' => 'Sütunları göster/gizle',
        ],
    ],

    'empty' => [
        'heading' => ':model bulunamadı',

        'description' => 'Başlamak için bir :model oluşturun.',
    ],

    'filters' => [
        'actions' => [
            'apply' => [
                'label' => 'Filtreleri uygula',
            ],

            'remove' => [
                'label' => 'Filtreyi kaldır',
            ],

            'remove_all' => [
                'label' => 'Tüm filtreleri kaldır',
                'tooltip' => 'Tüm filtreleri kaldır',
            ],

            'reset' => [
                'label' => 'Sıfırla',
            ],
        ],

        'heading' => 'Filtreler',

        'indicator' => 'Aktif filtreler',

        'multi_select' => [
            'placeholder' => 'Tümü',
        ],

        'select' => [
            'placeholder' => 'Tümü',
        ],

        'trashed' => [
            'label' => 'Silinen kayıtlar',

            'only_trashed' => 'Sadece silinen kayıtlar',

            'with_trashed' => 'Silinen kayıtlarla birlikte',

            'without_trashed' => 'Silinen kayıtlar hariç',
        ],
    ],

    'grouping' => [
        'fields' => [
            'group' => [
                'label' => 'Gruplandır',
                'placeholder' => 'Gruplandır',
            ],

            'direction' => [
                'label' => 'Gruplama yönü',

                'options' => [
                    'asc' => 'Artan',
                    'desc' => 'Azalan',
                ],
            ],
        ],
    ],

    'reorder_indicator' => 'Kayıtları sürükle ve bırak ile sırala.',

    'selection_indicator' => [
        'selected_count' => '1 kayıt seçildi|:count kayıt seçildi',

        'actions' => [
            'select_all' => [
                'label' => 'Tüm :count kaydı seç',
            ],

            'deselect_all' => [
                'label' => 'Tüm seçimleri kaldır',
            ],
        ],
    ],

    'sorting' => [
        'fields' => [
            'column' => [
                'label' => 'Sırala',
            ],

            'direction' => [
                'label' => 'Sıralama yönü',

                'options' => [
                    'asc' => 'Artan',
                    'desc' => 'Azalan',
                ],
            ],
        ],
    ],
];
