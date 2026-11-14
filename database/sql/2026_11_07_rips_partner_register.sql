-- RIPS 관리 > RIPS 업체 등록 (2026-11-07)
-- 먼저 실행 : 2026_11_07_rips_profile_barcode_feedback.sql
-- DB 별로 1회만 실행
--
-- RIPS 업체 등록 페이지에서는 홈페이지 파트너용 값(유형, 카테고리)을 받지 않고,
-- 지역(시/구)은 베트남 업체만 필수로 받으므로 비워 둘 수 있게 변경
-- (홈페이지에는 home_recommend / partner_recommend = 1 인 파트너만 노출되며, RIPS 업체 등록 시 0 으로 저장)

ALTER TABLE partner
	MODIFY types_idx INT UNSIGNED NULL COMMENT '유형 (대형마트, 편의점 등)',
	MODIFY forms_idx INT UNSIGNED NULL COMMENT '카테고리',
	MODIFY cities_idx TINYINT UNSIGNED NULL COMMENT '시',
	MODIFY districts_idx INT UNSIGNED NULL COMMENT '구·군';

-- 권한 : RIPS 업체 등록 (rips.create) - RIPS 업체 수정 권한(rips.update)이 있는 관리자에게 복사
INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'rips.create'
FROM admin_permissions p
WHERE p.permission_key = 'rips.update'
AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'rips.create');
