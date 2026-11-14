-- 테스트베드 신청 구분 (2026-11-06)
-- RIPS 업체 화면(리치포트 상품 > 테스트베드 신청)에서 신청하면 신청 업체(partner.idx)를 저장
-- partner_idx 가 NULL 이면 홈페이지 신청, 값이 있으면 RIPS 업체 신청

ALTER TABLE testbed_inquire
	ADD COLUMN partner_idx INT UNSIGNED NULL DEFAULT NULL COMMENT '신청 RIPS 업체(partner.idx), NULL=홈페이지 신청' AFTER types_idx,
	ADD INDEX idx_testbed_inquire_partner (partner_idx);
