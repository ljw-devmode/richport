-- RIPS 발주 관리 (2026-10-30)
-- 관리자 > RIPS 관리 > 발주 관리 : 진행상태(상품준비 > 물류 > 통관 > 입고) / 일정 / 리치포트 발주 정보
-- DB 별로 1회만 실행 (적용 여부 확인: SHOW COLUMNS FROM rips_order LIKE 'received_date';)

-- 발주 : 물류 / 통관 / 입고 일정, 관리 메모
ALTER TABLE rips_order
	ADD COLUMN logistics_start DATE NULL COMMENT '물류 시작일' AFTER expected_date,
	ADD COLUMN logistics_end DATE NULL COMMENT '물류 종료일' AFTER logistics_start,
	ADD COLUMN customs_start DATE NULL COMMENT '통관 시작일' AFTER logistics_end,
	ADD COLUMN customs_end DATE NULL COMMENT '통관 종료일' AFTER customs_start,
	ADD COLUMN received_date DATE NULL COMMENT '입고일' AFTER customs_end,
	ADD COLUMN admin_memo TEXT NULL COMMENT '관리자 메모' AFTER received_date,
	ADD KEY idx_rips_order_received (received_date);

-- 발주 상품 : 리치포트가 공급 업체에 발주한 정보
ALTER TABLE rips_order_item
	ADD COLUMN rp_product_idx INT UNSIGNED NULL COMMENT '리치포트 상품(product.idx, 발주 정보 저장 시점 매핑)' AFTER amount,
	ADD COLUMN supplier_idx INT UNSIGNED NULL COMMENT '리치포트 발주 업체(partner.idx)' AFTER rp_product_idx,
	ADD COLUMN purchase_date DATE NULL COMMENT '리치포트 발주일' AFTER supplier_idx,
	ADD COLUMN purchase_qty INT UNSIGNED NULL COMMENT '리치포트 발주 수량' AFTER purchase_date,
	ADD COLUMN purchase_amount BIGINT NULL COMMENT '리치포트 발주 금액' AFTER purchase_qty,
	ADD KEY idx_rips_order_item_supplier (supplier_idx);

-- 발주 상태 변경 이력
CREATE TABLE rips_order_log (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	order_idx INT UNSIGNED NOT NULL COMMENT '발주(rips_order.idx)',
	status VARCHAR(20) NOT NULL COMMENT '변경된 상태',
	prev_status VARCHAR(20) NULL COMMENT '이전 상태',
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '변경일시',
	created_by VARCHAR(50) DEFAULT NULL COMMENT '변경자',
	PRIMARY KEY (idx),
	KEY idx_rips_order_log_order (order_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS 발주 상태 이력';
