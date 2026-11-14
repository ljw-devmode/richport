-- RIPS 업체 정보 / 상품 바코드 / 테스트베드 피드백 (2026-11-07)
-- DB 별로 1회만 실행

-- 1. 테스트베드 피드백 : 관리자가 작성, RIPS 업체 화면(테스트베드 신청 상세)에 공개
--    (response_contents 처리내용은 관리자 내부 메모로 업체에 공개하지 않음)
ALTER TABLE testbed_inquire
	ADD COLUMN feedback MEDIUMTEXT NULL COMMENT '피드백 (RIPS 업체에 공개)' AFTER response_contents,
	ADD COLUMN feedback_by VARCHAR(50) NULL COMMENT '피드백 작성자' AFTER feedback,
	ADD COLUMN feedback_at DATETIME NULL COMMENT '피드백 작성(수정)일시' AFTER feedback_by;

-- 2. 상품 바코드 (등록 시 필수, 기존 상품은 비어 있음)
ALTER TABLE product
	ADD COLUMN barcode VARCHAR(50) NULL COMMENT '바코드' AFTER product_name_vn,
	ADD KEY idx_product_barcode (barcode);

ALTER TABLE rips_product
	ADD COLUMN barcode VARCHAR(50) NULL COMMENT '바코드' AFTER product_name,
	ADD KEY idx_rips_product_barcode (partner_idx, barcode);

-- 3. RIPS 업체 정보 (RIPS > 설정 > 내 정보 수정)
--    업체구분 · 담당자명 · 담당자 연락처 : RIPS 신청 시 받은 정보 (관리자 > 파트너 > RIPS 계정 에서 입력)
--    통관고유번호 · 사업자번호 : 최초 1회 입력 후 업체는 수정 불가
ALTER TABLE partner
	ADD COLUMN rips_type_idx INT UNSIGNED NULL COMMENT 'RIPS 업체구분(rips_inquire_types.idx)' AFTER rips_pw,
	ADD COLUMN photo_ori_name VARCHAR(255) NULL COMMENT '업체 사진 원본명' AFTER rips_type_idx,
	ADD COLUMN photo_up_name VARCHAR(255) NULL COMMENT '업체 사진 저장경로' AFTER photo_ori_name,
	ADD COLUMN company_address VARCHAR(255) NULL COMMENT '회사 주소' AFTER photo_up_name,
	ADD COLUMN company_tel VARCHAR(50) NULL COMMENT '회사 대표전화' AFTER company_address,
	ADD COLUMN ceo_name VARCHAR(100) NULL COMMENT '대표자명' AFTER company_tel,
	ADD COLUMN customs_code VARCHAR(50) NULL COMMENT '통관고유번호 (최초 입력 후 수정 불가)' AFTER ceo_name,
	ADD COLUMN biz_no VARCHAR(50) NULL COMMENT '사업자번호 (최초 입력 후 수정 불가)' AFTER customs_code,
	ADD COLUMN manager_name VARCHAR(100) NULL COMMENT '담당자명' AFTER biz_no,
	ADD COLUMN manager_hp VARCHAR(50) NULL COMMENT '담당자 연락처' AFTER manager_name,
	ADD COLUMN manager_email VARCHAR(200) NULL COMMENT '담당자 이메일' AFTER manager_hp,
	ADD COLUMN manager_task VARCHAR(200) NULL COMMENT '담당업무' AFTER manager_email;
