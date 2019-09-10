<?php

namespace App\Domain\Reports\Formatters;

class ExcelFormats
{
	const BASE = [
		'font' => [
			'bold' => false,
			'color' => [
				'rgb' => '000000',
			],
			'size' => 11,
		],
		'alignment' => [
			'horizontal' => 'left',
		],
		'numberformat' => [
			'code' => '@'
		],
	];

	const BORDER = [
		'inherit' => 'base',
		'borders' => [
			'allborders' => [
				'style' => 'thin',
				'color' => [
					'rgb' => 'DDDDDD',
				],
			],
		],
	];

	const DATE = [
		'inherit' => 'border',
		'alignment' => [
			'horizontal' => 'left',
		],
		'numberformat' => [
			'code' => 'yyyy/mm/dd',
		],
	];

	const TIME = [
		'inherit' => 'border',
		'alignment' => [
			'horizontal' => 'left',
		],
		'numberformat' => [
			'code' => 'h:mm AM/PM',
		],
	];

	const DATETIME = [
		'inherit' => 'border',
		'alignment' => [
			'horizontal' => 'left',
		],
		'numberformat' => [
			'code' => 'yyyy/mm/dd h:mm AM/PM',
		],
	];

	const NUMERIC = [
		'inherit' => 'border',
		'numberformat' => [
			'code' => '#,##0',
		],
		'alignment' => [
			'horizontal' => 'right',
		],
	];

	const DECIMAL = [
		'inherit' => 'border',
		'numberformat' => [
			'code' => '#,##0.00',
		],
		'alignment' => [
			'horizontal' => 'right',
		],
	];

	const CURRENCY = [
		'inherit' => 'border',
		'numberformat' => [
			'code' => '_-$* #,##0.00_-;-$* #,##0.00_-;_-$* "-"??_-;_-@_-',
		],
	];

	const TITLE = [
		'inherit' => 'base',
		'font' => [
			'bold' => true,
			'size' => 15,
			'color' => [
				'rgb' => '1F497D',
			],
		],
		'borders' => [
			'bottom' => [
				'style' => 'medium',
				'color' => [
					'rgb' => '4f81bd',
				],
			],
		],
	];

	const SUBTITLE = [
		'inherit' => 'base',
		'font' => [
			'bold' => true,
			'size' => 12,
			'color' => [
				'rgb' => '000000',
			],
		],
		'borders' => [
			'allborders' => [
				'style' => 'none',
			],
		],
	];

	const HEADING = [
		'inherit' => 'base',
		'font' => [
			'bold' => true,
			'color' => [
				'rgb' => '7f7f7f',
			],
			'size' => 10,
		],
		'alignment' => [
			'vertical' => 'center',
		],
	];

	const LABEL = [
		'inherit' => 'base',
		'font' => [
			'bold' => true,
			'color' => [
				'rgb' => '1F497D',
			],
		],
		'alignment' => [
			'vertical' => 'top',
			'horizontal' => 'right',
			'indent' => 1,
		],
	];

	protected static $formats = [
		'base' => self::BASE,
		'border' => self::BORDER,
		'date' => self::DATE,
		'time' => self::TIME,
		'datetime' => self::DATETIME,
		'numeric' => self::NUMERIC,
		'decimal' => self::DECIMAL,
		'currency' => self::CURRENCY,
		'title' => self::TITLE,
		'subtitle' => self::SUBTITLE,
		'heading' => self::HEADING,
		'label' => self::LABEL,
	];

	private static $initialized = false;

	public static function get($format_name)
	{
		self::initialize();
		if (isset(static::$formats[$format_name])) {
			return static::$formats[$format_name];
		}
	}

	public static function all()
	{
		self::initialize();
		return static::$formats;
	}

	private static function initialize()
	{
		if (self::$initialized) {
			return;
		}

		foreach (['border', 'numeric', 'decimal', 'currency'] as $inherited_format) {
			// Totals
			static::$formats['total_'.$inherited_format] = [
				'inherit' => $inherited_format,
				'font' => [
					'bold' => true,
				],
				'borders' => [
					'top' => [
						'style' => 'double',
						'color' => [
							'rgb' => 'DDDDDD',
						],
					],
				],
			];

			// Alts
			static::$formats[$inherited_format.'_alt'] = [
				'inherit' => $inherited_format,
				'fill' => [
					'type' => 'solid',
					'color' => [
						'rgb' => 'D7D7D7',
					],
				],
			];

			// Mutes
			static::$formats[$inherited_format.'_muted'] = [
				'inherit' => $inherited_format,
				'fill' => [
					'type' => 'solid',
					'color' => [
						'rgb' => 'F0F0F0',
					],
				],
			];

			// Total Alts
			static::$formats['total_'.$inherited_format.'_alt'] = [
				'inherit' => $inherited_format,
				'font' => [
					'bold' => true
				],
				'fill' => [
					'type' => 'solid',
					'color' => [
						'rgb' => 'D7D7D7',
					],
				],
				'borders' => [
					'top' => [
						'style' => 'double',
						'color' => [
							'rgb' => 'DDDDDD',
						],
					],
				],
			];
		}

		// Alignments
		foreach (['heading'] as $inherited_format) {
			foreach (['left', 'center', 'right'] as $alignment) {
				static::$formats[$inherited_format.'_'.$alignment] = [
					'inherit' => $inherited_format,
					'alignment' => [
						'horizontal' => $alignment,
					]
				];
			}
		}

		// Apply inheritance - Do not edit
		foreach (static::$formats as $name => &$style) {
			$temp = [];

			if (array_key_exists('inherit', $style)) {
				if (! is_array($style['inherit'])) {
					$style['inherit'] = explode(',', $style['inherit']);
				}

				foreach ($style['inherit'] as $inherit) {
					$temp = array_replace_recursive($temp, static::$formats[$inherit]);
				}

				unset($style['inherit']);
			}

			$style = array_replace_recursive($temp, $style);
		}

		self::$initialized = true;
	}
}