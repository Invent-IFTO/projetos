<?php

namespace App\Helpers;

class DataTablesHelper
{
    /**
     * Retorna configuração padrão do DataTables com tradução pt_BR
     */
    public static function getConfig($options = [])
    {
        $defaultConfig = [
            'language' => [
                'processing' => __('datatables.processing'),
                'search' => __('datatables.search'),
                'lengthMenu' => __('datatables.lengthMenu'),
                'info' => __('datatables.info'),
                'infoEmpty' => __('datatables.infoEmpty'),
                'infoFiltered' => __('datatables.infoFiltered'),
                'loadingRecords' => __('datatables.loadingRecords'),
                'zeroRecords' => __('datatables.zeroRecords'),
                'emptyTable' => __('datatables.emptyTable'),
                'paginate' => [
                    'first' => __('datatables.paginate.first'),
                    'previous' => __('datatables.paginate.previous'),
                    'next' => __('datatables.paginate.next'),
                    'last' => __('datatables.paginate.last')
                ],
                'aria' => [
                    'sortAscending' => __('datatables.aria.sortAscending'),
                    'sortDescending' => __('datatables.aria.sortDescending')
                ]
            ],
            'responsive' => true,
            'pageLength' => 10,
            'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
            'order' => [[0, 'desc']]
        ];

        return array_merge_recursive($defaultConfig, $options);
    }

    /**
     * Configuração com botões de exportação
     */
    public static function getConfigWithButtons($options = [])
    {
        $buttonsConfig = [
            'dom' => 'Bfrtip',
            'buttons' => [
                [
                    'extend' => 'copy',
                    'text' => __('datatables.buttons.copy')
                ],
                [
                    'extend' => 'csv',
                    'text' => __('datatables.buttons.csv')
                ],
                [
                    'extend' => 'excel',
                    'text' => __('datatables.buttons.excel')
                ],
                [
                    'extend' => 'pdf',
                    'text' => __('datatables.buttons.pdf')
                ],
                [
                    'extend' => 'print',
                    'text' => __('datatables.buttons.print')
                ]
            ]
        ];

        return self::getConfig(array_merge_recursive($buttonsConfig, $options));
    }
}