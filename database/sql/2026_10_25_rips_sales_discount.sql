-- RIPS 판매 데이터 할인율 (2026-10-25)
-- 프로모션이 할인(discount)일 때만 저장, 그 외는 NULL

ALTER TABLE rips_sales
	ADD COLUMN discount_rate DECIMAL(5,2) NULL COMMENT '할인율(%, 프로모션 할인일 때)' AFTER promotion;
