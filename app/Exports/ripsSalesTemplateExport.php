<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * RIPS 판매 데이터 엑셀 업로드 양식
 * 컬럼 순서는 RipsController::setSalesUpload 와 동일해야 함
 */
class ripsSalesTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $productNames;

    public function __construct($productNames)
    {
        $this->productNames = collect($productNames)->values();
    }

    public function headings(): array
    {
        return ['구분(건별/일별합계/재고)', '판매일자(YYYY-MM-DD)', '판매시각(건별, HH:MM)', '상품명', '총 판매수량', '총 판매가', '잔여수량', '프로모션(N/할인/1+1/2+1)', '할인율(%, 할인일 때)', '판매매대(미입력/기본상품코너/계산대앞/할인/이벤트코너)'];
    }

    public function array(): array
    {
        $name1 = $this->productNames[0] ?? '등록된 상품명';
        $name2 = $this->productNames[1] ?? $name1;
        $today = now()->toDateString();

        return [
            ['일별합계', $today, '', $name1, 10, 120000, 35, 'N', '', '기본상품코너'],
            ['일별합계', $today, '', $name2, 5, 54000, 15, '할인', 10, '할인/이벤트코너'],
            ['건별', $today, '13:30', $name2, 2, 24000, 20, '1+1', '', '계산대앞'],
            ['재고', $today, '', $name1, '', '', 100, '', '', ''],
        ];
    }
}
