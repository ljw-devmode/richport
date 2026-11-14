-- RIPS 관리 메뉴 분리 (2026-10-30)
-- 사업관리 > 파트너 상세의 RIPS 매핑 → RIPS 관리 > RIPS 업체 로 이동, 권한 rips.view / rips.update 신설
-- 고객문의 > RIPS 이용신청 → RIPS 관리 > RIPS 이용신청 (권한 키 inquireRips.* 는 그대로)
--
-- 기존에 파트너 권한으로 매핑하던 관리자가 계속 사용할 수 있도록 권한 복사
--   partner.view   → rips.view
--   partner.update → rips.update
-- (최고관리자 level 1 은 권한과 무관하게 모두 사용 가능, 여러 번 실행해도 중복 등록되지 않음)

INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'rips.view'
FROM admin_permissions p
WHERE p.permission_key = 'partner.view'
AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'rips.view');

INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'rips.update'
FROM admin_permissions p
WHERE p.permission_key = 'partner.update'
AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'rips.update');
