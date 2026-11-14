-- AI 재고 예측 연동 API (2026-11-07)
-- AI 서버가 RICHPORT 데이터를 가져가 예측하고(GET), 예측 결과를 기준일별로 보내면(POST) 저장해 RIPS 화면에 반영
-- 관리자 > RIPS 관리 > AI 연동 에서 인증키 · 사용 방식 관리
-- DB 별로 1회만 실행

-- 1. 예측 결과 (상품 · 기준일별 1행, 같은 상품 · 기준일로 다시 보내면 덮어씀)
CREATE TABLE rips_forecast (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
	partner_idx INT UNSIGNED NOT NULL COMMENT 'RIPS 업체 (partner.idx)',
	product_idx INT UNSIGNED NOT NULL COMMENT '업체 상품 (rips_product.idx)',
	base_date DATE NOT NULL COMMENT '예측 기준일 (이 날짜 기준으로 계산한 예측)',
	expect_out_date DATE NULL COMMENT '잔여소진 예상일',
	recommend_order_date DATE NULL COMMENT '추천 발주일',
	recommend_qty INT UNSIGNED NULL COMMENT '추천 발주수량',
	daily_avg DECIMAL(12,2) NULL COMMENT '예측 일평균 판매량 (참고)',
	confidence DECIMAL(5,4) NULL COMMENT '예측 신뢰도 (0~1, 참고)',
	model_version VARCHAR(50) NOT NULL COMMENT 'AI 모델 버전',
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '최초 수신일시',
	updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '최종 수신일시',
	PRIMARY KEY (idx),
	UNIQUE KEY uq_rips_forecast_product_date (product_idx, base_date),
	KEY idx_rips_forecast_partner_date (partner_idx, base_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='RIPS AI 재고 예측 결과';

-- 2. AI 연동 설정 (1행)
CREATE TABLE ai_api_setting (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
	api_use TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'API 사용여부 (0: 미사용, 1: 사용)',
	api_key VARCHAR(64) NULL COMMENT 'API 인증키 (Bearer 토큰)',
	forecast_mode VARCHAR(10) NOT NULL DEFAULT 'auto' COMMENT '화면 예측값 (auto: AI 값 우선·없으면 계산식, ai: AI 값만, calc: 계산식만)',
	valid_days INT UNSIGNED NOT NULL DEFAULT 3 COMMENT 'AI 예측값 유효기간 (기준일부터 일수, 지나면 auto 는 계산식 사용)',
	allowed_ips VARCHAR(500) NULL COMMENT '허용 IP (쉼표 구분, 비우면 제한 없음)',
	last_request_at DATETIME NULL COMMENT '마지막 API 요청일시',
	last_push_at DATETIME NULL COMMENT '마지막 예측 결과 수신일시',
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일시',
	updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일시',
	updated_by VARCHAR(50) NULL COMMENT '수정자',
	PRIMARY KEY (idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AI 연동 설정';

INSERT INTO ai_api_setting (api_use, forecast_mode, valid_days) VALUES (0, 'auto', 3);

-- 3. AI API 요청 로그
CREATE TABLE ai_api_log (
	idx INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '고유번호(PK)',
	method VARCHAR(10) NOT NULL COMMENT 'HTTP 메서드',
	endpoint VARCHAR(200) NOT NULL COMMENT '요청 주소',
	status_code SMALLINT UNSIGNED NOT NULL COMMENT 'HTTP 응답 코드',
	result VARCHAR(10) NOT NULL COMMENT '결과 (success, fail)',
	item_count INT UNSIGNED NULL COMMENT '처리 건수 (수신 · 조회 행 수)',
	message VARCHAR(500) NULL COMMENT '실패 메시지',
	ip VARCHAR(45) NULL COMMENT '요청 IP',
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '요청일시',
	PRIMARY KEY (idx),
	KEY idx_ai_api_log_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AI API 요청 로그';

-- 4. 권한 : AI 연동 (aiApi.view / aiApi.update) - RIPS 업체 수정 권한(rips.update)이 있는 관리자에게 복사
INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'aiApi.view' FROM admin_permissions p
WHERE p.permission_key = 'rips.update' AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'aiApi.view');
INSERT INTO admin_permissions (admin_idx, permission_key)
SELECT p.admin_idx, 'aiApi.update' FROM admin_permissions p
WHERE p.permission_key = 'rips.update' AND NOT EXISTS (SELECT 1 FROM admin_permissions x WHERE x.admin_idx = p.admin_idx AND x.permission_key = 'aiApi.update');

-- 5. rips_product 의 AI 컬럼 (expect_out_date, recommend_order_date, recommend_qty) 은 더 이상 사용하지 않음 (rips_forecast 사용)
ALTER TABLE rips_product
	MODIFY expect_out_date DATE NULL COMMENT '(미사용) 잔여소진 예상일 - AI 예측은 rips_forecast 사용',
	MODIFY recommend_order_date DATE NULL COMMENT '(미사용) 추천 발주일 - AI 예측은 rips_forecast 사용',
	MODIFY recommend_qty INT UNSIGNED NULL COMMENT '(미사용) 추천수량 - AI 예측은 rips_forecast 사용';

-- 6. 위 컬럼에 남아 있던 값(테스트 데이터) 정리 : 화면은 rips_forecast 의 AI 값 또는 계산식을 사용
UPDATE rips_product SET expect_out_date = NULL, recommend_order_date = NULL, recommend_qty = NULL
WHERE expect_out_date IS NOT NULL OR recommend_order_date IS NOT NULL OR recommend_qty IS NOT NULL;
