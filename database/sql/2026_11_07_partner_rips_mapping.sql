-- 파트너 ↔ RIPS 업체 매핑 (2026-11-07)
-- 먼저 실행 : 2026_11_07_rips_partner_register.sql
-- DB 별로 1회만 실행
--
-- RIPS 업체는 RIPS 관리 > RIPS 업체 등록 에서만 만들고 (rips_only = 1, 사업관리 > 파트너 목록에는 표시 안 함)
-- 사업관리 > 파트너 에서는 'RIPS 업체 설정' 으로 등록된 RIPS 업체를 선택해 연결만 함 (rips_partner_idx, RIPS 업체 1곳은 파트너 1곳에만 연결)

ALTER TABLE partner
	ADD COLUMN rips_only TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'RIPS 업체 등록으로 만든 업체 (1 : 파트너 목록 · 선택에서 제외)' AFTER rips_use,
	ADD COLUMN rips_partner_idx INT UNSIGNED NULL COMMENT '연결된 RIPS 업체 (partner.idx)' AFTER rips_only,
	ADD KEY idx_partner_rips_partner (rips_partner_idx);

-- 기존 데이터 : 파트너 화면에서 RIPS 계정을 만든 업체는 자기 자신을 RIPS 업체로 연결
UPDATE partner SET rips_partner_idx = idx WHERE rips_id IS NOT NULL AND rips_id <> '' AND rips_partner_idx IS NULL;

-- 이번 작업 전에 RIPS 업체 등록 화면으로 만든 업체 (홈페이지 미노출 + 파트너 화면 유형 미입력) 는 RIPS 전용으로 표시
UPDATE partner SET rips_only = 1 WHERE rips_id IS NOT NULL AND rips_id <> '' AND types_idx IS NULL AND home_recommend = 0 AND partner_recommend = 0;
