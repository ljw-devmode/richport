-- =====================================================================
-- 상품 > Tự Công Bố (최근 등록 상품 / 등록 완료 상품) 샘플 데이터 (2026-11-07)
--  1) 테스트 상품(1111, 1234)은 CongBo 등록 해제 (상품 자체는 유지)
--  2) 샘플 이미지를 등록한 신상품을 CongBo 등록 상품으로 지정 + 등록번호 부여 (상품명 기준)
-- =====================================================================
SET NAMES utf8mb4;

UPDATE `product` SET `congbo` = 0, `congbo_number` = NULL WHERE `product_name` IN ('1111', '1234');

UPDATE `product` SET `congbo` = 1, `congbo_number` = '01/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 삼양 까르보 불닭볶음면 130g';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '02/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 비비고 김치왕교자 1.05kg';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '03/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 정관장 홍삼정 에브리타임 10ml×30포';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '04/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] CJ 햇반 210g×3';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '05/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 오리온 꼬북칩 초코츄러스 160g';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '06/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 빙그레 바나나맛우유 240ml';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '07/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 메디힐 티트리 마스크팩 10매';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '08/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 오뚜기 진라면 순한맛 120g×5';
UPDATE `product` SET `congbo` = 1, `congbo_number` = '09/RICHPORT/2026' WHERE `product_name` = '[TEST-TB] 농심 신라면 블랙 134g×4';
