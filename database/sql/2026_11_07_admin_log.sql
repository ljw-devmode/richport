-- 관리자 작업 로그 (2026-11-07)
-- 관리자 화면에서 등록 / 수정 / 삭제 요청을 자동 기록 (App\Http\Middleware\AdminActionLog)
-- 서비스 관리 > 관리자 로그 에서 조회, 권한 adminLog.view 신설 (사용자 관리 조회 권한이 있는 관리자에게 복사)
-- DB 별로 1회만 실행

CREATE TABLE admin_log (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	admin_idx INT UNSIGNED NULL COMMENT '관리자(admin.idx)',
	admin_id VARCHAR(50) NULL COMMENT '관리자 아이디 (기록 시점)',
	admin_name VARCHAR(100) NULL COMMENT '관리자 이름 (기록 시점)',
	menu VARCHAR(50) NOT NULL COMMENT '메뉴 (예: 제품, 발주 관리)',
	action VARCHAR(10) NOT NULL COMMENT '작업 (create:등록, update:수정, delete:삭제)',
	action_detail VARCHAR(50) NULL COMMENT '세부 작업 (예: 상태 변경, 순서 변경)',
	target_idx INT UNSIGNED NULL COMMENT '대상 idx',
	target_name VARCHAR(255) NULL COMMENT '대상 이름 (기록 시점)',
	route_name VARCHAR(100) NULL COMMENT '라우트 이름',
	method VARCHAR(10) NOT NULL COMMENT 'HTTP 메서드',
	url VARCHAR(500) NOT NULL COMMENT '요청 주소',
	request_data MEDIUMTEXT NULL COMMENT '요청 내용 (JSON, 비밀번호 등은 가림)',
	result VARCHAR(10) NOT NULL DEFAULT 'success' COMMENT '결과 (success, fail)',
	result_message VARCHAR(500) NULL COMMENT '실패 메시지',
	ip VARCHAR(45) NULL COMMENT '접속 IP',
	user_agent VARCHAR(500) NULL COMMENT '브라우저',
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '작업일시',
	PRIMARY KEY (idx),
	KEY idx_admin_log_created (created_at),
	KEY idx_admin_log_admin (admin_idx, created_at),
	KEY idx_admin_log_menu (menu, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='관리자 작업 로그';

-- 권한 : 사용자 관리 조회 권한(user.view)이 있는 관리자에게 adminLog.view 복사 (최고관리자 level 1 은 권한과 무관하게 사용 가능)
INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'adminLog.view'
FROM admin_permissions p
WHERE p.permission_key = 'user.view'
AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'adminLog.view');
