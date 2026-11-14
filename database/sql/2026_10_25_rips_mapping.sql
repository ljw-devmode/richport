-- RIPS 업체 카테고리/상품 ↔ 리치포트 카테고리/상품 매핑, 발주금액 (2026-10-25)
-- 발주 단가 적용 순서 : 개별금액(rips_product.custom_price) → 매핑된 리치포트 상품의 기본 발주금액(product.order_price) → 업체 입력 단가(rips_product.unit_price)

-- 1. 리치포트 상품 기본 발주금액
ALTER TABLE product
	ADD COLUMN order_price BIGINT NULL COMMENT '발주금액(기본)' AFTER product_name_vn;

-- 2. 업체 카테고리 → 리치포트 카테고리(form_types)
ALTER TABLE rips_category
	ADD COLUMN forms_idx INT UNSIGNED NULL COMMENT '리치포트 카테고리 매핑(form_types.idx)' AFTER name;

-- 3. 업체 상품 → 리치포트 상품, 발주금액 선택
ALTER TABLE rips_product
	ADD COLUMN product_idx INT UNSIGNED NULL COMMENT '리치포트 상품 매핑(product.idx)' AFTER category_idx,
	ADD COLUMN price_type VARCHAR(10) NOT NULL DEFAULT 'default' COMMENT '발주금액(default:기본, custom:개별)' AFTER unit_price,
	ADD COLUMN custom_price BIGINT NULL COMMENT '개별 발주금액' AFTER price_type,
	ADD KEY idx_rips_product_product (product_idx);
