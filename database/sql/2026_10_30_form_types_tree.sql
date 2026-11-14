-- 리치포트 카테고리 1~4단계 트리 구조 (2026-10-30)
-- form_types 에 parent_idx / depth / orderby 추가 (rips_category 와 같은 구조)
-- 기존 데이터는 1단계, 제품·파트너·RIPS 매핑 등 기존 화면은 1단계만 사용
-- 관리자 > 사업관리 > 카테고리 관리 에서 등록/수정/삭제
-- 2단계 초기 데이터: 20230608 New category structure.xlsx (KR/EN/VN 시트)
-- ※ DB 별로 1회만 실행 (로컬 richport 는 2026-10-30 적용 완료)
--   적용 여부 확인: SHOW COLUMNS FROM form_types LIKE 'parent_idx';  → 결과가 있으면 이미 적용됨

ALTER TABLE form_types
	ADD COLUMN parent_idx INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '상위 카테고리(0:최상위)' AFTER idx,
	ADD COLUMN depth TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '단계(1~4)' AFTER parent_idx,
	ADD COLUMN orderby INT NOT NULL DEFAULT 0 COMMENT '정렬순서(작은 값이 위)' AFTER subject_vn,
	ADD KEY idx_form_types_parent (parent_idx, orderby);

-- 기존 1단계는 등록순으로 정렬순서 부여
SET @n = 0;
UPDATE form_types SET orderby = (@n := @n + 1) ORDER BY idx;

-- 2단계 : 상위는 공백을 제외한 한국어명으로 매칭 (서버마다 idx 가 달라도 동작)
CREATE TEMPORARY TABLE tmp_form_types_seed (
	parent_name VARCHAR(200) NOT NULL,
	subject VARCHAR(200) NOT NULL,
	subject_en VARCHAR(200) NOT NULL,
	subject_vn VARCHAR(200) NOT NULL,
	orderby INT NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp_form_types_seed (parent_name, subject, subject_en, subject_vn, orderby) VALUES
	('채소', '일반채소', 'Basic vegetables', 'Rau cơ bản', 1),
	('채소', '뿌리채소·버섯·잎채소', 'Roots·Mushroom·Leaves', 'Rau ăn lá·Củ·Nấm', 2),
	('채소', '생허브·콩', 'Fresh herbs·Beans', 'Rau gia vị·Đậu', 3),
	('채소', '냉동채소', 'Frozen vegetables', 'Rau củ đông lạnh', 4),
	('과일·견과류·쌀', '수입과일', 'Imported fruits', 'Trái cây nhập khẩu', 1),
	('과일·견과류·쌀', '국산과일(베트남산)', 'Domestic fruits', 'Trái cây nội địa', 2),
	('과일·견과류·쌀', '냉동·건조 과일', 'Frozen & Dried fruits', 'Trái cây sấy & đông lạnh', 3),
	('과일·견과류·쌀', '쌀·시리얼·견과류', 'Rice·Fresh cereals·Nuts', 'Gạo·Ngũ cốc nguyên hạt·Các loại hạt', 4),
	('정육·계란', '소고기', 'Beef', 'Thịt bò', 1),
	('정육·계란', '돼지고기', 'Pork', 'Thịt heo', 2),
	('정육·계란', '닭·오리고기', 'Poultry', 'Thịt gia cầm', 3),
	('정육·계란', '양념고기·돈가스', 'Seasoned meat·Tonkatsu', 'Thịt tẩm ướp·Thịt chiên xù', 4),
	('정육·계란', '계란', 'Eggs', 'Trứng', 5),
	('해산물', '생선', 'Fishery', 'Cá', 1),
	('해산물', '새우·게·랍스터', 'Shrimp·Crab·Lobster·Others', 'Tôm·Cua·Hải sản khác', 2),
	('해산물', '오징어·문어', 'Squid·Octopus', 'Mực·Bạch tuộc', 3),
	('베이커리·치즈·잼', '빵·쿠키·냉동생지', 'Bread·Frozen bakery', 'Bánh tươi·Bánh đông lạnh', 1),
	('베이커리·치즈·잼', '치즈·버터', 'Cheese·Butter·Cream', 'Bơ·Phô mai', 2),
	('베이커리·치즈·잼', '잼·꿀', 'Jam·Honey', 'Mứt·Mật ong', 3),
	('반찬·가공식품', '떡볶이·해초류', 'Tteokbokki·Seaweed', 'Tteokbokki·Rong biển', 1),
	('반찬·가공식품', '두부·어묵·전', 'Tofu·Fish & Seafoods cake·Vegetable pancake', 'Đậu hủ·Bánh cá & hải sản·Bánh rau củ', 2),
	('반찬·가공식품', '김치·피클', 'Mandu·Kimchi·Pickled vegetables', 'Mandu·Kimchi·Rau củ ngâm', 3),
	('반찬·가공식품', '햄·소시지·통조림', 'Ham·Sausage·Canned food', 'Thịt xông khói·Xúc xích·Đồ hộp', 4),
	('반찬·가공식품', '피자·파스타·면', 'Pizza·Pasta·Noodle', 'Pizza·Mì Ý·Mì các loại', 5),
	('반찬·가공식품', '해산물요리·볶음밥·국물요리·죽', 'Seafoods·Spring roll·Soups·Porridge', 'Hải sản chế biến·Chả giò·Súp·Cháo', 6),
	('면·오일·양념', '면', 'Noodles', 'Mì', 1),
	('면·오일·양념', '파스타', 'Pasta', 'Mì Ý', 2),
	('면·오일·양념', '식용유·식초', 'Oil·Vinegar', 'Dầu·Giấm', 3),
	('면·오일·양념', '설탕·소금·조미료', 'Sugar·Salt·MSG·Spice', 'Đường·Muối·Gia vị khác', 4),
	('면·오일·양념', '드레싱·소스', 'Dressings·Sauces', 'Sốt·Nước chấm', 5),
	('면·오일·양념', '가루·믹스', 'Flour·Powder Mix', 'Bột·Bột hỗn hợp', 6),
	('음료·우유', '우유·두유·요거트', 'Milk·Plant-based Milk·Yogurt', 'Sữa·Sữa hạt·Sữa chua', 1),
	('음료·우유', '생수·주스', 'Water·Juice', 'Nước tinh khiết·Nước ép', 2),
	('음료·우유', '탄산음료·비탄산음료·시럽', 'Soft drinks·Non-carb drinks·Syrup', 'Nước ngọt·Thức uống không ga·Si rô', 3),
	('음료·우유', '과일주', 'Cider', '', 4),
	('음료·우유', '차·커피·커피 용품', 'Cacao·Tea·Coffee·Tea Coffee equipment', '', 5),
	('음료·우유', '맥주·와인·소주·사케·증류주', 'Beer·Wine·Soju·Sake·Spirits', '', 6),
	('군것질·아이스크림', '스낵·비스킷·시리얼', 'Snacks·Biscuit·Cereals', 'Thức ăn vặt·Bánh quy·Ngũ cốc', 1),
	('군것질·아이스크림', '초콜릿·껌', 'Chocolate·Gum', 'Sô-cô-la·Kẹo cao su', 2),
	('군것질·아이스크림', '사탕·젤리', 'Candy·Jelly', 'Kẹo·Kẹo dẻo', 3),
	('군것질·아이스크림', '아이스크림', 'Ice cream', 'Kem', 4),
	('건강식품·헬스케어', '인삼·홍삼', 'Ginseng', 'Sâm', 1),
	('건강식품·헬스케어', '비타민·프로바이오틱스·영양식품', 'Vitamin·Probiotics·Nutrient foods', 'Vitamin·Thực phẩm dinh dưỡng·Men tiêu hóa', 2),
	('건강식품·헬스케어', '건강음료', 'Healthy juice & drinks', 'Thức uống bổ dưỡng', 3),
	('건강식품·헬스케어', '영양제·분말영양제', 'Health powder·pills', 'Bột dinh dưỡng·Viên uống', 4),
	('건강식품·헬스케어', '스마트 헬스케어·마스크', 'Healthcare equipments·Masks·Medical instruments', 'Khẩu trang·Thiết bị chăm sóc sức khỏe·Dụng cụ y tế', 5),
	('건강식품·헬스케어', '운동 기구', 'Sport equipments', 'Dụng cụ thể thao', 6),
	('뷰티·개인위생도구', '헤어케어', 'Hair care', 'Chăm sóc tóc', 1),
	('뷰티·개인위생도구', '바디케어', 'Body care', 'Chăm sóc cơ thể', 2),
	('뷰티·개인위생도구', '스킨케어', 'Skin care', 'Chăm sóc da mặt', 3),
	('뷰티·개인위생도구', '화장품', 'Cosmetic', 'Mỹ phẩm', 4),
	('뷰티·개인위생도구', '구강청결·제모', 'Oral·Shaving', 'Chăm sóc răng miệng·Dao cạo', 5),
	('뷰티·개인위생도구', '데오드란트·생리대', 'Deodorant·Sanitary Pads', 'Khử mùi·Băng vệ sinh', 6),
	('생활용품·리빙·문구', '세탁·주방·청소세제', 'Laundry·Dishwashing·Cleaners', 'Giặt ủi·Nước tẩy rửa·Làm sạch', 1),
	('생활용품·리빙·문구', '방향제·살충제', 'Air freshener·Insecticides', 'Xịt phòng·Diệt côn trùng', 2),
	('생활용품·리빙·문구', '티슈·물티슈', 'Tissue·Wet wipes', 'Khăn giấy·Giấy ướt', 3),
	('생활용품·리빙·문구', '정수기', 'Water purifier equipment', 'Trang thiết bị lọc nước', 4),
	('생활용품·리빙·문구', '생활잡화·문구', 'Stationery', 'Văn phòng phẩm', 5),
	('주방·인테리어·가드닝', '조리기구·주방기구·수납', 'Utensils·Cookware·Storage', 'Dụng cụ bếp·Dụng cụ nấu·Hộp bảo quản', 1),
	('주방·인테리어·가드닝', '일회용 식기', 'Plastic·Disposable tableware', 'Đồ dùng 1 lần·Màng bọc thực phẩm', 2),
	('주방·인테리어·가드닝', '접시·유리잔', 'Dining·Glassware', 'Đồ dùng bàn ăn·Đồ thủy tinh', 3),
	('주방·인테리어·가드닝', '꽃·씨앗·원예용품', 'Flowers·Seeds·Gardening', 'Hoa·Hạt giống·Dụng cụ vườn', 4),
	('주방·인테리어·가드닝', '인테리어·가구·액세서리', 'Interior·Furnishings·Accessories', 'Nội thất·Đồ trang trí·Phụ kiện', 5),
	('주방·인테리어·가드닝', '주방가전', 'Cooking appliance', 'Trang thiết bị nhà bếp', 6),
	('침구·욕실·패션', '베개·쿠션·담요', 'Pillows·Bedsheets·Blanket·Mattress', 'Ga gối·Nệm·Chăn', 1),
	('침구·욕실·패션', '수건·목욕가운·매트', 'Towels·Bathrobes·Mats', 'Khăn·Áo choàng tắm·Thảm', 2),
	('침구·욕실·패션', '양말', 'Socks', 'Tất vớ', 3),
	('침구·욕실·패션', '선글라스', 'Lens·Sunglasses', 'Kính áp tròng·Mắt kính', 4),
	('침구·욕실·패션', '의류', 'Adults', 'Thời trang người lớn', 5),
	('침구·욕실·패션', '유아 의류', 'Children', 'Thời trang trẻ em', 6),
	('침구·욕실·패션', '액세서리', 'Accessories', 'Phụ kiện', 7),
	('전자기기', '스마트폰', 'Smart phone', 'Điện thoại di động', 1),
	('전자기기', '멀티탭·일반기기', 'Electrical accessories', 'Phụ tùng điện tử', 2),
	('전자기기', '음향기기', 'Audio', 'Thiết bị âm thanh', 3),
	('전자기기', '컴퓨터 액세서리', 'Computer accessories', 'Phụ kiện máy tính', 4),
	('전자기기', '태블릿', 'Tablet', 'Máy tính bảng', 5),
	('전자기기', '모니터', 'Monitors', 'Màn hình', 6),
	('전자기기', '모바일 액세서리', 'Mobile accessories', 'Phụ kiện điện thoại', 7),
	('전자기기', '티비', 'Television', 'Ti vi', 8),
	('전자기기', '웨어러블', 'Wearable', 'Thiết bị đeo tay', 9),
	('전자기기', '에어컨·공기청정기·선풍기', 'Air conditioner·Air purifier·Fan', 'Máy lạnh·Máy lọc không khí·Thiết bị gia dụng', 10),
	('전자기기', '진공청소기', 'Vacuum cleaners', 'Máy hút bụi', 11),
	('전자기기', '냉장고·세탁기·건조기', 'Refrigerator·Washing machine·Dryer', 'Tủ lạnh·Máy giặt·Máy sấy', 12),
	('반려동물', '반려동물 식품', 'Pet foods', 'Thức ăn thú cưng', 1),
	('반려동물', '반려동물 용품', 'Pet accessories', 'Phụ kiện thú cưng', 2),
	('반려동물', '반려동물 장난감', 'Pet toys', 'Đồ chơi thú cưng', 3),
	('베이비·장난감', '유아·어린이음식·음료', 'Baby foods·Baby drinks', 'Thực phẩm cho bé·Thức uống cho bé', 1),
	('베이비·장난감', '유아스킨·구강케어·기저귀', 'Diapers·Baby care', 'Bỉm tã·Chăm sóc cơ thể bé', 2),
	('베이비·장난감', '유아 의류 세탁', 'Baby laundry', 'Giặt ủi cho bé', 3),
	('베이비·장난감', '분유', 'Powdered milk', 'Sữa bột', 4),
	('베이비·장난감', '장난감·아이 용품', 'Toys·Baby accessories', 'Đồ chơi·Phụ kiện trẻ em', 5);

INSERT INTO form_types (parent_idx, depth, subject, subject_en, subject_vn, orderby)
SELECT f.idx, 2, s.subject, s.subject_en, s.subject_vn, s.orderby
FROM tmp_form_types_seed s
JOIN form_types f ON f.depth = 1 AND REPLACE(TRIM(f.subject), ' ', '') = s.parent_name
WHERE NOT EXISTS (SELECT 1 FROM form_types x WHERE x.depth = 2)   -- 재실행 시 중복 등록 방지
ORDER BY f.orderby, s.orderby;

DROP TEMPORARY TABLE tmp_form_types_seed;
