-- ============================================================
-- [테스트] 파트너 RIPS 매핑 테스트용 상품 (2026-10-30)
-- 운영 DB 에서 실행하지 마세요. 모든 데이터는 이름 앞에 [TEST] 가 붙습니다.
--
-- 1) 리치포트 상품 (product, 관리자 > 제품)   : 1단계 카테고리별, 기본 발주금액 포함
--    업체 상품(롯데마트·GS25) 각각에 매칭할 수 있는 리치포트 상품 포함
-- 2) 롯데마트 업체 상품 (rips_product)         : 기존 롯데마트 카테고리(냉동/통조림/면류...)에 등록
-- 3) GS25 업체 카테고리 3단계 + 업체 상품       : 업체 3단계 ↔ 리치포트 1~2단계 매핑 테스트용
-- 4) 일부 상품은 미리 매핑 (기본금액 / 개별금액 / 리치포트 기본금액 없음 케이스)
--
-- 삭제는 맨 아래 [정리] 블록 실행
-- ============================================================

-- 공급 파트너 / RIPS 업체
SET @supplier  = (SELECT idx FROM partner WHERE partner_name = '한국 삼양' LIMIT 1);
SET @supplier  = IFNULL(@supplier, (SELECT MIN(idx) FROM partner));
SET @lotte     = (SELECT idx FROM partner WHERE rips_id = 'lottemart' LIMIT 1);
SET @gs25      = (SELECT idx FROM partner WHERE rips_id = 'gs25' LIMIT 1);

-- ------------------------------------------------------------
-- 1) 리치포트 상품 (상품은 1단계 카테고리에만 등록)
-- ------------------------------------------------------------
CREATE TEMPORARY TABLE tmp_test_product (
	forms_name VARCHAR(200) NOT NULL,
	product_name VARCHAR(200) NOT NULL,
	product_name_en VARCHAR(200) NOT NULL,
	product_name_vn VARCHAR(200) NOT NULL,
	order_price BIGINT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_test_product VALUES
	('면·오일·양념',        '[TEST] 삼양라면 120g',           '[TEST] Samyang Ramen 120g',          '[TEST] Mì Samyang 120g',              12000),
	('면·오일·양념',        '[TEST] 불닭볶음면 140g',          '[TEST] Buldak Ramen 140g',           '[TEST] Mì Buldak 140g',               18000),
	('면·오일·양념',        '[TEST] 짜짜로니 140g',            '[TEST] Jjajjaroni 140g',             '[TEST] Mì Jjajjaroni 140g',           15000),
	('면·오일·양념',        '[TEST] 소면 900g',                '[TEST] Somen Noodles 900g',          '[TEST] Mì Somen 900g',                35000),
	('면·오일·양념',        '[TEST] 고추장 500g',              '[TEST] Gochujang 500g',              '[TEST] Tương ớt Gochujang 500g',      65000),
	('반찬·가공 식품',      '[TEST] 비비고 왕교자 1kg',        '[TEST] Bibigo Mandu 1kg',            '[TEST] Bánh xếp Bibigo 1kg',         185000),
	('반찬·가공 식품',      '[TEST] 종가 포기김치 1kg',        '[TEST] Jongga Kimchi 1kg',           '[TEST] Kim chi Jongga 1kg',          150000),
	('반찬·가공 식품',      '[TEST] 동원참치 150g',            '[TEST] Dongwon Tuna 150g',           '[TEST] Cá ngừ Dongwon 150g',          45000),
	('반찬·가공 식품',      '[TEST] 스팸 클래식 200g',         '[TEST] Spam Classic 200g',           '[TEST] Spam Classic 200g',            NULL),
	('음료·우유',           '[TEST] 바나나맛우유 240ml',       '[TEST] Banana Milk 240ml',           '[TEST] Sữa chuối 240ml',              22000),
	('음료·우유',           '[TEST] 맥심 모카골드 20T',        '[TEST] Maxim Mocha Gold 20T',        '[TEST] Cà phê Maxim 20 gói',         120000),
	('음료·우유',           '[TEST] 참이슬 360ml',             '[TEST] Chamisul Soju 360ml',         '[TEST] Rượu Chamisul 360ml',          55000),
	('군것질·아이스크림',   '[TEST] 신라면 블랙 컵',           '[TEST] Shin Ramyun Black Cup',       '[TEST] Mì ly Shin Black',             28000),
	('군것질·아이스크림',   '[TEST] 초코파이 12개입',          '[TEST] Choco Pie 12ea',              '[TEST] Bánh Choco Pie 12 cái',        75000),
	('군것질·아이스크림',   '[TEST] 메로나 5개입',             '[TEST] Melona 5ea',                  '[TEST] Kem Melona 5 cây',             70000),
	('채소',                '[TEST] 국산 깻잎 100g',           '[TEST] Perilla Leaves 100g',         '[TEST] Lá tía tô 100g',               25000),
	('정육·계란',           '[TEST] 양념 불고기 500g',         '[TEST] Bulgogi 500g',                '[TEST] Thịt bò Bulgogi 500g',        210000),
	-- 업체 상품과 1:1 로 매칭할 수 있는 상품 (멀티팩·도시락 등)
	('반찬·가공 식품',      '[TEST] 비비고 왕교자 1.05kg',     '[TEST] Bibigo Mandu 1.05kg',         '[TEST] Bánh xếp Bibigo 1.05kg',      188000),
	('반찬·가공 식품',      '[TEST] 해태 고향만두 1kg',        '[TEST] Haitai Gohyang Mandu 1kg',    '[TEST] Bánh xếp Gohyang 1kg',        155000),
	('반찬·가공 식품',      '[TEST] 오뚜기 옥수수콘 340g',     '[TEST] Ottogi Sweet Corn 340g',      '[TEST] Bắp ngọt Ottogi 340g',         28000),
	('반찬·가공 식품',      '[TEST] 불고기 도시락',            '[TEST] Bulgogi Lunch Box',           '[TEST] Cơm hộp Bulgogi',              50000),
	('반찬·가공 식품',      '[TEST] 김치볶음밥 도시락',        '[TEST] Kimchi Fried Rice Box',       '[TEST] Cơm chiên kim chi hộp',        42000),
	('반찬·가공 식품',      '[TEST] 돈까스 도시락',            '[TEST] Pork Cutlet Lunch Box',       '[TEST] Cơm hộp thịt chiên xù',        48000),
	('반찬·가공 식품',      '[TEST] 참치마요 삼각김밥',        '[TEST] Tuna Mayo Triangle Kimbap',   '[TEST] Cơm nắm cá ngừ mayo',          16000),
	('면·오일·양념',        '[TEST] 삼양라면 5입',             '[TEST] Samyang Ramen 5ea',           '[TEST] Mì Samyang 5 gói',             55000),
	('면·오일·양념',        '[TEST] 불닭볶음면 5입',           '[TEST] Buldak Ramen 5ea',            '[TEST] Mì Buldak 5 gói',              85000),
	('면·오일·양념',        '[TEST] 짜짜로니 4입',             '[TEST] Jjajjaroni 4ea',              '[TEST] Mì Jjajjaroni 4 gói',          58000),
	('면·오일·양념',        '[TEST] 신라면 블랙 컵 6입',       '[TEST] Shin Ramyun Black Cup 6ea',   '[TEST] Mì ly Shin Black 6 ly',       150000),
	('면·오일·양념',        '[TEST] 풀무원 생칼국수 2인',      '[TEST] Pulmuone Kalguksu 2p',        '[TEST] Mì Kalguksu Pulmuone 2 phần',  45000),
	('군것질·아이스크림',   '[TEST] 메로나 멀티팩 8개입',      '[TEST] Melona Multipack 8ea',        '[TEST] Kem Melona 8 cây',             68000),
	('음료·우유',           '[TEST] 칠성사이다 500ml',         '[TEST] Chilsung Cider 500ml',        '[TEST] Nước ngọt Chilsung 500ml',     18000),
	('음료·우유',           '[TEST] 바나나맛우유 240ml 6입',   '[TEST] Banana Milk 240ml 6ea',       '[TEST] Sữa chuối 240ml 6 hộp',       125000);

INSERT INTO product (partner_idx, forms_idx, product_name, product_name_en, product_name_vn, order_price, fo_up_name, fo_file_size, contents, created_by)
SELECT @supplier, f.idx, t.product_name, t.product_name_en, t.product_name_vn, t.order_price, '', 0, '테스트 상품입니다.', 'TEST'
FROM tmp_test_product t
JOIN form_types f ON f.depth = 1 AND REPLACE(TRIM(f.subject), ' ', '') = REPLACE(t.forms_name, ' ', '')
WHERE NOT EXISTS (SELECT 1 FROM product p WHERE p.product_name = t.product_name);

DROP TEMPORARY TABLE tmp_test_product;

-- ------------------------------------------------------------
-- 2) 롯데마트 업체 상품 (기존 롯데마트 카테고리명으로 등록)
-- ------------------------------------------------------------
CREATE TEMPORARY TABLE tmp_test_rips (
	category_name VARCHAR(100) NOT NULL,
	company_name VARCHAR(200) NOT NULL,
	product_name VARCHAR(200) NOT NULL,
	unit_price BIGINT NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_test_rips VALUES
	('만두',     'CJ제일제당', '[TEST] 비비고 왕교자 1.05kg',  190000),
	('만두',     '해태',       '[TEST] 고향만두 1kg',          160000),
	('냉동',     '빙그레',     '[TEST] 메로나 멀티팩',          72000),
	('참치',     '동원',       '[TEST] 동원 라이트스탠다드 150g', 47000),
	('반찬',     '종가',       '[TEST] 종가집 포기김치 1kg',    155000),
	('반찬',     '롯데햄',     '[TEST] 스팸 200g',              60000),
	('통조림',   '오뚜기',     '[TEST] 오뚜기 옥수수콘 340g',   30000),
	('흰국물',   '삼양식품',   '[TEST] 삼양라면 5입',           58000),
	('빨간국물', '삼양식품',   '[TEST] 불닭볶음면 5입',         90000),
	('빨간국물', '농심',       '[TEST] 신라면 블랙 컵 6입',    160000),
	('짜장',     '삼양식품',   '[TEST] 짜짜로니 4입',           62000),
	('국수',     '오뚜기',     '[TEST] 옛날 소면 900g',         36000),
	('생면',     '풀무원',     '[TEST] 생칼국수 2인',           48000),
	('면류',     '오뚜기',     '[TEST] 오뚜기 고추장 500g',     66000);

INSERT INTO rips_product (partner_idx, category_idx, company_name, product_name, unit_price, stock_qty, created_by)
SELECT @lotte, c.idx, t.company_name, t.product_name, t.unit_price, 100, 'TEST'
FROM tmp_test_rips t
JOIN rips_category c ON c.partner_idx = @lotte AND c.name = t.category_name
WHERE NOT EXISTS (SELECT 1 FROM rips_product r WHERE r.partner_idx = @lotte AND r.product_name = t.product_name);

DROP TEMPORARY TABLE tmp_test_rips;

-- ------------------------------------------------------------
-- 3) GS25 업체 카테고리 (3단계) + 업체 상품
--    간편식 > 도시락 > 한식도시락 / 간편식 > 삼각김밥
--    음료 > 커피 / 음료 > 주류 > 소주
-- ------------------------------------------------------------
INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, 0, 1, '[TEST] 간편식', 1 FROM DUAL
WHERE @gs25 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 간편식');
SET @g1 = (SELECT idx FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 간편식');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, 0, 1, '[TEST] 음료', 2 FROM DUAL
WHERE @gs25 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 음료');
SET @g2 = (SELECT idx FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 음료');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g1, 2, '[TEST] 도시락', 1 FROM DUAL
WHERE @g1 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 도시락');
SET @g11 = (SELECT idx FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 도시락');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g1, 2, '[TEST] 삼각김밥', 2 FROM DUAL
WHERE @g1 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 삼각김밥');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g11, 3, '[TEST] 한식도시락', 1 FROM DUAL
WHERE @g11 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 한식도시락');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g2, 2, '[TEST] 커피', 1 FROM DUAL
WHERE @g2 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 커피');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g2, 2, '[TEST] 주류', 2 FROM DUAL
WHERE @g2 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 주류');
SET @g22 = (SELECT idx FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 주류');

INSERT INTO rips_category (partner_idx, parent_idx, depth, name, orderby)
SELECT @gs25, @g22, 3, '[TEST] 소주', 1 FROM DUAL
WHERE @g22 IS NOT NULL AND NOT EXISTS (SELECT 1 FROM rips_category WHERE partner_idx = @gs25 AND name = '[TEST] 소주');

CREATE TEMPORARY TABLE tmp_test_rips (
	category_name VARCHAR(100) NOT NULL,
	company_name VARCHAR(200) NOT NULL,
	product_name VARCHAR(200) NOT NULL,
	unit_price BIGINT NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_test_rips VALUES
	('[TEST] 한식도시락', 'GS리테일', '[TEST] 불고기 도시락',     55000),
	('[TEST] 한식도시락', 'GS리테일', '[TEST] 김치볶음밥 도시락', 45000),
	('[TEST] 도시락',     'GS리테일', '[TEST] 돈까스 도시락',     52000),
	('[TEST] 삼각김밥',   'GS리테일', '[TEST] 참치마요 삼각김밥', 18000),
	('[TEST] 커피',       '동서식품', '[TEST] 맥심 모카골드 20T', 125000),
	('[TEST] 커피',       '빙그레',   '[TEST] 바나나맛우유',      23000),
	('[TEST] 소주',       '하이트진로', '[TEST] 참이슬 후레쉬',   56000),
	('[TEST] 음료',       '롯데칠성', '[TEST] 칠성사이다 500ml',  20000);

INSERT INTO rips_product (partner_idx, category_idx, company_name, product_name, unit_price, stock_qty, created_by)
SELECT @gs25, c.idx, t.company_name, t.product_name, t.unit_price, 50, 'TEST'
FROM tmp_test_rips t
JOIN rips_category c ON c.partner_idx = @gs25 AND c.name = t.category_name
WHERE NOT EXISTS (SELECT 1 FROM rips_product r WHERE r.partner_idx = @gs25 AND r.product_name = t.product_name);

DROP TEMPORARY TABLE tmp_test_rips;

-- ------------------------------------------------------------
-- 4) 미리 매핑해 두는 상품 (발주금액 표시 케이스 확인용)
--    기본금액 적용 / 개별금액 적용 / 매핑 상품의 기본금액 없음(업체 단가 적용)
-- ------------------------------------------------------------
UPDATE rips_product r
JOIN (SELECT idx FROM product WHERE product_name = '[TEST] 삼양라면 120g' LIMIT 1) p
SET r.product_idx = p.idx, r.price_type = 'default', r.custom_price = NULL
WHERE r.partner_idx = @lotte AND r.product_name = '[TEST] 삼양라면 5입' AND r.product_idx IS NULL;

UPDATE rips_product r
JOIN (SELECT idx FROM product WHERE product_name = '[TEST] 불닭볶음면 140g' LIMIT 1) p
SET r.product_idx = p.idx, r.price_type = 'custom', r.custom_price = 85000
WHERE r.partner_idx = @lotte AND r.product_name = '[TEST] 불닭볶음면 5입' AND r.product_idx IS NULL;

UPDATE rips_product r
JOIN (SELECT idx FROM product WHERE product_name = '[TEST] 스팸 클래식 200g' LIMIT 1) p
SET r.product_idx = p.idx, r.price_type = 'default', r.custom_price = NULL
WHERE r.partner_idx = @lotte AND r.product_name = '[TEST] 스팸 200g' AND r.product_idx IS NULL;

-- 결과 확인
SELECT '리치포트 상품' AS 구분, COUNT(*) AS 건수 FROM product WHERE product_name LIKE '[TEST]%'
UNION ALL SELECT '롯데마트 업체 상품', COUNT(*) FROM rips_product WHERE partner_idx = @lotte AND product_name LIKE '[TEST]%'
UNION ALL SELECT 'GS25 업체 카테고리', COUNT(*) FROM rips_category WHERE partner_idx = @gs25 AND name LIKE '[TEST]%'
UNION ALL SELECT 'GS25 업체 상품', COUNT(*) FROM rips_product WHERE partner_idx = @gs25 AND product_name LIKE '[TEST]%'
UNION ALL SELECT '미리 매핑된 상품', COUNT(*) FROM rips_product WHERE product_name LIKE '[TEST]%' AND product_idx IS NOT NULL;


-- ============================================================
-- [정리] 테스트 데이터 삭제 (필요할 때 아래 주석을 풀고 실행)
-- ============================================================
-- DELETE FROM rips_product  WHERE product_name LIKE '[TEST]%';
-- DELETE FROM rips_category WHERE name LIKE '[TEST]%';
-- DELETE FROM product       WHERE product_name LIKE '[TEST]%';
