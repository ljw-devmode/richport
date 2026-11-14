<?php

namespace App\Models\Concerns;

/**
 * 다국어 컬럼 (예: partner_name / partner_name_en / partner_name_vn)
 * - tr('partner_name') : 현재 언어(app()->getLocale(): ko|en|vn)의 값, 비어 있으면 한국어 값
 * - 한국어 컬럼명이 다른 경우 모델에 $localizedBase 지정 (예: ['name' => 'name_kr'])
 */
trait HasLocalizedFields
{
    public function tr(string $field)
    {
        $lang = app()->getLocale();
        $base = property_exists($this, 'localizedBase') ? ($this->localizedBase[$field] ?? $field) : $field;

        if (in_array($lang, ['en', 'vn'], true)) {
            $value = $this->getAttribute($field.'_'.$lang);
            if ($value !== null && trim((string) $value) !== '') {
                return $value;
            }
        }

        return $this->getAttribute($base);
    }
}
