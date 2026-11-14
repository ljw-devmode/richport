-- RIPS 서비스 (2026-10-25)

-- 1. 파트너 RIPS 계정 / 매장명
ALTER TABLE partner
	ADD COLUMN store_name VARCHAR(200) NULL COMMENT '매장명' AFTER partner_name_vn,
	ADD COLUMN rips_use TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'RIPS 사용여부(0:미사용,1:사용)' AFTER partner_recommend,
	ADD COLUMN rips_id VARCHAR(50) NULL COMMENT 'RIPS 아이디' AFTER rips_use,
	ADD COLUMN rips_pw VARCHAR(255) NULL COMMENT 'RIPS 비밀번호(bcrypt)' AFTER rips_id,
	ADD UNIQUE KEY uk_partner_rips_id (rips_id);

-- 2. 상품 카테고리 (최대 4depth)
CREATE TABLE rips_category (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	partner_idx INT UNSIGNED NOT NULL COMMENT '파트너(partner.idx)',
	parent_idx INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '상위 카테고리(0:최상위)',
	depth TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '깊이(1~4)',
	name VARCHAR(100) NOT NULL COMMENT '카테고리명',
	orderby INT NOT NULL DEFAULT 0 COMMENT '정렬순서',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (idx),
	KEY idx_partner_parent (partner_idx, parent_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 상품 카테고리';

-- 3. 상품
CREATE TABLE rips_product (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	partner_idx INT UNSIGNED NOT NULL COMMENT '파트너(partner.idx)',
	category_idx INT UNSIGNED NOT NULL COMMENT '카테고리(rips_category.idx)',
	company_name VARCHAR(200) NOT NULL COMMENT '업체명(제조/공급사)',
	product_name VARCHAR(200) NOT NULL COMMENT '상품명',
	unit_price BIGINT NOT NULL DEFAULT 0 COMMENT '발주 단가',
	stock_qty INT NOT NULL DEFAULT 0 COMMENT '잔여수량',
	lead_days INT UNSIGNED NULL COMMENT '예약 입고 소요기간(일)',
	expect_out_date DATE NULL COMMENT '잔여소진 예상일(AI)',
	recommend_order_date DATE NULL COMMENT '추천 발주일(AI)',
	recommend_qty INT UNSIGNED NULL COMMENT '추천수량(AI)',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	created_by VARCHAR(50) DEFAULT NULL,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	updated_by VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (idx),
	KEY idx_partner_category (partner_idx, category_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 상품';

-- 4. 판매 데이터
CREATE TABLE rips_sales (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	partner_idx INT UNSIGNED NOT NULL COMMENT '파트너(partner.idx)',
	product_idx INT UNSIGNED NOT NULL COMMENT '상품(rips_product.idx)',
	category_idx INT UNSIGNED NOT NULL COMMENT '카테고리(rips_category.idx)',
	input_type VARCHAR(10) NOT NULL DEFAULT 'daily' COMMENT '구분(case:건별, daily:일별합계)',
	source VARCHAR(10) NOT NULL DEFAULT 'manual' COMMENT '등록경로(manual, excel, api)',
	sales_date DATE NOT NULL COMMENT '판매일자',
	sales_time TIME NULL COMMENT '판매시각(건별)',
	qty INT NOT NULL DEFAULT 0 COMMENT '총 판매수량',
	total_amount BIGINT NOT NULL DEFAULT 0 COMMENT '총 판매가',
	unit_price BIGINT NOT NULL DEFAULT 0 COMMENT '상품별 가격(총 판매가/총 수량, 반올림)',
	stock_qty INT NULL COMMENT '잔여수량',
	promotion VARCHAR(10) NOT NULL DEFAULT 'N' COMMENT '프로모션(N, discount, 1+1, 2+1)',
	display_place VARCHAR(10) NOT NULL DEFAULT '' COMMENT '판매매대(공백:미입력, basic, counter, event)',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	created_by VARCHAR(50) DEFAULT NULL,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	updated_by VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (idx),
	KEY idx_partner_date (partner_idx, sales_date),
	KEY idx_product_date (product_idx, sales_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 판매 데이터';

-- 5. 판매 데이터 API 연동 설정
CREATE TABLE rips_sales_setting (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	partner_idx INT UNSIGNED NOT NULL COMMENT '파트너(partner.idx)',
	api_use TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'API 연동 사용(0:미사용,1:사용)',
	api_key VARCHAR(64) NULL COMMENT 'API 인증키',
	last_received_at DATETIME NULL COMMENT '마지막 수신일시',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (idx),
	UNIQUE KEY uk_partner (partner_idx),
	UNIQUE KEY uk_api_key (api_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 판매 데이터 연동 설정';

-- 6. 발주
CREATE TABLE rips_order (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	partner_idx INT UNSIGNED NOT NULL COMMENT '파트너(partner.idx)',
	order_no VARCHAR(30) NULL COMMENT '발주번호',
	order_date DATETIME NOT NULL COMMENT '발주일',
	orderer_dept VARCHAR(100) NULL COMMENT '발주자 부서',
	orderer_name VARCHAR(50) NOT NULL COMMENT '발주자명',
	orderer_tel VARCHAR(30) NOT NULL COMMENT '연락처(전화)',
	orderer_email VARCHAR(100) NULL COMMENT '연락처(이메일)',
	total_items INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '총 상품 가지수',
	total_qty INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '총 수량',
	total_amount BIGINT NOT NULL DEFAULT 0 COMMENT '발주총액',
	status VARCHAR(20) NOT NULL DEFAULT 'ordered' COMMENT '상태(ordered, preparing, shipping, customs, received, canceled)',
	expected_date DATE NULL COMMENT '예상입고일',
	sign_path VARCHAR(255) NULL COMMENT '서명 이미지 경로',
	canceled_at DATETIME NULL COMMENT '취소일시',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	created_by VARCHAR(50) DEFAULT NULL,
	updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	updated_by VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (idx),
	KEY idx_partner_date (partner_idx, order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 발주';

-- 7. 발주 상품
CREATE TABLE rips_order_item (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	order_idx INT UNSIGNED NOT NULL COMMENT '발주(rips_order.idx)',
	product_idx INT UNSIGNED NOT NULL COMMENT '상품(rips_product.idx)',
	category_idx INT UNSIGNED NOT NULL COMMENT '카테고리(rips_category.idx)',
	company_name VARCHAR(200) NOT NULL COMMENT '업체명(발주 시점)',
	product_name VARCHAR(200) NOT NULL COMMENT '상품명(발주 시점)',
	unit_price BIGINT NOT NULL DEFAULT 0 COMMENT '단가(발주 시점)',
	qty INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '수량',
	amount BIGINT NOT NULL DEFAULT 0 COMMENT '발주금액(수량*단가)',
	PRIMARY KEY (idx),
	KEY idx_order (order_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 발주 상품';
