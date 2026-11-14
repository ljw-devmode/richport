-- =====================================================================
-- 도입사례(portfolio) 샘플 데이터 (2026-11-07)
--  1) 테스트 도입사례(111, ㅂㅂ) 및 시공이미지 삭제
--  2) RIPS 사용 업체(롯데마트 남사이공점, GS25 1군 벤탄점) 도입사례 등록 (station_name_en 기준 중복 방지)
--  ※ 이미지 파일 storage/app/public/portfolioThumb/sample/*.png, storage/app/public/portfolio/sample/*.png 를 서버에 함께 복사해야 함
-- =====================================================================
SET NAMES utf8mb4;

DELETE FROM `portfolio_files` WHERE `portfolio_idx` IN (SELECT `idx` FROM `portfolio` WHERE `station_name` IN ('111', 'ㅂㅂ'));
DELETE FROM `portfolio` WHERE `station_name` IN ('111', 'ㅂㅂ');
DELETE FROM `portfolio_files` WHERE `portfolio_idx` NOT IN (SELECT `idx` FROM `portfolio`);

-- 롯데마트 남사이공점
INSERT INTO `portfolio` (`types_idx`, `cities_idx`, `districts_idx`, `station_name`, `station_name_en`, `station_name_vn`, `pc_ori_name`, `pc_up_name`, `pc_file_ext`, `pc_file_size`, `fo_ori_name`, `fo_up_name`, `fo_file_ext`, `fo_file_size`, `mo_ori_name`, `mo_up_name`, `mo_file_ext`, `mo_file_size`, `contents`, `contents_en`, `contents_vn`, `home_recommend`, `portfolio_recommend`, `created_at`, `created_by`)
SELECT 1, 1, 234, '롯데마트 남사이공점', 'LOTTE Mart South Saigon', 'LOTTE Mart Nam Sài Gòn', 'lottemart.png', 'portfolioThumb/sample/lottemart.png', 'png', 25702, 'lottemart.png', 'portfolioThumb/sample/lottemart.png', 'png', 25702, 'lottemart.png', 'portfolioThumb/sample/lottemart.png', 'png', 25702, '호치민 7군 롯데마트 남사이공점은 RIPS를 도입해 한국 식품 코너의 재고와 판매를 관리하고 있습니다. 매장 판매 데이터를 기반으로 재고 소진 예상일과 추천 발주일을 확인하고, 발주가 필요한 상품을 한 화면에서 파악해 한국 공급사에 바로 발주합니다. 신상품은 테스트베드로 먼저 입점해 판매 반응과 고객 설문 결과를 확인한 뒤 정식 입점 여부를 결정합니다.', 'LOTTE Mart South Saigon in District 7, Ho Chi Minh City uses RIPS to manage inventory and sales in its Korean food corner. Based on in-store sales data, the store checks expected stock-out dates and recommended order dates, sees every product that needs reordering on one screen, and orders directly from Korean suppliers. New products first enter through the test bed, and the store reviews sales response and customer survey results before deciding on a regular listing.', 'LOTTE Mart Nam Sài Gòn tại Quận 7, TP. Hồ Chí Minh sử dụng RIPS để quản lý tồn kho và doanh số khu thực phẩm Hàn Quốc. Dựa trên dữ liệu bán hàng tại cửa hàng, siêu thị kiểm tra ngày dự kiến hết hàng và ngày đặt hàng đề xuất, xem tất cả sản phẩm cần đặt thêm trên một màn hình và đặt hàng trực tiếp từ nhà cung cấp Hàn Quốc. Sản phẩm mới được đưa vào thử nghiệm (test bed) trước, sau đó siêu thị xem xét phản hồi bán hàng và kết quả khảo sát khách hàng để quyết định niêm yết chính thức.', 1, 1, NOW() - INTERVAL 0 MINUTE, '관리자' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `portfolio` WHERE `station_name_en` = 'LOTTE Mart South Saigon');
INSERT INTO `portfolio_files` (`portfolio_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'lottemart_1.png', 'portfolio/sample/lottemart_1.png', 'png', 25222, 1, '관리자', NOW() FROM `portfolio` p WHERE p.`station_name_en` = 'LOTTE Mart South Saigon'
AND NOT EXISTS (SELECT 1 FROM `portfolio_files` f WHERE f.`portfolio_idx` = p.`idx` AND f.`up_name` = 'portfolio/sample/lottemart_1.png');
INSERT INTO `portfolio_files` (`portfolio_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'lottemart_2.png', 'portfolio/sample/lottemart_2.png', 'png', 25401, 2, '관리자', NOW() FROM `portfolio` p WHERE p.`station_name_en` = 'LOTTE Mart South Saigon'
AND NOT EXISTS (SELECT 1 FROM `portfolio_files` f WHERE f.`portfolio_idx` = p.`idx` AND f.`up_name` = 'portfolio/sample/lottemart_2.png');

-- GS25 1군 벤탄점
INSERT INTO `portfolio` (`types_idx`, `cities_idx`, `districts_idx`, `station_name`, `station_name_en`, `station_name_vn`, `pc_ori_name`, `pc_up_name`, `pc_file_ext`, `pc_file_size`, `fo_ori_name`, `fo_up_name`, `fo_file_ext`, `fo_file_size`, `mo_ori_name`, `mo_up_name`, `mo_file_ext`, `mo_file_size`, `contents`, `contents_en`, `contents_vn`, `home_recommend`, `portfolio_recommend`, `created_at`, `created_by`)
SELECT 3, 1, 229, 'GS25 1군 벤탄점', 'GS25 District 1 Ben Thanh', 'GS25 Quận 1 Bến Thành', 'gs25.png', 'portfolioThumb/sample/gs25.png', 'png', 28666, 'gs25.png', 'portfolioThumb/sample/gs25.png', 'png', 28666, 'gs25.png', 'portfolioThumb/sample/gs25.png', 'png', 28666, '호치민 1군 GS25 벤탄점은 RIPS로 한국 라면·스낵·음료 등 회전이 빠른 편의점 상품의 재고를 관리하고 있습니다. 매일 잔여재고를 등록하면 판매 추이에 맞춰 추천 발주 수량이 계산되어, 품절이 잦던 인기 상품을 미리 발주할 수 있습니다. 작은 매장 공간에 맞춰 필요한 수량만 발주하고, 통관 진행 상황도 RIPS에서 함께 확인합니다.', 'GS25 Ben Thanh in District 1, Ho Chi Minh City uses RIPS to manage inventory of fast-moving convenience items such as Korean ramyun, snacks and beverages. By registering remaining stock daily, the store gets recommended order quantities based on sales trends and can reorder popular items before they sell out. It orders only what its small store space needs and tracks customs clearance status in RIPS as well.', 'GS25 Bến Thành tại Quận 1, TP. Hồ Chí Minh sử dụng RIPS để quản lý tồn kho các mặt hàng bán nhanh như mì, snack và đồ uống Hàn Quốc. Khi nhập tồn kho còn lại mỗi ngày, hệ thống tính số lượng đặt hàng đề xuất theo xu hướng bán, giúp cửa hàng đặt trước các sản phẩm bán chạy trước khi hết hàng. Cửa hàng chỉ đặt đúng số lượng cần thiết cho diện tích nhỏ và theo dõi tình trạng thông quan ngay trên RIPS.', 1, 1, NOW() - INTERVAL 1 MINUTE, '관리자' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `portfolio` WHERE `station_name_en` = 'GS25 District 1 Ben Thanh');
INSERT INTO `portfolio_files` (`portfolio_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'gs25_1.png', 'portfolio/sample/gs25_1.png', 'png', 28666, 1, '관리자', NOW() FROM `portfolio` p WHERE p.`station_name_en` = 'GS25 District 1 Ben Thanh'
AND NOT EXISTS (SELECT 1 FROM `portfolio_files` f WHERE f.`portfolio_idx` = p.`idx` AND f.`up_name` = 'portfolio/sample/gs25_1.png');
INSERT INTO `portfolio_files` (`portfolio_idx`, `ori_name`, `up_name`, `file_ext`, `file_size`, `orderby`, `created_by`, `created_at`)
SELECT p.`idx`, 'gs25_2.png', 'portfolio/sample/gs25_2.png', 'png', 26782, 2, '관리자', NOW() FROM `portfolio` p WHERE p.`station_name_en` = 'GS25 District 1 Ben Thanh'
AND NOT EXISTS (SELECT 1 FROM `portfolio_files` f WHERE f.`portfolio_idx` = p.`idx` AND f.`up_name` = 'portfolio/sample/gs25_2.png');

