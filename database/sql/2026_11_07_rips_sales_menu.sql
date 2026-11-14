-- RIPS 관리 > 판매 관리 메뉴 신설 (2026-11-07)
-- 권한 ripsSales.view 신설, 발주 관리 조회 권한(ripsOrder.view)이 있는 관리자에게 복사
-- (최고관리자 level 1 은 권한과 무관하게 모두 사용 가능, 여러 번 실행해도 중복 등록되지 않음)

INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'ripsSales.view'
FROM admin_permissions p
WHERE p.permission_key = 'ripsOrder.view'
AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'ripsSales.view');
