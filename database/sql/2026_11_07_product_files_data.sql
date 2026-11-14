-- =====================================================================
-- 신상품 상세 이미지(product_files) 샘플 (2026-11-07)
--  상세 이미지가 없는 신상품에 대표/상품정보 이미지 2장씩 등록 (상품명 기준, 중복 방지)
--  신상품 목록 썸네일도 대표 샘플 이미지로 변경
--  ※ 이미지 파일 storage/app/public/product/sample/*.png, storage/app/public/productThumb/sample/*.png 를 서버에 함께 복사해야 함
-- =====================================================================
SET NAMES utf8mb4;

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p78_main.png', 'product/sample/p78_main.png', 'png', 22160, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 삼양 까르보 불닭볶음면 130g'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p78_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p78_info.png', 'product/sample/p78_info.png', 'png', 36120, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 삼양 까르보 불닭볶음면 130g'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p78_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p79_main.png', 'product/sample/p79_main.png', 'png', 21867, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 비비고 김치왕교자 1.05kg'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p79_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p79_info.png', 'product/sample/p79_info.png', 'png', 32728, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 비비고 김치왕교자 1.05kg'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p79_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p80_main.png', 'product/sample/p80_main.png', 'png', 27334, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 정관장 홍삼정 에브리타임 10ml×30포'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p80_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p80_info.png', 'product/sample/p80_info.png', 'png', 37009, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 정관장 홍삼정 에브리타임 10ml×30포'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p80_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p81_main.png', 'product/sample/p81_main.png', 'png', 20383, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] CJ 햇반 210g×3'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p81_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p81_info.png', 'product/sample/p81_info.png', 'png', 30312, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] CJ 햇반 210g×3'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p81_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p82_main.png', 'product/sample/p82_main.png', 'png', 23093, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 오리온 꼬북칩 초코츄러스 160g'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p82_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p82_info.png', 'product/sample/p82_info.png', 'png', 32997, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 오리온 꼬북칩 초코츄러스 160g'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p82_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p83_main.png', 'product/sample/p83_main.png', 'png', 19045, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 빙그레 바나나맛우유 240ml'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p83_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p83_info.png', 'product/sample/p83_info.png', 'png', 27504, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 빙그레 바나나맛우유 240ml'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p83_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p84_main.png', 'product/sample/p84_main.png', 'png', 17594, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 메디힐 티트리 마스크팩 10매'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p84_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p84_info.png', 'product/sample/p84_info.png', 'png', 28582, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 메디힐 티트리 마스크팩 10매'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p84_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p85_main.png', 'product/sample/p85_main.png', 'png', 22169, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 오뚜기 진라면 순한맛 120g×5'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p85_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p85_info.png', 'product/sample/p85_info.png', 'png', 29695, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 오뚜기 진라면 순한맛 120g×5'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p85_info.png');

INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p86_main.png', 'product/sample/p86_main.png', 'png', 18457, 1, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 농심 신라면 블랙 134g×4'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p86_main.png');
INSERT INTO `product_files` (`product_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'p86_info.png', 'product/sample/p86_info.png', 'png', 28726, 2, '관리자', NOW() FROM `product` p WHERE p.`product_name` = '[TEST-TB] 농심 신라면 블랙 134g×4'
AND NOT EXISTS (SELECT 1 FROM `product_files` f WHERE f.`product_idx` = p.`idx` AND f.`up_name` = 'product/sample/p86_info.png');



-- 신상품 목록 썸네일(PC/Fold/Mobile)을 대표 샘플 이미지로 변경
UPDATE `product` SET `pc_ori_name` = 'p78.png', `pc_up_name` = 'productThumb/sample/p78.png', `pc_file_ext` = 'png', `pc_file_size` = 22160, `fo_ori_name` = 'p78.png', `fo_up_name` = 'productThumb/sample/p78.png', `fo_file_ext` = 'png', `fo_file_size` = 22160, `mo_ori_name` = 'p78.png', `mo_up_name` = 'productThumb/sample/p78.png', `mo_file_ext` = 'png', `mo_file_size` = 22160 WHERE `product_name` = '[TEST-TB] 삼양 까르보 불닭볶음면 130g';
UPDATE `product` SET `pc_ori_name` = 'p79.png', `pc_up_name` = 'productThumb/sample/p79.png', `pc_file_ext` = 'png', `pc_file_size` = 21867, `fo_ori_name` = 'p79.png', `fo_up_name` = 'productThumb/sample/p79.png', `fo_file_ext` = 'png', `fo_file_size` = 21867, `mo_ori_name` = 'p79.png', `mo_up_name` = 'productThumb/sample/p79.png', `mo_file_ext` = 'png', `mo_file_size` = 21867 WHERE `product_name` = '[TEST-TB] 비비고 김치왕교자 1.05kg';
UPDATE `product` SET `pc_ori_name` = 'p80.png', `pc_up_name` = 'productThumb/sample/p80.png', `pc_file_ext` = 'png', `pc_file_size` = 27334, `fo_ori_name` = 'p80.png', `fo_up_name` = 'productThumb/sample/p80.png', `fo_file_ext` = 'png', `fo_file_size` = 27334, `mo_ori_name` = 'p80.png', `mo_up_name` = 'productThumb/sample/p80.png', `mo_file_ext` = 'png', `mo_file_size` = 27334 WHERE `product_name` = '[TEST-TB] 정관장 홍삼정 에브리타임 10ml×30포';
UPDATE `product` SET `pc_ori_name` = 'p81.png', `pc_up_name` = 'productThumb/sample/p81.png', `pc_file_ext` = 'png', `pc_file_size` = 20383, `fo_ori_name` = 'p81.png', `fo_up_name` = 'productThumb/sample/p81.png', `fo_file_ext` = 'png', `fo_file_size` = 20383, `mo_ori_name` = 'p81.png', `mo_up_name` = 'productThumb/sample/p81.png', `mo_file_ext` = 'png', `mo_file_size` = 20383 WHERE `product_name` = '[TEST-TB] CJ 햇반 210g×3';
UPDATE `product` SET `pc_ori_name` = 'p82.png', `pc_up_name` = 'productThumb/sample/p82.png', `pc_file_ext` = 'png', `pc_file_size` = 23093, `fo_ori_name` = 'p82.png', `fo_up_name` = 'productThumb/sample/p82.png', `fo_file_ext` = 'png', `fo_file_size` = 23093, `mo_ori_name` = 'p82.png', `mo_up_name` = 'productThumb/sample/p82.png', `mo_file_ext` = 'png', `mo_file_size` = 23093 WHERE `product_name` = '[TEST-TB] 오리온 꼬북칩 초코츄러스 160g';
UPDATE `product` SET `pc_ori_name` = 'p83.png', `pc_up_name` = 'productThumb/sample/p83.png', `pc_file_ext` = 'png', `pc_file_size` = 19045, `fo_ori_name` = 'p83.png', `fo_up_name` = 'productThumb/sample/p83.png', `fo_file_ext` = 'png', `fo_file_size` = 19045, `mo_ori_name` = 'p83.png', `mo_up_name` = 'productThumb/sample/p83.png', `mo_file_ext` = 'png', `mo_file_size` = 19045 WHERE `product_name` = '[TEST-TB] 빙그레 바나나맛우유 240ml';
UPDATE `product` SET `pc_ori_name` = 'p84.png', `pc_up_name` = 'productThumb/sample/p84.png', `pc_file_ext` = 'png', `pc_file_size` = 17594, `fo_ori_name` = 'p84.png', `fo_up_name` = 'productThumb/sample/p84.png', `fo_file_ext` = 'png', `fo_file_size` = 17594, `mo_ori_name` = 'p84.png', `mo_up_name` = 'productThumb/sample/p84.png', `mo_file_ext` = 'png', `mo_file_size` = 17594 WHERE `product_name` = '[TEST-TB] 메디힐 티트리 마스크팩 10매';
UPDATE `product` SET `pc_ori_name` = 'p85.png', `pc_up_name` = 'productThumb/sample/p85.png', `pc_file_ext` = 'png', `pc_file_size` = 22169, `fo_ori_name` = 'p85.png', `fo_up_name` = 'productThumb/sample/p85.png', `fo_file_ext` = 'png', `fo_file_size` = 22169, `mo_ori_name` = 'p85.png', `mo_up_name` = 'productThumb/sample/p85.png', `mo_file_ext` = 'png', `mo_file_size` = 22169 WHERE `product_name` = '[TEST-TB] 오뚜기 진라면 순한맛 120g×5';
UPDATE `product` SET `pc_ori_name` = 'p86.png', `pc_up_name` = 'productThumb/sample/p86.png', `pc_file_ext` = 'png', `pc_file_size` = 18457, `fo_ori_name` = 'p86.png', `fo_up_name` = 'productThumb/sample/p86.png', `fo_file_ext` = 'png', `fo_file_size` = 18457, `mo_ori_name` = 'p86.png', `mo_up_name` = 'productThumb/sample/p86.png', `mo_file_ext` = 'png', `mo_file_size` = 18457 WHERE `product_name` = '[TEST-TB] 농심 신라면 블랙 134g×4';
