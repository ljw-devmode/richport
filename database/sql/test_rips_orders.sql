-- ============================================================
-- [테스트] RIPS 발주 데이터 (2026-10-30)
-- 운영 DB 에서 실행하지 마세요. created_by = 'TEST' 로 구분합니다.
--
-- 먼저 실행 : 2026_10_30_rips_order_manage.sql, test_products.sql
-- 롯데마트 / GS25 의 [TEST] 업체 상품으로 발주 9건 (발주접수 ~ 입고완료, 발주취소)
-- 상품준비 이후 발주는 리치포트 발주 정보(공급 업체, 발주일, 수량, 금액)와 물류/통관/입고 일정 포함
-- 이미 TEST 발주가 있으면 아무것도 하지 않음 / 삭제는 맨 아래 [정리] 블록
-- ============================================================

SET @exists = (SELECT COUNT(*) FROM rips_order WHERE created_by = 'TEST');

-- 발주 (일정은 오늘 기준 며칠 전, NULL 이면 미입력)
CREATE TEMPORARY TABLE tmp_order (
	k VARCHAR(10) NOT NULL,
	rips_id VARCHAR(50) NOT NULL,
	days_ago INT NOT NULL,
	status VARCHAR(20) NOT NULL,
	dept VARCHAR(100), name VARCHAR(50), tel VARCHAR(30), email VARCHAR(100),
	l_start INT NULL, l_end INT NULL, c_start INT NULL, c_end INT NULL, recv INT NULL,
	memo VARCHAR(200) NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_order VALUES
	('T1', 'lottemart', 1,  'ordered',   '구매팀', '김발주', '028-1234-0001', 'order@lotte.test', NULL, NULL, NULL, NULL, NULL, NULL),
	('T2', 'lottemart', 5,  'preparing', '구매팀', '김발주', '028-1234-0001', 'order@lotte.test', NULL, NULL, NULL, NULL, NULL, '김치는 공급사 재고 확인 중'),
	('T3', 'lottemart', 12, 'shipping',  '구매팀', '김발주', '028-1234-0001', 'order@lotte.test', 8,    NULL, NULL, NULL, NULL, NULL),
	('T4', 'lottemart', 20, 'customs',   '물류팀', '이물류', '028-1234-0002', 'logi@lotte.test',  16,   10,   9,    NULL, NULL, '통관 서류 보완 요청됨'),
	('T5', 'lottemart', 35, 'received',  '물류팀', '이물류', '028-1234-0002', 'logi@lotte.test',  30,   22,   21,   16,   14,   NULL),
	('T6', 'lottemart', 3,  'canceled',  '구매팀', '김발주', '028-1234-0001', 'order@lotte.test', NULL, NULL, NULL, NULL, NULL, NULL),
	('T7', 'gs25',      2,  'ordered',   '상품팀', '박편의', '028-5555-0101', 'buyer@gs25.test',  NULL, NULL, NULL, NULL, NULL, NULL),
	('T8', 'gs25',      40, 'received',  '상품팀', '박편의', '028-5555-0101', 'buyer@gs25.test',  36,   28,   27,   20,   18,   NULL),
	('T9', 'gs25',      10, 'shipping',  '상품팀', '박편의', '028-5555-0101', 'buyer@gs25.test',  6,    NULL, NULL, NULL, NULL, NULL);

-- 발주 상품 (supplier : 리치포트 발주 업체명, purchase_ago : 리치포트 발주일, NULL 이면 리치포트 발주 정보 미입력)
CREATE TEMPORARY TABLE tmp_item (
	k VARCHAR(10) NOT NULL,
	product_name VARCHAR(200) NOT NULL,
	qty INT NOT NULL,
	supplier VARCHAR(200) NULL,
	purchase_ago INT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_item VALUES
	('T1', '[TEST] 삼양라면 5입',             20, NULL,        NULL),
	('T1', '[TEST] 불닭볶음면 5입',           10, NULL,        NULL),
	('T2', '[TEST] 비비고 왕교자 1.05kg',     15, '1234',      4),
	('T2', '[TEST] 종가집 포기김치 1kg',      10, NULL,        NULL),
	('T2', '[TEST] 스팸 200g',                30, '1234',      4),
	('T3', '[TEST] 신라면 블랙 컵 6입',        8, '한국 삼양', 11),
	('T3', '[TEST] 짜짜로니 4입',             12, '한국 삼양', 11),
	('T4', '[TEST] 동원 라이트스탠다드 150g', 50, '1234',      19),
	('T4', '[TEST] 오뚜기 고추장 500g',       20, '111',       19),
	('T5', '[TEST] 메로나 멀티팩',            40, '111',       34),
	('T5', '[TEST] 오뚜기 옥수수콘 340g',     24, '111',       34),
	('T6', '[TEST] 생칼국수 2인',              5, NULL,        NULL),
	('T7', '[TEST] 불고기 도시락',            30, NULL,        NULL),
	('T7', '[TEST] 참치마요 삼각김밥',        50, NULL,        NULL),
	('T8', '[TEST] 맥심 모카골드 20T',        10, '111',       39),
	('T8', '[TEST] 바나나맛우유',             30, '111',       39),
	('T8', '[TEST] 참이슬 후레쉬',            40, '한국 삼양', 39),
	('T9', '[TEST] 칠성사이다 500ml',         60, '111',       9),
	('T9', '[TEST] 돈까스 도시락',            20, '1234',      9);

-- 1) 발주 (order_no 는 임시로 TEST-키 → 상품 연결 후 RO 형식으로 변경)
INSERT INTO rips_order (partner_idx, order_no, order_date, orderer_dept, orderer_name, orderer_tel, orderer_email,
	status, expected_date, logistics_start, logistics_end, customs_start, customs_end, received_date, admin_memo, canceled_at, created_at, created_by)
SELECT p.idx, CONCAT('TEST-', o.k), NOW() - INTERVAL o.days_ago DAY, o.dept, o.name, o.tel, o.email,
	o.status, CURDATE() - INTERVAL o.days_ago DAY + INTERVAL 14 DAY,
	CURDATE() - INTERVAL o.l_start DAY, CURDATE() - INTERVAL o.l_end DAY,
	CURDATE() - INTERVAL o.c_start DAY, CURDATE() - INTERVAL o.c_end DAY,
	CURDATE() - INTERVAL o.recv DAY, o.memo,
	IF(o.status = 'canceled', NOW() - INTERVAL o.days_ago DAY + INTERVAL 2 HOUR, NULL),
	NOW() - INTERVAL o.days_ago DAY, 'TEST'
FROM tmp_order o
JOIN partner p ON p.rips_id = o.rips_id
WHERE @exists = 0
ORDER BY o.days_ago DESC;

-- 2) 발주 상품 : 단가는 현재 발주 단가 규칙 (개별금액 → 리치포트 기본금액 → 업체 단가)
--    리치포트 발주 금액은 요청금액의 약 80% 로 가정
INSERT INTO rips_order_item (order_idx, product_idx, category_idx, company_name, product_name, unit_price, qty, amount,
	rp_product_idx, supplier_idx, purchase_date, purchase_qty, purchase_amount)
SELECT ro.idx, rp.idx, rp.category_idx, rp.company_name, rp.product_name, u.price, t.qty, u.price * t.qty,
	IF(t.purchase_ago IS NULL, NULL, rp.product_idx),
	IF(t.purchase_ago IS NULL, NULL, COALESCE(s.idx, pr.partner_idx)),
	CURDATE() - INTERVAL t.purchase_ago DAY,
	IF(t.purchase_ago IS NULL, NULL, t.qty),
	IF(t.purchase_ago IS NULL, NULL, ROUND(u.price * t.qty * 0.8, -2))
FROM tmp_item t
JOIN rips_order ro ON ro.order_no = CONCAT('TEST-', t.k) AND ro.created_by = 'TEST'
JOIN rips_product rp ON rp.partner_idx = ro.partner_idx AND rp.product_name = t.product_name
LEFT JOIN product pr ON pr.idx = rp.product_idx
LEFT JOIN partner s ON s.partner_name = t.supplier
JOIN (
	SELECT rp2.idx,
		CASE WHEN rp2.price_type = 'custom' AND rp2.custom_price IS NOT NULL THEN rp2.custom_price
		     WHEN p2.order_price IS NOT NULL THEN p2.order_price
		     ELSE rp2.unit_price END AS price
	FROM rips_product rp2 LEFT JOIN product p2 ON p2.idx = rp2.product_idx
) u ON u.idx = rp.idx
ORDER BY ro.idx, t.product_name;

-- 3) 합계 / 발주번호
UPDATE rips_order ro
JOIN (SELECT order_idx, COUNT(*) cnt, SUM(qty) qty, SUM(amount) amount FROM rips_order_item GROUP BY order_idx) s ON s.order_idx = ro.idx
SET ro.total_items = s.cnt, ro.total_qty = s.qty, ro.total_amount = s.amount
WHERE ro.created_by = 'TEST' AND ro.order_no LIKE 'TEST-%';

UPDATE rips_order
SET order_no = CONCAT('RO', DATE_FORMAT(order_date, '%y%m%d'), '-', LPAD(idx, 5, '0'))
WHERE created_by = 'TEST' AND order_no LIKE 'TEST-%';

-- 4) 상태 이력 : 발주접수 → (현재 상태)
INSERT INTO rips_order_log (order_idx, status, prev_status, created_at, created_by)
SELECT idx, 'ordered', NULL, order_date, orderer_name FROM rips_order
WHERE created_by = 'TEST' AND NOT EXISTS (SELECT 1 FROM rips_order_log l WHERE l.order_idx = rips_order.idx);

INSERT INTO rips_order_log (order_idx, status, prev_status, created_at, created_by)
SELECT ro.idx, ro.status, 'ordered', COALESCE(ro.canceled_at, ro.order_date + INTERVAL 1 DAY), IF(ro.status = 'canceled', ro.orderer_name, 'TEST')
FROM rips_order ro
WHERE ro.created_by = 'TEST' AND ro.status <> 'ordered'
AND (SELECT COUNT(*) FROM rips_order_log l WHERE l.order_idx = ro.idx) = 1;

DROP TEMPORARY TABLE tmp_order;
DROP TEMPORARY TABLE tmp_item;

-- 결과 확인
SELECT ro.order_no, p.partner_name, ro.status, ro.total_items, ro.total_qty, ro.total_amount,
	(SELECT SUM(purchase_amount) FROM rips_order_item i WHERE i.order_idx = ro.idx) AS purchase_amount,
	ro.logistics_start, ro.logistics_end, ro.customs_start, ro.customs_end, ro.received_date
FROM rips_order ro JOIN partner p ON p.idx = ro.partner_idx
WHERE ro.created_by = 'TEST' ORDER BY ro.idx;


-- ============================================================
-- [정리] 테스트 발주 삭제 (필요할 때 아래 주석을 풀고 실행)
-- ============================================================
-- DELETE FROM rips_order_log  WHERE order_idx IN (SELECT idx FROM rips_order WHERE created_by = 'TEST');
-- DELETE FROM rips_order_item WHERE order_idx IN (SELECT idx FROM rips_order WHERE created_by = 'TEST');
-- DELETE FROM rips_order      WHERE created_by = 'TEST';
