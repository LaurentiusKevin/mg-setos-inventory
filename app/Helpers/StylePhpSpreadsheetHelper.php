<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StylePhpSpreadsheetHelper
{
    public static function HeaderStyle()
    {
        return [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ];
    }

    public static function tHead()
    {
        return [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ];
    }

    public static function tBody()
    {
        return [
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ];
    }

    public static function center()
    {
        return [
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];
    }

    public static function bold()
    {
        return [
            'font' => [
                'wrapText' => true,
                'bold' => true,
            ]
        ];
    }

    public static function bgSuccess()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '1BC5BD',
                ]
            ],
            'font' => [
                'color' => [
                    'argb' => 'FFFFFF',
                ]
            ]
        ];
    }

    public static function bgWarning()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFA800',
                ]
            ],
            'font' => [
                'color' => [
                    'argb' => 'FFFFFF',
                ]
            ]
        ];
    }

    public static function bgYellow()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFF00',
                ]
            ],
            'font' => [
                'wrapText' => true,
                'bold' => true,
            ]
        ];
    }

    public static function bgLightBlue()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'D9E1F2',
                ]
            ],
            'font' => [
                'wrapText' => true,
                'bold' => true,
            ]
        ];
    }

    public static function bgLightOrange()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FCE4D6',
                ]
            ],
            'font' => [
                'wrapText' => true,
                'bold' => true,
            ]
        ];
    }

    public static function bgDanger()
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'F64E60',
                ]
            ],
            'font' => [
                'color' => [
                    'argb' => 'FFFFFF',
                ]
            ]
        ];
    }
}
