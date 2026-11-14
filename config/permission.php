<?php


return [
    '회사소개' => [
        '연혁' => [
            'history.view'      => '조회',
            'history.create'    => '등록',
            'history.update'    => '수정',
            'history.delete'    => '삭제',
            'history.excel'     => '엑셀',
        ],
    ],

    '사업관리' => [
        '포트폴리오' => [
            'portfolio.view'   => '조회',
            'portfolio.create' => '등록',
            'portfolio.update' => '수정',
            'portfolio.delete' => '삭제',
            'portfolio.excel'  => '엑셀',
            'portfolio.detail' => '상세',
        ],

        '파트너' => [
            'partner.view'   => '조회',
            'partner.create' => '등록',
            'partner.update' => '수정',
            'partner.delete' => '삭제',
            'partner.excel'  => '엑셀',
            'partner.detail' => '상세',
        ],

        '상품' => [
            'product.view'   => '조회',
            'product.create' => '등록',
            'product.update' => '수정',
            'product.delete' => '삭제',
            'product.excel'  => '엑셀',
            'product.detail' => '상세',
        ],

        '카테고리 관리' => [
            'formCategory.view'   => '조회',
            'formCategory.create' => '등록',
            'formCategory.update' => '수정',
            'formCategory.delete' => '삭제',
        ],

        '설문조사 관리' => [
            'checklist.view'    => '조회',
            'checklist.create'  => '등록',
            'checklist.update'  => '수정',
            'checklist.copy'    => '복사',
            'checklist.delete'  => '삭제',
        ],

        '설문조사 결과 관리' => [
            'checkresult.view'      => '설문결과',
        ],
    ],

    'RIPS 관리' => [
        'RIPS 업체' => [
            'rips.view'     => '조회',
            'rips.create'   => '업체 등록',
            'rips.update'   => '업체 정보·매핑·발주금액 수정',
        ],

        '판매 관리' => [
            'ripsSales.view'    => '조회',
        ],

        'AI 연동' => [
            'aiApi.view'    => '조회',
            'aiApi.update'  => '설정 · 인증키 재발급',
        ],

        '발주 관리' => [
            'ripsOrder.view'    => '조회',
            'ripsOrder.update'  => '상태·일정·발주정보 수정',
        ],

        // 권한 키는 기존(inquireRips.*) 그대로 사용, 메뉴만 RIPS 관리로 이동
        'RIPS 이용신청' => [
            'inquireRips.view'      => '조회',
            'inquireRips.save'      => '저장',
            'inquireRips.excel'     => '엑셀',
        ],

        '통관 이슈' => [
            'customsIssue.view'     => '조회',
            'customsIssue.create'   => '등록',
            'customsIssue.update'   => '수정',
            'customsIssue.delete'   => '삭제',
        ],
    ],

    '고객문의' => [
        '고객문의' => [
            'inquire.view'      => '조회',
            'inquire.save'      => '저장',
            'inquire.excel'     => '엑셀',
        ],

        '테스트베드 신청' => [
            'inquireTestbed.view'      => '조회',
            'inquireTestbed.save'      => '저장',
            'inquireTestbed.excel'     => '엑셀',
        ],

        'FAQ' => [
            'faq.view'      => '조회',
            'faq.create'    => '등록',
            'faq.update'    => '수정',
            'faq.delete'    => '삭제',
        ],
    ],

    '서비스관리' => [
        '관리자 관리' => [
            'user.view'     => '조회',
            'user.create'   => '등록',
            'user.update'   => '수정',
            'user.delete'   => '삭제',
        ],

        '관리자 로그' => [
            'adminLog.view' => '조회',
        ],
    
    ],    
];