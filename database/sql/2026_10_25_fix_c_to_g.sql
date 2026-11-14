-- C~G 항목 정리 (2026-10-25)

-- G1. 문의 구분 테이블 다국어 컬럼명 통일 (subject_cn -> subject_vn)
ALTER TABLE inquire_types         CHANGE subject_cn subject_vn VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE rips_inquire_types    CHANGE subject_cn subject_vn VARCHAR(200) NOT NULL DEFAULT '';
ALTER TABLE testbed_inquire_types CHANGE subject_cn subject_vn VARCHAR(200) NOT NULL DEFAULT '';

-- E2. RIPS 문의 업체구분을 사용자 폼(한국 제조업체/한국 수출업체/베트남 유통업체/베트남 수입업체)과 일치
UPDATE rips_inquire_types SET subject = '한국 제조업체',   subject_en = 'Korean Manufacturer',   subject_vn = 'Nhà sản xuất Hàn Quốc'   WHERE idx = 1;
UPDATE rips_inquire_types SET subject = '한국 수출업체',   subject_en = 'Korean Exporter',       subject_vn = 'Nhà xuất khẩu Hàn Quốc'  WHERE idx = 2;
UPDATE rips_inquire_types SET subject = '베트남 유통업체', subject_en = 'Vietnamese Distributor', subject_vn = 'Nhà phân phối Việt Nam' WHERE idx = 3;
UPDATE rips_inquire_types SET subject = '베트남 수입업체', subject_en = 'Vietnamese Importer',   subject_vn = 'Nhà nhập khẩu Việt Nam'  WHERE idx = 4;
UPDATE rips_inquire_types SET subject = '기타',            subject_en = 'Others',                subject_vn = 'Khác'                    WHERE idx = 5;

-- E3. 테스트베드 문의의 types_idx 는 product.idx 를 저장함 (tinyint -> int 로 확장)
ALTER TABLE testbed_inquire MODIFY types_idx INT UNSIGNED NULL COMMENT '신청 상품(product.idx)';

-- G3. types 다국어 placeholder 값 정리
UPDATE types SET subject_en = 'Hypermarket',       subject_vn = 'Đại siêu thị'          WHERE idx = 1;
UPDATE types SET subject_en = 'Mini Mart',         subject_vn = 'Siêu thị mini'         WHERE idx = 2;
UPDATE types SET subject_en = 'Convenience Store', subject_vn = 'Cửa hàng tiện lợi'     WHERE idx = 3;
UPDATE types SET subject_en = 'Local Market',      subject_vn = 'Chợ truyền thống'      WHERE idx = 4;
UPDATE types SET subject_en = 'Manufacturer',      subject_vn = 'Doanh nghiệp sản xuất' WHERE idx = 14;
