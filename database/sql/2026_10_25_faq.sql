-- RIPS FAQ (2026-10-25)
-- 관리자 > 고객문의 > FAQ 에서 등록, 사용자 /ko/rips/check 에 표시

CREATE TABLE faq (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	question VARCHAR(500) NOT NULL COMMENT '질문(ko)',
	question_en VARCHAR(500) NOT NULL DEFAULT '' COMMENT '질문(en)',
	question_vn VARCHAR(500) NOT NULL DEFAULT '' COMMENT '질문(vn)',
	answer TEXT NOT NULL COMMENT '답변(ko)',
	answer_en TEXT NULL COMMENT '답변(en)',
	answer_vn TEXT NULL COMMENT '답변(vn)',
	orderby INT NOT NULL DEFAULT 0 COMMENT '정렬순서(작은 값이 위)',
	use_yn TINYINT(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '노출여부(0:미노출,1:노출)',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '작성일',
	created_by VARCHAR(50) DEFAULT NULL COMMENT '작성자',
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
	updated_by VARCHAR(50) DEFAULT NULL COMMENT '수정자',
	PRIMARY KEY (idx),
	KEY idx_faq_order (use_yn, orderby)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS FAQ';
