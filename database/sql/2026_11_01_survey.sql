-- 테스트베드 설문조사 (2026-11-01)
-- 체크리스트 → 테스트베드 상품별 설문조사로 전환 : 응답자는 로그인 없이 /survey/{토큰} 에서 답변
-- DB 별로 1회만 실행 (적용 여부 확인: SHOW COLUMNS FROM check_list LIKE 'survey_token';)

-- 설문 : 대상 테스트베드 상품, 공개 URL 토큰
ALTER TABLE check_list
	ADD COLUMN product_idx INT UNSIGNED NULL COMMENT '테스트베드 상품(product.idx)' AFTER depart_idx,
	ADD COLUMN survey_token VARCHAR(40) NULL COMMENT '설문 URL 토큰' AFTER product_idx,
	ADD UNIQUE KEY uq_check_list_token (survey_token),
	ADD KEY idx_check_list_product (product_idx);

-- 기존 설문에도 토큰 발급 (상품을 지정하기 전까지는 응답 불가)
UPDATE check_list SET survey_token = MD5(CONCAT(idx, '-', RAND(), '-', NOW())) WHERE survey_token IS NULL;

-- 응답자 정보 : 연령대, 성별, 거주지(시, 구역)
ALTER TABLE check_execution
	ADD COLUMN age_group VARCHAR(10) NULL COMMENT '연령대(1-10,11-20,21-30,31-40,41-50,51-59,60+)' AFTER user_sign,
	ADD COLUMN gender CHAR(1) NULL COMMENT '성별(M:남,F:여)' AFTER age_group,
	ADD COLUMN cities_idx TINYINT UNSIGNED NULL COMMENT '거주지 시(cities.idx)' AFTER gender,
	ADD COLUMN districts_idx INT UNSIGNED NULL COMMENT '거주지 구역(districts.idx)' AFTER cities_idx,
	ADD KEY idx_check_execution_list (list_idx);

-- 답변 : 주관식 200자 제한 해제, 집계용 인덱스
-- 다중 선택은 선택한 옵션마다 1행, 비고는 해당 문항의 첫 행 result_value 에 저장
ALTER TABLE check_result
	MODIFY result_value TEXT NULL,
	ADD KEY idx_check_result_list (list_idx),
	ADD KEY idx_check_result_exec (exec_idx);
