<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use jeemce\helpers\ArrayHelper;
use jeemce\helpers\FileHelper;
use jeemce\helpers\StringHelper;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

trait ExcelTrait
{
	public array $fields;
	public array $labels;

	public $path;
	public $name = 'excel';
	public bool $debug = false;

	public array $styles = [
		'cell-border-thin',
	];
	public array $headerStyles = [
		'text-bold',
		'cell-autowidth',
		'cell-bg' => 'D3D3D3',
		'cell-fg' => '000000',
	];
	public array $contentStyles = [];

	public array $defaultStyles;

	public Spreadsheet $spreadsheet;
	public Worksheet $worksheet;

	public function excel(Request $request, $query, $options)
	{
		$this->input($request);

		$options = ArrayHelper::assoc($options, function($option) {
			return StringHelper::headline($option);
		});

		$this->fields = array_keys($options);
		$this->labels = $options;

		$styles = $request->get('styles', $this->styles);
		$this->styles = $styles;

		$headerStyles = $request->get('headerStyles', $this->headerStyles);
		$this->headerStyles = $headerStyles;

		$contentStyles = $request->get('contentStyles', $this->contentStyles);
		$this->contentStyles = $contentStyles;

		$no = 1;
		if (is_array($query)) {
			$models = $query;
			$models = array_map(function($model) use (&$no) {
				$model['No'] = $no++;
				return $model;
			}, $models);
		} else {
			$models = $query->get();
			$models = $models->map(function($model) use (&$no) {
				$model->No = $no++;
				return $model;
			});
		}

		return $this->render($models);
	}

	public function render($models)
	{
		$spreadsheet = new Spreadsheet;
		$worksheet = $spreadsheet->getActiveSheet();

		$labels = $this->labels;
		$fields = $this->fields;
		$fields = array_merge(['A' => 'No'], $fields);

		$yMin = $y = 1;
		$xMin = $x = 'A';

		foreach ($fields as $field) {
			$data = $labels[$field] ?? $field;
			$cell = $worksheet->getCell("{$x}{$y}");
			$cell->setValue($data);
			$this->cellApplyStyles($cell, $data, $this->styles);
			$this->cellApplyStyles($cell, $data, ArrayHelper::except($this->headerStyles, $fields));
			$this->cellApplyStyles($cell, $data, $this->headerStyles[$field] ?? []);
			$x++;
		}
		$y++;

		foreach ($models as $model) {
			$x = $xMin;
			foreach ($fields as $field) {
				$data = ArrayHelper::get($model, $field);
				$cell = $worksheet->getCell("{$x}{$y}");
				$cell->setValue($data);
				$this->cellApplyStyles($cell, $data, $this->styles);
				$this->cellApplyStyles($cell, $data, ArrayHelper::except($this->contentStyles, $fields));
				$this->cellApplyStyles($cell, $data, $this->contentStyles[$field] ?? []);
				$x++;
			}
			$y++;
		}

		// set width kolom a
		$worksheet->getColumnDimension('A')->setWidth(5);

		$this->output($spreadsheet);
	}

	public function input(Request $request)
	{
		$this->path = storage_path('app/public/excel-export');
		FileHelper::createDirectory($this->path);

		$this->name = $request->get('name', $this->name);
		$this->debug = (bool) $request->get('debug', $this->debug);

		$preset = (require __DIR__ . '/../../vendor/jeemce/laravel/config/export-excel-styles.php');
		$custom = config_path('export-excel-styles.php');
		if (file_exists($custom) && is_readable($custom)) {
			$custom = (require $custom);
			$preset = array_merge($preset, $custom);
		}
		$this->defaultStyles = $preset;
	}

	public function cellApplyStyles(Cell $cell, $data, $styles)
	{
		$defaults = $this->defaultStyles;
		foreach ($styles as $k => $v) {
			if (is_numeric($k)) {
				if (isset($defaults[$v])) {
					$defaults[$v]($cell, $data);
				}
			} else {
				if (isset($defaults[$k])) {
					if (is_array($v)) {
						$v[] = $cell;
						array_push($v, $cell);
						array_push($v, $data);
						call_user_func_array($defaults[$k], $v);
					} else {
						call_user_func($defaults[$k], $v, $cell, $data);
					}
				}
			}
		}
	}

	public function output(Spreadsheet $spreadsheet)
	{
		$writer = new WriterXlsx($spreadsheet);
		if ($this->debug) {
			$writer->save($this->path . '/' . date('YmdHis') . '.xlsx');
		} else {
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="' . $this->name . '.xlsx"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
		exit;
	}
}
